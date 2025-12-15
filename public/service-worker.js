/**
 * @file The service worker for the Progressive Web App (PWA).
 *
 * This script is responsible for caching application assets, intercepting network
 * requests to serve cached content when offline, and managing the cache lifecycle.
 */

/**
 * The name of the current cache version.
 * @type {string}
 */
const CACHE_NAME = 'myapp-v1';

/**
 * An array of URLs to be pre-cached during the service worker's installation.
 * @type {string[]}
 */
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

/**
 * Handles the 'install' event of the service worker.
 *
 * This event is triggered when the service worker is first installed. It opens the cache
 * and pre-caches all the URLs specified in the `URLS_TO_CACHE` array.
 *
 * @param {ExtendableEvent} event - The install event object.
 */
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(URLS_TO_CACHE))
  );
});

/**
 * Handles the 'activate' event of the service worker.
 *
 * This event is triggered when the service worker becomes active. It is used to clean up
 * old, unused caches, ensuring that only the current version of the cache is retained.
 *
 * @param {ExtendableEvent} event - The activate event object.
 */
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

/**
 * Handles the 'fetch' event to intercept network requests.
 *
 * This event listener allows the service worker to act as a proxy between the
 * application and the network. It implements a cache-first strategy:
 * 1. It checks if the requested resource is already in the cache.
 * 2. If found, it returns the cached response.
 * 3. If not found, it fetches the resource from the network.
 * 4. As a fallback, if the network request fails (e.g., the user is offline),
 *    it provides a predefined offline page for navigation requests.
 *
 * @param {FetchEvent} event - The fetch event object.
 */
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