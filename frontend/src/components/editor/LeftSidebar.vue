<template>
  <div class="relative w-[280px] bg-[#161B22] border-r border-[#434656] flex flex-col select-none h-full font-geist">
    <button
      @click="store.setLeftSidebarCollapsed(true)"
      class="absolute right-2 top-2 z-20 h-7 w-7 rounded bg-[#0B0E14] border border-[#434656] text-[#8d90a2] hover:text-white hover:border-[#2E62FF] flex items-center justify-center"
      title="Collapse left sidebar"
    >
      <span class="material-symbols-outlined text-[16px]">left_panel_close</span>
    </button>
    <!-- Header Removed -->
    
    <!-- Navigation Tabs -->
    <div class="flex flex-col border-b border-[#434656]">
      <button 
        @click="selectTab('layers')"
        class="px-5 py-3 flex items-center gap-3 transition-all duration-150"
        :class="store.activeLeftTab === 'layers' ? 'bg-[#2E62FF]/15 text-[#2E62FF] border-l-2 border-[#2E62FF]' : 'text-[#c3c5d8] hover:bg-[#2d3449] border-l-2 border-transparent'"
      >
        <span class="material-symbols-outlined text-[18px]">layers</span>
        <span class="text-[13px] font-medium font-geist">Layers</span>
      </button>

      <button 
        @click="selectTab('blocks')"
        class="px-5 py-3 flex items-center gap-3 transition-all duration-150"
        :class="store.activeLeftTab === 'blocks' ? 'bg-[#2E62FF]/15 text-[#2E62FF] border-l-2 border-[#2E62FF]' : 'text-[#c3c5d8] hover:bg-[#2d3449] border-l-2 border-transparent'"
      >
        <span class="material-symbols-outlined text-[18px]" :style="store.activeLeftTab === 'blocks' ? 'font-variation-settings: \'FILL\' 1;' : ''">add_box</span>
        <span class="text-[13px] font-medium font-geist">Blocks</span>
      </button>

      <button 
        @click="selectTab('symbols')"
        class="px-5 py-3 flex items-center gap-3 transition-all duration-150"
        :class="store.activeLeftTab === 'symbols' ? 'bg-[#2E62FF]/15 text-[#2E62FF] border-l-2 border-[#2E62FF]' : 'text-[#c3c5d8] hover:bg-[#2d3449] border-l-2 border-transparent'"
      >
        <span class="material-symbols-outlined text-[18px]">widgets</span>
        <span class="text-[13px] font-medium font-geist">Symbols</span>
      </button>

      <button 
        @click="selectTab('assets')"
        class="px-5 py-3 flex items-center gap-3 transition-all duration-150"
        :class="store.activeLeftTab === 'assets' ? 'bg-[#2E62FF]/15 text-[#2E62FF] border-l-2 border-[#2E62FF]' : 'text-[#c3c5d8] hover:bg-[#2d3449] border-l-2 border-transparent'"
      >
        <span class="material-symbols-outlined text-[18px]">image</span>
        <span class="text-[13px] font-medium font-geist">Assets</span>
      </button>

      <button 
        @click="selectTab('config')"
        class="px-5 py-3 flex items-center gap-3 transition-all duration-150"
        :class="store.activeLeftTab === 'config' ? 'bg-[#2E62FF]/15 text-[#2E62FF] border-l-2 border-[#2E62FF]' : 'text-[#c3c5d8] hover:bg-[#2d3449] border-l-2 border-transparent'"
      >
        <span class="material-symbols-outlined text-[18px]">tune</span>
        <span class="text-[13px] font-medium font-geist">Config</span>
      </button>
    </div>

    <!-- Active Tab Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Layers Navigator Content -->
      <div v-show="store.activeLeftTab === 'layers'" class="flex-1 flex flex-col overflow-hidden">
        <div class="mt-6 px-3">
          <div class="flex items-center justify-between mb-2">
            <span class="font-geist text-[11px] font-bold text-[#8d90a2] uppercase tracking-wider">Navigator</span>
            <span class="material-symbols-outlined text-[#8d90a2] text-sm cursor-pointer hover:text-white">search</span>
          </div>
        </div>
        <div id="layers-container" class="flex-1 overflow-y-auto custom-scrollbar px-3 pb-3"></div>
      </div>

      <!-- Blocks Content -->
      <div v-show="store.activeLeftTab === 'blocks'" class="flex-1 flex flex-col overflow-hidden">
        <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-6">
          <!-- Search -->
          <div class="relative">
            <span class="material-symbols-outlined absolute left-2 top-1.5 text-[#8d90a2] text-sm">search</span>
            <input v-model="blockSearch" @input="filterBlocks" class="w-full bg-[#0B0E14] border border-[#434656] rounded px-8 py-1 text-xs focus:border-[#2E62FF] focus:ring-0 outline-none text-white placeholder:text-[#8d90a2]" placeholder="Search elements..." type="text"/>
          </div>
          
          <!-- GrapesJS Block Manager Container -->
          <div id="blocks-container" class="w-full">
            <!-- GrapesJS Block Manager automatically appends categories here. We style them using CSS overrides below -->
          </div>
        </div>
      </div>

      <!-- Code Panel -->
      <div v-show="store.activeLeftTab === 'code'" class="p-2 space-y-4">
        <span class="text-white text-xs font-bold uppercase tracking-wider block">Element Code</span>
        <div class="p-3 bg-[#060e20] border border-[#434656] rounded text-[10px] font-mono text-gray-400 space-y-2 overflow-x-auto">
          <div>// HTML Code Snippet</div>
          <div class="text-[#10B981] whitespace-pre-wrap">{{ store.selectedComponent ? store.selectedComponent.toHTML() : '<!-- Select an element to view code -->' }}</div>
          <div class="pt-2 border-t border-[#434656]/30">// CSS Code Snippet</div>
          <div class="text-[#8B5CF6] whitespace-pre-wrap">{{ store.selectedComponent && store.editor ? store.editor.CodeManager.getCode(store.selectedComponent, 'css') : '/* Select an element to view CSS */' }}</div>
        </div>
      </div>

      <!-- Symbols Content -->
      <div v-show="store.activeLeftTab === 'symbols'" class="flex-1 flex flex-col overflow-hidden p-4">
        <span class="text-[#8d90a2] text-[11px] font-bold uppercase tracking-wider mb-3">Symbols Collection</span>
        
        <div class="flex-grow overflow-y-auto custom-scrollbar flex flex-col gap-3">
          <!-- Navbar Symbol Card -->
          <div @click="insertSymbol('navbar')" class="group relative bg-[#060e20] border border-[#434656] p-2 rounded hover:border-[#2E62FF] transition-all cursor-pointer">
            <div class="h-16 w-full bg-[#2d3449] rounded-sm flex items-center justify-center mb-1.5 overflow-hidden">
              <div class="w-full h-full bg-cover bg-center opacity-40 grayscale" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAtwtbNxuqYWTD_bNx-3Fv1OwhPconWVtycNs8xqO74xflDsTYzSgyj9WkS-iM-odnbO_t3-0SlCfOJam1IVulcqhimlr3qr2KrHmPTBX0sywMZ527bQeiyWMv8h2V6zqWUJJ5ure4R-kvrL-jYpuYQnE0X2JObkLhjsREgG9biLqkpdiZkPS7KwsuBBNzuws4AtZF1cB9WNj-nHC5kgyBxd2_pSVQtNWYzLYd4Upw_YzLGK1G29N-mPclWyLqwlS_3ZbGy2o0PHyDt')"></div>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs font-semibold text-white">Navbar</span>
              <span class="flex items-center gap-1 text-[9px] text-[#2E62FF] bg-[#2E62FF]/10 px-1 rounded font-mono">
                <span class="material-symbols-outlined text-[9px]">link</span> Instance
              </span>
            </div>
          </div>

          <!-- Footer Symbol Card -->
          <div @click="insertSymbol('footer')" class="group relative bg-[#060e20] border border-[#434656] p-2 rounded hover:border-[#2E62FF] transition-all cursor-pointer">
            <div class="h-16 w-full bg-[#2d3449] rounded-sm flex items-center justify-center mb-1.5 overflow-hidden">
              <div class="w-full h-full bg-cover bg-center opacity-40 grayscale" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuC0wlqykMAPgnSvg1qyvHo-A93sloclVU0k0KRT-Y3ptXzlCGLSnyQpP0qJtV_bn2HuR2mM5XIKJIbHlTnaIQtz1IXuIvabvTr3zC_cniXHopkB4qo9UK_g-7bBSZrTHfU8juxW45vqgdiOhgJ5lbhQh7BJAWjA4xNuuDrKYE8K3_XcPbJZb_yDZaZFX4mgmfjXFt9c2JmtDpUCy8ClK12C_NvcsrrbzmybaWJ6_hsfVDqh9ASdjIlODC4zQe7HhlmTDoAEJwAIhIwl')"></div>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs font-semibold text-white">Footer</span>
              <span class="flex items-center gap-1 text-[9px] text-[#2E62FF] bg-[#2E62FF]/10 px-1 rounded font-mono">
                <span class="material-symbols-outlined text-[9px]">link</span> Instance
              </span>
            </div>
          </div>

          <!-- Hero Card Symbol Card (Triggers Isolation Mode) -->
          <div 
            @click="toggleIsolation" 
            class="group relative p-2 rounded cursor-pointer transition-all border"
            :class="store.isIsolationMode ? 'bg-[#2E62FF]/10 border-[#2E62FF]' : 'bg-[#060e20] border-[#434656] hover:border-[#2E62FF]'"
          >
            <div class="h-16 w-full bg-[#2d3449] rounded-sm flex items-center justify-center mb-1.5 overflow-hidden">
              <div class="w-full h-full bg-cover bg-center opacity-70" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAXV-zWI0S6-7uDzQ-DDu5fSLVOAjxj9CHnpT_iAS_o7DTxCMXHdqpbEi_g3B-7JlnOZKO7VcKtSni7XmwY85CZg58yYaFZqOCcR9c2bdASI6PeSDDibta5BN4e_YtgZJ-YHUoz8V8L2-iG0ZN-_zoyvnXqCSavW-GTCgluPLXSObLZdR0RbbkrNSe42rcgregHp0ehD7VkJekoh2GNxpgxiIZGUrSCE0Di53yuw_lk6aPCNlkdUflR903jXJOp5KQaCEZ8kEkJDj1v')"></div>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs font-semibold" :class="store.isIsolationMode ? 'text-[#2E62FF]' : 'text-white'">Hero Card</span>
              <span class="flex items-center gap-1 text-[9px] text-[#2E62FF] bg-[#2E62FF]/15 px-1.5 py-0.5 rounded font-mono font-bold">
                <span class="material-symbols-outlined text-[10px]" style="font-variation-settings: 'FILL' 1;">auto_fix_high</span> {{ store.isIsolationMode ? 'Isolated' : 'Edit Symbol' }}
              </span>
            </div>
          </div>

          <!-- Button Group Symbol Card -->
          <div @click="insertSymbol('buttons')" class="group relative bg-[#060e20] border border-[#434656] p-2 rounded hover:border-[#2E62FF] transition-all cursor-pointer">
            <div class="h-16 w-full bg-[#2d3449] rounded-sm flex items-center justify-center mb-1.5 overflow-hidden">
              <div class="w-full h-full bg-cover bg-center opacity-40 grayscale" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAKP-WlGYaJK7kvR93L7YRt1KO-X66DUetNoUxFMPGZ0NMSR6c7yhvB74LZBC8GAfWtjh-J_bkcl2etM6jo8uzx68L6DE-w6H9ICFrPJGhTUaPVvMmYDHGIuZwi9nh8t6XJFz7Cjb503M69SIYrjX0Fe3cw7EO9K3rBNST-fsKFJFZi593EmmaE_KxnoA4QFkoMATYMsdvZnpN5u-FiNOlh1X4u3oWT2jpPmkopTQjnoEyDZJqqDCUvjeoI2s4Nn9wgoMT3UGBzvGxw')"></div>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs font-semibold text-white">Button Group</span>
              <span class="flex items-center gap-1 text-[9px] text-[#2E62FF] bg-[#2E62FF]/10 px-1 rounded font-mono">
                <span class="material-symbols-outlined text-[9px]">link</span> Instance
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Assets Content -->
      <div v-show="store.activeLeftTab === 'assets'" class="flex-grow overflow-hidden flex flex-col p-4">
        <div class="flex justify-between items-center mb-3">
          <span class="text-[#8d90a2] text-[11px] font-bold uppercase tracking-wider">Media Library</span>
          <button
            @click="assetInput?.click()"
            class="h-7 px-2 rounded bg-[#131b2e] border border-[#434656] text-[#c3c5d8] hover:text-white hover:border-[#2E62FF] transition flex items-center gap-1 text-[11px] font-bold"
            title="Upload your own image"
          >
            <span class="material-symbols-outlined text-[15px]">add_photo_alternate</span>
            Upload
          </button>
          <input ref="assetInput" type="file" accept="image/*" class="hidden" @change="uploadAsset">
        </div>
        <button
          @click="assetInput?.click()"
          class="mb-3 w-full rounded border border-dashed border-[#434656] bg-[#0B0E14] px-3 py-2 text-left hover:border-[#2E62FF] hover:bg-[#131b2e] transition"
        >
          <span class="flex items-center gap-2 text-xs font-bold text-[#dae2fd]">
            <span class="material-symbols-outlined text-[18px] text-[#2E62FF]">upload_file</span>
            Upload your own image
          </span>
          <span class="block pl-7 text-[10px] text-[#8d90a2]">PNG, JPG, WebP, GIF. Inserts image on canvas after selection.</span>
        </button>
        
        <div class="flex-grow overflow-y-auto custom-scrollbar grid grid-cols-2 gap-2 pr-1">
          <div
            v-for="(asset, index) in assetLibrary"
            :key="asset.src"
            draggable="true"
            @click="insertAsset(asset.src, asset.alt)"
            @dragstart="e => e.dataTransfer.setData('text/html', `<img src='${asset.src}' alt='${asset.alt}' style='width: 100%; height: auto;'/>`)"
            class="group relative aspect-square bg-[#0B0E14] border rounded-sm overflow-hidden cursor-grab active:cursor-grabbing hover:border-white transition-all"
            :class="index === 0 ? 'border-[#2E62FF] ring-1 ring-[#2E62FF]' : 'border-[#434656]'"
          >
            <img class="w-full h-full object-cover pointer-events-none" :src="asset.src" />
            <div v-if="index === 0" class="absolute inset-0 bg-[#2E62FF]/20 flex items-center justify-center pointer-events-none">
              <span class="material-symbols-outlined text-[#2E62FF]">drag_pan</span>
            </div>
            <div class="absolute bottom-0 left-0 right-0 bg-[#161B22]/90 p-1 pointer-events-none">
              <p class="font-mono text-[9px] truncate text-white">{{ asset.name }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Config Content -->
      <div v-show="store.activeLeftTab === 'config'" class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-4">
        <div class="text-[#8d90a2] text-[11px] font-bold uppercase tracking-wider">Workspace Settings</div>
        <div class="bg-[#060e20] border border-[#434656] rounded p-3 space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-xs text-[#c3c5d8]">Autosave State</span>
            <span class="text-[10px] font-mono" :class="store.isUnsaved ? 'text-[#F59E0B]' : 'text-[#10B981]'">{{ store.isUnsaved ? 'UNSAVED' : 'SYNCED' }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-xs text-[#c3c5d8]">API Endpoint</span>
            <span class="text-[10px] font-mono text-[#8d90a2] truncate max-w-[150px]">{{ store.apiBaseUrl }}</span>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-2">
          <button @click="store.editor?.runCommand('core:component-outline')" class="py-2 bg-[#131b2e] border border-[#434656] rounded text-xs text-[#dae2fd] hover:border-[#2E62FF] transition">Outlines</button>
          <button @click="store.editor?.runCommand('core:preview')" class="py-2 bg-[#131b2e] border border-[#434656] rounded text-xs text-[#dae2fd] hover:border-[#2E62FF] transition">Preview</button>
        </div>
        <div class="bg-[#060e20] border border-[#434656] rounded p-3 space-y-3">
          <div class="text-white text-xs font-bold uppercase tracking-wider">Page Defaults</div>
          <div class="space-y-1">
            <label class="text-[10px] text-[#8d90a2] uppercase font-bold">SEO Title</label>
            <input v-model="seoTitle" class="w-full h-8 bg-[#0B0E14] border border-[#434656] rounded px-2 text-xs text-white outline-none focus:border-[#2E62FF]" />
          </div>
          <div class="space-y-1">
            <label class="text-[10px] text-[#8d90a2] uppercase font-bold">Meta Description</label>
            <textarea v-model="seoDescription" class="w-full h-16 bg-[#0B0E14] border border-[#434656] rounded p-2 text-xs text-white outline-none focus:border-[#2E62FF] resize-none"></textarea>
          </div>
        </div>
        <div class="bg-[#060e20] border border-[#434656] rounded p-3 space-y-3">
          <div class="text-white text-xs font-bold uppercase tracking-wider">Canvas UI</div>
          <button @click="store.setRightSidebarCollapsed(!store.isRightSidebarCollapsed)" class="w-full py-2 bg-[#131b2e] border border-[#434656] rounded text-xs text-[#dae2fd] hover:border-[#2E62FF] transition">
            {{ store.isRightSidebarCollapsed ? 'Show Right Panel' : 'Hide Right Panel' }}
          </button>
          <button @click="store.setLeftSidebarCollapsed(true)" class="w-full py-2 bg-[#131b2e] border border-[#434656] rounded text-xs text-[#dae2fd] hover:border-[#2E62FF] transition">Hide Left Panel</button>
        </div>
      </div>
    </div>

    <!-- Sidebar Footer -->
    <div class="mt-auto border-t border-[#434656] p-4 bg-[#060e20]/50">
      <div class="flex flex-col gap-3 text-[#8d90a2] text-[13px] font-medium">
        <button @click="showHelp = true" class="flex items-center gap-2 hover:text-white transition cursor-pointer">
          <span class="material-symbols-outlined text-[18px]">help</span>
          <span>Help</span>
        </button>
        <button @click="showFeedback = true" class="flex items-center gap-2 hover:text-white transition cursor-pointer">
          <span class="material-symbols-outlined text-[18px]">chat_bubble</span>
          <span>Feedback</span>
        </button>
      </div>
    </div>
    <div v-if="showHelp || showFeedback" class="fixed inset-0 z-[70] bg-black/70 backdrop-blur-sm flex items-center justify-center">
      <div class="w-[420px] bg-[#161B22] border border-[#434656] rounded shadow-2xl p-5">
        <h3 class="text-white text-sm font-bold mb-2">{{ showHelp ? 'Editor Help' : 'Send Feedback' }}</h3>
        <p v-if="showHelp" class="text-sm text-[#c3c5d8] leading-relaxed">Use Blocks for sections, Symbols for reusable patterns, Assets for media, and Element Properties for style, data, SEO, code, and version export.</p>
        <textarea v-else v-model="feedbackText" class="w-full h-28 bg-[#0B0E14] border border-[#434656] rounded text-sm text-white p-2 outline-none" placeholder="What should improve?"></textarea>
        <div class="flex justify-end gap-2 mt-5">
          <button @click="showHelp = false; showFeedback = false" class="px-4 py-2 border border-[#434656] text-[#c3c5d8] rounded text-xs hover:text-white">Close</button>
          <button v-if="showFeedback" @click="showFeedback = false; feedbackText = ''" class="px-4 py-2 bg-[#2E62FF] text-white rounded text-xs font-bold">Send</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, nextTick, computed } from 'vue'
import { useWorkspaceStore } from '../../stores/workspace'

const store = useWorkspaceStore()
const blockSearch = ref('')
const assetInput = ref(null)
const uploadedAssets = ref([])
const showHelp = ref(false)
const showFeedback = ref(false)
const feedbackText = ref('')

const baseAssets = ref([
  { src: '/images/arch_hero_01.png', alt: 'Architecture hero', name: 'arch_hero_01.png' },
  { src: '/images/texture_vibe.png', alt: 'Metal texture', name: 'texture_vibe.png' },
  { src: '/images/product_cam.png', alt: 'Product camera', name: 'product_cam.png' },
  { src: '/images/vector_flat.png', alt: 'Workspace illustration', name: 'vector_flat.png' }
])

const assetLibrary = computed(() => {
  const seen = new Set()
  return [...uploadedAssets.value, ...baseAssets.value].filter((asset) => {
    if (seen.has(asset.src)) return false
    seen.add(asset.src)
    return true
  })
})

const seoTitle = computed({
  get: () => store.pageMeta.seoTitle || '',
  set: (value) => store.setPageMeta({ seoTitle: value })
})

const seoDescription = computed({
  get: () => store.pageMeta.seoDescription || '',
  set: (value) => store.setPageMeta({ seoDescription: value })
})

const selectTab = (tab) => {
  store.setActiveLeftTab(tab)
  
  // Automatic active tab links to RightSidebar properties tabs
  if (tab === 'layers' || tab === 'blocks') {
    store.setActiveRightTab('style')
  } else if (tab === 'assets' || tab === 'config') {
    store.setActiveRightTab('settings')
  } else if (tab === 'symbols') {
    store.setActiveRightTab('events')
  }
}

const toggleIsolation = () => {
  const isCurrentlyIsolated = store.isIsolationMode
  store.setIsolationMode(!isCurrentlyIsolated, 'Hero Card')
}

const insertSymbol = (type) => {
  if (!store.editor) return
  const symbols = {
    navbar: `<header data-symbol="navbar" class="w-full bg-[#0B0E14] border-b border-[#434656] px-8 py-4 flex items-center justify-between text-white">
  <div class="font-black text-lg">StudioPro</div>
  <nav class="flex gap-6 text-sm text-[#c3c5d8]"><a>Work</a><a>Pricing</a><a>Contact</a></nav>
  <button class="bg-[#2E62FF] px-4 py-2 rounded text-xs font-bold">Start</button>
</header>`,
    footer: `<footer data-symbol="footer" class="w-full bg-[#0B0E14] border-t border-[#434656] px-8 py-10 text-white">
  <div class="max-w-6xl mx-auto flex justify-between gap-8">
    <div><div class="font-black text-lg">StudioPro</div><p class="text-[#8d90a2] text-sm mt-2">Visual commerce, CMS, and pages.</p></div>
    <div class="grid grid-cols-3 gap-8 text-sm text-[#c3c5d8]"><span>Pages</span><span>CMS</span><span>Commerce</span></div>
  </div>
</footer>`,
    buttons: `<div data-symbol="button-group" class="flex gap-3 items-center">
  <button class="bg-[#2E62FF] text-white px-5 py-2.5 rounded font-bold">Primary</button>
  <button class="border border-[#434656] text-white px-5 py-2.5 rounded font-bold">Secondary</button>
</div>`
  }
  store.editor.addComponents(symbols[type])
  store.markDirty()
}

const uploadAsset = (event) => {
  const file = event.target.files?.[0]
  if (!file) return
  const reader = new FileReader()
  reader.onload = () => {
    const asset = {
      src: String(reader.result),
      alt: file.name.replace(/\.[^.]+$/, ''),
      name: file.name
    }
    uploadedAssets.value.unshift(asset)
    insertAsset(asset.src, asset.alt)
    event.target.value = ''
  }
  reader.readAsDataURL(file)
}

const insertAsset = (src, alt) => {
  if (!store.editor) return
  const component = `<img src="${src}" alt="${alt}" class="w-full h-auto object-cover rounded-sm" />`
  const selected = store.selectedComponent
  if (selected && selected.components) {
    selected.append(component)
  } else {
    store.editor.addComponents(component)
  }
  store.markDirty()
}

const filterBlocks = async () => {
  await nextTick()
  const query = blockSearch.value.trim().toLowerCase()
  document.querySelectorAll('#blocks-container .gjs-block').forEach((block) => {
    const text = block.textContent?.toLowerCase() || ''
    if (!query || text.includes(query)) {
      block.style.removeProperty('display')
    } else {
      block.style.setProperty('display', 'none', 'important')
    }
  })
}

watch([() => store.editor, blockSearch], filterBlocks)
</script>

<style>
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

/* GrapesJS Block Manager categories override */
.gjs-block-categories {
  display: flex !important;
  flex-direction: column !important;
  gap: 18px !important;
  width: 100% !important;
}
.gjs-block-category {
  border: none !important;
}
.gjs-block-category .gjs-title {
  background-color: transparent !important;
  color: #8d90a2 !important;
  font-family: 'Geist', sans-serif !important;
  font-size: 10px !important;
  font-weight: 700 !important;
  text-transform: uppercase !important;
  letter-spacing: 0.05em !important;
  padding: 0 0 6px 0 !important;
  border-bottom: 1px solid rgba(67, 70, 86, 0.2) !important;
  margin-bottom: 8px !important;
  display: flex !important;
  justify-content: justify !important;
}
.gjs-block-category .gjs-title .gjs-caret-icon {
  display: none !important; /* Hide caret icon */
}
.gjs-blocks-container {
  display: grid !important;
  grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
  gap: 8px !important;
  padding: 0 !important;
  background: transparent !important;
  width: 100% !important;
}

.gjs-block {
  background: #131b2e !important;
  border: 1px solid #434656 !important;
  border-radius: 4px;
  color: #c3c5d8 !important;
  transition: all 0.2s;
  padding: 12px 6px !important;
  box-shadow: none !important;
  cursor: grab;
  font-family: 'Geist', sans-serif !important;
  font-weight: 600 !important;
  font-size: 10px !important;
  text-transform: uppercase !important;
  letter-spacing: 0.05em !important;
  text-align: center !important;
  display: flex !important;
  flex-direction: column !important;
  align-items: center !important;
  justify-content: center !important;
  min-height: 72px !important;
  box-sizing: border-box !important;
}
.gjs-block:hover {
  border-color: #2E62FF !important;
  background-color: #161B22 !important;
  color: #fff !important;
}
.gjs-block:hover span.material-symbols-outlined {
  color: #2E62FF !important;
}

/* Horizontal styling for Heading & Paragraph blocks in Typography category */
.gjs-block[id*="heading"],
.gjs-block[id*="paragraph"] {
  grid-column: span 2 / span 2 !important;
  flex-direction: row !important;
  justify-content: flex-start !important;
  align-items: center !important;
  gap: 12px !important;
  padding: 8px 16px !important;
  min-height: 38px !important;
  text-align: left !important;
}

/* Layer Navigator styling to match StudioPro dark premium tree */
.gjs-layer-header {
  background-color: transparent !important;
  border: none !important;
  padding: 4px 8px !important;
  font-family: 'JetBrains Mono', monospace !important;
  font-size: 11px !important;
  color: #c3c5d8 !important;
  display: flex !important;
  align-items: center !important;
  gap: 8px !important;
  cursor: pointer !important;
  border-left: 2px solid transparent !important;
  border-radius: 4px !important;
  margin: 1px 0 !important;
  transition: all 0.15s ease-in-out !important;
}
.gjs-layer-header:hover {
  background-color: #2d3449 !important;
  color: #fff !important;
}
.gjs-layer.gjs-layer-active > .gjs-layer-header,
.gjs-layer.gjs-selected > .gjs-layer-header,
.gjs-layer-header.gjs-layer-active {
  background-color: rgba(46, 98, 255, 0.2) !important;
  border-left: 2px solid #2E62FF !important;
  color: #fff !important;
  border-radius: 0 4px 4px 0 !important; /* rounded-r */
}
.gjs-layer-title {
  font-family: 'JetBrains Mono', monospace !important;
  font-weight: 400 !important;
}
.gjs-layer-caret {
  color: #8d90a2 !important;
  margin-right: 4px !important;
  font-size: 14px !important;
  display: flex !important;
  align-items: center !important;
}
.gjs-layer-icon {
  color: #8d90a2 !important;
  margin-right: 8px !important;
}
.gjs-layer.gjs-layer-active > .gjs-layer-header .gjs-layer-icon,
.gjs-layer.gjs-selected > .gjs-layer-header .gjs-layer-icon,
.gjs-layer-header.gjs-layer-active .gjs-layer-icon {
  color: #2E62FF !important;
}
.gjs-layers {
  padding-left: 12px !important;
}
/* Style specific icons if possible based on classes, otherwise use generic */

/* Fix gray backgrounds for Layer & Block managers */
.gjs-layer,
.gjs-layer-children,
.gjs-layers,
.gjs-layers-c,
.gjs-block-category,
.gjs-block-categories,
.gjs-category-items,
.gjs-blocks-c,
.gjs-blocks-container {
  background-color: transparent !important;
  background: transparent !important;
  border-color: transparent !important;
}
</style>
