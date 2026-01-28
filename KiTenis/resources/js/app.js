import './bootstrap';

// ✅ Carrega Bootstrap como módulo (inclui Popper via dependência)
import * as bootstrap from 'bootstrap';

// ✅ Se quiser usar window.bootstrap (ex.: Toast), agora existe
window.bootstrap = bootstrap;

import { initAccountPage } from './pages/account';
import './pages/home.js';

document.addEventListener('DOMContentLoaded', () => {
    initAccountPage();
});
