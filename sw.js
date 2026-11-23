/**
 * MTsN 1 Blitar - Modern Progressive Web App (PWA) Service Worker
 */

const CACHE_NAME = 'mtsn1-cache-v3';
const OFFLINE_URL = 'offline.html';

const PRECACHE_ASSETS = [
  './',
  'manifest.json',
  'offline.html',
  'pwa-icon-192.png',
  'pwa-icon-512.png'
];

// Install Event: Activate immediately
self.addEventListener('install', (event) => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(PRECACHE_ASSETS).catch(() => {});
    })
  );
});

// Activate Event: Claim all clients immediately & purge obsolete caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    Promise.all([
      self.clients.claim(),
      caches.keys().then((cacheNames) => {
        return Promise.all(
          cacheNames.map((name) => {
            if (name !== CACHE_NAME) {
              return caches.delete(name);
            }
          })
        );
      })
    ])
  );
});

// Fetch Event
self.addEventListener('fetch', (event) => {
  // Only handle GET requests and valid http/https schemes
  if (event.request.method !== 'GET') return;
  if (!event.request.url.startsWith('http')) return;

  const url = new URL(event.request.url);

  // If requesting an HTML navigation page: Network First with offline fallback
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request)
        .then((response) => {
          if (response && response.status === 200) {
            try {
              const responseClone = response.clone();
              caches.open(CACHE_NAME).then((cache) => {
                cache.put(event.request, responseClone).catch(() => {});
              }).catch(() => {});
            } catch (err) {}
          }
          return response;
        })
        .catch(async () => {
          try {
            const cachedResponse = await caches.match(event.request);
            if (cachedResponse) {
              return cachedResponse;
            }
            return caches.match(OFFLINE_URL);
          } catch (e) {
            return caches.match(OFFLINE_URL);
          }
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
        const fetchPromise = fetch(event.request)
          .then((networkResponse) => {
            if (networkResponse && (networkResponse.status === 200 || networkResponse.type === 'opaque')) {
              try {
                const responseToCache = networkResponse.clone();
                caches.open(CACHE_NAME).then((cache) => {
                  cache.put(event.request, responseToCache).catch(() => {});
                }).catch(() => {});
              } catch (e) {
                // If clone fails for any reason, ignore gracefully
              }
            }
            return networkResponse;
          })
          .catch(() => cachedResponse);

        return cachedResponse || fetchPromise;
      })
    );
    return;
  }

  // Default: Network with cache fallback
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    }).catch(() => fetch(event.request))
  );
});
