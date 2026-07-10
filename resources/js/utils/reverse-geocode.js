const NOMINATIM_URL = 'https://nominatim.openstreetmap.org/reverse';
const CACHE_PREFIX = 'brandes:reverse-geocode:';
const LAST_REQUEST_KEY = `${CACHE_PREFIX}last-request-at`;
const CACHE_MAX_AGE_MS = 1000 * 60 * 60 * 24 * 30;
const DEFAULT_MIN_INTERVAL_MS = 1000 * 60;

const pendingRequests = new Map();

function numericCoordinate(value) {
    const number = Number(value);
    return Number.isFinite(number) ? number : null;
}

function cacheKey(lat, lng) {
    return `${CACHE_PREFIX}${lat.toFixed(4)},${lng.toFixed(4)}`;
}

function readCache(key) {
    try {
        const cached = JSON.parse(localStorage.getItem(key) || 'null');
        if (!cached || !cached.address || Date.now() - cached.storedAt > CACHE_MAX_AGE_MS) return null;
        return cached.address;
    } catch {
        return null;
    }
}

function writeCache(key, address) {
    try {
        localStorage.setItem(key, JSON.stringify({ address, storedAt: Date.now() }));
    } catch {
        // Ignore storage quota/private mode errors.
    }
}

function canRequest(minIntervalMs) {
    const lastRequestAt = Number(localStorage.getItem(LAST_REQUEST_KEY) || 0);
    return Date.now() - lastRequestAt >= minIntervalMs;
}

function markRequested() {
    try {
        localStorage.setItem(LAST_REQUEST_KEY, String(Date.now()));
    } catch {
        // Ignore storage quota/private mode errors.
    }
}

export async function reverseGeocodeAddress(latValue, lngValue, options = {}) {
    const lat = numericCoordinate(latValue);
    const lng = numericCoordinate(lngValue);
    const fallback = options.fallback || '-';
    const minIntervalMs = options.minIntervalMs ?? DEFAULT_MIN_INTERVAL_MS;

    if (lat === null || lng === null) return fallback;

    const key = cacheKey(lat, lng);
    const cachedAddress = readCache(key);
    if (cachedAddress) return cachedAddress;

    if (pendingRequests.has(key)) return pendingRequests.get(key);
    if (!canRequest(minIntervalMs)) return fallback;

    const url = new URL(NOMINATIM_URL);
    url.searchParams.set('format', 'jsonv2');
    url.searchParams.set('lat', lat.toFixed(8));
    url.searchParams.set('lon', lng.toFixed(8));
    url.searchParams.set('zoom', '18');
    url.searchParams.set('addressdetails', '1');
    url.searchParams.set('layer', 'address');
    url.searchParams.set('accept-language', 'id');

    const request = fetch(url.toString(), {
        headers: {
            Accept: 'application/json',
        },
    })
        .then((response) => {
            if (!response.ok) throw new Error(`Reverse geocode gagal: ${response.status}`);
            return response.json();
        })
        .then((data) => {
            const address = data.display_name || fallback;
            if (address !== fallback) writeCache(key, address);
            return address;
        })
        .catch(() => fallback)
        .finally(() => pendingRequests.delete(key));

    markRequested();
    pendingRequests.set(key, request);
    return request;
}
