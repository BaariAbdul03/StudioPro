<template>
  <aside class="w-[280px] bg-surface-container border-r border-outline flex flex-col z-40 shrink-0 absolute md:relative left-0 top-0 bottom-0 h-full font-geist">
    <button
      @click="store.setLeftSidebarCollapsed(true)"
      class="absolute right-2 top-2 z-20 h-7 w-7 rounded bg-editor-bg border border-outline text-on-surface-variant hover:text-white hover:border-primary flex items-center justify-center"
      title="Collapse left sidebar"
    >
      <span class="material-symbols-outlined text-[16px]">left_panel_close</span>
    </button>
    <!-- Header Removed -->
    
    <!-- Navigation Tabs -->
    <div class="flex flex-col border-b border-outline">
      <button 
        @click="selectTab('layers')"
        class="px-5 py-3 flex items-center gap-3 transition-all duration-150"
        :class="store.activeLeftTab === 'layers' ? 'bg-primary/15 text-primary border-l-2 border-primary' : 'text-on-surface-variant hover:bg-[#2d3449] border-l-2 border-transparent'"
      >
        <span class="material-symbols-outlined text-[18px]">layers</span>
        <span class="text-[13px] font-medium font-geist">Layers</span>
      </button>

      <button 
        @click="selectTab('blocks')"
        class="px-5 py-3 flex items-center gap-3 transition-all duration-150"
        :class="store.activeLeftTab === 'blocks' ? 'bg-primary/15 text-primary border-l-2 border-primary' : 'text-on-surface-variant hover:bg-[#2d3449] border-l-2 border-transparent'"
      >
        <span class="material-symbols-outlined text-[18px]" :style="store.activeLeftTab === 'blocks' ? 'font-variation-settings: \'FILL\' 1;' : ''">add_box</span>
        <span class="text-[13px] font-medium font-geist">Blocks</span>
      </button>

      <button 
        @click="selectTab('symbols')"
        class="px-5 py-3 flex items-center gap-3 transition-all duration-150"
        :class="store.activeLeftTab === 'symbols' ? 'bg-primary/15 text-primary border-l-2 border-primary' : 'text-on-surface-variant hover:bg-[#2d3449] border-l-2 border-transparent'"
      >
        <span class="material-symbols-outlined text-[18px]">widgets</span>
        <span class="text-[13px] font-medium font-geist">Symbols</span>
      </button>

      <button 
        @click="selectTab('assets')"
        class="px-5 py-3 flex items-center gap-3 transition-all duration-150"
        :class="store.activeLeftTab === 'assets' ? 'bg-primary/15 text-primary border-l-2 border-primary' : 'text-on-surface-variant hover:bg-[#2d3449] border-l-2 border-transparent'"
      >
        <span class="material-symbols-outlined text-[18px]">image</span>
        <span class="text-[13px] font-medium font-geist">Assets</span>
      </button>

      <button 
        @click="selectTab('config')"
        class="px-5 py-3 flex items-center gap-3 transition-all duration-150"
        :class="store.activeLeftTab === 'config' ? 'bg-primary/15 text-primary border-l-2 border-primary' : 'text-on-surface-variant hover:bg-[#2d3449] border-l-2 border-transparent'"
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
            <span class="font-geist text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Navigator</span>
            <span class="material-symbols-outlined text-on-surface-variant text-sm cursor-pointer hover:text-white">search</span>
          </div>
        </div>
        <div id="layers-container" class="flex-1 overflow-y-auto custom-scrollbar px-3 pb-3"></div>
      </div>

      <!-- Blocks Content -->
      <div v-show="store.activeLeftTab === 'blocks'" class="flex-1 flex flex-col overflow-hidden">
        <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-6">
          <!-- Search -->
          <div class="relative">
            <span class="material-symbols-outlined absolute left-2 top-1.5 text-on-surface-variant text-sm">search</span>
            <input v-model="blockSearch" @input="filterBlocks" class="w-full bg-editor-bg border border-outline rounded px-8 py-1 text-xs focus:border-primary focus:ring-0 outline-none text-white placeholder:text-on-surface-variant" placeholder="Search elements..." type="text"/>
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
        <div class="p-3 bg-[#060e20] border border-outline rounded text-[10px] font-mono text-on-surface-variant space-y-2 overflow-x-auto">
          <div>// HTML Code Snippet</div>
          <div class="text-[#10B981] whitespace-pre-wrap">{{ store.selectedComponent ? store.selectedComponent.toHTML() : '<!-- Select an element to view code -->' }}</div>
          <div class="pt-2 border-t border-outline/30">// CSS Code Snippet</div>
          <div class="text-[#8B5CF6] whitespace-pre-wrap">{{ store.selectedComponent && store.editor ? store.editor.CodeManager.getCode(store.selectedComponent, 'css') : '/* Select an element to view CSS */' }}</div>
        </div>
      </div>

      <!-- Symbols Content -->
      <div v-show="store.activeLeftTab === 'symbols'" class="flex-1 flex flex-col overflow-hidden p-4">
        <span class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider mb-3">Symbols Collection</span>
        
        <div class="flex-grow overflow-y-auto custom-scrollbar flex flex-col gap-3">
          <!-- Navbar Symbol Card -->
          <div @click="insertSymbol('navbar')" class="group relative bg-[#060e20] border border-outline p-2 rounded hover:border-primary transition-all cursor-pointer">
            <div class="h-16 w-full bg-[#2d3449] rounded-sm flex items-center justify-center mb-1.5 overflow-hidden">
              <div class="w-full h-full bg-cover bg-center opacity-40 grayscale" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAtwtbNxuqYWTD_bNx-3Fv1OwhPconWVtycNs8xqO74xflDsTYzSgyj9WkS-iM-odnbO_t3-0SlCfOJam1IVulcqhimlr3qr2KrHmPTBX0sywMZ527bQeiyWMv8h2V6zqWUJJ5ure4R-kvrL-jYpuYQnE0X2JObkLhjsREgG9biLqkpdiZkPS7KwsuBBNzuws4AtZF1cB9WNj-nHC5kgyBxd2_pSVQtNWYzLYd4Upw_YzLGK1G29N-mPclWyLqwlS_3ZbGy2o0PHyDt')"></div>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs font-semibold text-white">Navbar</span>
              <span class="flex items-center gap-1 text-[9px] text-primary bg-primary/10 px-1 rounded font-mono">
                <span class="material-symbols-outlined text-[9px]">link</span> Instance
              </span>
            </div>
          </div>

          <!-- Footer Symbol Card -->
          <div @click="insertSymbol('footer')" class="group relative bg-[#060e20] border border-outline p-2 rounded hover:border-primary transition-all cursor-pointer">
            <div class="h-16 w-full bg-[#2d3449] rounded-sm flex items-center justify-center mb-1.5 overflow-hidden">
              <div class="w-full h-full bg-cover bg-center opacity-40 grayscale" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuC0wlqykMAPgnSvg1qyvHo-A93sloclVU0k0KRT-Y3ptXzlCGLSnyQpP0qJtV_bn2HuR2mM5XIKJIbHlTnaIQtz1IXuIvabvTr3zC_cniXHopkB4qo9UK_g-7bBSZrTHfU8juxW45vqgdiOhgJ5lbhQh7BJAWjA4xNuuDrKYE8K3_XcPbJZb_yDZaZFX4mgmfjXFt9c2JmtDpUCy8ClK12C_NvcsrrbzmybaWJ6_hsfVDqh9ASdjIlODC4zQe7HhlmTDoAEJwAIhIwl')"></div>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs font-semibold text-white">Footer</span>
              <span class="flex items-center gap-1 text-[9px] text-primary bg-primary/10 px-1 rounded font-mono">
                <span class="material-symbols-outlined text-[9px]">link</span> Instance
              </span>
            </div>
          </div>

          <!-- Hero Card Symbol Card (Triggers Isolation Mode) -->
          <div 
            @click="toggleIsolation" 
            class="group relative p-2 rounded cursor-pointer transition-all border"
            :class="store.isIsolationMode ? 'bg-primary/10 border-primary' : 'bg-[#060e20] border-outline hover:border-primary'"
          >
            <div class="h-16 w-full bg-[#2d3449] rounded-sm flex items-center justify-center mb-1.5 overflow-hidden">
              <div class="w-full h-full bg-cover bg-center opacity-70" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAXV-zWI0S6-7uDzQ-DDu5fSLVOAjxj9CHnpT_iAS_o7DTxCMXHdqpbEi_g3B-7JlnOZKO7VcKtSni7XmwY85CZg58yYaFZqOCcR9c2bdASI6PeSDDibta5BN4e_YtgZJ-YHUoz8V8L2-iG0ZN-_zoyvnXqCSavW-GTCgluPLXSObLZdR0RbbkrNSe42rcgregHp0ehD7VkJekoh2GNxpgxiIZGUrSCE0Di53yuw_lk6aPCNlkdUflR903jXJOp5KQaCEZ8kEkJDj1v')"></div>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs font-semibold" :class="store.isIsolationMode ? 'text-primary' : 'text-white'">Hero Card</span>
              <span class="flex items-center gap-1 text-[9px] text-primary bg-primary/15 px-1.5 py-0.5 rounded font-mono font-bold">
                <span class="material-symbols-outlined text-[10px]" style="font-variation-settings: 'FILL' 1;">auto_fix_high</span> {{ store.isIsolationMode ? 'Isolated' : 'Edit Symbol' }}
              </span>
            </div>
          </div>

          <!-- Button Group Symbol Card -->
          <div @click="insertSymbol('buttons')" class="group relative bg-[#060e20] border border-outline p-2 rounded hover:border-primary transition-all cursor-pointer">
            <div class="h-16 w-full bg-[#2d3449] rounded-sm flex items-center justify-center mb-1.5 overflow-hidden">
              <div class="w-full h-full bg-cover bg-center opacity-40 grayscale" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAKP-WlGYaJK7kvR93L7YRt1KO-X66DUetNoUxFMPGZ0NMSR6c7yhvB74LZBC8GAfWtjh-J_bkcl2etM6jo8uzx68L6DE-w6H9ICFrPJGhTUaPVvMmYDHGIuZwi9nh8t6XJFz7Cjb503M69SIYrjX0Fe3cw7EO9K3rBNST-fsKFJFZi593EmmaE_KxnoA4QFkoMATYMsdvZnpN5u-FiNOlh1X4u3oWT2jpPmkopTQjnoEyDZJqqDCUvjeoI2s4Nn9wgoMT3UGBzvGxw')"></div>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs font-semibold text-white">Button Group</span>
              <span class="flex items-center gap-1 text-[9px] text-primary bg-primary/10 px-1 rounded font-mono">
                <span class="material-symbols-outlined text-[9px]">link</span> Instance
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Assets Content -->
      <div v-show="store.activeLeftTab === 'assets'" class="flex-grow overflow-hidden flex flex-col p-4">
        <div class="flex justify-between items-center mb-3">
          <span class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Media Library</span>
          <input ref="assetInput" type="file" accept="image/*" class="hidden" @change="uploadAsset">
        </div>
        <button
          @click="assetInput?.click()"
          class="mb-3 w-full rounded border border-dashed border-outline bg-editor-bg px-3 py-2 text-left hover:border-primary hover:bg-surface-container-light transition"
          title="Upload image from computer"
        >
          <span class="flex items-center gap-2 text-xs font-bold text-on-surface">
            <span class="material-symbols-outlined text-[18px] text-primary">upload_file</span>
            Upload your own image
          </span>
          <span class="block pl-7 text-[10px] text-on-surface-variant">PNG, JPG, WebP, GIF. Inserts image on canvas after selection.</span>
        </button>
        
        <div class="flex-grow overflow-y-auto custom-scrollbar pr-1 pb-4">
          <div class="grid grid-cols-2 gap-3 content-start">
            <div
              v-for="(asset, index) in assetLibrary"
            :key="asset.url || asset.src"
            draggable="true"
            @click="insertAsset(asset.url || asset.src, asset.filename || asset.alt)"
            @dragstart="e => e.dataTransfer.setData('text/html', `<img src='${asset.url || asset.src}' alt='${asset.filename || asset.alt}' style='width: 100%; height: auto;'/>`)"
            class="group relative aspect-square bg-editor-bg border rounded-sm overflow-hidden cursor-grab active:cursor-grabbing hover:border-white transition-all"
            :class="index === 0 ? 'border-primary ring-1 ring-[#2E62FF]' : 'border-outline'"
          >
            <img class="w-full h-full object-cover pointer-events-none" :src="asset.url || asset.src" />
            <div v-if="index === 0" class="absolute inset-0 bg-primary/20 flex items-center justify-center pointer-events-none">
              <span class="material-symbols-outlined text-primary">drag_pan</span>
            </div>
            <div class="absolute bottom-0 left-0 right-0 bg-surface-container/90 p-1 pointer-events-none">
              <p class="font-mono text-[9px] truncate text-white">{{ asset.filename || asset.name }}</p>
            </div>
          </div>
        </div>
        </div>
      </div>

      <!-- Config Content -->
      <div v-show="store.activeLeftTab === 'config'" class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-5 pb-8">
        
        <div>
          <h2 class="text-on-surface text-sm font-black mb-1">Configuration</h2>
          <p class="text-[10px] text-on-surface-variant leading-relaxed">
            Optimize your workspace settings below. Proper configuration ensures maximum performance, higher SEO rankings, and seamless CI/CD integration, giving your business a competitive edge.
          </p>
        </div>

        <!-- SEO Settings -->
        <div class="bg-[#060e20] border border-outline rounded p-3 space-y-3">
          <div>
            <h3 class="text-white text-xs font-bold uppercase tracking-wider">Page SEO Defaults</h3>
            <p class="text-[9px] text-on-surface-variant mt-1 leading-relaxed">Critical for organic reach. Higher search rankings directly translate to more traffic and potential revenue.</p>
          </div>
          <div class="space-y-1.5">
            <label class="text-[10px] text-on-surface-variant uppercase font-bold">SEO Title</label>
            <input v-model="seoTitle" class="w-full h-8 bg-editor-bg border border-outline rounded px-2 text-xs text-white outline-none focus:border-primary" placeholder="Primary Keyword - Brand Name" />
          </div>
          <div class="space-y-1.5">
            <label class="text-[10px] text-on-surface-variant uppercase font-bold">Meta Description</label>
            <textarea v-model="seoDescription" class="w-full h-16 bg-editor-bg border border-outline rounded p-2 text-xs text-white outline-none focus:border-primary resize-none" placeholder="A compelling summary to increase click-through rates..."></textarea>
          </div>
        </div>

        <!-- Deployment Settings -->
        <div class="bg-[#060e20] border border-outline rounded p-3 space-y-3">
          <div>
            <h3 class="text-white text-xs font-bold uppercase tracking-wider">Automated Deployment</h3>
            <p class="text-[9px] text-on-surface-variant mt-1 leading-relaxed">Trigger remote builds automatically. Saves engineering hours and reduces time-to-market for new updates.</p>
          </div>
          <div class="space-y-1.5">
            <label class="text-[10px] text-on-surface-variant uppercase font-bold">Webhook URL</label>
            <input 
              v-model="deployWebhook" 
              @change="saveDeployWebhook" 
              class="w-full h-8 bg-editor-bg border border-outline rounded px-2 text-xs text-white outline-none focus:border-primary" 
              placeholder="https://api.example.com/deploy" 
            />
          </div>
          <div class="flex items-center justify-between text-[10px]">
            <span class="text-on-surface-variant uppercase font-bold">Pipeline Status</span>
            <span class="font-mono font-bold px-1.5 py-0.5 rounded" :class="deployWebhook ? 'bg-[#10B981]/10 text-[#10B981]' : 'bg-[#434656]/30 text-on-surface-variant'">{{ deployWebhook ? 'ACTIVE' : 'INACTIVE' }}</span>
          </div>
        </div>

        <!-- Workspace Settings -->
        <div class="bg-[#060e20] border border-outline rounded p-3 space-y-3">
          <div>
            <h3 class="text-white text-xs font-bold uppercase tracking-wider">Workspace Sync</h3>
            <p class="text-[9px] text-on-surface-variant mt-1 leading-relaxed">Cloud synchronization guarantees data integrity and prevents loss of work during unexpected interruptions.</p>
          </div>
          <div class="flex items-center justify-between bg-editor-bg border border-outline p-2 rounded">
            <span class="text-xs font-bold text-on-surface-variant">Autosave State</span>
            <div class="flex items-center gap-1.5">
              <span class="relative flex h-2 w-2">
                <span v-if="!store.isUnsaved" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#10B981] opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2" :class="store.isUnsaved ? 'bg-[#F59E0B]' : 'bg-[#10B981]'"></span>
              </span>
              <span class="text-[10px] font-mono font-bold" :class="store.isUnsaved ? 'text-[#F59E0B]' : 'text-[#10B981]'">{{ store.isUnsaved ? 'UNSAVED' : 'SYNCED' }}</span>
            </div>
          </div>
        </div>

        <!-- Canvas UI & Debugging -->
        <div class="bg-[#060e20] border border-outline rounded p-3 space-y-3">
          <div>
            <h3 class="text-white text-xs font-bold uppercase tracking-wider">Canvas & Debugging</h3>
            <p class="text-[9px] text-on-surface-variant mt-1 leading-relaxed">Tools to preview structure and manage the editor layout for an optimal authoring experience.</p>
          </div>
          <div class="grid grid-cols-2 gap-2">
            <button @click="store.editor?.runCommand('core:component-outline')" class="py-2 bg-surface-container-light border border-outline rounded text-[11px] text-on-surface font-bold hover:border-primary hover:bg-primary/10 transition">Outlines</button>
            <button @click="store.editor?.runCommand('core:preview')" class="py-2 bg-surface-container-light border border-outline rounded text-[11px] text-on-surface font-bold hover:border-primary hover:bg-primary/10 transition">Preview</button>
          </div>
          <div class="grid grid-cols-2 gap-2">
            <button @click="store.setRightSidebarCollapsed(!store.isRightSidebarCollapsed)" class="py-2 bg-surface-container-light border border-outline rounded text-[9px] text-on-surface font-bold hover:border-primary hover:bg-primary/10 transition text-center uppercase tracking-wider">
              Toggle Right
            </button>
            <button @click="store.setLeftSidebarCollapsed(true)" class="py-2 bg-surface-container-light border border-outline rounded text-[9px] text-on-surface font-bold hover:border-primary hover:bg-primary/10 transition text-center uppercase tracking-wider">
              Hide Left
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar Footer -->
    <div class="mt-auto border-t border-outline p-4 bg-[#060e20]/50">
      <div class="flex flex-col gap-3 text-on-surface-variant text-[13px] font-medium">
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
      <div class="w-[420px] bg-surface-container border border-outline rounded shadow-2xl p-5">
        <h3 class="text-white text-sm font-bold mb-2">{{ showHelp ? 'Editor Help' : 'Send Feedback' }}</h3>
        <p v-if="showHelp" class="text-sm text-on-surface-variant leading-relaxed">Use Blocks for sections, Symbols for reusable patterns, Assets for media, and Element Properties for style, data, SEO, code, and version export.</p>
        <textarea v-else v-model="feedbackText" class="w-full h-28 bg-editor-bg border border-outline rounded text-sm text-white p-2 outline-none" placeholder="What should improve?"></textarea>
        <div class="flex justify-end gap-2 mt-5">
          <button @click="showHelp = false; showFeedback = false" class="px-4 py-2 border border-outline text-on-surface-variant rounded text-xs hover:text-white">Close</button>
          <button v-if="showFeedback" @click="showFeedback = false; feedbackText = ''" class="px-4 py-2 bg-primary text-white rounded text-xs font-bold">Send</button>
        </div>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { ref, watch, nextTick, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useWorkspaceStore } from '../../stores/workspace'

const store = useWorkspaceStore()
const route = useRoute()
const projectId = computed(() => route.params.projectId || 1)

const blockSearch = ref('')
const assetInput = ref(null)
const uploadedAssets = ref([])
const showHelp = ref(false)
const showFeedback = ref(false)
const feedbackText = ref('')

const baseAssets = ref([
  { src: '/images/arch_hero_01.png', alt: 'Architecture Hero', name: 'arch_hero_01.png' },
  { src: '/images/texture_vibe.png', alt: 'Metal Texture', name: 'texture_vibe.png' },
  { src: '/images/product_cam.png', alt: 'Product Camera', name: 'product_cam.png' },
  { src: '/images/vector_flat.png', alt: 'Workspace Illustration', name: 'vector_flat.png' },
  { src: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=600&q=80', alt: 'Analytics Dashboard', name: 'dashboard_analytics.jpg' },
  { src: 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=600&q=80', alt: 'UI Design Layout', name: 'ui_design_layout.jpg' },
  { src: 'https://images.unsplash.com/photo-1581291518633-83b4ebd1d83e?auto=format&fit=crop&w=600&q=80', alt: 'SaaS Platform Mock', name: 'saas_platform.jpg' },
  { src: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80', alt: 'Abstract Gradient Flow', name: 'gradient_flow.jpg' },
  { src: 'https://images.unsplash.com/photo-1634017839464-5c339ebe3cb4?auto=format&fit=crop&w=600&q=80', alt: 'Neon 3D Geometry', name: 'neon_geometry.jpg' },
  { src: 'https://images.unsplash.com/photo-1579546929518-9e396f3cc809?auto=format&fit=crop&w=600&q=80', alt: 'Vibrant Mesh Gradient', name: 'mesh_gradient.jpg' },
  { src: 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=600&q=80', alt: 'Developer Desk Setup', name: 'developer_desk.jpg' },
  { src: 'https://images.unsplash.com/photo-1531403009284-440f080d1e12?auto=format&fit=crop&w=600&q=80', alt: 'Collaborative UX Session', name: 'ux_session.jpg' },
  { src: 'https://images.unsplash.com/photo-1532795986-dbef1643a596?auto=format&fit=crop&w=600&q=80', alt: 'Premium Matcha Bowl', name: 'matcha_bowl.jpg' },
  { src: 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?auto=format&fit=crop&w=600&q=80', alt: 'Mechanical Keyboard', name: 'keyboard_wireless.jpg' },
  { src: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80', alt: 'Sarah Connor CEO', name: 'avatar_sarah.jpg' },
  { src: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80', alt: 'Marcus Aurelius Dev', name: 'avatar_marcus.jpg' }
])

const assetLibrary = computed(() => {
  const seen = new Set()
  return [...uploadedAssets.value, ...baseAssets.value].filter((asset) => {
    const srcVal = asset.url || asset.src
    if (seen.has(srcVal)) return false
    seen.add(srcVal)
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
    navbar: `<header data-symbol="navbar" class="w-full bg-editor-bg border-b border-outline px-8 py-4 flex items-center justify-between text-white">
  <div class="font-black text-lg">StudioPro</div>
  <nav class="flex gap-6 text-sm text-on-surface-variant"><a>Work</a><a>Pricing</a><a>Contact</a></nav>
  <button class="bg-primary px-4 py-2 rounded text-xs font-bold">Start</button>
</header>`,
    footer: `<footer data-symbol="footer" class="w-full bg-editor-bg border-t border-outline px-8 py-10 text-white">
  <div class="max-w-6xl mx-auto flex justify-between gap-8">
    <div><div class="font-black text-lg">StudioPro</div><p class="text-on-surface-variant text-sm mt-2">Visual commerce, CMS, and pages.</p></div>
    <div class="grid grid-cols-3 gap-8 text-sm text-on-surface-variant"><span>Pages</span><span>CMS</span><span>Commerce</span></div>
  </div>
</footer>`,
    buttons: `<div data-symbol="button-group" class="flex gap-3 items-center">
  <button class="bg-primary text-white px-5 py-2.5 rounded font-bold">Primary</button>
  <button class="border border-outline text-white px-5 py-2.5 rounded font-bold">Secondary</button>
</div>`
  }
  store.editor.addComponents(symbols[type])
  store.markDirty()
}

const uploadAsset = async (event) => {
  const file = event.target.files?.[0]
  if (!file) return
  const formData = new FormData()
  formData.append('file', file)

  try {
    const response = await fetch(`${store.apiBaseUrl}/projects/${projectId.value}/assets`, {
      method: 'POST',
      body: formData
    })
    if (response.ok) {
      const asset = await response.json()
      uploadedAssets.value.unshift(asset)
      insertAsset(asset.url, asset.filename)
    } else {
      console.error('Asset upload failed')
    }
  } catch (err) {
    console.error('Upload error:', err)
  } finally {
    event.target.value = ''
  }
}

const deployWebhook = computed({
  get: () => store.activeProject?.settings?.deploy_webhook || '',
  set: (val) => {
    if (!store.activeProject) return
    store.activeProject = {
      ...store.activeProject,
      settings: {
        ...(store.activeProject.settings || {}),
        deploy_webhook: val
      }
    }
  }
})

const saveDeployWebhook = async () => {
  if (!store.activeProject) return
  try {
    await store.updateProjectSettings(store.activeProject.id, store.activeProject.settings)
  } catch (err) {
    console.error('Failed to save deploy webhook URL:', err)
  }
}

onMounted(async () => {
  try {
    const res = await fetch(`${store.apiBaseUrl}/projects/${projectId.value}/assets`)
    if (res.ok) {
      uploadedAssets.value = await res.json()
    }
  } catch (err) {
    console.error('Failed to load assets', err)
  }
})

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
  background-color: rgba(255, 255, 255, 0.03) !important; /* Grayish base when not selected */
  border: none !important;
  padding: 4px 8px !important;
  font-family: 'JetBrains Mono', monospace !important;
  font-size: 11px !important;
  color: #9ca3af !important;
  display: flex !important;
  align-items: center !important;
  gap: 8px !important;
  cursor: pointer !important;
  border-left: 2px solid transparent !important;
  border-radius: 4px !important;
  margin: 2px 0 !important;
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
}
.gjs-layer-header:hover {
  background-color: rgba(46, 98, 255, 0.15) !important; /* Blue shade on hover */
  border-left: 2px solid rgba(46, 98, 255, 0.5) !important;
  color: #fff !important;
}
.gjs-layer-header:hover .gjs-layer-icon,
.gjs-layer-header:hover .gjs-layer-caret {
  color: #3b82f6 !important;
}
.gjs-layer.gjs-layer-active > .gjs-layer-header,
.gjs-layer.gjs-selected > .gjs-layer-header,
.gjs-layer-header.gjs-layer-active {
  background-color: rgba(46, 98, 255, 0.35) !important; /* Richer/more bluish when selected */
  border-left: 3px solid #2E62FF !important; /* Stronger bluish selected bar */
  color: #fff !important;
  font-weight: 500 !important;
  border-radius: 0 4px 4px 0 !important; /* rounded-r */
  box-shadow: 0 0 10px rgba(46, 98, 255, 0.15) !important;
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
  transition: color 0.2s ease !important;
}
.gjs-layer-icon {
  color: #8d90a2 !important;
  margin-right: 8px !important;
  transition: color 0.2s ease !important;
}
.gjs-layer.gjs-layer-active > .gjs-layer-header .gjs-layer-icon,
.gjs-layer.gjs-selected > .gjs-layer-header .gjs-layer-icon,
.gjs-layer-header.gjs-layer-active .gjs-layer-icon,
.gjs-layer.gjs-layer-active > .gjs-layer-header .gjs-layer-caret,
.gjs-layer.gjs-selected > .gjs-layer-header .gjs-layer-caret,
.gjs-layer-header.gjs-layer-active .gjs-layer-caret {
  color: #3b82f6 !important;
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
.gjs-blocks-container,
.gjs-pn-views,
.gjs-pn-views-container,
.gjs-pn-panels {
  background-color: transparent !important;
  background: transparent !important;
  border-color: transparent !important;
}
</style>
