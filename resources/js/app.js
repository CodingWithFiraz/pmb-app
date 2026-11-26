import './bootstrap';
import { renderStaticHejHeroGradient } from './hej-sphere-gradient';

function initProgressiveBlurUpImages() {
    document.querySelectorAll('img.img-blur-up').forEach((img) => {
        const markLoaded = () => {
            requestAnimationFrame(() => {
                img.classList.add('is-loaded');
            });
        };

        if (img.complete && img.naturalWidth > 0) {
            markLoaded();
        } else {
            img.addEventListener('load', markLoaded, { once: true });
            img.addEventListener('error', markLoaded, { once: true });
        }
    });
}

function initApp() {
    renderStaticHejHeroGradient();
    initProgressiveBlurUpImages();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initApp);
} else {
    initApp();
}
