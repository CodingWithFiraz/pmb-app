import './bootstrap';
import { renderStaticHejHeroGradient } from './hej-sphere-gradient';

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', renderStaticHejHeroGradient);
} else {
    renderStaticHejHeroGradient();
}
