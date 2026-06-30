import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import './style.css'
import axios from 'axios'

// 1. Intercept native window.fetch
window.originalFetch = window.fetch;
window.fetch = async function (url, options = {}) {
  const token = localStorage.getItem('studiopro_token');
  if (token) {
    options.headers = options.headers || {};
    if (options.headers instanceof Headers) {
      if (!options.headers.has('Authorization')) {
        options.headers.set('Authorization', `Bearer ${token}`);
      }
    } else {
      if (!options.headers['Authorization']) {
        options.headers['Authorization'] = `Bearer ${token}`;
      }
    }
  }
  
  const response = await window.originalFetch(url, options);
  
  if (response.status === 401 && !url.includes('/login') && !url.includes('/register')) {
    localStorage.removeItem('studiopro_token');
    localStorage.removeItem('studiopro_user');
    window.location.href = '/login';
  }
  return response;
};

// 2. Intercept Axios
axios.interceptors.request.use(config => {
  const token = localStorage.getItem('studiopro_token');
  if (token) {
    config.headers['Authorization'] = `Bearer ${token}`;
  }
  return config;
}, error => {
  return Promise.reject(error);
});

axios.interceptors.response.use(response => {
  return response;
}, error => {
  if (error.response && error.response.status === 401 && !error.config.url.includes('/login') && !error.config.url.includes('/register')) {
    localStorage.removeItem('studiopro_token');
    localStorage.removeItem('studiopro_user');
    window.location.href = '/login';
  }
  return Promise.reject(error);
});

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
