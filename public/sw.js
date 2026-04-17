// Service worker minimal — rend l'app installable (PWA).
// Stratégie : network-first (l'app admin doit TOUJOURS être à jour).
// Fallback offline uniquement sur /admin/login (les autres pages dépendent de la BDD).

const CACHE = 'vp-cal-v1';
const OFFLINE_URL = '/admin/login';

self.addEventListener('install', (event) => {
    event.waitUntil((async () => {
        const cache = await caches.open(CACHE);
        await cache.add(OFFLINE_URL);
        self.skipWaiting();
    })());
});

self.addEventListener('activate', (event) => {
    event.waitUntil((async () => {
        // Nettoyer les vieux caches
        const keys = await caches.keys();
        await Promise.all(keys.filter(k => k !== CACHE).map(k => caches.delete(k)));
        await self.clients.claim();
    })());
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;
    event.respondWith((async () => {
        try {
            return await fetch(event.request);
        } catch {
            const cache = await caches.open(CACHE);
            const cached = await cache.match(OFFLINE_URL);
            return cached || new Response('Hors ligne', { status: 503, statusText: 'Service Unavailable' });
        }
    })());
});
