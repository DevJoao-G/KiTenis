import axios from 'axios';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap; // garante que "bootstrap" fique disponível globalmente
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
