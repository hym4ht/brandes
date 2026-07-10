/**
 * ==========================================================================
 * lokasi.js
 * Deskripsi: Mengelola peta Google Maps embed dan detail lokasi brankas
 *            secara real-time melalui polling API IoT.
 * ==========================================================================
 */

import { reverseGeocodeAddress } from '../utils/reverse-geocode';

let lastLokasiMapKey = '';
let lastLokasiReverseGeocodeKey = '';
const resolvedLokasiAddressByCoordinate = new Map();

function initLokasiMap() {
    if (!document.getElementById('lokasi-map') || !window.GPS_CONFIG) return;

    if (window.lokasiPollingInterval) {
        clearInterval(window.lokasiPollingInterval);
    }

    const config = window.GPS_CONFIG;

    updateMapIframe(config.lat, config.lng);
    updateGpsBadge(config.gpsValid);
    resolveLokasiAddress(config.lat, config.lng, config.lokasi || '-');

    fetchRealtimeGPS();
    window.lokasiPollingInterval = setInterval(fetchRealtimeGPS, 3000);
}

function fetchRealtimeGPS() {
    fetch('/api/iot/status', {
        headers: {
            Accept: 'application/json',
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (!data.success) return;

            const hasCoordinates = isValidCoordinate(data.latitude, data.longitude);
            if (!hasCoordinates) return;

            const coordinateKey = getCoordinateKey(data.latitude, data.longitude);
            const fallbackAddress = data.lokasi || window.GPS_CONFIG?.lokasi || '-';
            const currentAddress = resolvedLokasiAddressByCoordinate.get(coordinateKey) || fallbackAddress;

            updateUIElement('val-lat', data.latitude);
            updateUIElement('val-lng', data.longitude);
            updateUIElement('val-hdop', data.hdop ?? '-');
            updateUIElement('val-sat', data.satellites ?? '-');
            updateUIElement('val-time', data.last_gps_update || '-');
            updateUIElement('lokasi-nama-val', data.nama_brankas || data.kode_brankas || '-');
            updateUIElement('lokasi-detail-val', currentAddress);

            updateGpsBadge(Number(data.hdop) <= 2);
            updateMapIframe(data.latitude, data.longitude);
            resolveLokasiAddress(data.latitude, data.longitude, fallbackAddress);
        })
        .catch((error) => console.error('Gagal mengambil data GPS realtime:', error));
}

function resolveLokasiAddress(latValue, lngValue, fallbackAddress = '-') {
    const latNumber = Number(latValue);
    const lngNumber = Number(lngValue);
    const key = getCoordinateKey(latValue, lngValue);
    if (!key) return;

    if (key === lastLokasiReverseGeocodeKey && resolvedLokasiAddressByCoordinate.has(key)) return;

    reverseGeocodeAddress(latNumber, lngNumber, { fallback: fallbackAddress })
        .then((address) => {
            if (!address) return;

            if (address !== fallbackAddress) {
                resolvedLokasiAddressByCoordinate.set(key, address);
                lastLokasiReverseGeocodeKey = key;
            }

            updateUIElement('lokasi-detail-val', address);
        });
}

function updateMapIframe(latValue, lngValue) {
    const mapIframe = document.getElementById('lokasi-map');
    const coordinateKey = getCoordinateKey(latValue, lngValue);

    if (!mapIframe || !coordinateKey || coordinateKey === lastLokasiMapKey) return;

    lastLokasiMapKey = coordinateKey;
    mapIframe.src = buildGoogleMapsEmbedUrl(latValue, lngValue);
}

function updateGpsBadge(isValid) {
    const badge = document.querySelector('.gps-badge');
    if (!badge) return;

    badge.classList.toggle('valid', Boolean(isValid));
    badge.classList.toggle('invalid', !isValid);
}

function isValidCoordinate(latValue, lngValue) {
    const latNumber = Number(latValue);
    const lngNumber = Number(lngValue);

    return Number.isFinite(latNumber)
        && Number.isFinite(lngNumber)
        && latNumber >= -90
        && latNumber <= 90
        && lngNumber >= -180
        && lngNumber <= 180;
}

function getCoordinateKey(latValue, lngValue) {
    if (!isValidCoordinate(latValue, lngValue)) return '';

    return `${Number(latValue).toFixed(4)},${Number(lngValue).toFixed(4)}`;
}

function buildGoogleMapsEmbedUrl(latValue, lngValue) {
    const latNumber = Number(latValue);
    const lngNumber = Number(lngValue);

    return `https://maps.google.com/maps?q=${latNumber},${lngNumber}&t=&z=17&ie=UTF8&iwloc=&output=embed`;
}

function cleanupLokasiMap() {
    if (window.lokasiPollingInterval) {
        clearInterval(window.lokasiPollingInterval);
        window.lokasiPollingInterval = null;
    }
}

function updateUIElement(id, value) {
    const el = document.getElementById(id);
    if (el) {
        el.textContent = value ?? '-';
    }
}

document.addEventListener('turbo:load', initLokasiMap);
document.addEventListener('DOMContentLoaded', initLokasiMap);
document.addEventListener('turbo:before-cache', cleanupLokasiMap);

if (document.readyState !== 'loading') {
    queueMicrotask(initLokasiMap);
}
