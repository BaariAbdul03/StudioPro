import { defineStore } from 'pinia'
import { computed, ref, watch } from 'vue'
import { apiFetch } from '../api'
import { useAuthStore } from './auth'

export const useProjectList = defineStore('project-list', () => {
  const auth = useAuthStore()
  const projects = ref([])
  const selectedId = ref('')
  const loading = ref(false)
  const error = ref('')
  const current = computed(() => projects.value.find(project => String(project.id) === selectedId.value) || projects.value[0])
  let pending

  watch(() => auth.user?.id, () => {
    projects.value = []
    selectedId.value = ''
    loading.value = false
    error.value = ''
    pending = undefined
  }, { flush: 'sync' })

  function load() {
    if (pending) return pending
    const userId = auth.user?.id
    if (!userId) return Promise.resolve()
    loading.value = true
    error.value = ''
    const request = apiFetch('/api/projects').then(async response => {
      if (!response.ok) throw new Error('Could not load projects.')
      const list = await response.json()
      if (auth.user?.id === userId) projects.value = list
    }).catch(() => {
      if (auth.user?.id === userId) error.value = 'Projects could not be loaded. Try again.'
    }).finally(() => {
      if (pending !== request) return
      pending = undefined
      loading.value = false
    })
    pending = request
    return request
  }

  function select(id) {
    selectedId.value = String(id)
  }

  async function create(name, page) {
    const slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '').slice(0, 230) || 'project'
    const response = await apiFetch('/api/projects', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ name, slug: slug + '-' + crypto.randomUUID().slice(0, 8), page })
    })
    if (!response.ok) throw new Error('Could not create the project. Try again.')
    const project = await response.json()
    projects.value = [project, ...projects.value]
    select(project.id)
    return project
  }

  return { projects, current, loading, error, load, select, create }
})
