const CACHE_NAME = 'myapp-v1';
const URLS_TO_CACHE = [
  '/',
  '/index.php',
  '/styles/main.css',
  '/scripts/app.js',
  '/images/icon-192x192.png',
  '/images/icon-512x512.png',
  '/offline.html'
  // add other static assets
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(URLS_TO_CACHE))
  );
});

self.addEventListener('activate', event => {
  // Optional: cleanup old caches
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys.filter(key => key !== CACHE_NAME)
            .map(oldKey => caches.delete(oldKey))
      )
    )
  );
});

self.addEventListener('fetch', event => {
  // For navigation requests, you might want an offline fallback page
  event.respondWith(
    caches.match(event.request).then(cachedResp => {
      if (cachedResp) {
        return cachedResp;
      }
      return fetch(event.request)
        .then(networkResp => {
          // Optionally cache new requests dynamically:
          return caches.open(CACHE_NAME).then(cache => {
            // Only cache GET requests and same-origin resources
            if (event.request.method === 'GET' && event.request.url.startsWith(self.origin)) {
              cache.put(event.request, networkResp.clone());
            }
            return networkResp;
          });
        })
        .catch(() => {
          // Optionally return a fallback HTML for navigations:
          if (event.request.mode === 'navigate') {
            return caches.match('/offline.html');
          }
        });
    })
  );
});