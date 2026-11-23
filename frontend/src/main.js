import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import './style.css'
import './workspace.css'
import './appearance.css'
import './editor.css'

try {
  localStorage.removeItem('studiopro_token')
  localStorage.removeItem('studiopro_user')
} catch {
  // The cookie-based session works when local storage is disabled.
}

createApp(App).use(createPinia()).use(router).mount('#app')
