<template>
  <div class="min-h-screen bg-[#0B0E14] text-[#dae2fd] font-geist overflow-y-auto">
    <header class="h-14 bg-[#161B22] border-b border-[#434656] flex items-center justify-between px-6 sticky top-0 z-40">
      <div class="flex items-center gap-8">
        <div class="text-white font-extrabold text-lg tracking-tight">StudioPro</div>
        <nav class="hidden md:flex items-center gap-2 text-[13px]">
          <button class="px-3 py-1.5 text-[#2E62FF] bg-[#2E62FF]/10 rounded-sm font-bold">Projects</button>
          <button @click="openRoute('/templates/1')" class="px-3 py-1.5 text-[#c3c5d8] hover:text-white hover:bg-[#2d3449] rounded-sm">Templates</button>
          <button @click="openRoute('/cms/1')" class="px-3 py-1.5 text-[#c3c5d8] hover:text-white hover:bg-[#2d3449] rounded-sm">CMS</button>
          <button @click="openRoute('/inventory/1')" class="px-3 py-1.5 text-[#c3c5d8] hover:text-white hover:bg-[#2d3449] rounded-sm">Commerce</button>
        </nav>
      </div>
      <button @click="openEditor(1)" class="bg-[#2E62FF] hover:bg-blue-600 text-white px-5 py-1.5 rounded-sm text-[13px] font-bold transition">
        Open Editor
      </button>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8">
      <section class="grid grid-cols-1 lg:grid-cols-[1.4fr_0.8fr] gap-6 mb-8">
        <div class="bg-[#161B22] border border-[#434656] rounded-lg p-6">
          <div class="flex items-start justify-between gap-6">
            <div>
              <p class="text-[11px] text-[#8d90a2] uppercase tracking-widest font-bold">Workspace</p>
              <h1 class="text-3xl font-black text-white mt-2">Project Dashboard</h1>
              <p class="text-[#c3c5d8] text-sm mt-3 max-w-2xl">
                Manage pages, product inventory, CMS collections, and templates for the StudioPro visual builder.
              </p>
            </div>
            <div class="hidden sm:flex h-16 w-16 rounded-lg bg-[#2E62FF] items-center justify-center shadow-lg shadow-[#2E62FF]/20">
              <span class="material-symbols-outlined text-white text-[32px]">dashboard</span>
            </div>
          </div>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-8">
            <div v-for="stat in stats" :key="stat.label" class="bg-[#0B0E14] border border-[#434656] rounded p-3">
              <div class="text-2xl font-black text-white">{{ stat.value }}</div>
              <div class="text-[11px] text-[#8d90a2] uppercase tracking-wider mt-1">{{ stat.label }}</div>
            </div>
          </div>
        </div>

        <div class="bg-[#161B22] border border-[#434656] rounded-lg p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-bold text-white uppercase tracking-wider">Quick Actions</h2>
            <span class="text-[10px] font-mono text-[#10B981]">LOCAL READY</span>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <button v-for="action in actions" :key="action.label" @click="openRoute(action.to)" class="p-4 bg-[#0B0E14] border border-[#434656] hover:border-[#2E62FF] rounded text-left transition">
              <span class="material-symbols-outlined text-[#2E62FF]">{{ action.icon }}</span>
              <div class="text-sm font-bold text-white mt-2">{{ action.label }}</div>
              <div class="text-[11px] text-[#8d90a2] mt-1">{{ action.help }}</div>
            </button>
          </div>
        </div>
      </section>

      <section class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6">
        <div class="bg-[#161B22] border border-[#434656] rounded-lg overflow-hidden">
          <div class="px-5 py-4 border-b border-[#434656] flex items-center justify-between">
            <h2 class="text-sm font-bold text-white uppercase tracking-wider">Projects</h2>
            <span class="text-[11px] text-[#8d90a2]">{{ loading ? 'Syncing...' : `${projects.length} available` }}</span>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 p-5">
            <button v-for="project in projects" :key="project.id" @click="openEditor(project.id)" class="group text-left bg-[#0B0E14] border border-[#434656] hover:border-[#2E62FF] rounded-lg overflow-hidden transition">
              <div class="aspect-[16/10] bg-[#1F2937] relative canvas-grid">
                <div class="absolute inset-4 bg-white rounded shadow-xl overflow-hidden">
                  <div class="h-10 bg-slate-50 border-b border-slate-200"></div>
                  <div class="p-4 space-y-2">
                    <div class="h-4 w-2/3 bg-[#2E62FF] rounded"></div>
                    <div class="h-2 w-full bg-slate-200 rounded"></div>
                    <div class="h-2 w-4/5 bg-slate-200 rounded"></div>
                  </div>
                </div>
              </div>
              <div class="p-4">
                <div class="flex items-center justify-between gap-3">
                  <h3 class="text-white font-bold">{{ project.name }}</h3>
                  <span class="material-symbols-outlined text-[#8d90a2] group-hover:text-[#2E62FF]">arrow_forward</span>
                </div>
                <p class="text-xs text-[#8d90a2] mt-1 line-clamp-2">{{ project.description || 'Visual page builder workspace' }}</p>
              </div>
            </button>
          </div>
        </div>

        <aside class="bg-[#161B22] border border-[#434656] rounded-lg p-5">
          <h2 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Phase 4 Coverage</h2>
          <div class="space-y-3">
            <div v-for="item in coverage" :key="item.label" class="flex items-start gap-3">
              <span class="material-symbols-outlined text-[#10B981] text-[18px]">check_circle</span>
              <div>
                <div class="text-sm text-white font-semibold">{{ item.label }}</div>
                <div class="text-[11px] text-[#8d90a2]">{{ item.detail }}</div>
              </div>
            </div>
          </div>
        </aside>
      </section>
    </main>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useWorkspaceStore } from '../stores/workspace'

const router = useRouter()
const store = useWorkspaceStore()

const loading = ref(true)
const projects = ref([
  {
    id: 1,
    name: 'Project Alpha',
    slug: 'project-alpha',
    description: 'Demo workspace with visual editor, commerce inventory, templates, and CMS collections.'
  }
])

const stats = computed(() => [
  { label: 'Projects', value: projects.value.length },
  { label: 'Pages', value: 1 },
  { label: 'Products', value: 4 },
  { label: 'Collections', value: 2 }
])

const actions = [
  { label: 'Visual Editor', icon: 'edit_square', to: '/editor/1/1', help: 'Canvas and panels' },
  { label: 'Commerce', icon: 'storefront', to: '/inventory/1', help: 'Inventory manager' },
  { label: 'CMS', icon: 'database', to: '/cms/1', help: 'Collections and items' },
  { label: 'Templates', icon: 'dashboard', to: '/templates/1', help: 'Starter layouts' }
]

const coverage = [
  { label: 'Custom editor shell', detail: 'GrapesJS canvas with custom sidebars and style controls.' },
  { label: 'Commerce flow', detail: 'Product inventory, product blocks, and cart preview.' },
  { label: 'CMS manager', detail: 'Collections, fields, content items, and binding block.' },
  { label: 'Template library', detail: 'Reusable layouts that write into the active page.' }
]

const openEditor = (projectId) => {
  router.push(`/editor/${projectId}/1`)
}

const openRoute = (to) => {
  router.push(to)
}

onMounted(async () => {
  try {
    const res = await fetch(`${store.apiBaseUrl}/projects`)
    if (res.ok) {
      const data = await res.json()
      if (Array.isArray(data) && data.length) {
        projects.value = data
      }
    }
  } catch (err) {
    console.warn('Using local dashboard fallback data', err)
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.canvas-grid {
  background-image: radial-gradient(#334155 1px, transparent 1px);
  background-size: 24px 24px;
}
</style>
