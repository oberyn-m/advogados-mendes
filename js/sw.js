const CACHE_NAME = 'advogados-mendes-v1';
const urlsToCache = [
    '/',
    '/css/style.css',
    '/js/main.js',
    '/img/hero-bg.jpg',
    '/img/about-img.jpg',
    '/img/team-1.jpg',
    '/img/team-2.jpg',
    '/img/team-3.jpg',
    '/img/team-4.jpg',
    '/img/testimonial-1.jpg',
    '/img/testimonial-2.jpg',
    '/img/testimonial-3.jpg',
    '/img/contact-bg.jpg'
];

// Instalação do Service Worker
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Cache aberto');
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