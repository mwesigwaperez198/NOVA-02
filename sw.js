const CACHE_NAME = 'quickshop-v1';
const STATIC_ASSETS = [
  '/index.php',
  '/assets/css/main-tailwind.css',
  '/assets/css/font-awesome.min.css',
  '/assets/img/favicon.png',
  '/assets/img/icon.png',
  '/assets/js/jquery-2.2.4.min.js',
  '/assets/js/owl.carousel.min.js',
  '/assets/js/select2.full.min.js',
  '/offline.php'
];

// Install: cache static assets
self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(STATIC_ASSETS).catch(() => {}))
  );
});

// Activate: delete old caches — this is what auto-updates the app on deploy
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k)))
    ).then(() => self.clients.claim())
  );
});

// Fetch: network-first for PHP pages, cache-first for static assets
self.addEventListener('fetch', event => {
  const url = new URL(event.request.url);

  // Skip non-GET and cross-origin requests
  if (event.request.method !== 'GET' || url.origin !== location.origin) return;

  // Cache-first for static assets (css, js, images, fonts)
  if (/\.(css|js|png|jpg|jpeg|gif|svg|woff|woff2|otf|ico)$/.test(url.pathname)) {
    event.respondWith(
      caches.match(event.request).then(cached => cached || fetch(event.request).then(res => {
        const clone = res.clone();
        caches.open(CACHE_NAME).then(cache => cache.put(event.request, clone));
        return res;
      }))
    );
    return;
  }

  // Network-first for PHP pages (always fresh from server)
  event.respondWith(
    fetch(event.request)
      .then(res => {
        const clone = res.clone();
        caches.open(CACHE_NAME).then(cache => cache.put(event.request, clone));
        return res;
      })
      .catch(() => caches.match(event.request).then(cached => cached || caches.match('/offline.php')))
  );
});
