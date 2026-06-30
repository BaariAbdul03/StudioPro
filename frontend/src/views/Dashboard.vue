<template>
  <div class="min-h-screen bg-[#0a0b10] text-on-surface font-geist flex flex-col mesh-bg relative overflow-x-hidden">
    <TopBar />

    <main class="max-w-[1500px] mx-auto px-8 py-8 space-y-8 w-full z-10 relative">
      
      <!-- Hero Section + Stats -->
      <section class="relative overflow-hidden rounded-3xl border border-white/10 p-10">
        <!-- Background glow -->
        <div class="absolute -top-32 -right-32 w-[500px] h-[500px] bg-primary/8 blur-[140px] rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-[#8B5CF6]/6 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-10">
          <div>
            <div class="flex items-center gap-2 mb-3">
              <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
              <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Workspace Active</span>
            </div>
            <h1 class="text-5xl font-extrabold text-white tracking-tight leading-tight mb-3">
              Project Dashboard
            </h1>
            <p class="text-on-surface-variant text-base max-w-xl">Manage your layouts, assets, and infrastructure in one centralized environment. Build, publish, and iterate at speed.</p>
          </div>
          <div class="flex gap-3 shrink-0">
            <button @click="openRoute('/templates/1')" class="px-5 py-2.5 border border-white/10 text-on-surface text-sm font-medium rounded-xl hover:bg-white/5 transition-all flex items-center gap-2">
              <span class="material-symbols-outlined text-[16px]">dashboard</span>
              Browse Templates
            </button>
            <button @click="isCreateModalOpen = true" class="px-5 py-2.5 bg-primary hover:brightness-110 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-primary/25 flex items-center gap-2 active:scale-95">
              <span class="material-symbols-outlined text-[16px]">add</span>
              New Project
            </button>
          </div>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 relative z-10">
          <div class="stat-card p-5 rounded-2xl">
            <div class="flex items-center justify-between mb-3">
              <div class="w-9 h-9 rounded-xl bg-primary/15 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-[18px]">folder_open</span>
              </div>
              <span class="text-[10px] font-bold text-emerald-400 bg-emerald-400/10 px-2 py-0.5 rounded-full uppercase tracking-wider">Active</span>
            </div>
            <span class="text-4xl font-black text-white block mb-1">{{ stats.projects }}</span>
            <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Projects</p>
          </div>

          <div class="stat-card p-5 rounded-2xl">
            <div class="flex items-center justify-between mb-3">
              <div class="w-9 h-9 rounded-xl bg-[#8B5CF6]/15 flex items-center justify-center">
                <span class="material-symbols-outlined text-[#8B5CF6] text-[18px]">article</span>
              </div>
            </div>
            <span class="text-4xl font-black text-white block mb-1">{{ stats.pages }}</span>
            <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Pages Built</p>
          </div>

          <div class="stat-card p-5 rounded-2xl">
            <div class="flex items-center justify-between mb-3">
              <div class="w-9 h-9 rounded-xl bg-[#10B981]/15 flex items-center justify-center">
                <span class="material-symbols-outlined text-[#10B981] text-[18px]">inventory_2</span>
              </div>
            </div>
            <span class="text-4xl font-black text-white block mb-1">{{ stats.products }}</span>
            <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Products</p>
          </div>

          <div class="stat-card p-5 rounded-2xl">
            <div class="flex items-center justify-between mb-3">
              <div class="w-9 h-9 rounded-xl bg-[#F59E0B]/15 flex items-center justify-center">
                <span class="material-symbols-outlined text-[#F59E0B] text-[18px]">database</span>
              </div>
            </div>
            <span class="text-4xl font-black text-white block mb-1">{{ stats.collections }}</span>
            <p class="text-xs font-bold uppercase tracking-widest text-gray-500">CMS Collections</p>
          </div>
        </div>
      </section>

      <!-- Recent Projects + Quick Panel -->
      <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Recent Projects -->
        <div class="lg:col-span-2 glass-card p-8 rounded-3xl">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h2 class="text-xl font-bold text-white tracking-tight">Recent Projects</h2>
              <p class="text-xs text-gray-500 mt-0.5">Continue where you left off</p>
            </div>
            <button class="text-xs text-[#3b82f6] font-semibold hover:underline flex items-center gap-1">
              View All <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </button>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- New Project Card -->
            <div class="group cursor-pointer flex flex-col justify-center items-center bg-white/3 rounded-2xl border-2 border-dashed border-white/10 hover:border-primary/60 hover:bg-primary/5 transition-all min-h-[200px] gap-3" @click="isCreateModalOpen = true">
              <div class="w-10 h-10 rounded-full bg-primary/15 flex items-center justify-center text-primary group-hover:scale-110 group-hover:bg-primary/25 transition-all">
                <span class="material-symbols-outlined text-[20px]">add</span>
              </div>
              <div class="text-center">
                <h4 class="text-white font-semibold text-sm">New Project</h4>
                <p class="text-[10px] text-gray-500 mt-0.5">Start from scratch</p>
              </div>
            </div>

            <!-- Dynamic Project Cards -->
            <div v-for="(project, index) in projects.slice(0, 5)" :key="project.id" class="group cursor-pointer flex flex-col justify-between bg-white/3 rounded-2xl border border-white/5 overflow-hidden hover:border-primary/40 hover:shadow-lg hover:shadow-primary/5 transition-all" @click="openEditor(project.id)">
              <div class="aspect-video relative overflow-hidden bg-[#12141c]">
                <div v-if="project.pages && project.pages.length > 0" class="w-[400%] h-[400%] origin-top-left scale-[0.25] pointer-events-none absolute inset-0">
                  <iframe class="w-full h-full border-none bg-white pointer-events-none" :srcdoc="getLivePreview(project)"></iframe>
                </div>
                <div v-else class="absolute inset-0 flex items-center justify-center">
                  <span class="material-symbols-outlined text-4xl text-[#434656] opacity-30">view_quilt</span>
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent pointer-events-none"></div>
                <div class="absolute bottom-3 left-3">
                  <span class="text-[10px] font-bold text-white bg-primary px-2 py-0.5 rounded-full">{{ project.pages_count || 0 }} pages</span>
                </div>
                <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                  <span class="w-8 h-8 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-[14px]">edit</span>
                  </span>
                </div>
              </div>
              <div class="p-4">
                <h4 class="text-white font-semibold text-sm truncate mb-0.5">{{ project.name }}</h4>
                <p class="text-[11px] text-gray-500">{{ new Date(project.updated_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }}</p>
                <button class="w-full mt-3 py-1.5 bg-white/5 rounded-lg text-xs font-medium hover:bg-white/10 transition-colors border border-white/5 text-on-surface group-hover:bg-primary/10 group-hover:border-primary/20 group-hover:text-primary">
                  Open Editor
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Panel -->
        <div class="flex flex-col gap-6">
          <!-- Quick Links -->
          <div class="glass-card p-6 rounded-3xl flex-1">
            <h2 class="text-base font-bold text-white tracking-tight mb-4">Workspace Tools</h2>
            <div class="grid grid-cols-2 gap-2.5">
              <button @click="navigateToTool('/templates')" class="quick-link-btn group">
                <div class="w-8 h-8 rounded-lg bg-primary/15 flex items-center justify-center mb-2 group-hover:bg-primary/30 transition-colors">
                  <span class="material-symbols-outlined text-primary text-[16px]">dashboard</span>
                </div>
                <span class="text-xs font-semibold text-white">Templates</span>
              </button>
              <button @click="navigateToTool('/cms')" class="quick-link-btn group">
                <div class="w-8 h-8 rounded-lg bg-[#8B5CF6]/15 flex items-center justify-center mb-2 group-hover:bg-[#8B5CF6]/30 transition-colors">
                  <span class="material-symbols-outlined text-[#8B5CF6] text-[16px]">database</span>
                </div>
                <span class="text-xs font-semibold text-white">CMS</span>
              </button>
              <button @click="navigateToTool('/inventory')" class="quick-link-btn group">
                <div class="w-8 h-8 rounded-lg bg-[#10B981]/15 flex items-center justify-center mb-2 group-hover:bg-[#10B981]/30 transition-colors">
                  <span class="material-symbols-outlined text-[#10B981] text-[16px]">shopping_bag</span>
                </div>
                <span class="text-xs font-semibold text-white">Commerce</span>
              </button>
              <button @click="navigateToTool('/settings')" class="quick-link-btn group">
                <div class="w-8 h-8 rounded-lg bg-[#F59E0B]/15 flex items-center justify-center mb-2 group-hover:bg-[#F59E0B]/30 transition-colors">
                  <span class="material-symbols-outlined text-[#F59E0B] text-[16px]">settings</span>
                </div>
                <span class="text-xs font-semibold text-white">Settings</span>
              </button>
            </div>
          </div>

          <!-- System Status -->
          <div class="glass-card p-6 rounded-3xl">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-base font-bold text-white tracking-tight">System Status</h2>
              <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
            </div>
            <div class="space-y-3">
              <div class="flex justify-between items-center text-xs py-2 border-b border-white/5">
                <span class="text-on-surface-variant">API Server</span>
                <span class="text-emerald-400 font-bold flex items-center gap-1.5">
                  <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> Online
                </span>
              </div>
              <div class="flex justify-between items-center text-xs py-2 border-b border-white/5">
                <span class="text-on-surface-variant">API Base URL</span>
                <span class="font-mono text-gray-300 text-[10px] truncate max-w-[120px]" :title="store.apiBaseUrl">{{ store.apiBaseUrl }}</span>
              </div>
              <div class="flex justify-between items-center text-xs py-2 border-b border-white/5">
                <span class="text-on-surface-variant">Environment</span>
                <span class="text-primary font-bold uppercase text-[10px]">Development</span>
              </div>
              <div class="flex justify-between items-center text-xs py-2">
                <span class="text-on-surface-variant">Version</span>
                <span class="text-gray-300 font-mono text-[10px]">v1.0.0</span>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <Footer />

    <!-- Create Project Modal -->
    <div v-if="isCreateModalOpen" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
      <div class="bg-surface-container border border-outline rounded-2xl p-8 w-full max-w-md shadow-2xl">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-white">Create New Project</h3>
          <button @click="isCreateModalOpen = false" class="text-gray-500 hover:text-white transition-colors">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        <form @submit.prevent="createProject" class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Project Name *</label>
            <input v-model="newProject.name" type="text" class="w-full bg-editor-bg border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:border-primary outline-none transition-colors" placeholder="My Awesome Site" required>
          </div>
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">URL Slug *</label>
            <input v-model="newProject.slug" type="text" class="w-full bg-editor-bg border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:border-primary outline-none transition-colors" placeholder="my-awesome-site" required>
          </div>
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Description</label>
            <textarea v-model="newProject.description" rows="2" class="w-full bg-editor-bg border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:border-primary outline-none transition-colors resize-none" placeholder="Optional description..."></textarea>
          </div>
          <div class="flex justify-end gap-3 pt-2">
            <button type="button" @click="isCreateModalOpen = false" class="px-4 py-2 text-sm text-on-surface-variant hover:text-white transition-colors">Cancel</button>
            <button type="submit" class="px-5 py-2 bg-primary hover:brightness-110 text-white rounded-xl text-sm font-bold transition-all active:scale-95" :disabled="isCreating">
              {{ isCreating ? 'Creating...' : 'Create Project' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import TopBar from '../components/editor/TopBar.vue'
import Footer from '../components/Footer.vue'
import { useWorkspaceStore } from '../stores/workspace'

const router = useRouter()
const store = useWorkspaceStore()
const projects = ref([])
const isCreateModalOpen = ref(false)
const isCreating = ref(false)
const newProject = ref({ name: '', slug: '', description: '' })
const stats = ref({ projects: 0, pages: 0, products: 0, collections: 0 })

onMounted(async () => {
  try {
    const res = await axios.get(`${store.apiBaseUrl}/projects`)
    projects.value = res.data
    stats.value.projects = projects.value.length
    stats.value.pages = projects.value.reduce((sum, p) => sum + (p.pages_count || 0), 0)
    stats.value.products = projects.value.reduce((sum, p) => sum + (p.products_count || 0), 0)
    stats.value.collections = projects.value.reduce((sum, p) => sum + (p.collections_count || 0), 0)
  } catch (e) {
    console.error('Failed to load projects', e)
  }
})

const createProject = async () => {
  isCreating.value = true
  try {
    const res = await axios.post(`${store.apiBaseUrl}/projects`, newProject.value)
    projects.value.unshift(res.data)
    stats.value.projects = projects.value.length
    isCreateModalOpen.value = false
    newProject.value = { name: '', slug: '', description: '' }
    const firstPageId = res.data.pages?.[0]?.id || 1
    router.push(`/editor/${res.data.id}/${firstPageId}`)
  } catch (e) {
    console.error('Failed to create project', e)
  } finally {
    isCreating.value = false
  }
}

const openEditor = (projectId) => {
  const p = projects.value.find(proj => proj.id === projectId)
  const firstPageId = p?.pages?.[0]?.id || 1
  router.push(`/editor/${projectId}/${firstPageId}`)
}

const getLivePreview = (project) => {
  if (!project.pages || project.pages.length === 0) return ''
  const page = project.pages[0]
  let html = '', css = ''
  if (page.content) {
    try {
      const data = typeof page.content === 'string' ? JSON.parse(page.content) : page.content
      html = data.html || ''
      css = data.css || ''
    } catch (e) {
      console.error(e)
    }
  } else {
    html = page.html || ''
    css = page.css || ''
  }
  return `<!doctype html><html><head><link rel="stylesheet" href="/canvas.css"><script src="https://cdn.tailwindcss.com"></scr` + `ipt><style>${css}</style></head><body style="margin:0; overflow:hidden;">${html}</body></html>`
}

const openRoute = (to) => router.push(to)

const navigateToTool = (toolPath) => {
  if (projects.value.length === 0) {
    alert('Please create a project first.')
    return
  }
  const activeId = projects.value[0].id
  router.push(`${toolPath}/${activeId}`)
}
</script>

<style scoped>
.stat-card {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.07);
  transition: all 0.2s ease;
}
.stat-card:hover {
  background: rgba(255, 255, 255, 0.05);
  border-color: rgba(46, 98, 255, 0.3);
}
.glass-card {
  background: rgba(255, 255, 255, 0.02);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.07);
}
.quick-link-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 12px 8px;
  background: rgba(255,255,255,0.03);
  border: 1px solid rgba(255,255,255,0.06);
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.2s ease;
  text-align: center;
}
.quick-link-btn:hover {
  background: rgba(255,255,255,0.06);
  border-color: rgba(255,255,255,0.12);
  transform: translateY(-1px);
}
.mesh-bg {
  background-color: #0a0b10;
  background-image: 
    radial-gradient(at 15% 20%, rgba(46, 98, 255, 0.1) 0px, transparent 50%),
    radial-gradient(at 85% 5%, rgba(139, 92, 246, 0.08) 0px, transparent 50%),
    radial-gradient(at 50% 80%, rgba(16, 185, 129, 0.04) 0px, transparent 50%);
}
</style>
