<?php

namespace App\Helpers;

/**
 * Class PdfGenerator
 * Generator PDF kustom, ringan, dan mandiri (bebas dependensi eksternal).
 * Berguna untuk membuat laporan tabular PDF berhalaman banyak dengan styling Brandes.
 */
class PdfGenerator
{
    protected $title;
    protected $headers;
    protected $columnWidths;
    protected $rows = [];
    protected $pagesContents = [];

    public function __construct($title, $headers, $columnWidths)
    {
        $this->title = $title;
        $this->headers = $headers;
        $this->columnWidths = $columnWidths;
    }

    /**
     * Menambahkan baris data ke dalam tabel PDF.
     * 
     * @param array $row Array berisi string nilai untuk setiap kolom
     */
    public function addRow($row)
    {
        $this->rows[] = $row;
    }

    /**
     * Membersihkan string dan melakukan encoding yang sesuai untuk PDF (Windows-1252 / ISO-8859-1).
     */
    protected function escape($str)
    {
        $str = html_entity_decode(strip_tags($str), ENT_QUOTES, 'UTF-8');
        if (function_exists('iconv')) {
            $str = iconv('UTF-8', 'windows-1252//IGNORE', $str);
        } else {
            $str = mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
        }
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $str);
    }

    /**
     * Membangun dokumen PDF biner.
     * 
     * @return string Konten biner PDF
     */
    public function build()
    {
        $pageHeight = 841.89; // A4 Portrait Height
        $pageWidth = 595.28;  // A4 Portrait Width
        $margin = 40;
        $usableWidth = $pageWidth - ($margin * 2);

        $currentPageContent = "";
        $y = 750; // Posisi Y awal untuk konten tabel

        // Fungsi pembantu untuk menggambar header dokumen
        $drawHeader = function () use (&$currentPageContent, &$y, $margin, $usableWidth, $pageWidth) {
            // Judul Laporan
            $currentPageContent .= "BT\n/F1 16 Tf\n0.12 0.53 0.24 rg\n$margin 800 Td\n(" . $this->escape($this->title) . ") Tj\nET\n";

            // Subtitle / Timestamp
            $currentPageContent .= "BT\n/F1 9 Tf\n0.5 0.5 0.5 rg\n$margin 782 Td\n(" . $this->escape("Sistem Keamanan Brandes - Dicetak pada: " . date('d-m-Y H:i:s')) . ") Tj\nET\n";

            // Menggambar latar belakang header tabel (Hijau Brandes)
            $currentPageContent .= "0.12 0.53 0.24 rg\n$margin 748 $usableWidth 22 re f\n";

            // Menulis teks header kolom
            $x = $margin + 5;
            foreach ($this->headers as $i => $h) {
                $currentPageContent .= "BT\n/F1 10 Tf\n1.0 1.0 1.0 rg\n$x 754 Td\n(" . $this->escape($h) . ") Tj\nET\n";
                $x += $this->columnWidths[$i];
            }

            // Menggambar border luar untuk header
            $currentPageContent .= "0.8 0.8 0.8 RG\n0.5 w\n";
            $currentPageContent .= "$margin 770 m " . ($pageWidth - $margin) . " 770 l S\n"; // Atas
            $currentPageContent .= "$margin 748 m $margin 770 l S\n"; // Kiri
            $currentPageContent .= ($pageWidth - $margin) . " 748 m " . ($pageWidth - $margin) . " 770 l S\n"; // Kanan
            $currentPageContent .= "$margin 748 m " . ($pageWidth - $margin) . " 748 l S\n"; // Bawah

            // Menggambar garis pemisah putih vertikal di dalam header kolom
            $currentPageContent .= "1.0 1.0 1.0 RG\n0.5 w\n";
            $hx = $margin;
            for ($i = 0; $i < count($this->columnWidths) - 1; $i++) {
                $hx += $this->columnWidths[$i];
                $currentPageContent .= "$hx 748 m $hx 770 l S\n";
            }

            $y = 744; // Diatur ke 744 agar Y + 4 = 748 (tepat bersentuhan dengan bagian bawah header)
        };

        // Gambar header halaman pertama
        $drawHeader();

        $rowIdx = 1;
        foreach ($this->rows as $rowData) {
            $cells = [];
            $maxLines = 1;

            // Lakukan wrap teks secara otomatis berdasarkan lebar kolom
            foreach ($rowData as $i => $val) {
                $str = (string) $val;
                $colWidth = $this->columnWidths[$i];
                // Estimasi: lebar 1 karakter rata-rata 5.5 unit PDF
                $maxChars = floor(($colWidth - 10) / 5.5);
                if ($maxChars < 5)
                    $maxChars = 5;

                $wrapped = wordwrap($str, $maxChars, "\n", true);
                $lines = explode("\n", $wrapped);
                $cells[$i] = $lines;
                if (count($lines) > $maxLines) {
                    $maxLines = count($lines);
                }
            }

            $rowHeight = ($maxLines * 12) + 12;

            // Deteksi page break
            if ($y - $rowHeight < 60) {
                $this->pagesContents[] = $currentPageContent;
                $currentPageContent = "";
                $drawHeader();
            }

            // Gambar latar belakang zebra striping untuk baris genap
            if ($rowIdx % 2 === 0) {
                $currentPageContent .= "0.96 0.96 0.96 rg\n$margin " . ($y - $rowHeight + 4) . " $usableWidth " . ($rowHeight) . " re f\n";
            }

            // Tulis teks untuk setiap baris / cell
            for ($lineIdx = 0; $lineIdx < $maxLines; $lineIdx++) {
                $x = $margin + 5;
                $textY = $y - 12 - ($lineIdx * 12);

                foreach ($cells as $i => $lines) {
                    if (isset($lines[$lineIdx])) {
                        $valStr = $lines[$lineIdx];
                        $color = "0.15 0.15 0.15 rg"; // Default warna teks abu-abu gelap

                        // Styling khusus warna untuk Status / Tipe notifikasi
                        $lower = strtolower($valStr);
                        if ($lower === 'kritis' || $lower === 'danger' || $lower === 'percobaan pembobolan') {
                            $color = "0.85 0.18 0.18 rg"; // Merah
                        } elseif ($lower === 'peringatan' || $lower === 'warning' || $lower === 'percobaan akses') {
                            $color = "0.95 0.55 0.0 rg";  // Jingga/Kuning
                        } elseif ($lower === 'akses' || $lower === 'success' || $lower === 'berhasil') {
                            $color = "0.0 0.65 0.24 rg";  // Hijau Brandes
                        }

                        $currentPageContent .= "BT\n/F1 9 Tf\n$color\n$x $textY Td\n(" . $this->escape($valStr) . ") Tj\nET\n";
                    }
                    $x += $this->columnWidths[$i];
                }
            }

            // Gambar border / stroke grid garis tipis di sekeliling sel tabel
            $currentPageContent .= "0.8 0.8 0.8 RG\n0.5 w\n";
            $topY = $y + 4;
            $bottomY = $y - $rowHeight + 4;

            // 1. Garis pembatas bawah
            $currentPageContent .= "$margin $bottomY m " . ($pageWidth - $margin) . " $bottomY l S\n";

            // 2. Garis pembatas vertikal antar kolom
            $vx = $margin;
            $currentPageContent .= "$vx $bottomY m $vx $topY l S\n"; // Pembatas kiri terluar
            foreach ($this->columnWidths as $w) {
                $vx += $w;
                $currentPageContent .= "$vx $bottomY m $vx $topY l S\n"; // Pembatas kolom
            }

            $y -= $rowHeight;
            $rowIdx++;
        }

        // --- BLOK TANDA TANGAN ---
        // Cek apakah ada cukup ruang di halaman saat ini untuk tanda tangan (butuh sekitar 100 unit)
        if ($y < 140) {
            $this->pagesContents[] = $currentPageContent;
            $currentPageContent = "";
            $drawHeader();
        }

        $waktuUnduh = date('d-m-Y');
        $pengunduh = session('user.nama') ?? 'Administrator';
        $sigX = $pageWidth - $margin - 160; // Posisi X di sebelah kanan
        $sigY = $y - 40; // Turun sedikit dari batas bawah tabel

        $strTop = "Diunduh pada tanggal: " . $waktuUnduh;
        $strBottom = "( " . $pengunduh . " )";

        $currentPageContent .= "BT\n/F1 10 Tf\n0.15 0.15 0.15 rg\n$sigX $sigY Td\n(" . $this->escape($strTop) . ") Tj\nET\n";
        
        $sigY -= 60; // Spasi kosong untuk tempat tanda tangan manual
        
        // Hitung offset agar $strBottom tepat berada di tengah-tengah $strTop
        $widthTop = strlen($strTop) * 5; // Estimasi rata-rata lebar char font size 10 adalah 5 unit
        $widthBottom = strlen($strBottom) * 5;
        $offset = ($widthTop - $widthBottom) / 2;
        
        $currentPageContent .= "BT\n/F1 10 Tf\n0.15 0.15 0.15 rg\n" . ($sigX + $offset) . " $sigY Td\n(" . $this->escape($strBottom) . ") Tj\nET\n";
        // -------------------------

        $this->pagesContents[] = $currentPageContent;

        // Susun struktur berkas PDF
        $out = "%PDF-1.4\n";
        $offsets = [];
        $objId = 1;

        // Obj 1: Catalog
        $offsets[$objId] = strlen($out);
        $out .= "$objId 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $objId++;

        // Obj 2: Pages container (akan ditulis belakangan)
        $pagesObjId = $objId;
        $objId++;

        // Obj 3: Font Helvetica
        $fontObjId = $objId;
        $offsets[$fontObjId] = strlen($out);
        $out .= "$fontObjId 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";
        $objId++;

        $pageObjIds = [];
        $contentObjIds = [];

        foreach ($this->pagesContents as $idx => $content) {
            $pId = $objId;
            $pageObjIds[] = $pId;
            $objId++;

            $cId = $objId;
            $contentObjIds[] = $cId;
            $objId++;

            // Stream Halaman
            $streamContent = $content;

            // Footer Halaman
            $footerText = "Halaman " . ($idx + 1) . " dari " . count($this->pagesContents);
            $footerX = $pageWidth - $margin - 80;
            $streamContent .= "BT\n/F1 8 Tf\n0.5 0.5 0.5 rg\n$footerX 30 Td\n(" . $this->escape($footerText) . ") Tj\nET\n";

            $len = strlen($streamContent);
            $offsets[$cId] = strlen($out);
            $out .= "$cId 0 obj\n<< /Length $len >>\nstream\n" . $streamContent . "\nendstream\nendobj\n";

            // Page Object
            $offsets[$pId] = strlen($out);
            $out .= "$pId 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 $pageWidth $pageHeight] /Contents $cId 0 R /Resources << /Font << /F1 $fontObjId 0 R >> >> >>\nendobj\n";
        }

        // Tulis Pages container sekarang karena kita telah mengetahui daftar page object IDs
        $offsets[$pagesObjId] = strlen($out);
        $kidsStr = implode(" 0 R ", $pageObjIds) . " 0 R";
        $out .= "$pagesObjId 0 obj\n<< /Type /Pages /Kids [$kidsStr] /Count " . count($pageObjIds) . " >>\nendobj\n";

        // Tulis Xref table
        $startxref = strlen($out);
        $out .= "xref\n0 $objId\n";
        $out .= "0000000000 65535 f \r\n";
        for ($i = 1; $i < $objId; $i++) {
            $out .= sprintf("%010d 00000 n \r\n", $offsets[$i]);
        }

        $out .= "trailer\n<< /Size $objId /Root 1 0 R >>\n";
        $out .= "startxref\n" . $startxref . "\n%%EOF\n";

        return $out;
    }
}