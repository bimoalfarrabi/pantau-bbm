import '../css/app.css';
import './bootstrap';

import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const loadingBarId = 'pantau-loading-bar';
let loadingStartedAt = 0;
let loadingFinishTimer = null;

function loadingBar() {
    let element = document.getElementById(loadingBarId);

    if (!element) {
        element = document.createElement('div');
        element.id = loadingBarId;
        document.body.appendChild(element);
    }

    return element;
}

router.on('start', (event) => {
    const isPartialReload = (event.detail.visit.only || []).length > 0;

    if (isPartialReload) {
        return;
    }

    clearTimeout(loadingFinishTimer);
    loadingStartedAt = performance.now();
    loadingBar().classList.add('is-loading');
    window.dispatchEvent(new CustomEvent('pantau:loading-start', { detail: { url: event.detail.visit.url?.href || '' } }));
});

router.on('finish', (event) => {
    const isPartialReload = (event.detail.visit.only || []).length > 0;

    if (isPartialReload) {
        return;
    }

    const elapsedMs = performance.now() - loadingStartedAt;
    const remainingMs = Math.max(420 - elapsedMs, 0);

    loadingFinishTimer = setTimeout(() => {
        loadingBar().classList.remove('is-loading');
        window.dispatchEvent(new CustomEvent('pantau:loading-finish'));
    }, remainingMs);
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: false,
});
