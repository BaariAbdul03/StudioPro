import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import './style.css'

localStorage.removeItem('studiopro_token')
localStorage.removeItem('studiopro_user')

createApp(App).use(createPinia()).use(router).mount('#app')
