import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;
const hostname = window.location.hostname; // получения хоста

function getCookieEcho(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    // wsHost: import.meta.env.VITE_REVERB_HOST,
    wsHost: hostname,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    auth: {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
            'X-XSRF-TOKEN': decodeURIComponent(getCookieEcho('XSRF-TOKEN')), // Функция для получения CSRF-токена
            'Accept': 'application/json',
        }
    },
    authEndpoint: '/broadcasting/auth', // Убедитесь, что этот endpoint правильный
});
