const CACHE_NAME = 'peminjaman-v1';
const urlsToCache = [
  '/',
  '/manifest.json',
];

// ✅ INSTALL - cache files
self.addEventListener('install', event => {
  console.log('🔧 Service Worker installing...');
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      console.log('✅ Cache opened');
      // Hanya cache manifest, assets akan di-cache saat diakses
      return cache.addAll(urlsToCache).catch(err => {
        console.warn('⚠️ Some files not cached:', err);
        return Promise.resolve(); // Continue meski ada error
      });
    })
  );
  self.skipWaiting();
});

// ✅ ACTIVATE - cleanup old caches
self.addEventListener('activate', event => {
  console.log('🔄 Service Worker activating...');
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            console.log('🧹 Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// ✅ FETCH - network first, fallback to cache
self.addEventListener('fetch', event => {
  // Skip non-GET requests
  if (event.request.method !== 'GET') return;

  // Skip chrome extensions
  if (event.request.url.startsWith('chrome-extension://')) return;

  event.respondWith(
    fetch(event.request)
      .then(response => {
        // Cache successful responses
        if (!response || response.status !== 200 || response.type === 'error') {
          return response;
        }

        const responseToCache = response.clone();
        caches.open(CACHE_NAME).then(cache => {
          cache.put(event.request, responseToCache);
        });

        return response;
      })
      .catch(() => {
        // Return cached version if offline
        return caches.match(event.request).then(cachedResponse => {
          if (cachedResponse) {
            console.log('📦 Serving from cache:', event.request.url);
            return cachedResponse;
          }

          // Return offline page jika ada
          if (event.request.mode === 'navigate') {
            return caches.match('/');
          }
        });
      })
  );
});