<template>
  <div class="h-screen w-full flex flex-col bg-[#0B0E14] text-[#dae2fd] overflow-hidden select-none font-geist">
    <TopBar />

    <div class="flex-1 flex overflow-hidden pt-1">
      <!-- Left Sidebar: Static Navigation for templates -->
      <aside class="w-[240px] lg:w-[280px] bg-[#161B22] border-r border-[#434656] flex flex-col z-40">
        <div class="p-4 flex flex-col gap-4 border-b border-[#434656]">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-[#2d3449] overflow-hidden">
              <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDnPXAr2i9QgLP0rw4YrtIPrxWSlRFATrdAJJy7p857zKFodbw27-d5Lqql9RD1e26GDA2EO4Q0rlaJ62jaeWwVJ8H4qGWGFwANYcc4056E8AtgD_E6snDUSwKNE-hVFpcnDNtZJlxabhHQ0P9mEN9vY93B72D5RgNNjDAfu1vVzZtwjQ8e40d0r115eIbVRjUuv2tgMjCaXe2AAoAPMTMZ79urXcVlhd1ysmsRiZEED48My8iQkkME5pviUWOrlG-lBzkQgj1cyymd">
            </div>
            <div>
              <div class="text-[11px] font-bold uppercase tracking-wider text-[#8d90a2]">Project Alpha</div>
              <div class="text-[12px] text-[#8d90a2]">V1.0.4</div>
            </div>
          </div>
        </div>
        <nav class="flex-1 py-4">
          <div class="px-4 mb-2 text-[#8d90a2] text-[11px] uppercase tracking-wider">Navigator</div>
          <div class="flex flex-col">
            <a @click="routeTo('pages')" class="flex items-center gap-2 px-4 py-2 text-[#c3c5d8] hover:bg-[#2d3449] transition-all cursor-pointer text-[13px]">
              <span class="material-symbols-outlined text-[18px]">layers</span> Pages
            </a>
            <a @click="routeTo('cms')" class="flex items-center gap-2 px-4 py-2 text-[#c3c5d8] hover:bg-[#2d3449] transition-all cursor-pointer text-[13px]">
              <span class="material-symbols-outlined text-[18px]">database</span> CMS
            </a>
            <a @click="routeTo('templates')" class="flex items-center gap-2 px-4 py-2 bg-[#2E62FF]/15 text-[#2E62FF] border-l-2 border-[#2E62FF] transition-all cursor-pointer text-[13px]">
              <span class="material-symbols-outlined text-[18px]">add_box</span> Templates
            </a>
            <a @click="routeTo('inventory')" class="flex items-center gap-2 px-4 py-2 text-[#c3c5d8] hover:bg-[#2d3449] transition-all cursor-pointer text-[13px]">
              <span class="material-symbols-outlined text-[18px]">storefront</span> Commerce
            </a>
          </div>
        </nav>
        <div class="p-4 mt-auto flex flex-col gap-3">
          <div class="flex flex-col gap-2 mt-2">
            <a @click="showHelp = true" class="flex items-center gap-2 px-2 py-1 text-[#c3c5d8] hover:text-white transition-colors text-[12px] cursor-pointer">
              <span class="material-symbols-outlined text-[16px]">help</span> Help
            </a>
            <a @click="showFeedback = true" class="flex items-center gap-2 px-2 py-1 text-[#c3c5d8] hover:text-white transition-colors text-[12px] cursor-pointer">
              <span class="material-symbols-outlined text-[16px]">chat_bubble</span> Feedback
            </a>
          </div>
        </div>
      </aside>

      <!-- Main Bento Grid Canvas -->
      <main class="flex-1 overflow-y-auto bg-[#0B0E14] px-6 lg:px-10 py-8 custom-scrollbar">
        <div class="max-w-5xl mx-auto">
          <!-- Header and Search -->
          <div class="mb-8 flex justify-between items-end">
            <div>
              <h1 class="text-3xl font-black text-white leading-tight">Template Library</h1>
              <p class="text-[#c3c5d8] text-sm mt-2 max-w-xl">
                Kickstart your visual editor layout with one of our hand-crafted, high-performance website templates.
              </p>
            </div>
            <div>
              <div class="relative group">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-[#8d90a2] group-focus-within:text-[#2E62FF] text-[18px]">search</span>
                <input 
                  v-model="searchQuery" 
                  class="bg-[#0B0E14] border border-[#434656] text-white text-xs h-9 pl-10 pr-4 rounded focus:border-[#2E62FF] focus:ring-0 outline-none transition-all w-64 placeholder:text-[#8d90a2]" 
                  placeholder="Search templates..." 
                  type="text"
                >
              </div>
            </div>
          </div>

          <!-- Category Filter Pills -->
          <div class="flex gap-2 mb-8 overflow-x-auto pb-2 no-scrollbar">
            <button 
              @click="selectedCategory = 'all'" 
              class="px-4 py-1.5 rounded-full text-xs font-bold transition-all"
              :class="selectedCategory === 'all' ? 'bg-[#2E62FF] text-white' : 'bg-[#161B22] text-[#c3c5d8] hover:text-white'"
            >
              All Templates
            </button>
            <button 
              v-for="cat in ['Business', 'Portfolio', 'Blog', 'E-commerce']" 
              :key="cat"
              @click="selectedCategory = cat"
              class="px-4 py-1.5 rounded-full text-xs font-bold transition-all"
              :class="selectedCategory === cat ? 'bg-[#2E62FF] text-white' : 'bg-[#161B22] text-[#c3c5d8] hover:text-white'"
            >
              {{ cat }}
            </button>
          </div>

          <!-- Bento Grid Gallery -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Large Feature Card -->
            <div 
              v-if="featuredTemplate" 
              class="group relative bg-[#161B22] border border-[#434656] rounded-lg overflow-hidden md:col-span-2 hover:border-[#2E62FF]/50 transition-all duration-300 shadow-xl flex flex-col justify-between"
            >
              <div class="aspect-[16/9] w-full relative overflow-hidden bg-[#060e20]">
                <img :src="featuredTemplate.image" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                <div class="absolute top-3 right-3 bg-[#2E62FF] text-white font-mono text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wider">FEATURED</div>
                
                <div class="absolute inset-0 bg-gradient-to-t from-[#161B22]/90 via-[#161B22]/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                  <div class="flex gap-2 w-full max-w-sm">
                    <button @click="useTemplate(featuredTemplate)" class="flex-1 py-2 bg-white text-[#0B0E14] text-xs font-bold rounded hover:bg-[#dae2fd] transition-colors">Use This Template</button>
                    <button @click="previewTemplate(featuredTemplate)" class="px-4 py-2 bg-[#0B0E14]/80 text-white border border-white/20 text-xs font-bold rounded hover:bg-[#0B0E14] transition-colors">Quick View</button>
                  </div>
                </div>
              </div>
              <div class="p-4 flex justify-between items-center border-t border-[#434656]">
                <div>
                  <h3 class="text-[15px] font-bold text-white">{{ featuredTemplate.name }}</h3>
                  <p class="text-xs text-[#8d90a2] mt-0.5">{{ featuredTemplate.description }}</p>
                </div>
                <div class="flex gap-2">
                  <button @click="previewTemplate(featuredTemplate)" class="px-3 py-1.5 border border-[#434656] text-[#c3c5d8] rounded text-xs font-bold hover:text-white">Preview</button>
                  <button @click="useTemplate(featuredTemplate)" class="px-3 py-1.5 bg-[#2E62FF] text-white rounded text-xs font-bold">Use</button>
                </div>
              </div>
            </div>

            <!-- Normal Grid Cards -->
            <div 
              v-for="tmpl in normalTemplates" 
              :key="tmpl.id"
              class="group bg-[#161B22] border border-[#434656] rounded-lg overflow-hidden hover:border-[#2E62FF]/50 transition-all duration-300 flex flex-col justify-between"
            >
              <div class="aspect-square relative overflow-hidden bg-[#060e20]">
                <img :src="tmpl.image" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                <div class="absolute inset-0 bg-[#0B0E14]/75 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-center items-center gap-2 p-4">
                  <button @click="useTemplate(tmpl)" class="w-full py-2 bg-[#2E62FF] text-white text-xs font-bold rounded hover:brightness-110 active:scale-95 transition-all">Use This Template</button>
                  <button @click="previewTemplate(tmpl)" class="w-full py-2 bg-transparent text-white border border-white/30 text-xs font-bold rounded hover:bg-white/10 active:scale-95 transition-all">Quick View</button>
                </div>
              </div>
              <div class="p-3 border-t border-[#434656]">
                <h3 class="text-[13px] font-bold text-white">{{ tmpl.name }}</h3>
                <p class="text-[11px] text-[#8d90a2] mt-0.5">{{ tmpl.category }} / {{ tmpl.type }}</p>
                <div class="flex gap-2 mt-3">
                  <button @click="previewTemplate(tmpl)" class="flex-1 py-1.5 border border-[#434656] text-[#c3c5d8] rounded text-xs font-bold hover:text-white">Preview</button>
                  <button @click="useTemplate(tmpl)" class="flex-1 py-1.5 bg-[#2E62FF] text-white rounded text-xs font-bold">Use</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="filteredTemplates.length === 0" class="py-16 text-center text-[#8d90a2] text-sm border border-dashed border-[#434656] rounded-lg">
            No templates matching search or category found.
          </div>

          <!-- Load More -->
          <div class="mt-12 border-t border-[#434656] pt-8 text-center pb-8">
            <button class="px-8 py-2.5 bg-[#161B22] hover:bg-[#2d3449] text-[#c3c5d8] text-xs font-bold uppercase tracking-widest rounded transition-all">
              Load More Templates
            </button>
            <div class="mt-3 text-[11px] font-mono text-[#8d90a2]">
              Showing {{ filteredTemplates.length }} of 12 available templates
            </div>
          </div>
        </div>
      </main>

      <!-- Right Sidebar: Contextual Sidebar for Templates -->
      <aside class="hidden xl:flex w-[280px] bg-[#161B22] border-l border-[#434656] flex-col z-40">
        <div class="p-4 border-b border-[#434656]">
          <div class="text-[12px] font-bold text-white mb-1">Theme Properties</div>
          <div class="text-[11px] font-mono text-[#8d90a2] uppercase">Selected: Template Filter</div>
        </div>
        <div class="p-4 flex flex-col gap-6">
          <div class="flex flex-col gap-2">
            <span class="text-[12px] text-[#c3c5d8]">Quick Filters</span>
            <div class="grid grid-cols-2 gap-1.5">
              <button 
                @click="selectedCategory = 'all'" 
                class="rounded-sm py-1.5 text-xs font-semibold"
                :class="selectedCategory === 'all' ? 'bg-[#2E62FF] text-white' : 'bg-[#0B0E14] text-[#c3c5d8] hover:bg-[#2d3449]'"
              >
                All
              </button>
              <button 
                @click="selectedCategory = 'Business'" 
                class="rounded-sm py-1.5 text-xs font-semibold"
                :class="selectedCategory === 'Business' ? 'bg-[#2E62FF] text-white' : 'bg-[#0B0E14] text-[#c3c5d8] hover:bg-[#2d3449]'"
              >
                Business
              </button>
              <button 
                @click="selectedCategory = 'Portfolio'" 
                class="rounded-sm py-1.5 text-xs font-semibold"
                :class="selectedCategory === 'Portfolio' ? 'bg-[#2E62FF] text-white' : 'bg-[#0B0E14] text-[#c3c5d8] hover:bg-[#2d3449]'"
              >
                Portfolio
              </button>
              <button 
                @click="selectedCategory = 'E-commerce'" 
                class="rounded-sm py-1.5 text-xs font-semibold"
                :class="selectedCategory === 'E-commerce' ? 'bg-[#2E62FF] text-white' : 'bg-[#0B0E14] text-[#c3c5d8] hover:bg-[#2d3449]'"
              >
                Commerce
              </button>
            </div>
          </div>

          <div class="flex flex-col gap-2">
            <span class="text-[12px] text-[#c3c5d8]">Layout Density</span>
            <div class="flex items-center bg-[#0B0E14] border border-[#434656] rounded p-1">
              <div 
                @click="density = 'compact'" 
                class="flex-1 text-center py-1.5 rounded-sm font-mono text-[11px] cursor-pointer transition-all"
                :class="density === 'compact' ? 'bg-[#2d3449] text-white' : 'text-[#8d90a2] hover:text-[#c3c5d8]'"
              >
                Compact
              </div>
              <div 
                @click="density = 'relaxed'" 
                class="flex-1 text-center py-1.5 rounded-sm font-mono text-[11px] cursor-pointer transition-all"
                :class="density === 'relaxed' ? 'bg-[#2d3449] text-white' : 'text-[#8d90a2] hover:text-[#c3c5d8]'"
              >
                Relaxed
              </div>
            </div>
          </div>

          <div class="flex flex-col gap-2">
            <span class="text-[12px] text-[#c3c5d8]">Theme Accent</span>
            <div class="flex gap-2">
              <div 
                v-for="color in ['#2E62FF', '#8B5CF6', '#10B981', '#EF4444']" 
                :key="color"
                @click="themeColor = color"
                class="w-6 h-6 rounded-full cursor-pointer transition-all hover:scale-110"
                :style="{ backgroundColor: color }"
                :class="themeColor === color ? 'ring-2 ring-white ring-offset-2 ring-offset-[#161B22]' : ''"
              ></div>
            </div>
          </div>
        </div>
      </aside>
    </div>

    <!-- Footer Status Bar -->
    <footer class="bg-[#060e20] border-t border-[#434656] fixed bottom-0 left-0 w-full h-8 flex justify-between items-center px-6 z-40">
      <div class="flex items-center gap-4">
        <span class="text-[11px] text-[#8d90a2]">© 2024 StudioPro Engine</span>
        <div class="flex items-center gap-2">
          <span class="text-[11px] font-mono text-[#8d90a2] hover:text-[#2E62FF] cursor-default transition-colors">
            Templates &gt; List View
          </span>
        </div>
      </div>
      <div class="flex items-center gap-4">
        <span class="text-[11px] font-mono text-[#8d90a2] hover:text-[#2E62FF] transition-colors cursor-default">Zoom 100%</span>
        <div class="flex items-center gap-1 text-[#8d90a2] hover:text-[#2E62FF] transition-colors cursor-default">
          <span class="material-symbols-outlined text-[14px]">help</span>
          <span class="text-[11px] font-mono">Canvas Help</span>
        </div>
      </div>
    </footer>

    <!-- Toast message -->
    <div 
      v-if="toastMessage" 
      class="fixed bottom-12 right-6 z-50 bg-[#2E62FF] border border-[#2E62FF]/50 text-white text-xs font-bold uppercase tracking-wider px-4 py-3 rounded shadow-xl flex items-center gap-2 animate-fade-in"
    >
      <span>✓</span> {{ toastMessage }}
    </div>

    <div v-if="previewingTemplate" class="fixed inset-0 z-[60] bg-black/75 backdrop-blur-sm flex items-center justify-center p-6">
      <div class="w-[min(1040px,100%)] h-[min(720px,100%)] bg-[#161B22] border border-[#434656] rounded-lg shadow-2xl flex flex-col overflow-hidden">
        <div class="h-12 border-b border-[#434656] px-4 flex items-center justify-between">
          <div>
            <div class="text-white text-sm font-bold">{{ previewingTemplate.name }}</div>
            <div class="text-[#8d90a2] text-[11px]">{{ previewingTemplate.category }} / {{ previewingTemplate.type }}</div>
          </div>
          <div class="flex gap-2">
            <button @click="useTemplate(previewingTemplate)" class="px-4 py-1.5 bg-[#2E62FF] text-white rounded text-xs font-bold">Use Template</button>
            <button @click="previewingTemplate = null" class="px-3 py-1.5 border border-[#434656] text-[#c3c5d8] rounded text-xs hover:text-white">Close</button>
          </div>
        </div>
        <iframe class="flex-1 bg-white" :srcdoc="previewSrcDoc"></iframe>
      </div>
    </div>

    <div v-if="showHelp || showFeedback" class="fixed inset-0 z-[60] bg-black/70 backdrop-blur-sm flex items-center justify-center">
      <div class="w-[420px] bg-[#161B22] border border-[#434656] rounded shadow-2xl p-5">
        <h3 class="text-white text-sm font-bold mb-2">{{ showHelp ? 'Template Help' : 'Send Feedback' }}</h3>
        <p v-if="showHelp" class="text-sm text-[#c3c5d8] leading-relaxed">Preview opens full template in a sandbox. Use Template writes HTML/CSS to Home Page and opens editor.</p>
        <textarea v-else v-model="feedbackText" class="w-full h-28 bg-[#0B0E14] border border-[#434656] rounded text-sm text-white p-2 outline-none" placeholder="What should improve?"></textarea>
        <div class="flex justify-end gap-2 mt-5">
          <button @click="showHelp = false; showFeedback = false" class="px-4 py-2 border border-[#434656] text-[#c3c5d8] rounded text-xs hover:text-white">Close</button>
          <button v-if="showFeedback" @click="showFeedback = false; showToast('Feedback saved locally')" class="px-4 py-2 bg-[#2E62FF] text-white rounded text-xs font-bold">Send</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useWorkspaceStore } from '../stores/workspace'
import TopBar from '../components/editor/TopBar.vue'

const router = useRouter()
const route = useRoute()
const store = useWorkspaceStore()
const projectId = route.params.projectId || 1

const searchQuery = ref('')
const selectedCategory = ref('all')
const density = ref('compact')
const themeColor = ref('#2E62FF')
const toastMessage = ref('')
const previewingTemplate = ref(null)
const showHelp = ref(false)
const showFeedback = ref(false)
const feedbackText = ref('')
const previewSrcDoc = computed(() => {
  if (!previewingTemplate.value) return ''
  return `<!doctype html><html><head><link rel="stylesheet" href="/canvas.css"></head><body style="margin:0">${previewingTemplate.value.html}<style>${previewingTemplate.value.css || ''}</style></body></html>`
})

const showToast = (msg) => {
  toastMessage.value = msg
  setTimeout(() => {
    toastMessage.value = ''
  }, 3000)
}

const templates = ref([
  {
    id: 1,
    name: 'Nexus Corporate',
    category: 'Business',
    type: 'Multi-page Corporate',
    description: 'A visual, high-performance solution layout configured for modern SaaS and enterprise brands.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDPgnCltFznEKuryWolGQhL-HevusVbdKtnGDv0jppQjP-pIJZ6EEYgZ8-gcmgLFE6LuOXJPxhigjwkqG2TG-5TuM_gGoZlb6lFU8hbdrkr28oGiuYBcKglJlPla6_MNYsUQg8EEGUzemm_IE9FGy5ORtgdjyjNNbzaOb3qkxMDLO43gMSD5CLK9CqSeFJp973KfmgpX8hR9s1IfHbDoVqj9BSWgAQFb8oUfa5qsUK5eXVdKskRmELtCeLke12tHhqdMYAq6DUY4ta9',
    featured: true,
    html: `<div class="w-full bg-[#0B0E14] text-white py-20 px-8">
  <div class="max-w-6xl mx-auto flex flex-col items-center text-center gap-6">
    <span class="text-[#2E62FF] text-xs font-bold uppercase tracking-wider">Nexus Systems</span>
    <h1 class="text-5xl font-black max-w-3xl leading-tight">Empowering Modern Enterprises with Visual Analytics</h1>
    <p class="text-gray-400 text-lg max-w-xl font-medium">Fully automated platform for real-time visualization of corporate workflows, data schemas, and design guidelines.</p>
    <button class="bg-[#2E62FF] text-white px-6 py-2.5 rounded font-bold hover:brightness-110 active:scale-95 transition-all">Get Started Today</button>
  </div>
</div>
<div class="max-w-6xl mx-auto py-16 px-4 grid grid-cols-3 gap-6 bg-[#0b1326] text-white">
  <div class="bg-[#161B22] border border-[#434656] p-6 rounded">
    <span class="material-symbols-outlined text-[#2E62FF] text-3xl mb-4">analytics</span>
    <h3 class="text-lg font-bold mb-2">Real-time Analytics</h3>
    <p class="text-gray-400 text-xs leading-relaxed">Instantly stream telemetry logs, analytics reports, and inventory counts straight to your dashboard.</p>
  </div>
  <div class="bg-[#161B22] border border-[#434656] p-6 rounded">
    <span class="material-symbols-outlined text-[#8B5CF6] text-3xl mb-4">speed</span>
    <h3 class="text-lg font-bold mb-2">Maximum Performance</h3>
    <p class="text-gray-400 text-xs leading-relaxed">Built on high-fidelity visual rendering engines for optimized memory footprints and low latencies.</p>
  </div>
  <div class="bg-[#161B22] border border-[#434656] p-6 rounded">
    <span class="material-symbols-outlined text-[#10B981] text-3xl mb-4">security</span>
    <h3 class="text-lg font-bold mb-2">Enterprise Security</h3>
    <p class="text-gray-400 text-xs leading-relaxed">Role-based access permissions, end-to-end encrypted databases, and robust token sessions.</p>
  </div>
</div>`,
    css: ''
  },
  {
    id: 2,
    name: 'Mono Minimalist',
    category: 'Portfolio',
    type: 'Creative Portfolio',
    description: 'Systematic minimalist architectural grids and soft tonal details.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAN3B6P1lPaWHYNA3Csa28TbYlRJhTOFDjQhCr40QSi-qUfpdbrBUhxgRl_qMXV-ngRAoZcntWm4eox3K1z_tFXCB4CbtT5s2vEtvzx1ARnporM-K4T1RmgQ6pJYHaVh-P2914m2jNcq97SHOl47eNayCHC7bCERj3TcPmOhZZQNZe1MXTue3e_qCdlukNy1Evi6DI5hBbUSyT1l3hsvqbcsaOzh321xJhgLZiJSvqSYvrTmgO3v6CCMk7HNR3SJ22kONEyicBy6SqK',
    html: `<div class="w-full bg-[#0B0E14] text-[#dae2fd] py-16 px-8 font-geist">
  <div class="max-w-6xl mx-auto flex justify-between items-end border-b border-[#434656] pb-8 mb-12">
    <div>
      <h1 class="text-3xl font-bold tracking-tight text-white">ARTHUR RIVERA</h1>
      <p class="text-gray-500 text-sm mt-1">Principal Visual Artist & Architectural Photographer</p>
    </div>
    <div class="text-right text-xs text-[#2E62FF] font-mono">Selected Works 2024 - 2025</div>
  </div>
  <div class="max-w-6xl mx-auto grid grid-cols-2 gap-8">
    <div class="group relative overflow-hidden bg-slate-900 border border-[#434656] rounded-lg">
      <img class="w-full aspect-[4/3] object-cover opacity-80" src="/images/arch_hero_01.png">
      <div class="p-4 bg-[#161B22] border-t border-[#434656]">
        <h3 class="text-white text-sm font-bold">Slate Monolith</h3>
        <p class="text-gray-500 text-xs mt-1">Architectural photography in blue hour slate tones.</p>
      </div>
    </div>
    <div class="group relative overflow-hidden bg-slate-900 border border-[#434656] rounded-lg">
      <img class="w-full aspect-[4/3] object-cover opacity-80" src="/images/texture_vibe.png">
      <div class="p-4 bg-[#161B22] border-t border-[#434656]">
        <h3 class="text-white text-sm font-bold">Liquid Platinum</h3>
        <p class="text-gray-500 text-xs mt-1">Abstract 3D renders focusing on organic metallic flows.</p>
      </div>
    </div>
  </div>
</div>`
  },
  {
    id: 3,
    name: 'Chronicle Press',
    category: 'Blog',
    type: 'Editorial Blog',
    description: 'News agency grid layout with sharp borders and distinct type treatments.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAQt6rBKDKEIr-oLhJNkZZ7WmSGz6yCb2Ty-NqpDq6d7sQ-QzV3SyUKNF35rdMicyIJSCkpKxunWdu3d0I5Lbi20r0uhLAbDpGNI74-EnTHzXCEbkHa_bYDnGa7vn96o35i5PzjCk_h4WmS67DpQbv63VmgZyd37wrmA4i5U8z_lE8UkqmvfuLikRRkn7DrIWhWzsnvhYUpaghmDYh645ziA6wpQLBR5ad-RfTszloIp6kKBdbJfmqMhBYgGDnDrrEzz2Q3O_bgQp8O',
    html: `<div class="w-full bg-[#0B0E14] text-white py-12 px-8 font-geist">
  <div class="max-w-6xl mx-auto flex flex-col gap-8">
    <div class="border-b border-[#434656] pb-4">
      <h1 class="text-4xl font-extrabold tracking-tight">CHRONICLE EDITORIAL</h1>
    </div>
    <div class="grid grid-cols-3 gap-6">
      <div class="col-span-2 flex flex-col gap-4">
        <img class="w-full aspect-[2/1] object-cover rounded border border-[#434656]" src="/images/arch_hero_01.png">
        <h2 class="text-2xl font-black">Future of Design Systems in 2025</h2>
        <p class="text-gray-400 text-sm leading-relaxed">The evolution of layout managers, headless visual interfaces, and dynamic components represents a systemic shift in product architecture...</p>
      </div>
      <div class="flex flex-col gap-6 border-l border-[#434656]/50 pl-6">
        <div>
          <h4 class="text-xs font-bold text-[#8B5CF6] uppercase tracking-wider">TRENDING</h4>
          <h3 class="text-md font-bold mt-1">Optimizing WebGL Renderers</h3>
          <p class="text-gray-400 text-xs mt-1">Sarah Chen dives deep into latency reduction profiles.</p>
        </div>
        <div class="border-t border-[#434656]/30 pt-4">
          <h4 class="text-xs font-bold text-[#2E62FF] uppercase tracking-wider">POPULAR</h4>
          <h3 class="text-md font-bold mt-1">Emotional Design Depth</h3>
          <p class="text-gray-400 text-xs mt-1">Why modern minimalist interfaces evoke calm focus.</p>
        </div>
      </div>
    </div>
  </div>
</div>`
  },
  {
    id: 4,
    name: 'Vogue Store',
    category: 'E-commerce',
    type: 'Luxury Retail',
    description: 'Sleek dark store template with catalog grid and Stripe bindings.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuD-NdTkuoH99UfYKjIQJSgRjka2lfkUXxz1SgQUmAkLZtVGKA3FsZDh120oiI38ZGkI_jCz206NCG8586S4LCNOVSg1H--loAkr4YAY-3N8RZkzyQtyTiEWkt-hXo3rNADCOwFFEUbOz6ATuXs2vppTrWAV_V85s0SHW5_ixU9fxhN_LGXtXVFtpmGBS2Va2p1mDNAAeqw57m76Ak8AajrEBCa6nHdPfB_kP1p9gGeOvt4OeYZ2IM27dRAglsjylsEJIAcf_npiTf3z',
    html: `<div class="w-full bg-[#0B0E14] py-12 px-8 font-geist text-white">
  <div class="max-w-6xl mx-auto text-center mb-12">
    <span class="text-[#8B5CF6] text-xs font-bold uppercase tracking-wider">Vogue Collection</span>
    <h1 class="text-4xl font-extrabold text-white mt-2">Curated Premium Workspace Tools</h1>
    <p class="text-gray-400 text-sm max-w-md mx-auto mt-2">Elevate your creative desk setup with our high-end, pro-grade aesthetic peripherals.</p>
  </div>
  <div class="max-w-6xl mx-auto grid grid-cols-3 gap-8">
    <div class="bg-[#161B22] border border-[#434656] rounded overflow-hidden flex flex-col">
      <img class="w-full aspect-square object-cover" src="/images/product_cam.png">
      <div class="p-4 flex-1 flex flex-col justify-between">
        <div>
          <h3 class="text-white font-bold text-md">Minimalist Desk Lamp</h3>
          <p class="text-gray-500 text-xs mt-1">Sleek matte black desk lighting.</p>
        </div>
        <div class="flex justify-between items-center mt-6">
          <span class="text-white font-bold text-lg">$129.00</span>
          <button class="bg-[#2E62FF] hover:bg-blue-600 text-white text-xs px-4 py-2 rounded font-bold active:scale-95 transition-all">Add to Cart</button>
        </div>
      </div>
    </div>
    <div class="bg-[#161B22] border border-[#434656] rounded overflow-hidden flex flex-col">
      <img class="w-full aspect-square object-cover" src="/images/vector_flat.png">
      <div class="p-4 flex-1 flex flex-col justify-between">
        <div>
          <h3 class="text-white font-bold text-md">Wireless Mechanical Keyboard</h3>
          <p class="text-gray-500 text-xs mt-1">Sleek wireless layout with custom blue LEDs.</p>
        </div>
        <div class="flex justify-between items-center mt-6">
          <span class="text-white font-bold text-lg">$199.00</span>
          <button class="bg-[#2E62FF] hover:bg-blue-600 text-white text-xs px-4 py-2 rounded font-bold active:scale-95 transition-all">Add to Cart</button>
        </div>
      </div>
    </div>
    <div class="bg-[#161B22] border border-[#434656] rounded overflow-hidden flex flex-col">
      <img class="w-full aspect-square object-cover" src="/images/arch_hero_01.png">
      <div class="p-4 flex-1 flex flex-col justify-between">
        <div>
          <h3 class="text-white font-bold text-md">Pro Audio Studio Monitor</h3>
          <p class="text-gray-500 text-xs mt-1">High-fidelity noise canceling monitors.</p>
        </div>
        <div class="flex justify-between items-center mt-6">
          <span class="text-white font-bold text-lg">$349.00</span>
          <button class="bg-[#2E62FF] hover:bg-blue-600 text-white text-xs px-4 py-2 rounded font-bold active:scale-95 transition-all">Add to Cart</button>
        </div>
      </div>
    </div>
  </div>
</div>`
  },
  {
    id: 5,
    name: 'Ignite SaaS',
    category: 'Business',
    type: 'SaaS Platform',
    description: 'High energy marketing landing page with glassmorphism panels and bold grids.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCt_Lucm-5_pEgDacphyIgdstoYm7D67kOb2EX7whiNnUZiAT_XWU0OR-o62SM_uyRSmrPTqUr_gYF9b_eUX-ZmlxhKYpnr9sLAjd9X3U2hooKiOnQsYFj3JFLyjMFMn2uuuApxa2BMTeGTQJM0UGlFLHMtxcbhvQVlqmus1XNa-qN4XVvUDvBFHzgWXtnux0poE2Uew0T-fPlPC_94sgWsQsA2O87lhSQXEuKo3aMwJtGcmAe0YiAidSMjG67yWWBtxOTJjYD0e870',
    html: `<div class="w-full bg-[#0B0E14] text-white py-16 px-8 font-geist">
  <div class="max-w-6xl mx-auto flex flex-col items-center text-center gap-4">
    <div class="bg-[#8B5CF6]/10 text-[#8B5CF6] text-[10px] font-bold px-3 py-1 rounded-full border border-[#8B5CF6]/20">IGNITE PLATFORM</div>
    <h1 class="text-5xl font-black tracking-tight leading-none">Automate Design-to-Code Workflows</h1>
    <p class="text-gray-400 text-sm max-w-lg mt-2">Bring layouts to life with custom WAAPI timelines, direct Stripe sessions, and Dynamic database CMS mappings.</p>
    <div class="flex gap-4 mt-6">
      <button class="px-6 py-2.5 bg-[#8B5CF6] rounded text-xs font-bold hover:bg-purple-600 transition-all">Deploy Now</button>
      <button class="px-6 py-2.5 border border-[#434656] text-white rounded text-xs hover:bg-[#161B22] transition-all">Request Demo</button>
    </div>
  </div>
</div>`
  }
])

const filteredTemplates = computed(() => {
  let list = templates.value
  
  if (selectedCategory.value !== 'all') {
    list = list.filter(t => t.category === selectedCategory.value)
  }

  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    list = list.filter(t => t.name.toLowerCase().includes(q) || t.description.toLowerCase().includes(q))
  }

  return list
})

const featuredTemplate = computed(() => {
  return filteredTemplates.value.find(t => t.featured) || filteredTemplates.value[0]
})

const normalTemplates = computed(() => {
  const feat = featuredTemplate.value
  if (!feat) return filteredTemplates.value
  return filteredTemplates.value.filter(t => t.id !== feat.id)
})

const useTemplate = async (template) => {
  const pageId = 1
  const useLocalTemplate = () => {
    localStorage.setItem('studiopro.pendingTemplate', JSON.stringify({
      projectId: String(projectId),
      pageId: String(pageId),
      name: template.name,
      html: template.html,
      css: template.css || ''
    }))
    showToast(`Loaded template: ${template.name}`)
    setTimeout(() => {
      router.push(`/editor/${projectId}/${pageId}`)
    }, 600)
  }

  try {
    const response = await fetch(`${store.apiBaseUrl}/projects/${projectId}/pages/${pageId}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        title: 'Home Page',
        slug: 'home',
        html: template.html,
        css: template.css || '',
        components: [],
        styles: []
      })
    })
    if (response.ok) {
      showToast(`Loaded template: ${template.name}`)
      setTimeout(() => {
        router.push(`/editor/${projectId}/${pageId}`)
      }, 1000)
    } else {
      useLocalTemplate()
    }
  } catch (err) {
    console.error(err)
    useLocalTemplate()
  }
}

const previewTemplate = (template) => {
  previewingTemplate.value = template
}

const routeTo = (view) => {
  if (view === 'pages') router.push(`/editor/${projectId}/1`)
  if (view === 'cms') router.push(`/cms/${projectId}`)
  if (view === 'templates') router.push(`/templates/${projectId}`)
  if (view === 'inventory') router.push(`/inventory/${projectId}`)
}
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 0;
  height: 0;
  display: none;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: transparent;
}
.custom-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
.animate-fade-in {
  animation: fadeIn 0.2s ease-out forwards;
}
</style>
