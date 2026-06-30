<template>
  <div class="h-screen w-full flex flex-col bg-surface text-on-surface overflow-hidden select-none font-geist mesh-bg relative">
    <TopBar />

    <div class="flex-1 overflow-y-auto px-8 py-8 custom-scrollbar z-10 relative">
      <div class="max-w-[1400px] mx-auto space-y-8">
        
        <!-- Header and Search -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
          <div class="space-y-3 max-w-2xl">
            <div class="flex items-center gap-2">
              <span class="w-2 h-2 bg-primary rounded-full"></span>
              <span class="text-xs font-bold text-primary uppercase tracking-widest">Template Library</span>
            </div>
            <h1 class="text-4xl font-bold text-white tracking-tight leading-tight">Find Your Perfect Template</h1>
            <p class="text-on-surface-variant text-sm leading-relaxed max-w-xl">
              Kickstart your creative workflow with our curated selection of high-performance website templates. Optimized for speed and precision.
            </p>
          </div>
          <div class="pb-2">
            <div class="relative group">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-[#434656] text-[18px]">search</span>
              <input 
                v-model="searchQuery" 
                class="bg-transparent border border-white/10 text-white text-xs h-10 pl-11 pr-4 rounded-lg focus:border-primary outline-none transition-all w-80 placeholder:text-[#434656]" 
                placeholder="Search templates..." 
                type="text"
              >
            </div>
          </div>
        </div>

        <!-- Category Filter Pills -->
        <div class="flex gap-4 overflow-x-auto pb-2 no-scrollbar">
          <button 
            @click="selectedCategory = 'all'" 
            class="flex items-center gap-2 px-6 py-2.5 rounded-full text-xs font-bold transition-all border"
            :class="selectedCategory === 'all' ? 'bg-primary text-white border-primary' : 'bg-surface-container/60 text-on-surface-variant border-white/5 hover:text-white hover:border-[#8d90a2]'"
          >
            <span class="material-symbols-outlined text-[14px]">grid_view</span> All Templates
          </button>
          <button 
            v-for="cat in ['Business', 'Portfolio', 'Blog', 'E-commerce']" 
            :key="cat"
            @click="selectedCategory = cat"
            class="px-6 py-2.5 rounded-full text-xs font-bold transition-all border"
            :class="selectedCategory === cat ? 'bg-primary text-white border-primary' : 'bg-surface-container/60 text-on-surface-variant border-white/5 hover:text-white hover:border-[#8d90a2]'"
          >
            {{ cat }}
          </button>
        </div>

        <!-- Uniform Canva-Style Grid Gallery -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          <div 
            v-for="tmpl in visibleTemplates" 
            :key="tmpl.id"
            class="group cursor-pointer flex flex-col gap-3"
            @click="useTemplate(tmpl)"
          >
            <!-- Card Image Wrapper with 16:10 aspect ratio -->
            <div class="aspect-[16/10] w-full relative overflow-hidden rounded-xl border border-white/5 bg-surface-container/60 shadow-md">
              <img :src="tmpl.image" class="w-full h-full object-cover group-hover:scale-[1.03] transition-all duration-300">
              
              <!-- Hover Overlay with Action Buttons -->
              <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-between p-4">
                <!-- Top Badge/Tag -->
                <div class="flex justify-between items-start">
                  <span v-if="tmpl.featured" class="text-[9px] font-bold text-white bg-primary px-2 py-0.5 rounded-full uppercase tracking-wider">Featured</span>
                  <span v-else class="w-1"></span>
                  <span class="text-[9px] font-bold text-white bg-white/10 backdrop-blur px-2 py-0.5 rounded-full uppercase tracking-wider">{{ tmpl.type.split(' ')[0] }}</span>
                </div>
                <!-- Bottom Action Buttons -->
                <div class="flex justify-between items-center">
                  <button @click.stop="useTemplate(tmpl)" class="bg-primary hover:bg-blue-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors shadow">
                    Use Template
                  </button>
                  <button @click.stop="previewTemplate(tmpl)" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                  </button>
                </div>
              </div>
            </div>

            <!-- Title & Info Below Image (Canva-style) -->
            <div class="px-1 py-0.5">
              <h3 class="text-xs font-bold text-white tracking-tight group-hover:text-primary transition-colors truncate">{{ tmpl.name }}</h3>
              <div class="flex items-center justify-between text-[10px] text-on-surface-variant mt-0.5">
                <span>{{ tmpl.category }}</span>
                <span class="text-[9px] text-gray-600 font-mono">ID: {{ tmpl.id }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredTemplates.length === 0" class="py-24 text-center border border-dashed border-outline rounded-xl bg-surface-container/30 mt-8">
          <span class="material-symbols-outlined text-[#434656] text-4xl mb-3">search_off</span>
          <h3 class="text-white text-sm font-bold mb-1">No templates found</h3>
          <p class="text-on-surface-variant text-xs">Try adjusting your search or category filters.</p>
        </div>

        <!-- Load More Section -->
        <div v-if="filteredTemplates.length > visibleLimit" class="pt-12 flex flex-col items-center gap-10">
          <button @click="visibleLimit += 8" class="px-8 py-3.5 border border-white/10 text-white text-xs font-bold uppercase tracking-widest rounded-[14px] hover:bg-white/5 hover:border-white/20 transition-all bg-surface-container/30 shadow-sm">
            Load More Templates
          </button>
          <div class="w-full max-w-lg flex items-center gap-6 opacity-60">
            <div class="flex-1 h-px bg-[#434656]"></div>
            <span class="text-[11px] font-geist text-on-surface-variant">Showing {{ Math.min(visibleLimit, filteredTemplates.length) }} of {{ filteredTemplates.length }} available templates</span>
            <div class="flex-1 h-px bg-[#434656]"></div>
          </div>
        </div>

        <!-- End of templates message -->
        <div v-else-if="filteredTemplates.length > 0" class="pt-12 flex flex-col items-center gap-4 text-center">
          <div class="w-full max-w-lg flex items-center gap-6 opacity-60 mb-2">
            <div class="flex-1 h-px bg-[#434656]"></div>
            <span class="text-[11px] font-geist text-on-surface-variant uppercase tracking-wider font-bold">End of Templates</span>
            <div class="flex-1 h-px bg-[#434656]"></div>
          </div>
          <p class="text-xs text-on-surface-variant max-w-md">You've reached the end of our template catalog. Want a custom layout? Use the AI Page Generator in the editor for unlimited designs.</p>
        </div>
      </div>
    </div>

    <Footer />

    <!-- Preview Modal -->
    <div v-if="previewingTemplate" class="fixed inset-0 z-[60] bg-black/75 backdrop-blur-sm flex items-center justify-center p-6">
      <div class="w-[min(1040px,100%)] h-[min(720px,100%)] bg-surface-container border border-white/10 rounded-2xl shadow-2xl flex flex-col overflow-hidden font-geist">
        <div class="h-14 border-b border-white/10 px-6 flex items-center justify-between bg-surface-container-light">
          <div>
            <div class="text-white text-sm font-bold">{{ previewingTemplate.name }}</div>
            <div class="text-on-surface-variant text-[11px] mt-0.5">{{ previewingTemplate.category }} / {{ previewingTemplate.type }}</div>
          </div>
          <div class="flex gap-2">
            <button @click="useTemplate(previewingTemplate)" class="px-4 py-1.5 bg-primary text-white rounded-lg text-xs font-bold hover:brightness-110">Use Template</button>
            <button @click="previewingTemplate = null" class="px-3 py-1.5 border border-outline text-on-surface-variant rounded-lg text-xs hover:text-white">Close</button>
          </div>
        </div>
        <iframe class="flex-1 bg-white" :srcdoc="previewSrcDoc"></iframe>
      </div>
    </div>

    <!-- Help Modal -->
    <div v-if="showHelp || showFeedback" class="fixed inset-0 z-[60] bg-black/70 backdrop-blur-sm flex items-center justify-center">
      <div class="w-[420px] bg-surface-container border border-outline rounded-2xl shadow-2xl p-5">
        <h3 class="text-white text-sm font-bold mb-2">{{ showHelp ? 'Template Help' : 'Send Feedback' }}</h3>
        <p v-if="showHelp" class="text-sm text-on-surface-variant leading-relaxed">Preview opens full template in a sandbox. Use Template writes HTML/CSS to Home Page and opens editor.</p>
        <textarea v-else v-model="feedbackText" class="w-full h-28 bg-editor-bg border border-outline rounded-lg text-sm text-white p-2 outline-none focus:border-primary" placeholder="What should improve?"></textarea>
        <div class="flex justify-end gap-2 mt-5">
          <button @click="showHelp = false; showFeedback = false" class="px-4 py-2 border border-outline text-on-surface-variant rounded-lg text-xs hover:text-white">Close</button>
          <button v-if="showFeedback" @click="showFeedback = false; showToast('Feedback saved locally')" class="px-4 py-2 bg-primary text-white rounded-lg text-xs font-bold hover:brightness-110">Send</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useWorkspaceStore } from '../stores/workspace'
import { templatesData } from '../data/templates'
import TopBar from '../components/editor/TopBar.vue'
import Footer from '../components/Footer.vue'

const router = useRouter()
const route = useRoute()
const store = useWorkspaceStore()
const projectId = route.params.projectId || 1

const searchQuery = ref('')
const selectedCategory = ref('all')
const toastMessage = ref('')
const previewingTemplate = ref(null)
const showHelp = ref(false)
const showFeedback = ref(false)
const feedbackText = ref('')
const visibleLimit = ref(8)
const previewSrcDoc = computed(() => {
  if (!previewingTemplate.value) return ''
  const html = previewingTemplate.value.html
  const css = previewingTemplate.value.css || ''
  return `<!doctype html><html><head><link rel="stylesheet" href="/canvas.css"><script src="https://cdn.tailwindcss.com"></scr` + `ipt>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.addEventListener('click', (e) => {
        const a = e.target.closest('a');
        if (a) {
          const href = a.getAttribute('href');
          if (href && href.startsWith('#')) {
            e.preventDefault();
            const targetId = href.substring(1);
            const targetEl = targetId ? document.getElementById(targetId) : null;
            if (targetEl) {
              targetEl.scrollIntoView({ behavior: 'smooth' });
            } else if (!targetId) {
              window.scrollTo({ top: 0, behavior: 'smooth' });
            }
          } else {
            e.preventDefault();
          }
        }
      });
    });
  </scr` + `ipt>
  </head><body style="margin:0">${html}<style>${css}</style></body></html>`
})

const showToast = (msg) => {
  toastMessage.value = msg
  setTimeout(() => {
    toastMessage.value = ''
  }, 3000)
}

const templates = ref(templatesData)

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

const visibleTemplates = computed(() => {
  return filteredTemplates.value.slice(0, visibleLimit.value)
})

const useTemplate = async (template) => {
  try {
    const projRes = await fetch(`${store.apiBaseUrl}/projects/${projectId}`)
    if (!projRes.ok) throw new Error('Failed to fetch project details')
    const project = await projRes.json()
    
    if (!project.pages || project.pages.length === 0) {
      throw new Error('Project has no pages')
    }
    
    const pageId = Number(route.query.pageId) || project.pages[0].id

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
      localStorage.setItem('studiopro.pendingTemplate', JSON.stringify({
        projectId: Number(projectId),
        pageId: Number(pageId),
        name: template.name,
        html: template.html,
        css: template.css || ''
      }))
      showToast(`Loaded template: ${template.name}`)
      setTimeout(() => {
        router.push(`/editor/${projectId}/${pageId}`)
      }, 1000)
    } else {
      throw new Error('Failed to save template')
    }
  } catch (err) {
    console.error(err)
    alert('Error applying template: ' + err.message)
  }
}

const previewTemplate = (template) => {
  previewingTemplate.value = template
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
.mesh-bg {
  background-color: #0b1326;
  background-image: 
    radial-gradient(at 0% 0%, rgba(46, 98, 255, 0.1) 0px, transparent 50%),
    radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.08) 0px, transparent 50%);
}
</style>
