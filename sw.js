/**
 * MTsN 1 Blitar - Modern Progressive Web App (PWA) Service Worker
 */

const CACHE_NAME = 'mtsn1-cache-v2';
const OFFLINE_URL = 'offline.html';

const PRECACHE_ASSETS = [
  './',
  'manifest.json',
  'offline.html',
  'pwa-icon-192.png',
  'pwa-icon-512.png'
];

// Install Event
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(PRECACHE_ASSETS);
    })
  );
  self.skipWaiting();
});

// Activate Event
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((name) => {
          if (name !== CACHE_NAME) {
            return caches.delete(name);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Fetch Event
self.addEventListener('fetch', (event) => {
  // Only handle GET requests
  if (event.request.method !== 'GET') return;

  const url = new URL(event.request.url);

  // If requesting an HTML navigation page: Network First with offline fallback
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request)
        .then((response) => {
          // Clone and cache the freshly fetched page
          if (response && response.status === 200) {
            const responseClone = response.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(event.request, responseClone);
            }).catch(() => {});
          }
          return response;
        })
        .catch(async () => {
          // Check if the page is in cache
          const cachedResponse = await caches.match(event.request);
          if (cachedResponse) {
            return cachedResponse;
          }
          // Fallback to offline.html
          return caches.match(OFFLINE_URL);
        })
    );
    return;
  }

  // Static Assets (CSS, JS, Fonts, Images): Stale-While-Revalidate
  if (
    url.pathname.match(/\.(css|js|woff2?|ttf|png|jpe?g|webp|svg|ico)$/i)
  ) {
    event.respondWith(
      caches.match(event.request).then((cachedResponse) => {
        const fetchPromise = fetch(event.request).then((networkResponse) => {
          if (networkResponse && (networkResponse.status === 200 || networkResponse.type === 'opaque')) {
            const responseToCache = networkResponse.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(event.request, responseToCache);
            }).catch(() => {});
          }
          return networkResponse;
        }).catch(() => {/* Ignore network errors for background revalidation */});

        return cachedResponse || fetchPromise;
      })
    );
    return;
  }

  // Default: Network with cache fallback
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});
