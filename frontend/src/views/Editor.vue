<template>
  <div class="studio-light studio-editor">
    <header class="studio-bar editor-bar">
      <StudioBrand :to="demo ? '/showcase' : '/'" />
      <span class="editor-title">{{ demo ? 'Fieldwork' : store.activePage?.title }}</span>
      <div class="studio-actions editor-devices" aria-label="Preview width">
        <button v-for="width in ['desktop', 'tablet', 'mobile']" :key="width" class="studio-button"
          :disabled="!ready" :aria-pressed="device === width" @click="setDevice(width)">{{ width }}</button>
      </div>
      <div class="studio-actions editor-actions">
        <button class="studio-button" :disabled="!ready" @click="previewPage">{{ preview ? 'Exit preview' : 'Preview' }}</button>
        <button class="studio-button" :disabled="!ready" @click="openReview">Responsive review</button>
        <button v-if="demo" class="studio-button" :disabled="!ready" @click="resetDialog.showModal()">Reset</button>
        <template v-else>
          <button v-if="aiEnabled" class="studio-button" :disabled="!ready || busy" @click="showGenerator = true">Generate a page</button>
          <button class="studio-button" :disabled="!ready || busy" @click="saveDraft">Save draft</button>
          <button class="studio-button primary" :disabled="!ready || busy" @click="publish(true)">
            {{ store.activePage?.public_url ? 'Republish' : 'Publish' }}
          </button>
        </template>
      </div>
    </header>
    <div class="editor-status" aria-live="polite">
      <div class="studio-actions">
        <button class="studio-button" :aria-expanded="!store.isLeftSidebarCollapsed" @click="togglePanel('left')">Layers &amp; blocks</button>
        <button class="studio-button" :aria-expanded="!store.isRightSidebarCollapsed" @click="togglePanel('right')">Properties</button>
        <button class="studio-button" :disabled="!ready || preview || (!isolatedName && !store.selectedComponent)"
          @click="isolatedName ? exitIsolation() : isolateSelection()">{{ isolatedName ? 'Exit isolation' : 'Isolate selection' }}</button>
        <template v-if="!demo">
          <RouterLink :to="{ name: 'templates', params: { projectId }, query: { pageId } }">Templates</RouterLink>
          <button class="studio-button" :disabled="!ready || busy" @click="exportDraft">Download static ZIP</button>
        </template>
      </div>
      <span role="status">{{ store.saveState || notice || (isolatedName ? 'Isolating ' + isolatedName : ready ? (demo ? 'Changes stay in this tab' : store.isUnsaved ? 'Unsaved changes' : 'Draft saved') : 'Loading editor…') }}</span>
      <div v-if="!demo && store.activePage?.public_url" class="studio-actions">
        <a :href="store.activePage.public_url" target="_blank" rel="noopener noreferrer">View live page</a>
        <button class="studio-button" @click="copyLink">Copy link</button>
        <button class="studio-button" :disabled="busy" @click="publish(false)">Unpublish</button>
      </div>
    </div>
    <div class="studio-canvas-layout">
      <LeftSidebar v-show="!store.isLeftSidebarCollapsed && !preview" :demo="demo" />
      <main class="studio-canvas" aria-label="Page canvas"><div id="gjs-container"></div></main>
      <RightSidebar v-show="!store.isRightSidebarCollapsed && !preview" :demo="demo" :ai-enabled="aiEnabled" />
    </div>
    <dialog ref="resetDialog" class="studio-dialog" aria-labelledby="reset-title">
      <h2 id="reset-title">Reset sample?</h2>
      <p>Your changes in this tab will be discarded.</p>
      <form method="dialog" class="studio-actions">
        <button class="studio-button" autofocus>Cancel</button>
        <button class="studio-button primary" @click="resetDemo">Reset sample</button>
      </form>
    </dialog>
    <ResponsiveReview ref="review" />
    <AiPageGenerator v-if="aiEnabled && showGenerator" :project-id="String(projectId)" :stitch-enabled="store.activeProject?.stitch_enabled"
      @close="showGenerator = false" @insert="replaceWithGeneratedDraft" />
  </div>
</template>

<script setup>
import StudioBrand from '../components/StudioBrand.vue'
import { computed, onMounted, onBeforeUnmount, ref, watch, nextTick } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import grapesjs from 'grapesjs'
import 'grapesjs/dist/css/grapes.min.css'
import { apiFetch } from '../api'
import { demoHtml, demoCss } from '../data/demo'
import { sanitizeHtml, sanitizeProjectData } from '../pageContent'
import { useWorkspaceStore } from '../stores/workspace'
import LeftSidebar from '../components/editor/LeftSidebar.vue'
import RightSidebar from '../components/editor/RightSidebar.vue'
import AiPageGenerator from '../components/editor/AiPageGenerator.vue'
import ResponsiveReview from '../components/editor/ResponsiveReview.vue'

const props = defineProps({ demo: Boolean })
const store = useWorkspaceStore()
const route = useRoute()
const router = useRouter()
const aiEnabled = computed(() => !props.demo && store.activeProject?.ai_enabled === true)
const showGenerator = ref(false)
const { projectId, pageId } = route.params
const demoKey = 'studiopro.demo.v1'
const ready = ref(false)
const busy = ref(false)
const notice = ref('')
const device = ref('desktop')
const preview = ref(false)
const resetDialog = ref(null)
const review = ref(null)
const isolatedName = ref('')
const isolationAttribute = 'data-studio-isolation-hidden'
let isolatedComponent
let isolationStyle
let hiddenElements = []
let disposed = false
let autosave

function setDevice(width) {
  store.editor.setDevice(width)
  device.value = width
}

function togglePanel(panel) {
  if (panel === 'left') {
    store.setLeftSidebarCollapsed(!store.isLeftSidebarCollapsed)
    if (window.innerWidth < 1000) store.setRightSidebarCollapsed(true)
  } else {
    store.setRightSidebarCollapsed(!store.isRightSidebarCollapsed)
    if (window.innerWidth < 1000) store.setLeftSidebarCollapsed(true)
  }
}

function previewPage() {
  exitIsolation()
  preview.value = !preview.value
  if (preview.value) store.editor.runCommand('preview')
  else store.editor.stopCommand('preview')
}

function restoreIsolationSiblings() {
  for (const [element, previous] of hiddenElements) {
    if (previous === null) element.removeAttribute(isolationAttribute)
    else element.setAttribute(isolationAttribute, previous)
  }
  hiddenElements = []
}

function refreshIsolation() {
  if (!isolatedComponent) return
  const element = isolatedComponent.getEl()
  const body = element?.ownerDocument.body
  if (!body || element === body || !body.contains(element)) {
    exitIsolation()
    return
  }

  restoreIsolationSiblings()
  for (let node = element; node !== body; node = node.parentElement) {
    for (const sibling of node.parentElement.children) {
      if (sibling === node) continue
      hiddenElements.push([sibling, sibling.getAttribute(isolationAttribute)])
      sibling.setAttribute(isolationAttribute, '')
    }
  }
}

function isolateSelection() {
  const component = store.editor.getSelected()
  const element = component?.getEl()
  if (!element || element === element.ownerDocument.body) return
  isolatedComponent = component
  isolatedName.value = component.getName() || element.localName
  notice.value = ''
  isolationStyle = element.ownerDocument.createElement('style')
  isolationStyle.textContent = `[${isolationAttribute}=""], [${isolationAttribute}=""] * { visibility: hidden !important; pointer-events: none !important; }`
  element.ownerDocument.head.append(isolationStyle)
  refreshIsolation()
}

function exitIsolation() {
  restoreIsolationSiblings()
  isolationStyle?.remove()
  isolationStyle = undefined
  isolatedComponent = undefined
  isolatedName.value = ''
}

function openReview() {
  const { editor, pageMeta } = store
  review.value.open(editor.getHtml(), editor.getCss(), pageMeta?.seoTitle)
}

async function exportDraft() {
  busy.value = true
  try {
    if (await saveDraft()) await store.downloadStaticExport(projectId, pageId)
  } catch {
    notice.value = 'Could not export the draft.'
  } finally {
    busy.value = false
  }
}

async function saveDraft() {
  clearTimeout(autosave)
  notice.value = ''
  try {
    await store.savePage(projectId, pageId)
    return true
  } catch {
    return false
  }
}

async function publish(enabled) {
  busy.value = true
  notice.value = ''
  clearTimeout(autosave)
  try {
    await store.publishPage(projectId, pageId, enabled)
    notice.value = enabled ? 'Page published' : 'Page unpublished'
  } catch (error) {
    notice.value = error.message
  } finally {
    busy.value = false
  }
}

async function copyLink() {
  try {
    await navigator.clipboard.writeText(store.activePage.public_url)
    notice.value = 'Link copied'
  } catch {
    notice.value = 'Open the live page and copy its address.'
  }
}

function loadSample(editor) {
  editor.setComponents(sanitizeHtml(demoHtml))
  editor.setStyle(demoCss)
  editor.UndoManager.clear()
}

function resetDemo() {
  exitIsolation()
  loadSample(store.editor)
  sessionStorage.removeItem(demoKey)
  notice.value = 'Sample reset'
}

function replaceWithGeneratedDraft(draft) {
  exitIsolation()
  store.editor.setComponents(sanitizeHtml(draft.html))
  store.editor.setStyle(draft.css || '')
  const meta = draft.meta || {}
  store.setPageMeta({
    seoTitle: meta.title || '',
    seoDescription: meta.description || '',
    ogTitle: meta.og_title || meta.title || '',
    ogDescription: meta.og_description || meta.description || ''
  })
  showGenerator.value = false
}

function warnBeforeUnload(event) {
  if (!props.demo && store.isUnsaved) event.preventDefault()
}

onBeforeRouteLeave(async () => {
  if (!props.demo && store.isUnsaved) return saveDraft()
})

watch(() => store.revision, () => {
  if (!ready.value || !store.isUnsaved) return
  notice.value = ''
  if (props.demo) {
    try {
      sessionStorage.setItem(demoKey, JSON.stringify(sanitizeProjectData(store.editor.getProjectData())))
    } catch {
      notice.value = 'Browser storage is full. Changes are still visible in the editor.'
    }
  } else if (store.preferences.editor.autoSave) {
    clearTimeout(autosave)
    const delay = Math.min(60, Math.max(1, Number(store.preferences.editor.autoSaveDelay) || 5))
    autosave = setTimeout(saveDraft, delay * 1000)
  }
})

onMounted(async () => {
  store.setActivePage(null)
  store.markDirty(false)
  store.saveState = ''
  store.setActiveLeftTab('layers')
  store.setLeftSidebarCollapsed(window.innerWidth < 1000)
  store.setRightSidebarCollapsed(window.innerWidth < 1000)
  let page
  if (!props.demo) {
    try {
      await store.fetchProject(projectId)
      page = store.activeProject?.pages?.find(item => String(item.id) === String(pageId))
      if (!page) {
        await router.replace({ name: 'error', params: { status: '404' } })
        return
      }
    } catch (error) {
      const status = ['403', '404'].includes(String(error.status)) ? String(error.status) : '503'
      await router.replace({ name: 'error', params: { status }, query: status === '503' ? { from: route.fullPath } : {} })
      return
    }
  }

  await nextTick()
  if (disposed) return
  const editor = grapesjs.init({
    container: '#gjs-container',
    height: '100%',
    width: '100%',
    storageManager: false,
    telemetry: false,
    assetManager: { upload: false, uploadText: '', autoAdd: false },
    panels: { defaults: [] },
    canvas: {
      styles: [
        'https://fonts.googleapis.com/css2?family=Geist:wght@400;500;700;800&display=swap',
        'https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&display=swap',
        'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap',
        '/canvas.css'
      ]
    },
    deviceManager: {
      devices: [
        { id: 'desktop', name: 'Desktop', width: '' },
        { id: 'tablet', name: 'Tablet', width: '768px', widthMedia: '768px' },
        { id: 'mobile', name: 'Mobile', width: '320px', widthMedia: '480px' }
      ]
    },
    selectorManager: { componentFirst: true },
    blockManager: {
      appendTo: '#blocks-container',
      blocks: [
        {
          id: 'section',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">view_agenda</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Section</span>`,
          content: '<section class="py-16 px-4 bg-slate-900 text-white min-h-[150px] flex flex-col justify-center items-center"><h2>New Section</h2></section>',
          category: 'Layout'
        },
        {
          id: 'container',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">view_column</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Container</span>`,
          content: '<div class="max-w-6xl mx-auto px-4 py-8 min-h-[100px] bg-slate-800/10">Container Content</div>',
          category: 'Layout'
        },
        {
          id: 'grid',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">grid_view</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Grid</span>`,
          content: '<div class="grid grid-cols-3 gap-4 p-4 min-h-[120px] bg-slate-800/5"><div class="bg-slate-800 p-4 rounded text-center">Col 1</div><div class="bg-slate-800 p-4 rounded text-center">Col 2</div><div class="bg-slate-800 p-4 rounded text-center">Col 3</div></div>',
          category: 'Layout'
        },
        {
          id: 'columns',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">splitscreen</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Columns</span>`,
          content: '<div class="flex gap-4 p-4 min-h-[120px] bg-slate-800/5"><div class="flex-1 bg-slate-800 p-4 rounded text-center">Col 1</div><div class="flex-1 bg-slate-800 p-4 rounded text-center">Col 2</div></div>',
          category: 'Layout'
        },
        {
          id: 'hero-section',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">web</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Hero</span>`,
          content: `<section class="w-full bg-editor-bg text-white px-8 py-20 text-center"><div class="max-w-3xl mx-auto flex flex-col gap-5 items-center"><span class="text-primary text-xs font-bold uppercase tracking-wider">Launch Faster</span><h1 class="text-5xl font-black leading-tight">Build better pages visually</h1><p class="text-on-surface-variant text-lg">Compose sections, bind data, add interactions, and export clean static files.</p><button class="bg-primary text-white px-6 py-3 rounded font-bold">Get Started</button></div></section>`,
          category: 'Sections'
        },
        {
          id: 'navbar',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">menu</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Navbar</span>`,
          content: `<header class="w-full bg-editor-bg border-b border-outline text-white px-8 py-4 flex items-center justify-between"><div class="font-black">Brand</div><nav class="flex gap-5 text-sm text-on-surface-variant"><a>Home</a><a>Work</a><a>Pricing</a></nav><button class="bg-primary px-4 py-2 rounded text-xs font-bold">Contact</button></header>`,
          category: 'Sections'
        },
        {
          id: 'feature-card',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">cards</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Card</span>`,
          content: `<article class="bg-surface-container border border-outline p-6 rounded text-white"><span class="material-symbols-outlined text-primary text-3xl">auto_awesome</span><h3 class="text-xl font-bold mt-4">Feature Card</h3><p class="text-on-surface-variant text-sm mt-2">Reusable content card for benefits, services, or product features.</p></article>`,
          category: 'Basic'
        },
        {
          id: 'testimonial',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">format_quote</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Quote</span>`,
          content: `<section class="bg-white text-slate-900 p-8 rounded border border-slate-200"><p class="text-2xl font-bold leading-tight">"StudioPro helped us ship landing pages without slowing engineering."</p><div class="text-sm text-slate-500 mt-4">Maya Chen, Growth Lead</div></section>`,
          category: 'Sections'
        },
        {
          id: 'pricing-card',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">payments</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Pricing</span>`,
          content: `<article class="bg-editor-bg text-white border border-outline p-8 rounded-lg"><h3 class="text-xl font-bold">Pro</h3><div class="text-4xl font-black mt-4">$49</div><p class="text-on-surface-variant text-sm mt-2">For growing stores and teams.</p><button class="w-full bg-primary py-3 mt-6 rounded font-bold">Choose Plan</button></article>`,
          category: 'Commerce'
        },
        {
          id: 'faq',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">quiz</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">FAQ</span>`,
          content: `<section class="max-w-3xl mx-auto p-6"><h2 class="text-3xl font-black text-slate-900 mb-4">Questions</h2><details class="border-b border-slate-200 py-3"><summary class="font-bold cursor-pointer">Can I export this page?</summary><p class="text-slate-600 mt-2">Yes. Export creates static HTML, CSS, assets, and runtime JavaScript.</p></details></section>`,
          category: 'Sections'
        },
        {
          id: 'image-block',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">image</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Image</span>`,
          content: '<img src="/images/arch_hero_01.png" alt="Page image" class="w-full h-auto object-cover rounded" />',
          category: 'Media'
        },
        {
          id: 'video-embed',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">smart_display</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Video</span>`,
          content: '<div class="w-full aspect-[16/9] bg-editor-bg border border-outline rounded flex items-center justify-center text-white"><span class="material-symbols-outlined text-5xl text-primary">play_circle</span></div>',
          category: 'Media'
        },
        {
          id: 'spacer',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">height</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Spacer</span>`,
          content: '<div style="height: 48px"></div>',
          category: 'Layout'
        },
        {
          id: 'button',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">smart_button</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Button</span>`,
          content: '<button class="px-6 py-2.5 bg-primary text-white rounded font-bold hover:bg-blue-600 transition duration-150">Click Me</button>',
          category: 'Basic'
        },
        {
          id: 'link',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">link</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Link</span>`,
          content: '<a href="#" class="text-primary hover:underline">Link Text</a>',
          category: 'Basic'
        },
        {
          id: 'heading',
          label: `<div class="flex items-center w-full"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl mr-3">title</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white">Heading</span></div>`,
          content: '<h1 class="text-4xl font-extrabold text-slate-900 leading-tight mb-4">Heading Title</h1>',
          category: 'Typography'
        },
        {
          id: 'paragraph',
          label: `<div class="flex items-center w-full"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl mr-3">text_fields</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white">Paragraph</span></div>`,
          content: '<p class="text-slate-600 text-lg leading-relaxed mb-6">Paragraph text content goes here.</p>',
          category: 'Typography'
        },
        {
          id: 'product-grid',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">grid_view</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Product Grid</span>`,
          content: `<section class="bg-editor-bg text-white py-12 px-8 font-geist">
  <div class="max-w-6xl mx-auto">
    <div class="mb-6">
      <span class="text-[#8B5CF6] text-xs font-bold uppercase tracking-wider">Commerce Collection</span>
      <h2 class="text-3xl font-extrabold mt-2">Featured Products</h2>
    </div>
    <div class="grid grid-cols-3 gap-6" data-commerce-source="products">
      <article class="bg-surface-container border border-outline rounded overflow-hidden flex flex-col">
        <img class="w-full aspect-square object-cover" src="/images/product_cam.png" alt="Minimalist Desk Lamp">
        <div class="p-4 flex flex-col gap-3">
          <h3 class="text-white font-bold text-lg">Minimalist Desk Lamp</h3>
          <p class="text-on-surface-variant text-xs leading-relaxed">Matte black studio lamp with architectural lines.</p>
          <div class="flex items-center justify-between">
            <span class="text-white font-bold text-xl">$129.00</span>
            <button data-action="open-cart" class="px-4 py-2 bg-primary text-white rounded-sm text-xs font-bold">Add to Cart</button>
          </div>
        </div>
      </article>
      <article class="bg-surface-container border border-outline rounded overflow-hidden flex flex-col">
        <img class="w-full aspect-square object-cover" src="/images/vector_flat.png" alt="Wireless Mechanical Keyboard">
        <div class="p-4 flex flex-col gap-3">
          <h3 class="text-white font-bold text-lg">Wireless Mechanical Keyboard</h3>
          <p class="text-on-surface-variant text-xs leading-relaxed">Low-profile keys with electric blue lighting.</p>
          <div class="flex items-center justify-between">
            <span class="text-white font-bold text-xl">$199.00</span>
            <button data-action="open-cart" class="px-4 py-2 bg-primary text-white rounded-sm text-xs font-bold">Add to Cart</button>
          </div>
        </div>
      </article>
      <article class="bg-surface-container border border-outline rounded overflow-hidden flex flex-col">
        <img class="w-full aspect-square object-cover" src="/images/arch_hero_01.png" alt="Pro Audio Studio Monitor">
        <div class="p-4 flex flex-col gap-3">
          <h3 class="text-white font-bold text-lg">Pro Audio Studio Monitor</h3>
          <p class="text-on-surface-variant text-xs leading-relaxed">High-fidelity monitor styled for creators.</p>
          <div class="flex items-center justify-between">
            <span class="text-white font-bold text-xl">$349.00</span>
            <button data-action="open-cart" class="px-4 py-2 bg-primary text-white rounded-sm text-xs font-bold">Add to Cart</button>
          </div>
        </div>
      </article>
    </div>
  </div>
</section>`,
          category: 'Commerce'
        },
        {
          id: 'product-image',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">image</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Product Image</span>`,
          content: '<img src="/images/product_cam.png" alt="Product" class="w-full h-auto object-cover rounded-sm" />',
          category: 'Commerce'
        },
        {
          id: 'price-tag',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">label</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Price Tag</span>`,
          content: '<div class="text-xl font-bold text-white">$0.00</div>',
          category: 'Commerce'
        },
        {
          id: 'buy-button',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">shopping_cart</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Buy Button</span>`,
          content: '<button data-action="open-cart" class="px-6 py-2.5 bg-primary text-white rounded font-bold hover:bg-blue-600 transition duration-150 flex items-center gap-2"><span class="material-symbols-outlined text-sm">shopping_cart</span> Add to Cart</button>',
          category: 'Commerce'
        },
        {
          id: 'code-embed',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">code_blocks</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Code Embed</span>`,
          content: { type: 'code-embed' },
          category: 'Advanced'
        },
        {
          id: 'lead-form',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">dynamic_form</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Form</span>`,
          content: `<form data-studiopro-form data-form-name="Lead Capture" class="max-w-xl mx-auto p-6 bg-surface-container border border-outline rounded-lg flex flex-col gap-4 font-geist">
  <div>
    <label class="text-xs text-on-surface-variant uppercase font-bold">Name</label>
    <input name="name" class="w-full mt-1 p-3 bg-editor-bg border border-outline rounded text-white" placeholder="Your name" required />
  </div>
  <div>
    <label class="text-xs text-on-surface-variant uppercase font-bold">Email</label>
    <input name="email" type="email" class="w-full mt-1 p-3 bg-editor-bg border border-outline rounded text-white" placeholder="you@example.com" required />
  </div>
  <div>
    <label class="text-xs text-on-surface-variant uppercase font-bold">Message</label>
    <textarea name="message" class="w-full mt-1 p-3 bg-editor-bg border border-outline rounded text-white" rows="4" placeholder="Tell us what you need"></textarea>
  </div>
  <button class="bg-primary text-white px-5 py-3 rounded font-bold" type="submit">Submit</button>
</form>`,
          category: 'Advanced'
        },
        {
          id: 'collection-list',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">database</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Collection List</span>`,
          content: { type: 'collection-list' },
          category: 'CMS'
        },
        {
          id: 'divider',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">horizontal_rule</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Divider</span>`,
          content: '<hr class="border-t border-slate-200 my-4" />',
          category: 'Basic'
        },
        {
          id: 'badge',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">local_police</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Badge</span>`,
          content: '<span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Badge</span>',
          category: 'Basic'
        },
        {
          id: 'avatar',
          label: `<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-xl block">account_circle</span><span class="text-[11px] font-medium text-on-surface-variant group-hover:text-white mt-1 block">Avatar</span>`,
          content: '<img class="w-10 h-10 rounded-full object-cover" src="/images/avatar_marcus.jpg" alt="Avatar" />',
          category: 'Media'
        }
      ]
    },
    layerManager: {
      appendTo: '#layers-container'
    },
    styleManager: {
      appendTo: '#styles-container',
      sectors: [{
          name: 'General',
          open: false,
          buildProps: ['display', 'position', 'top', 'right', 'left', 'bottom'],
        },{
          name: 'Layout',
          open: false,
          buildProps: ['width', 'height', 'max-width', 'min-height', 'margin', 'padding'],
        },{
          name: 'Typography',
          open: false,
          buildProps: ['font-family', 'font-size', 'font-weight', 'color', 'text-align'],
        },{
          name: 'Decorations',
          open: false,
          buildProps: ['background-color', 'border-radius', 'border', 'box-shadow'],
        }]
    },
    traitManager: {
      appendTo: '#traits-container'
    }
  })

  // Register custom CMS collection list component
  for (const block of [...editor.BlockManager.getAll().models]) {
    const category = block.getCategoryLabel()
    if (['code-embed', 'lead-form'].includes(block.getId()) || (props.demo && ['CMS', 'Commerce', 'Advanced'].includes(category))) {
      editor.BlockManager.remove(block.getId())
    }
  }

  if (!props.demo) {
    editor.Components.addType('collection-list', {
      model: {
        defaults: {
          tagName: 'div',
            attributes: { class: 'collection-list-container flex flex-col gap-4 p-4 border border-dashed border-primary/40 rounded min-h-[120px]', 'data-cms-source': '' },
          traits: [
            {
              type: 'select',
              name: 'collection',
              label: 'CMS Collection',
              options: [
                { value: '', name: '-- Select Collection --' },
                { value: 'blog-posts', name: 'Blog Posts' },
                { value: 'team', name: 'Team' }
              ],
              changeProp: 1
            }
          ],
          collection: '',
        },
        init() {
          this.on('change:collection', this.onCollectionChange);
        },
        async onCollectionChange() {
          const colSlug = this.get('collection');
          this.addAttributes({ 'data-cms-source': colSlug || '' });
          if (!colSlug) return;

          try {
            const res = await apiFetch(`${store.apiBaseUrl}/projects/${projectId}/collections`);
            if (res.ok) {
              const collections = await res.json();
              const selectedCol = collections.find(c => c.slug === colSlug);
              if (selectedCol) {
                const itemsRes = await apiFetch(`${store.apiBaseUrl}/collections/${selectedCol.id}/items`);
                if (itemsRes.ok) {
                  const items = await itemsRes.json();

                  // Clear existing child components
                  this.components().reset();

                  // Dynamically render collection list mockup cards
                  if (!items.length) {
                    this.append('<div class="p-4 bg-surface-container border border-outline rounded text-on-surface-variant text-sm">No CMS items found for this collection.</div>');
                  }

                  items.forEach(item => {
                    let rowHtml = `<div class="flex items-center gap-4 bg-surface-container p-4 rounded border border-outline text-white">`;

                    // Image
                    const imgField = selectedCol.fields.find(f => f.type === 'asset');
                    if (imgField && item.data[imgField.key]) {
                      rowHtml += `<img class="w-16 h-16 object-cover rounded" src="${item.data[imgField.key]}" />`;
                    }

                    rowHtml += `<div class="flex-1">`;

                    // Name/Title
                    const nameField = selectedCol.fields.find(f => f.key === 'name' || f.key === 'title');
                    if (nameField && item.data[nameField.key]) {
                      rowHtml += `<h4 class="text-sm font-bold text-primary">${item.data[nameField.key]}</h4>`;
                    }

                    // Other text fields
                    selectedCol.fields.forEach(f => {
                      if (f.type !== 'asset' && f.key !== 'name' && f.key !== 'title') {
                        rowHtml += `<p class="text-xs text-on-surface-variant mt-1">${f.name}: ${item.data[f.key] || ''}</p>`;
                      }
                    });

                    rowHtml += `</div></div>`;

                    this.append(sanitizeHtml(rowHtml));
                  });
                }
              }
            }
          } catch (err) {
            console.error(err);
          }
        }
      }
    });

  }
  store.setEditor(editor)
  if (props.demo) {
    loadSample(editor)
    try {
      const saved = sessionStorage.getItem(demoKey)
      if (saved) editor.loadProjectData(sanitizeProjectData(JSON.parse(saved)))
    } catch {
      notice.value = 'This tab?s saved draft could not be read.'
    }
  } else {
    store.setActivePage(page)
    store.loadDraft(editor, page)
  }

  editor.on('component:selected', component => {
    if (isolatedComponent && !isolatedComponent.getEl()?.contains(component.getEl())) exitIsolation()
    store.setSelectedComponent(component)
  })
  editor.on('component:deselected', () => store.setSelectedComponent(null))
  editor.on('update', () => {
    if (!ready.value) return
    refreshIsolation()
    store.incrementStyleTrigger()
    store.markDirty()
  })
  editor.UndoManager.clear()
  store.markDirty(false)
  ready.value = true
  window.addEventListener('beforeunload', warnBeforeUnload)
})

onBeforeUnmount(() => {
  disposed = true
  ready.value = false
  clearTimeout(autosave)
  exitIsolation()
  window.removeEventListener('beforeunload', warnBeforeUnload)
  store.editor?.destroy()
  store.setEditor(null)
  store.setSelectedComponent(null)
})
</script>
