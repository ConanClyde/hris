import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo is loaded asynchronously after DOM ready to reduce initial bundle parse time
 * and allow code-splitting (see app.js).
 */
