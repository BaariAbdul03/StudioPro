import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'
import Editor from '../views/Editor.vue'
import InventoryManager from '../views/InventoryManager.vue'
import CmsManager from '../views/CmsManager.vue'
import TemplateLibrary from '../views/TemplateLibrary.vue'
import Settings from '../views/Settings.vue'
import Documentation from '../views/Documentation.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: Login,
      meta: { guest: true }
    },
    {
      path: '/register',
      name: 'register',
      component: Register,
      meta: { guest: true }
    },
    {
      path: '/',
      name: 'dashboard',
      component: Dashboard,
      meta: { requiresAuth: true }
    },
    {
      path: '/editor/:projectId/:pageId',
      name: 'editor',
      component: Editor,
      meta: { requiresAuth: true }
    },
    {
      path: '/inventory/:projectId',
      name: 'inventory',
      component: InventoryManager,
      meta: { requiresAuth: true }
    },
    {
      path: '/cms/:projectId',
      name: 'cms',
      component: CmsManager,
      meta: { requiresAuth: true }
    },
    {
      path: '/templates/:projectId',
      name: 'templates',
      component: TemplateLibrary,
      meta: { requiresAuth: true }
    },
    {
      path: '/settings/:projectId',
      name: 'settings',
      component: Settings,
      meta: { requiresAuth: true }
    },
    {
      path: '/docs',
      name: 'docs',
      component: Documentation,
      meta: { requiresAuth: true }
    }
  ]
})

router.beforeEach((to, from) => {
  const token = localStorage.getItem('studiopro_token')
  const isAuthenticated = !!token

  if (to.meta.requiresAuth && !isAuthenticated) {
    return '/login'
  } else if (to.meta.guest && isAuthenticated) {
    return '/'
  }
})

export default router
