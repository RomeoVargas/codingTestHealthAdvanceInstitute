const API_VERSION = import.meta.env.VITE_API_VERSION || 'v1';
const BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost';

window.axios.defaults.baseURL = `${BASE_URL}/api/${API_VERSION}`;
window.axios.defaults.withCredentials = true;
