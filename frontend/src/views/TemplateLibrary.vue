<template>
  <div class="studio-light studio-management min-h-dvh md:h-dvh w-full flex flex-col bg-surface text-on-surface overflow-x-clip md:overflow-hidden font-geist mesh-bg relative">
    <TopBar />

    <div class="flex-1 overflow-y-auto px-4 py-5 sm:px-8 sm:py-8 custom-scrollbar z-10 relative">
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
              Browse {{ templatesData.length }} editable layouts for businesses, portfolios, blogs, and stores.
            </p>
          </div>
          <div class="pb-2 w-full md:w-auto">
            <div class="relative group">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-[#434656] text-[18px]">search</span>
              <input 
                v-model="searchQuery" 
                aria-label="Search templates"
                class="bg-transparent border border-white/10 text-white text-xs h-10 pl-11 pr-4 rounded-lg focus:border-primary outline-none transition-all w-full md:w-80 placeholder:text-[#434656]"
                placeholder="Search templates..." 
                type="search"
              >
            </div>
          </div>
        </div>

        <!-- Category Filter Pills -->
        <div class="flex gap-4 overflow-x-auto pb-2 no-scrollbar">
          <button 
            @click="selectedCategory = 'all'" 
            :aria-pressed="selectedCategory === 'all'"
            class="flex items-center gap-2 px-6 py-2.5 rounded-full text-xs font-bold transition-all border"
            :class="selectedCategory === 'all' ? 'bg-primary text-white border-primary' : 'bg-surface-container/60 text-on-surface-variant border-white/5 hover:text-white hover:border-[#8d90a2]'"
          >
            <span class="material-symbols-outlined text-[14px]">grid_view</span> All Templates
          </button>
          <button 
            v-for="cat in ['Business', 'Portfolio', 'Blog', 'E-commerce']" 
            :key="cat"
            @click="selectedCategory = cat"
            :aria-pressed="selectedCategory === cat"
            class="px-6 py-2.5 rounded-full text-xs font-bold transition-all border"
            :class="selectedCategory === cat ? 'bg-primary text-white border-primary' : 'bg-surface-container/60 text-on-surface-variant border-white/5 hover:text-white hover:border-[#8d90a2]'"
          >
            {{ cat }}
          </button>
        </div>

        <p role="status" class="text-sm text-on-surface-variant">{{ filteredTemplates.length }} of {{ templatesData.length }} templates</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          <article
            v-for="tmpl in filteredTemplates"
            :key="tmpl.id"
            class="group flex flex-col gap-2"
          >
            <!-- Card Image Wrapper with 16:10 aspect ratio -->
            <div class="aspect-[16/10] w-full relative overflow-hidden rounded-xl border border-white/5 bg-surface-container/60 shadow-md">
              <img :src="tmpl.image" alt="" loading="lazy" class="w-full h-full object-cover">
            </div>

            <!-- Title & Info Below Image (Canva-style) -->
            <div class="px-1 py-0.5">
              <h3 class="text-xs font-bold text-white tracking-tight group-hover:text-primary transition-colors truncate">{{ tmpl.name }}</h3>
              <div class="flex items-center justify-between text-[10px] text-on-surface-variant mt-0.5">
                <span>{{ tmpl.category }}</span>
              </div>
            </div>
            <div class="studio-actions">
              <button class="studio-button" :aria-label="'Preview ' + tmpl.name" @click="previewTemplate(tmpl)">Preview</button>
              <button class="studio-button primary" :aria-label="'Use ' + tmpl.name" @click="chooseTemplate(tmpl)">Use template</button>
            </div>
          </article>
        </div>

        <!-- Empty State -->
        <div v-if="filteredTemplates.length === 0" class="py-24 text-center border border-dashed border-outline rounded-xl bg-surface-container/30 mt-8">
          <span class="material-symbols-outlined text-[#434656] text-4xl mb-3">search_off</span>
          <h3 class="text-white text-sm font-bold mb-1">No templates found</h3>
          <p class="text-on-surface-variant text-xs">Try adjusting your search or category filters.</p>
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
            <button @click="chooseTemplate(previewingTemplate)" class="px-4 py-1.5 bg-primary text-white rounded-lg text-xs font-bold hover:brightness-110">Use Template</button>
            <button @click="previewingTemplate = null" class="px-3 py-1.5 border border-outline text-on-surface-variant rounded-lg text-xs hover:text-white">Close</button>
          </div>
        </div>
        <iframe class="flex-1 bg-white" title="Template preview" sandbox="" :srcdoc="previewSrcDoc"></iframe>
      </div>
    </div>
    <dialog ref="confirmDialog" class="studio-dialog" aria-labelledby="confirm-template-title" @close="selectedTemplate = null">
      <h2 id="confirm-template-title">Use {{ selectedTemplate?.name }}?</h2>
      <p>This replaces the selected page’s saved draft. Its published version stays live until you republish.</p>
      <form method="dialog" class="studio-actions">
        <button class="studio-button" autofocus>Cancel</button>
        <button class="studio-button primary" @click="useTemplate(selectedTemplate)">Replace draft</button>
      </form>
    </dialog>

    <!-- Help Modal -->
    <div v-if="showHelp || showFeedback" class="fixed inset-0 z-[60] bg-black/70 backdrop-blur-sm flex items-center justify-center">
      <div class="w-[420px] max-w-[calc(100%-1.5rem)] bg-surface-container border border-outline rounded-2xl shadow-2xl p-5">
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
import { previewDocument, sanitizeHtml } from '../pageContent'
import { apiFetch } from '../api'

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
const confirmDialog = ref(null)
const selectedTemplate = ref(null)
const previewSrcDoc = computed(() => previewDocument(previewingTemplate.value?.html, previewingTemplate.value?.css))

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

function chooseTemplate(template) {
  selectedTemplate.value = template
  confirmDialog.value.showModal()
}

const useTemplate = async (template) => {
  if (!template) return
  try {
    const projRes = await apiFetch(`${store.apiBaseUrl}/projects/${projectId}`)
    if (!projRes.ok) throw new Error('Failed to fetch project details')
    const project = await projRes.json()
    
    if (!project.pages || project.pages.length === 0) {
      throw new Error('Project has no pages')
    }
    
    const pageId = Number(route.query.pageId) || project.pages[0].id

    const response = await apiFetch(`${store.apiBaseUrl}/projects/${projectId}/pages/${pageId}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        html: sanitizeHtml(template.html),
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
