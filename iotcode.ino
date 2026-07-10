/*
 * ==========================================================================
 * SISTEM MONITORING BRANKAS - KODE IOT FINAL VERSI 3.4 (OTA ENABLED)
 * Versi: 3.4 (Menu A/B/C Integrated, Robust Diagnostic & Wireless Upload)
 * ==========================================================================
 */

#include <WiFi.h>
#include <esp_wifi.h> 
#include <HTTPClient.h>
#include <WiFiClientSecure.h>
#include <ArduinoJson.h>
#include <Wire.h> 
#include <LiquidCrystal_I2C.h>
#include <Keypad.h>
#include <Adafruit_Fingerprint.h>
#include <HardwareSerial.h>
#include <TinyGPS++.h>
#include <ArduinoOTA.h>  // ← LIBRARY TAMBAHAN UNTUK UPLOAD TANPA KABEL

// ===== [1] KONFIGURASI WIFI & SERVER =====
const char* WIFI_SSID     = "rahmasertamulia2G"; 
const char* WIFI_PASSWORD = "sepedalipatkudicustomkayaneapik"; 

const char* SERVER_URL    = "https://brandes.web.id";
const char* KODE_BRANKAS  = "BRX-001";

const uint32_t GPS_BAUD_RATE = 9600; // Umumnya NEO-6M default 9600. Ubah ke 4800 jika modul sudah dikonfigurasi manual.
const unsigned long HEARTBEAT_INTERVAL_MS = 5000;
const unsigned long GPS_INTERVAL_MS       = 5000;
const unsigned long ALERT_COOLDOWN_MS     = 60000;
const int API_TIMEOUT_MS                  = 8000;

// ===== [2] PIN HARDWARE =====
#define RELAY 23
#define BUZZER 18

// ===== [3] LCD & KEYPAD =====
LiquidCrystal_I2C lcd(0x27, 16, 2);
const byte ROWS = 4;
const byte COLS = 4;
char keys[ROWS][COLS] = {
  {'1','2','3','A'},
  {'4','5','6','B'},
  {'7','8','9','C'}, // 'C' Untuk Hapus Memori
  {'*','0','#','D'}
};
byte rowPins[ROWS] = {32, 33, 25, 26};
byte colPins[COLS] = {27, 14, 12, 13};
Keypad keypad = Keypad(makeKeymap(keys), rowPins, colPins, ROWS, COLS);

// ===== [4] SENSOR FINGERPRINT & GPS =====
HardwareSerial mySerial(1);
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);
HardwareSerial gpsSerial(2);
TinyGPSPlus gps;

// ===== [5] VARIABEL GLOBAL =====
unsigned long lastGpsUpdate = 0;
unsigned long lastHeartbeat = 0;
unsigned long lastAlert = 0;
unsigned long lastMenuCycle = 0;
int menuState = 0;

// ===== [6] FUNGSI UTILITAS =====
void tampilLCD(String a, String b) {
  lcd.clear();
  delay(10);
  lcd.setCursor(0, 0); lcd.print(a);
  lcd.setCursor(0, 1); lcd.print(b);
}

void bunyiKlik()    { digitalWrite(BUZZER, HIGH); delay(30);  digitalWrite(BUZZER, LOW); }
void bunyiSuccess() { digitalWrite(BUZZER, HIGH); delay(150); digitalWrite(BUZZER, LOW); }
void bunyiGagal()   { digitalWrite(BUZZER, HIGH); delay(700); digitalWrite(BUZZER, LOW); }

void reinitLCD() {
  lcd.init();
  delay(50);
  lcd.backlight();
  delay(50);
}

void resetSistem() {
  lastMenuCycle = millis();
  menuState = 0;
  tampilLCD("BRANKAS SIAP", "");
}

// ===== [7] FUNGSI KOMUNIKASI API =====
String buildApiUrl(String endpoint) {
  String base = String(SERVER_URL);
  base.trim();

  while (base.endsWith("/")) {
    base.remove(base.length() - 1);
  }

  if (!endpoint.startsWith("/")) {
    endpoint = String("/") + endpoint;
  }

  return base + endpoint;
}

String httpPost(String endpoint, String jsonBody) {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("[HTTP] WiFi belum terhubung, request dibatalkan.");
    return "";
  }

  String url = buildApiUrl(endpoint);
  HTTPClient http;

  WiFiClient plainClient;
  WiFiClientSecure secureClient;
  bool started = false;

  if (url.startsWith("https://")) {
    // Praktis untuk VPS/domain dengan SSL publik maupun self-signed.
    // Untuk produksi yang lebih ketat, ganti dengan sertifikat CA root.
    secureClient.setInsecure();
    started = http.begin(secureClient, url);
  } else {
    started = http.begin(plainClient, url);
  }

  if (!started) {
    Serial.print("[HTTP] Gagal mulai koneksi: ");
    Serial.println(url);
    return "";
  }

  http.addHeader("Content-Type", "application/json");
  http.addHeader("Accept", "application/json");
  // Untuk sementara request API dikirim tanpa header autentikasi.
  http.setTimeout(API_TIMEOUT_MS);
  http.setFollowRedirects(HTTPC_STRICT_FOLLOW_REDIRECTS);

  Serial.print("[HTTP] POST ");
  Serial.println(url);
  Serial.print("[HTTP] Body: ");
  Serial.println(jsonBody);

  int code = http.POST(jsonBody);
  Serial.print("[HTTP] Code: "); Serial.println(code); 
  String res = (code > 0) ? http.getString() : "";
  Serial.print("[HTTP] Response: ");
  Serial.println(res);
  http.end();
  return res;
}

bool apiSuccess(const String& response) {
  if (response.length() == 0) return false;

  StaticJsonDocument<300> doc;
  DeserializationError error = deserializeJson(doc, response);
  if (!error && doc["success"].is<bool>()) {
    return doc["success"].as<bool>();
  }

  return response.indexOf("\"success\":true") >= 0;
}

// ===== [8] HEARTBEAT =====
void kirimHeartbeat() {
  lastHeartbeat = millis();
  StaticJsonDocument<200> doc;
  doc["kode_brankas"] = KODE_BRANKAS;
  doc["status_pintu"] = (digitalRead(RELAY) == LOW) ? "TERBUKA" : "TERKUNCI";
  String body; serializeJson(doc, body);
  httpPost("/api/iot/heartbeat", body);
}

void kirimAlertPembobolan() {
  if (lastAlert != 0 && millis() - lastAlert < ALERT_COOLDOWN_MS) return;
  lastAlert = millis();

  StaticJsonDocument<200> doc;
  doc["kode_brankas"] = KODE_BRANKAS;
  doc["status_pintu"] = (digitalRead(RELAY) == LOW) ? "TERBUKA" : "TERKUNCI";

  String body;
  serializeJson(doc, body);
  httpPost("/api/iot/alert", body);
}

bool kirimResetMemory() {
  StaticJsonDocument<200> doc;
  doc["kode_brankas"] = KODE_BRANKAS;

  String body;
  serializeJson(doc, body);
  String res = httpPost("/api/iot/reset", body);
  return apiSuccess(res);
}

// ===== [9] UPDATE GPS =====
void updateGPSData() {
  lastGpsUpdate = millis();
  const bool locationValid = gps.location.isValid();
  const uint32_t charsProcessed = gps.charsProcessed();

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("GPS Sat: "); lcd.print(gps.satellites.value());
  lcd.setCursor(0, 1);
  if (locationValid) {
    lcd.print("Sinyal: OK");
  } else {
    lcd.print("Sinyal: MENCARI");
  }
  delay(2000); 
  lcd.clear();

  StaticJsonDocument<400> doc;
  doc["kode_brankas"] = KODE_BRANKAS;
  doc["gps_valid"]    = locationValid;
  doc["chars"]        = charsProcessed;
  doc["hdop"]         = gps.hdop.isValid() ? gps.hdop.hdop() : 0;
  doc["satellites"]   = gps.satellites.isValid() ? gps.satellites.value() : 0;
  doc["speed_kmh"]    = gps.speed.isValid() ? gps.speed.kmph() : 0;
  doc["fix_quality"]  = locationValid ? 1 : 0;

  if (locationValid) {
    doc["latitude"]  = gps.location.lat();
    doc["longitude"] = gps.location.lng();
    if (gps.altitude.isValid()) {
      doc["altitude"] = gps.altitude.meters();
    }
  } else {
    Serial.print("[GPS] Belum valid. Chars: ");
    Serial.print(charsProcessed);
    Serial.print(" Sat: ");
    Serial.println(gps.satellites.isValid() ? gps.satellites.value() : 0);
  }

  String body;
  serializeJson(doc, body);
  String res = httpPost("/api/iot/gps", body);
  if (apiSuccess(res)) {
    Serial.println("[GPS] Backend menerima update GPS.");
  } else {
    Serial.println("[GPS] Backend belum menerima update GPS.");
  }
}

// ===== [10] PROSES AKSES (TOMBOL A) - DENGAN LIMIT 3X GAGAL =====
void prosesAkses() {
  int attempts = 0;
  int id = -1;
  
  while (attempts < 3) {
    tampilLCD("Tempelkan Jari", "Percobaan: " + String(attempts + 1) + "/3");
    
    // Tunggu jari ditempel (max 5 detik per percobaan)
    unsigned long waitStart = millis();
    bool jariDitempel = false;
    while(millis() - waitStart < 5000) {
      // Wajib Handle OTA saat menunggu jari agar sistem tidak hang jika di-upload
      ArduinoOTA.handle(); 
      
      if (finger.getImage() == FINGERPRINT_OK) { jariDitempel = true; break; }
    }

    if (jariDitempel) {
      if (finger.image2Tz() == FINGERPRINT_OK && finger.fingerFastSearch() == FINGERPRINT_OK) {
        id = finger.fingerID;
        bunyiSuccess();
        break; // Berhasil! Keluar dari loop attempts
      } else {
        attempts++;
        tampilLCD("JARI SALAH!", "Coba Lagi...");
        bunyiGagal(); delay(1500);
      }
    } else {
      tampilLCD("Waktu Habis", "Jari tdk nempel");
      delay(2000); resetSistem(); return;
    }
  }

  // --- JIKA 3X GAGAL ---
  if (attempts >= 3) {
    tampilLCD("AKSES ILEGAL!", "Melapor ke Web");
    digitalWrite(BUZZER, HIGH); // Buzzer bunyi panjang sebagai alarm
    
    // Kirim Laporan Ilegal ke Server (ID 0 = Ilegal)
    StaticJsonDocument<200> doc;
    doc["kode_brankas"] = KODE_BRANKAS;
    doc["fingerprint_id"] = 0; 
    doc["pin"] = "000000";
    String body; serializeJson(doc, body);
    httpPost("/api/iot/verify", body);
    
    delay(3000); digitalWrite(BUZZER, LOW);
    resetSistem(); return;
  }

  // --- JIKA BERHASIL (Minta PIN) ---
  if (id != -1) {
    tampilLCD("Jari Cocok", "Masukan PIN:");
    String inputPin = "";
    while (inputPin.length() < 6) {
      ArduinoOTA.handle(); // ← Handle OTA saat proses PIN
      char key = keypad.getKey();
      if (key >= '0' && key <= '9') { 
        inputPin += key; 
        String stars = ""; for(int i=0; i<inputPin.length(); i++) stars += "*";
        tampilLCD("PIN: " + stars, ""); bunyiKlik(); 
      }
      if (key == '*') { inputPin = ""; tampilLCD("Masukan PIN:", ""); }
      if (key == '#' && inputPin.length() == 6) break;
    }

    tampilLCD("Memverifikasi...", "");
    StaticJsonDocument<200> doc;
    doc["kode_brankas"] = KODE_BRANKAS;
    doc["fingerprint_id"] = id;
    doc["pin"] = inputPin;
    String body; serializeJson(doc, body);
    String res = httpPost("/api/iot/verify", body);
    
    if (apiSuccess(res)) {
      tampilLCD("AKSES DITERIMA", "Selamat Datang"); bunyiSuccess();
      digitalWrite(RELAY, LOW);
      kirimHeartbeat();
      delay(5000);
      digitalWrite(RELAY, HIGH);
      kirimHeartbeat();
    } else {
      tampilLCD("PIN SALAH!", "Akses Ditolak"); bunyiGagal();
    }
  }

  delay(2000);
  resetSistem();
}

// ===== [11] PROSES DAFTAR (TOMBOL B) - VERSI ANTI GAGAL =====
void prosesDaftar() {
  String strID = "";
  tampilLCD("Masukan ID:", "* (Hapus) # (Ok)");
  while (true) {
    ArduinoOTA.handle(); 
    char key = keypad.getKey();
    if (key >= '0' && key <= '9') { strID += key; tampilLCD("Masukan ID: " + strID, "* (Hapus) # (Ok)"); bunyiKlik(); }
    if (key == '*') { strID = ""; tampilLCD("Masukan ID:", "* (Hapus) # (Ok)"); bunyiKlik(); }
    if (key == '#' && strID != "") { bunyiKlik(); break; }
  }
  int id = strID.toInt();

  tampilLCD("Tempelkan Jari", "");
  int p = -1;
  while (p != FINGERPRINT_OK) {
    ArduinoOTA.handle(); 
    p = finger.getImage();
    if (p == FINGERPRINT_OK) {
      p = finger.image2Tz(1);
      if (p != FINGERPRINT_OK) { p = -1; continue; }
      tampilLCD("Angkat Jari", ""); bunyiSuccess(); delay(1000);

      while (finger.getImage() != FINGERPRINT_NOFINGER) { ArduinoOTA.handle(); }
      tampilLCD("Tempel Lagi", "");

      while (finger.getImage() != FINGERPRINT_OK) { ArduinoOTA.handle(); }
      p = finger.image2Tz(2);
      if (p != FINGERPRINT_OK) { p = -1; continue; }
      p = finger.createModel();

      if (p != FINGERPRINT_OK) { tampilLCD("Gagal Model!", ""); bunyiGagal(); delay(2000); resetSistem(); return; }
      p = finger.storeModel(id);

      if (p == FINGERPRINT_OK) {
        tampilLCD("Simpan Data", "BERHASIL!"); bunyiSuccess(); delay(1000);
      } else {
        tampilLCD("Gagal Simpan Data", "GAGAL!"); bunyiGagal(); delay(2000); resetSistem(); return;
      }
    }
  }

  tampilLCD("Ketik PIN min 6", "");
  String newPin = "";
  while (newPin.length() < 6) {
    ArduinoOTA.handle(); 
    char key = keypad.getKey();
    if (key >= '0' && key <= '9') { newPin += key; tampilLCD("PIN: " + newPin, ""); bunyiKlik(); }
    if (key == '*') { newPin = ""; tampilLCD("Ketik PIN min 6", "* (Hapus) # (Ok)"); bunyiKlik();}
  }

  tampilLCD("Mendaftarkan...", "*Mohon tunggu");

  StaticJsonDocument<200> doc;
  doc["kode_brankas"] = KODE_BRANKAS;
  doc["fingerprint_id"] = id;
  doc["pin"] = newPin;
  String body; serializeJson(doc, body);
  String res = httpPost("/api/iot/register", body);

  if (apiSuccess(res)) {

    tampilLCD("DAFTAR SUKSES!", "*Cek Menu Tambah User");
    bunyiSuccess();

  } else if (res.indexOf("Kapasitas penuh") >= 0 || res.indexOf("penuh") >= 0) {
    tampilLCD("PENUH! Max 8", "User Terdaftar");
    bunyiGagal();

  } else {
    tampilLCD("DAFTAR GAGAL!", "*Data Penuh");
    bunyiGagal();
  }
  
  delay(3000); resetSistem();
}

// ===== [12] PEMBERSIH TOTAL (TOMBOL C) =====
void prosesHapusMemori() {
  tampilLCD("HAPUS SEMUA DATA?", "*Tekan '#' JIKA YA");

  while(true) {
    ArduinoOTA.handle();
    char key = keypad.getKey();

    if (key == '#') {
      tampilLCD("MENGHAPUS DATA", "*Harap Tunggu");
      finger.emptyDatabase();
      bunyiSuccess();
      delay(2000);

      tampilLCD("SINKRON WEB...", "*Mohon tunggu");
      bool resetOk = kirimResetMemory();

      if (resetOk) {
        tampilLCD("DATA KOSONG", "*Web sinkron");
      } else {
        tampilLCD("DATA KOSONG", "*Web gagal sync");
      }

      delay(3000);
      break;
    }
    if (key == '*') break; // Batal
  }
  resetSistem();
}

// ===== [13] SETUP & LOOP =====
void setup() {
  Serial.begin(115200);
  delay(500);
  
  pinMode(RELAY, OUTPUT); digitalWrite(RELAY, HIGH);
  pinMode(BUZZER, OUTPUT);
  
  Wire.begin();
  delay(100);
  lcd.init();
  delay(100);
  lcd.backlight();
  delay(100);
  tampilLCD("SISTEM MENYALA", "*Inisialisasi...");
  delay(1000);

  mySerial.begin(57600, SERIAL_8N1, 16, 17);
  gpsSerial.begin(GPS_BAUD_RATE, SERIAL_8N1, 34, -1); 
  gpsSerial.setRxBufferSize(1024);
  delay(200);

  // ── Koneksi WiFi ──
  WiFi.mode(WIFI_STA);
  WiFi.disconnect(true);
  delay(300);

  esp_wifi_set_ps(WIFI_PS_NONE);     
  WiFi.setTxPower(WIFI_POWER_19_5dBm); 
  
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  tampilLCD("MENCARI WIFI...", "*Mohon tunggu...");

  int attempt = 0;
  while (WiFi.status() != WL_CONNECTED && attempt < 40) {
    delay(500);
    attempt++;
    Serial.print(".");
  }

  reinitLCD();

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\n[WiFi] Terhubung! IP: " + WiFi.localIP().toString());
    Serial.print("[API] Base URL: ");
    Serial.println(SERVER_URL);
    tampilLCD("WIFI TERHUBUNG!", WiFi.localIP().toString().c_str());
    delay(2000);

    // =========================================================
    // ↓ INIT ARDUINO OTA (UPLOAD TANPA KABEL VIA WIFI) ↓
    // =========================================================
    ArduinoOTA.setHostname("Brankas-IoT-ESP32");
    ArduinoOTA.setPassword("admin123"); 
    // Password saat upload OTA di Arduino IDE

    ArduinoOTA.onStart([]() {
      String type = (ArduinoOTA.getCommand() == U_FLASH) ? "sketch" : "filesystem";
      tampilLCD("UPDATE OTA...", "*Memulai...");
      Serial.println("Start updating " + type);
    });
    
    ArduinoOTA.onEnd([]() {
      tampilLCD("UPDATE SELESAI!", "*Rebooting...");
      Serial.println("\nEnd");
    });
    
    ArduinoOTA.onProgress([](unsigned int progress, unsigned int total) {
      Serial.printf("Progress: %u%%\r", (progress / (total / 100)));
    });
    
    ArduinoOTA.onError([](ota_error_t error) {
      tampilLCD("UPDATE GAGAL!", "*Error OTA");
      Serial.printf("Error[%u]: ", error);
      delay(3000);
      resetSistem();
    });
    
    ArduinoOTA.begin();
    // =========================================================

  } else {
    Serial.println("\n[WiFi] GAGAL konek!");
    tampilLCD("WiFi GAGAL!", "*Cek Koneksi");
    delay(2000);
  }

  // ── Inisialisasi Sensor Sidik Jari ──
  finger.begin(57600);
  if (finger.verifyPassword()) {
    finger.getTemplateCount();
    Serial.print("[Sensor] OK. Jumlah sidik jari: ");
    Serial.println(finger.templateCount);

    // === tampilLCD("Sensor OK", "FP: " + String(finger.templateCount)); 
    // ===delay(1500);=====
  } 
  else {
    Serial.println("[Sensor] ERROR!");
    tampilLCD("Sensor ERROR!", "*Cek Kabel atau koneksi");
    delay(2000);
  }

  resetSistem();
}

void loop() {
  // 1. WAJIB DI PANTAU UNTUK OTA OVER THE AIR
  ArduinoOTA.handle();

  // 2. RUTINITAS SISTEM (GPS & HEARTBEAT)
  while (gpsSerial.available()) gps.encode(gpsSerial.read());
  if (millis() - lastHeartbeat > HEARTBEAT_INTERVAL_MS) kirimHeartbeat();
  if (millis() - lastGpsUpdate > GPS_INTERVAL_MS) updateGPSData();
  
  // 3. ANIMASI MENU LCD
  if (millis() - lastMenuCycle > 3000) {
    lastMenuCycle = millis();
    menuState = (menuState + 1) % 3;
    if (menuState == 0)      tampilLCD("BRANKAS SIAP", "");
    else if (menuState == 1) tampilLCD("Ketik A untuk", "akses brankas");
    else if (menuState == 2) tampilLCD("Ketik B untuk", "mendaftar");
    }
  
    // 4. INPUT KEYPAD
  char key = keypad.getKey();
  if (key == 'A') { bunyiKlik(); prosesAkses(); }
  if (key == 'B') { bunyiKlik(); prosesDaftar(); }
  if (key == 'C') { bunyiKlik(); prosesHapusMemori(); }
}
