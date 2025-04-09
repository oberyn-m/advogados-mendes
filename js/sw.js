const CACHE_NAME = 'advogados-mendes-v1';
const urlsToCache = [
    '/advogados-mendes/',
    '/advogados-mendes/css/main.css',
    '/advogados-mendes/js/main.js',
    '/advogados-mendes/js/articles.js',
    '/advogados-mendes/js/articles-data.js',
    '/advogados-mendes/js/modules/navigation.js',
    '/advogados-mendes/js/modules/forms.js',
    '/advogados-mendes/img/placeholder.jpg',
    '/advogados-mendes/img/lawyer.jpg',
    '/advogados-mendes/img/hero-bg.jpg',
    '/advogados-mendes/uploads/67f04eb34d578.jpg',
    '/advogados-mendes/uploads/67f04eeef0043.jpg',
    '/advogados-mendes/uploads/67f56467b3bd4.jpg',
    '/advogados-mendes/uploads/67f56fecba5b2.jpg'
];

// Instalação do Service Worker
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache);
            })
    );
});

// Ativação do Service Worker
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// Interceptação de requisições
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                if (response) {
                    return response;
                }
                return fetch(event.request)
                    .then(response => {
                        if (!response || response.status !== 200 || response.type !== 'basic') {
                            return response;
                        }
                        const responseToCache = response.clone();
                        caches.open(CACHE_NAME)
                            .then(cache => {
                                cache.put(event.request, responseToCache);
                            });
                        return response;
                    });
            })
    );
}); 