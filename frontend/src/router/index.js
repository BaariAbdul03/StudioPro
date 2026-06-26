import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'
import Editor from '../views/Editor.vue'
import InventoryManager from '../views/InventoryManager.vue'
import CmsManager from '../views/CmsManager.vue'
import TemplateLibrary from '../views/TemplateLibrary.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'dashboard',
      component: Dashboard
    },
    {
      path: '/editor/:projectId/:pageId',
      name: 'editor',
      component: Editor
    },
    {
      path: '/inventory/:projectId',
      name: 'inventory',
      component: InventoryManager
    },
    {
      path: '/cms/:projectId',
      name: 'cms',
      component: CmsManager
    },
    {
      path: '/templates/:projectId',
      name: 'templates',
      component: TemplateLibrary
    }
  ]
})

export default router
