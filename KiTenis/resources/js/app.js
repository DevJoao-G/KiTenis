import './bootstrap';
import axios from 'axios';
import './account';
import './auth';
import './header';
import { showToast } from './toast';

// deixa disponível globalmente (opcional mas prático)
window.showToast = showToast;

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
