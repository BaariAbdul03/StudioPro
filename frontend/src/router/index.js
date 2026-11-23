import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useProjectList } from '../stores/projects'

const portfolio = import.meta.env.MODE === 'portfolio' || import.meta.env.VITE_PUBLIC_DEMO === 'true'
const protectedRoutes = portfolio ? [] : [
  { path: '/', name: 'dashboard', component: () => import('../views/Dashboard.vue') },
  { path: '/editor/:projectId/:pageId', name: 'editor', component: () => import('../views/Editor.vue') },
  { path: '/inventory/:projectId?', name: 'inventory', component: () => import('../views/InventoryManager.vue') },
  { path: '/cms/:projectId?', name: 'cms', component: () => import('../views/CmsManager.vue') },
  { path: '/templates/:projectId?', name: 'templates', component: () => import('../views/TemplateLibrary.vue') },
  { path: '/settings/:projectId?', name: 'settings', component: () => import('../views/Settings.vue') },
  { path: '/account', name: 'account', component: () => import('../views/Account.vue') },
  { path: '/docs', name: 'docs', component: () => import('../views/Documentation.vue') }
].map(route => ({ ...route, meta: { requiresAuth: true } }))

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || (to.hash === '#faq' ? { el: '#faq' } : { top: 0 })
  },
  routes: [
    { path: '/demo', name: 'demo', component: () => import('../views/Editor.vue'), props: { demo: true } },
    { path: '/showcase', name: 'showcase', component: () => import('../views/Showcase.vue') },
    { path: '/welcome', name: 'welcome', component: () => import('../views/Portfolio.vue') },
    ...['about', 'privacy', 'terms'].map(kind => ({
      path: '/' + kind,
      name: kind,
      component: () => import('../views/SiteInformation.vue'),
      props: { kind }
    })),
    {
      path: '/error/:status(403|404|500|503)',
      name: 'error',
      component: () => import('../views/ErrorPage.vue'),
      props: true
    },
    ...(portfolio
      ? [{ path: '/', name: 'portfolio', component: () => import('../views/Portfolio.vue') }]
      : [
          { path: '/login', name: 'login', component: () => import('../views/Login.vue') },
          { path: '/register', name: 'register', component: () => import('../views/Register.vue') },
          ...protectedRoutes
        ]),
    { path: '/:pathMatch(.*)*', component: () => import('../views/ErrorPage.vue'), props: { status: '404' } }
  ]
})

router.beforeEach(async to => {
  if (!to.meta.requiresAuth) return
  const auth = useAuthStore()
  try {
    if (!auth.user && !await auth.session()) return { name: 'login' }
  } catch {
    return { name: 'error', params: { status: '503' }, query: { from: to.fullPath } }
  }
  if (to.name === 'settings' && to.query.section === 'account') return { name: 'account' }
  if (!to.params.projectId && ['cms', 'inventory'].includes(to.name)) {
    const projects = useProjectList()
    await projects.load()
    if (projects.current) return { name: to.name, params: { projectId: projects.current.id }, replace: true }
  }
})

router.afterEach(to => {
  const titles = { about: 'About us', privacy: 'Privacy policy', terms: 'Terms & conditions', error: 'Error' }
  document.title = titles[to.name] ? titles[to.name] + ' — StudioPro' : 'StudioPro'
})

export default router
