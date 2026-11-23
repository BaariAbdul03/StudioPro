<template>
  <aside id="editor-library" aria-label="Editor library" class="editor-library w-[280px] bg-surface-container border-r border-outline flex flex-col z-40 shrink-0 absolute md:relative left-0 top-0 bottom-0 h-full font-geist">
    <button
      @click="store.setLeftSidebarCollapsed(true)"
      class="absolute right-2 top-2 z-20 h-7 w-7 rounded bg-editor-bg border border-outline text-on-surface-variant hover:text-white hover:border-primary flex items-center justify-center"
      title="Collapse left sidebar"
    >
      <span class="material-symbols-outlined text-[16px]">left_panel_close</span>
    </button>
    <!-- Header Removed -->
    
    <!-- Navigation Tabs -->
    <div class="editor-library-tabs flex flex-col border-b border-outline">
      <button 
        @click="selectTab('layers')" :aria-pressed="store.activeLeftTab === 'layers'"
        class="px-5 py-3 flex items-center gap-3 transition-all duration-150"
        :class="store.activeLeftTab === 'layers' ? 'bg-primary/15 text-primary border-l-2 border-primary' : 'text-on-surface-variant hover:bg-[#2d3449] border-l-2 border-transparent'"
      >
        <span class="material-symbols-outlined text-[18px]" aria-hidden="true">layers</span>
        <span class="text-[13px] font-medium font-geist">Layers</span>
      </button>

      <button 
        @click="selectTab('blocks')" :aria-pressed="store.activeLeftTab === 'blocks'"
        class="px-5 py-3 flex items-center gap-3 transition-all duration-150"
        :class="store.activeLeftTab === 'blocks' ? 'bg-primary/15 text-primary border-l-2 border-primary' : 'text-on-surface-variant hover:bg-[#2d3449] border-l-2 border-transparent'"
      >
        <span class="material-symbols-outlined text-[18px]" aria-hidden="true">add_box</span>
        <span class="text-[13px] font-medium font-geist">Blocks</span>
      </button>

      <button 
        v-if="!demo" @click="selectTab('symbols')" :aria-pressed="store.activeLeftTab === 'symbols'"
        class="px-5 py-3 flex items-center gap-3 transition-all duration-150"
        :class="store.activeLeftTab === 'symbols' ? 'bg-primary/15 text-primary border-l-2 border-primary' : 'text-on-surface-variant hover:bg-[#2d3449] border-l-2 border-transparent'"
      >
        <span class="material-symbols-outlined text-[18px]" aria-hidden="true">widgets</span>
        <span class="text-[13px] font-medium font-geist">Symbols</span>
      </button>

      <button 
        v-if="!demo" @click="selectTab('assets')" :aria-pressed="store.activeLeftTab === 'assets'"
        class="px-5 py-3 flex items-center gap-3 transition-all duration-150"
        :class="store.activeLeftTab === 'assets' ? 'bg-primary/15 text-primary border-l-2 border-primary' : 'text-on-surface-variant hover:bg-[#2d3449] border-l-2 border-transparent'"
      >
        <span class="material-symbols-outlined text-[18px]" aria-hidden="true">image</span>
        <span class="text-[13px] font-medium font-geist">Assets</span>
      </button>

      <button 
        v-if="!demo" @click="selectTab('config')" :aria-pressed="store.activeLeftTab === 'config'"
        class="px-5 py-3 flex items-center gap-3 transition-all duration-150"
        :class="store.activeLeftTab === 'config' ? 'bg-primary/15 text-primary border-l-2 border-primary' : 'text-on-surface-variant hover:bg-[#2d3449] border-l-2 border-transparent'"
      >
        <span class="material-symbols-outlined text-[18px]" aria-hidden="true">tune</span>
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
          <div v-show="!compact" id="blocks-container" class="w-full">
            <!-- GrapesJS Block Manager automatically appends categories here. We style them using CSS overrides below -->
          </div>
          <div v-if="compact" class="editor-symbol-list">
            <button v-for="{ block, label } in tapBlocks" :key="block.getId()" class="editor-symbol-button"
              :aria-label="'Add ' + label" @click="insertBlock(block)">
              {{ label }} <span class="material-symbols-outlined" aria-hidden="true">add</span>
            </button>
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

      <section v-if="!demo" v-show="store.activeLeftTab === 'symbols'" class="editor-panel-content" aria-label="Symbols library">
        <h2>Symbols</h2>
        <p>Linked components for this page. Editing an instance updates the others.</p>
        <form v-if="canCreateSymbol" class="editor-symbol-form" @submit.prevent="createSymbol">
          <label>Symbol name<input v-model.trim="symbolName" maxlength="60" required></label>
          <button class="studio-button primary">Create symbol</button>
        </form>
        <p v-else-if="!symbols.length">Select an element on the canvas to make your first symbol.</p>
        <div v-if="symbols.length" class="editor-symbol-list">
          <h3>Saved symbols</h3>
          <button v-for="symbol in symbols" :key="symbol.id" class="editor-symbol-button" :aria-label="'Insert linked ' + symbol.name" @click="addSymbolInstance(symbol.component)">
            <span><strong>{{ symbol.name }}</strong><small>{{ symbol.instances }} linked {{ symbol.instances === 1 ? 'instance' : 'instances' }}</small></span>
            <span class="material-symbols-outlined" aria-hidden="true">add</span>
          </button>
        </div>
        <div class="editor-symbol-list">
          <h3>Starter components</h3>
          <button v-for="preset in presets" :key="preset.type" class="editor-symbol-button" :disabled="!store.editor" @click="insertPreset(preset)">
            <span class="material-symbols-outlined" aria-hidden="true">{{ preset.icon }}</span>
            <strong>{{ preset.name }}</strong>
            <span class="material-symbols-outlined" aria-hidden="true">add</span>
          </button>
        </div>
        <p v-if="symbolNotice" role="status">{{ symbolNotice }}</p>
      </section>

      <section v-if="!demo" v-show="store.activeLeftTab === 'assets'" class="editor-panel-content" aria-label="Media library">
        <h2>Assets</h2>
        <input ref="assetInput" type="file" accept="image/jpeg,image/png,image/webp" hidden @change="uploadAsset">
        <button class="studio-button" :disabled="uploading || !store.editor" @click="assetInput?.click()">
          {{ uploading ? 'Uploading?' : 'Upload image' }}
        </button>
        <p>JPEG, PNG or WebP up to 10 MB. Select an image to add it to the canvas.</p>
        <p v-if="assetError" role="alert">{{ assetError }}</p>
        <div class="editor-asset-grid">
          <button v-for="asset in assetLibrary" :key="asset.src" class="editor-asset"
            :aria-label="'Insert ' + asset.name" :disabled="!store.editor || !!failedAssets[asset.src]"
            :draggable="!failedAssets[asset.src]" @dragstart="event => dragAsset(event, asset)"
            @click="insertAsset(asset.src, asset.alt)">
            <img v-if="!failedAssets[asset.src]" :src="asset.src" alt="" width="156" height="104" loading="lazy"
              @error="failedAssets[asset.src] = true">
            <span v-else class="editor-asset-unavailable">Image unavailable</span>
            <span class="editor-asset-name">{{ asset.name }}</span>
          </button>
        </div>
      </section>

      <section v-if="!demo" v-show="store.activeLeftTab === 'config'" class="editor-panel-content" aria-label="Page configuration">
        <h2>Config</h2>
        <section class="editor-config-group">
          <h3>Page metadata</h3>
          <label>Page SEO title<input v-model="seoTitle" maxlength="255" placeholder="Page title"></label>
          <label>Page meta description<textarea v-model="seoDescription" maxlength="1000" rows="3" placeholder="Describe this page"></textarea></label>
          <p>Saved with the draft.</p>
        </section>
        <section class="editor-config-group">
          <h3>Editor</h3>
          <ThemeControl />
          <label class="editor-config-checkbox"><input v-model="autosaveEnabled" type="checkbox">Autosave drafts</label>
          <button class="studio-button" :aria-pressed="outlined" :disabled="!store.editor" @click="emit('toggle-outlines')">
            {{ outlined ? 'Hide component outlines' : 'Show component outlines' }}
          </button>
          <RouterLink class="studio-button" :to="{ name: 'settings', params: { projectId } }">Workspace settings</RouterLink>
        </section>
      </section>
    </div>
  </aside>
</template>

<script setup>
const props = defineProps({ demo: Boolean, outlined: Boolean, compact: Boolean })
const emit = defineEmits(['toggle-outlines'])
import { apiFetch } from '../../api'
import ThemeControl from '../ThemeControl.vue'

import { ref, watch, nextTick, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useWorkspaceStore } from '../../stores/workspace'

const store = useWorkspaceStore()
const route = useRoute()
const projectId = computed(() => route.params.projectId)

const blockSearch = ref('')
const assetInput = ref(null)
const uploadedAssets = ref([])
const failedAssets = ref({})
const assetError = ref('')
const uploading = ref(false)
const symbolName = ref('')
const symbolNotice = ref('')

const baseAssets = [
  { src: '/images/arch_hero_01.png', alt: 'Architecture Hero', name: 'arch_hero_01.png' },
  { src: '/images/texture_vibe.png', alt: 'Metal Texture', name: 'texture_vibe.png' },
  { src: '/images/product_cam.png', alt: 'Product Camera', name: 'product_cam.png' },
  { src: '/images/vector_flat.png', alt: 'Workspace Illustration', name: 'vector_flat.png' },
  { src: '/images/template-1551288049-bebda4e38f71.webp', alt: 'Analytics Dashboard', name: 'dashboard_analytics.webp' },
  { src: '/images/template-1507238691740-187a5b1d37b8.webp', alt: 'UI Design Layout', name: 'ui_design_layout.webp' },
  { src: '/images/template-1581291518633-83b4ebd1d83e.webp', alt: 'SaaS Platform Mock', name: 'saas_platform.webp' },
  { src: '/images/template-1618005182384-a83a8bd57fbe.webp', alt: 'Abstract Gradient Flow', name: 'gradient_flow.webp' },
  { src: '/images/template-1634017839464-5c339ebe3cb4.webp', alt: 'Neon 3D Geometry', name: 'neon_geometry.webp' },
  { src: '/images/template-1579546929518-9e396f3cc809.webp', alt: 'Vibrant Mesh Gradient', name: 'mesh_gradient.webp' },
  { src: '/images/template-1498050108023-c5249f4df085.webp', alt: 'Developer Desk Setup', name: 'developer_desk.webp' },
  { src: '/images/template-1531403009284-440f080d1e12.webp', alt: 'Collaborative UX Session', name: 'ux_session.webp' },
  { src: '/images/template-1532795986-dbef1643a596.webp', alt: 'Premium Matcha Bowl', name: 'matcha_bowl.webp' },
  { src: '/images/template-1544787219-7f47ccb76574.webp', alt: 'Mechanical Keyboard', name: 'keyboard_wireless.webp' },
  { src: '/images/template-1534528741775-53994a69daeb.webp', alt: 'Sarah Connor CEO', name: 'avatar_sarah.webp' },
  { src: '/images/template-1507003211169-0a1dd7228f2d.webp', alt: 'Marcus Aurelius Dev', name: 'avatar_marcus.webp' }
]

const assetLibrary = computed(() => {
  const seen = new Set()
  return [...uploadedAssets.value.map(asset => ({ src: asset.url, name: asset.filename, alt: '' })), ...baseAssets].filter(asset => {
    if (!asset.src || seen.has(asset.src)) return false
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

const selectTab = tab => store.setActiveLeftTab(tab)

const tapBlocks = computed(() => {
  if (!props.compact || store.activeLeftTab !== 'blocks' || !store.editor) return []
  const query = blockSearch.value.trim().toLowerCase()
  return store.editor.BlockManager.getAll().map(block => {
    const markup = new DOMParser().parseFromString(block.getLabel(), 'text/html')
    markup.querySelectorAll('.material-symbols-outlined').forEach(icon => icon.remove())
    return { block, label: markup.body.textContent.trim() }
  }).filter(item => item.label.toLowerCase().includes(query))
})

function insertBlock(block) {
  const editor = store.editor
  if (!editor) return
  const [component] = editor.addComponents(block.getContent())
  if (component) editor.select(component)
  store.setLeftSidebarCollapsed(true)
}

const autosaveEnabled = computed({
  get: () => store.preferences.editor.autoSave,
  set: autoSave => store.savePreferences({ editor: { ...store.preferences.editor, autoSave } })
})

const presets = [
  { type: 'navbar', name: 'Navigation', icon: 'web_asset' },
  { type: 'hero', name: 'Hero', icon: 'view_agenda' },
  { type: 'buttons', name: 'Button group', icon: 'smart_button' },
  { type: 'footer', name: 'Footer', icon: 'bottom_panel_close' }
]

const symbols = computed(() => {
  void store.styleTrigger
  const components = store.editor?.Components
  return components?.getSymbols().map(component => ({
    component, id: component.getId(), name: component.getName(),
    instances: components.getSymbolInfo(component).instances.length
  })) || []
})

const canCreateSymbol = computed(() => {
  void store.styleTrigger
  const editor = store.editor
  const selected = store.selectedComponent
  return selected && editor && selected !== editor.getWrapper() && !editor.Components.getSymbolInfo(selected).isSymbol
})

function createSymbol() {
  if (!canCreateSymbol.value || !symbolName.value) return
  store.selectedComponent.set('name', symbolName.value)
  store.editor.Components.addSymbol(store.selectedComponent)
  symbolNotice.value = 'Created ' + symbolName.value + '.'
  symbolName.value = ''
  store.incrementStyleTrigger()
  store.markDirty()
}

function addSymbolInstance(symbol) {
  const editor = store.editor
  const [existing] = editor.Components.getSymbolInfo(symbol).instances
  const instance = editor.Components.addSymbol(symbol)
  if (!instance) return
  editor.addComponents(instance)
  const style = existing?.getStyle() || symbol.getStyle()
  if (Object.keys(style).length) instance.setStyle(style)
  editor.select(instance)
  symbolNotice.value = 'Inserted ' + symbol.getName() + '.'
  store.incrementStyleTrigger()
  store.markDirty()
}

function insertPreset(preset) {
  const editor = store.editor
  if (!editor) return
  const existing = editor.Components.getSymbols().find(component => component.getAttributes()['data-studio-preset'] === preset.type)
  if (existing) return addSymbolInstance(existing)
  const content = {
    hero: editor.BlockManager.get('hero-section')?.get('content'),
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
  const [component] = editor.addComponents(content[preset.type])
  if (!component) return
  component.set('name', preset.name)
  component.addAttributes({ 'data-studio-preset': preset.type })
  editor.Components.addSymbol(component)
  editor.select(component)
  symbolNotice.value = 'Inserted ' + preset.name + '.'
  store.incrementStyleTrigger()
  store.markDirty()
}

const uploadAsset = async event => {
  const file = event.target.files?.[0]
  if (!file || uploading.value) return
  uploading.value = true
  assetError.value = ''
  const formData = new FormData()
  formData.append('file', file)

  try {
    const response = await apiFetch('/api/projects/' + projectId.value + '/assets', {
      method: 'POST',
      body: formData
    })
    if (!response.ok) {
      const data = await response.json()
      throw new Error(data.errors?.file?.[0] || 'Image could not be uploaded.')
    }
    const asset = await response.json()
    uploadedAssets.value.unshift(asset)
    insertAsset(asset.url, '')
  } catch (error) {
    assetError.value = error.message || 'Image could not be uploaded.'
  } finally {
    uploading.value = false
    event.target.value = ''
  }
}

onMounted(async () => {
  if (props.demo) return
  try {
    const response = await apiFetch('/api/projects/' + projectId.value + '/assets')
    if (!response.ok) throw new Error()
    uploadedAssets.value = await response.json()
  } catch {
    assetError.value = 'Project uploads could not be loaded.'
  }
})

function dragAsset(event, asset) {
  const image = document.createElement('img')
  image.src = asset.src
  image.alt = asset.alt || ''
  event.dataTransfer.setData('text/html', image.outerHTML)
}

function insertAsset(src, alt) {
  const editor = store.editor
  if (!editor) return
  const selected = store.selectedComponent
  if (selected?.is('image')) {
    selected.addAttributes({ src, alt: alt || '' })
  } else {
    const [image] = editor.addComponents({
      type: 'image',
      attributes: { src, alt: alt || '', class: 'w-full h-auto object-cover rounded-sm' }
    })
    if (image) editor.select(image)
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
  color: var(--studio-muted) !important;
  font-family: 'Geist', sans-serif !important;
  font-size: 10px !important;
  font-weight: 700 !important;
  text-transform: uppercase !important;
  letter-spacing: 0.05em !important;
  padding: 0 0 6px 0 !important;
  border-bottom: 1px solid rgba(67, 70, 86, 0.2) !important;
  margin-bottom: 8px !important;
  display: flex !important;
  justify-content: space-between !important;
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
  background: var(--studio-well) !important;
  border: 1px solid var(--studio-line) !important;
  border-radius: 4px;
  color: var(--studio-fg) !important;
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
  border-color: var(--studio-link) !important;
  background-color: var(--studio-hover) !important;
  color: var(--studio-fg) !important;
}
.gjs-block:hover span.material-symbols-outlined {
  color: var(--studio-link) !important;
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
  background-color: var(--studio-well) !important; /* Grayish base when not selected */
  border: none !important;
  padding: 4px 8px !important;
  font-family: 'JetBrains Mono', monospace !important;
  font-size: 11px !important;
  color: var(--studio-muted) !important;
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
  background-color: var(--studio-hover) !important; /* Blue shade on hover */
  border-left: 2px solid var(--studio-link) !important;
  color: var(--studio-fg) !important;
}
.gjs-layer-header:hover .gjs-layer-icon,
.gjs-layer-header:hover .gjs-layer-caret {
  color: var(--studio-link) !important;
}
.gjs-layer.gjs-layer-active > .gjs-layer-header,
.gjs-layer.gjs-selected > .gjs-layer-header,
.gjs-layer-header.gjs-layer-active {
  background-color: var(--studio-selected) !important; /* Richer/more bluish when selected */
  border-left: 3px solid var(--studio-link) !important; /* Stronger bluish selected bar */
  color: var(--studio-fg) !important;
  font-weight: 500 !important;
  border-radius: 0 4px 4px 0 !important; /* rounded-r */
  box-shadow: none !important;
}
.gjs-layer-title {
  font-family: 'JetBrains Mono', monospace !important;
  font-weight: 400 !important;
}
.gjs-layer-caret {
  color: var(--studio-muted) !important;
  margin-right: 4px !important;
  font-size: 14px !important;
  display: flex !important;
  align-items: center !important;
  transition: color 0.2s ease !important;
}
.gjs-layer-icon {
  color: var(--studio-muted) !important;
  margin-right: 8px !important;
  transition: color 0.2s ease !important;
}
.gjs-layer.gjs-layer-active > .gjs-layer-header .gjs-layer-icon,
.gjs-layer.gjs-selected > .gjs-layer-header .gjs-layer-icon,
.gjs-layer-header.gjs-layer-active .gjs-layer-icon,
.gjs-layer.gjs-layer-active > .gjs-layer-header .gjs-layer-caret,
.gjs-layer.gjs-selected > .gjs-layer-header .gjs-layer-caret,
.gjs-layer-header.gjs-layer-active .gjs-layer-caret {
  color: var(--studio-link) !important;
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
