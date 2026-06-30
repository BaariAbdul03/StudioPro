<template>
  <div class="h-screen w-full flex flex-col bg-editor-bg font-geist">
    <TopBar />
    
    <!-- Workspace -->
    <div class="flex-1 flex overflow-hidden">
      <LeftSidebar v-show="!store.isLeftSidebarCollapsed" />
      <button
        v-show="store.isLeftSidebarCollapsed"
        @click="store.setLeftSidebarCollapsed(false)"
        class="absolute left-3 top-20 z-30 h-9 w-9 rounded bg-surface-container border border-outline text-on-surface-variant hover:text-white hover:border-primary flex items-center justify-center shadow-xl"
        title="Expand left sidebar"
      >
        <span class="material-symbols-outlined text-[18px]">right_panel_open</span>
      </button>
      
      <!-- Canvas Area -->
      <div class="flex-1 bg-[#1F2937] overflow-hidden flex flex-col relative">
        <div id="gjs-container" class="flex-1 w-full h-full absolute inset-0"></div>
        
        <!-- Isolation Dimming Overlay -->
        <div v-if="store.isIsolationMode" class="absolute inset-0 bg-black/60 z-20 pointer-events-none transition-all duration-300"></div>

        <!-- Isolation Header Banner -->
        <div v-if="store.isIsolationMode" class="absolute top-4 left-4 right-4 flex justify-between items-center z-30 pointer-events-auto">
          <div class="flex items-center gap-2 bg-surface-container/90 backdrop-blur-[12px] border border-outline px-4 py-1.5 rounded-full">
            <span class="text-primary material-symbols-outlined text-[18px]">auto_fix_high</span>
            <span class="text-xs font-bold text-white uppercase tracking-wider">Symbol Isolation: <span class="text-on-surface-variant">{{ store.isolatedSymbolName }}</span></span>
            <div class="h-4 w-[1px] bg-[#434656] mx-2"></div>
            <button @click="store.setIsolationMode(false)" class="text-on-surface-variant hover:text-white flex items-center gap-1 cursor-pointer">
              <span class="text-xs font-bold uppercase tracking-wider">Exit</span>
              <span class="material-symbols-outlined text-[16px]">close</span>
            </button>
          </div>
          <div class="bg-surface-container/90 backdrop-blur-[12px] border border-outline px-3 py-1.5 rounded text-[10px] font-mono text-on-surface-variant">
            X: 0px Y: 0px
          </div>
        </div>

        <!-- Isolated Symbol Mockup Card (Centred overlay) -->
        <div v-if="store.isIsolationMode" class="absolute z-[25] w-[min(600px,calc(100%-32px))] bg-surface-container border border-primary shadow-2xl rounded shadow-primary/20 p-12 flex flex-col gap-6 overflow-hidden top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-auto">
          <!-- Selection Handles -->
          <div class="absolute top-0 left-0 w-2 h-2 bg-primary border border-white -translate-x-1/2 -translate-y-1/2"></div>
          <div class="absolute top-0 right-0 w-2 h-2 bg-primary border border-white translate-x-1/2 -translate-y-1/2"></div>
          <div class="absolute bottom-0 left-0 w-2 h-2 bg-primary border border-white -translate-x-1/2 translate-y-1/2"></div>
          <div class="absolute bottom-0 right-0 w-2 h-2 bg-primary border border-white translate-x-1/2 translate-y-1/2"></div>
          
          <div class="flex flex-col gap-3">
            <span class="text-primary text-[11px] font-bold uppercase tracking-widest text-left">New Feature</span>
            <h1 class="text-3xl font-bold text-white max-w-md leading-tight text-left">Design at the speed of thought.</h1>
            <p class="text-xs text-on-surface-variant max-w-sm leading-relaxed text-left">The ultimate visual canvas for high-performance development and creative automation.</p>
          </div>
          
          <div class="flex gap-4 mt-2 justify-start">
            <button class="bg-primary text-white px-5 py-2 rounded-sm text-xs font-bold transition hover:bg-blue-600 active:scale-95">Get Started</button>
            <button class="border border-outline text-white px-5 py-2 rounded-sm text-xs font-medium hover:bg-[#2d3449] transition active:scale-95">Documentation</button>
          </div>
          
          <!-- Interaction Indicator -->
          <div class="absolute top-4 right-4">
            <span class="material-symbols-outlined text-primary animate-pulse">bolt</span>
          </div>
        </div>

        <CanvasFooter class="absolute bottom-0 left-0 right-0 z-10" />

        <!-- Mock Cart Drawer, hidden by default unless triggered via vue store or local state -->
        <CartDrawer v-if="store.isCartDrawerOpen" />
        <AiPageGenerator v-if="store.isAiGeneratorOpen" @insert="insertAiPage" />
      </div>
      
      <RightSidebar v-show="!store.isRightSidebarCollapsed" />
      <button
        v-show="store.isRightSidebarCollapsed"
        @click="store.setRightSidebarCollapsed(false)"
        class="absolute right-3 top-20 z-30 h-9 w-9 rounded bg-surface-container border border-outline text-on-surface-variant hover:text-white hover:border-primary flex items-center justify-center shadow-xl"
        title="Expand right sidebar"
      >
        <span class="material-symbols-outlined text-[18px]">left_panel_open</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref, watch, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import grapesjs from 'grapesjs'
import 'grapesjs/dist/css/grapes.min.css'
import { useWorkspaceStore } from '../stores/workspace'
import TopBar from '../components/editor/TopBar.vue'
import LeftSidebar from '../components/editor/LeftSidebar.vue'
import RightSidebar from '../components/editor/RightSidebar.vue'
import CanvasFooter from '../components/editor/CanvasFooter.vue'
import CartDrawer from '../components/editor/CartDrawer.vue'
import AiPageGenerator from '../components/editor/AiPageGenerator.vue'

const store = useWorkspaceStore()
const route = useRoute()
const router = useRouter()
const runtimeFallbackTimer = ref(null)

watch(() => [route.params.projectId, route.params.pageId], ([projId, pgId]) => {
  if (projId && pgId) {
    localStorage.setItem('lastWorkingProject', projId)
    localStorage.setItem('lastWorkingPage', pgId)
  }
}, { immediate: true })

const starterPageHtml = `
<main class="w-full bg-white font-geist">
  <section class="py-20 px-8 bg-slate-50 text-center border-b border-slate-200">
    <div class="max-w-3xl mx-auto flex flex-col items-center gap-6">
      <div class="w-24 h-24 bg-primary rounded-2xl flex items-center justify-center text-white text-4xl font-bold shadow-lg">S</div>
      <h1 class="text-5xl font-black text-slate-900 leading-tight">Elevate Your Design Workflow with StudioPro</h1>
      <p class="text-xl text-slate-600">A pro-grade visual editor with commerce blocks and dynamic CMS bindings.</p>
      <div class="flex gap-4 justify-center">
        <button class="bg-primary text-white px-6 py-3 rounded-xl font-bold text-lg">Get Started</button>
        <button class="bg-white border-2 border-slate-200 text-slate-900 px-6 py-3 rounded-xl font-bold text-lg">View Demo</button>
      </div>
    </div>
  </section>
  <section class="max-w-6xl mx-auto py-16 px-8 grid grid-cols-3 gap-6">
    <article class="bg-surface-container border border-outline rounded-lg overflow-hidden">
      <img class="w-full aspect-[4/3] object-cover" src="/images/arch_hero_01.png" alt="Architecture hero">
      <div class="p-4">
        <h3 class="text-white text-lg font-bold">Visual Canvas</h3>
        <p class="text-on-surface-variant text-sm mt-1">Drag blocks, select layers, and style live components.</p>
      </div>
    </article>
    <article class="bg-surface-container border border-outline rounded-lg overflow-hidden">
      <img class="w-full aspect-[4/3] object-cover" src="/images/product_cam.png" alt="Product preview">
      <div class="p-4">
        <h3 class="text-white text-lg font-bold">Commerce Blocks</h3>
        <p class="text-on-surface-variant text-sm mt-1">Drop product grids, price tags, and cart triggers.</p>
      </div>
    </article>
    <article class="bg-surface-container border border-outline rounded-lg overflow-hidden">
      <img class="w-full aspect-[4/3] object-cover" src="/images/vector_flat.png" alt="CMS preview">
      <div class="p-4">
        <h3 class="text-white text-lg font-bold">CMS Bindings</h3>
        <p class="text-on-surface-variant text-sm mt-1">Preview collection data directly in the canvas.</p>
      </div>
    </article>
  </section>
</main>`

const loadStarterPage = (editor) => {
  editor.setComponents(starterPageHtml)
  editor.setStyle('')
  store.markDirty(false)
}

const insertAiPage = (payload) => {
  if (!store.editor || !payload) return
  store.editor.setComponents(payload.html || '')
  store.editor.setStyle(payload.css || '')
  
  // Dynamically load any external stylesheets (e.g. fonts, Tailwind CDN) returned by the generator
  if (payload.meta && Array.isArray(payload.meta.stylesheets)) {
    payload.meta.stylesheets.forEach(url => {
      try {
        store.editor.Canvas.addStylesheet(url)
      } catch (err) {
        console.warn('Failed to add dynamic stylesheet:', url, err)
      }
    })
  }

  if (payload.meta) {
    store.setPageMeta({
      seoTitle: payload.meta.title || payload.meta.seoTitle || store.pageMeta.seoTitle,
      seoDescription: payload.meta.description || payload.meta.seoDescription || store.pageMeta.seoDescription,
      ogTitle: payload.meta.og_title || payload.meta.ogTitle || payload.meta.title || store.pageMeta.ogTitle,
      ogDescription: payload.meta.og_description || payload.meta.ogDescription || payload.meta.description || store.pageMeta.ogDescription
    })
  }
  store.setAiGeneratorOpen(false)
  store.markDirty()
}

const escapeAttr = (value = '') => String(value)
  .replaceAll('&', '&amp;')
  .replaceAll('"', '&quot;')
  .replaceAll('<', '&lt;')
  .replaceAll('>', '&gt;')

const buildCodeEmbedSrcDoc = ({ html = '<div>Custom embed</div>', css = '', js = '' } = {}) => {
  return `<!doctype html><html><head><style>body{margin:0;font-family:Geist,Arial,sans-serif}${css}</style></head><body>${html}<script>${js}<\/script></body></html>`
}

const buildCodeEmbedFrame = (attrs = {}) => {
  const srcdoc = buildCodeEmbedSrcDoc({
    html: attrs['data-code-html'] || '<div style="padding:24px;background:#0B0E14;color:white">Custom embed</div>',
    css: attrs['data-code-css'] || '',
    js: attrs['data-code-js'] || ''
  })
  return `<iframe sandbox="allow-scripts allow-same-origin" class="w-full min-h-[150px] border border-outline rounded bg-white" srcdoc="${escapeAttr(srcdoc)}"></iframe>`
}

const attachStudioProRuntime = (editor, projectId, pageId) => {
  const doc = editor.Canvas.getDocument()
  if (!doc?.body || doc.defaultView.__studioProRuntimeAttached) return

  doc.defaultView.__studioProRuntimeAttached = true

  doc.addEventListener('click', (event) => {
    const cartTarget = event.target.closest('[data-action="open-cart"]')
    if (cartTarget) {
      event.preventDefault()
      store.toggleCartDrawer(true)
      return
    }

    const a = event.target.closest('a')
    if (a) {
      const href = a.getAttribute('href')
      if (href && href.startsWith('#')) {
        event.preventDefault()
        const targetId = href.substring(1)
        const targetEl = targetId ? doc.getElementById(targetId) : null
        if (targetEl) {
          targetEl.scrollIntoView({ behavior: 'smooth' })
        } else if (!targetId) {
          doc.defaultView.scrollTo({ top: 0, behavior: 'smooth' })
        }
      } else {
        event.preventDefault()
      }
    }
  })

  const runAnimation = (element) => {
    const action = element.dataset.interactionAction || 'move'
    const duration = Number(element.dataset.interactionDuration || 500)
    const delay = Number(element.dataset.interactionDelay || 0)
    const easing = element.dataset.interactionEasing || 'ease-out'
    const moveX = Number(element.dataset.interactionMoveX || 0)
    const moveY = Number(element.dataset.interactionMoveY || -16)
    const keyframes = {
      move: [{ transform: 'translate(0, 0)' }, { transform: `translate(${moveX}px, ${moveY}px)` }, { transform: 'translate(0, 0)' }],
      scale: [{ transform: 'scale(1)' }, { transform: 'scale(1.05)' }, { transform: 'scale(1)' }],
      rotate: [{ transform: 'rotate(0deg)' }, { transform: 'rotate(3deg)' }, { transform: 'rotate(0deg)' }],
      opacity: [{ opacity: 1 }, { opacity: 0.45 }, { opacity: 1 }],
      blur: [{ filter: 'blur(0)' }, { filter: 'blur(3px)' }, { filter: 'blur(0)' }]
    }[action]
    if (keyframes && element.animate) element.animate(keyframes, { duration, delay, easing })
  }

  const wireInteractions = () => {
    doc.querySelectorAll('[data-interaction-trigger]').forEach((element) => {
      if (element.dataset.runtimeReady) return
      element.dataset.runtimeReady = 'true'
      const trigger = element.dataset.interactionTrigger
      if (trigger === 'page-load') runAnimation(element)
      if (trigger === 'click') element.addEventListener('click', () => runAnimation(element))
      if (trigger === 'hover') element.addEventListener('mouseenter', () => runAnimation(element))
      if (trigger === 'scroll-into-view' && 'IntersectionObserver' in doc.defaultView) {
        const observer = new doc.defaultView.IntersectionObserver((entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              runAnimation(element)
              observer.disconnect()
            }
          })
        }, { threshold: 0.35 })
        observer.observe(element)
      }
    })
  }

  const wireForms = () => {
    doc.querySelectorAll('form[data-studiopro-form]').forEach((form) => {
      if (form.dataset.formRuntimeReady) return
      form.dataset.formRuntimeReady = 'true'
      form.addEventListener('submit', async (event) => {
        event.preventDefault()
        const payload = Object.fromEntries(new doc.defaultView.FormData(form).entries())
        try {
          await fetch(`${store.apiBaseUrl}/projects/${projectId}/form-submissions`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
              page_id: Number(pageId),
              form_name: form.dataset.formName || 'Contact Form',
              payload,
              source_url: doc.location?.href || 'editor-preview'
            })
          })
          form.reset()
        } catch (err) {
          console.warn('StudioPro form preview submission failed', err)
        }
      })
    })
  }

  wireInteractions()
  wireForms()
  editor.on('component:add component:update component:update:attributes', () => {
    setTimeout(() => {
      wireInteractions()
      wireForms()
    }, 50)
  })
}

onMounted(async () => {
  const projectId = route.params.projectId || 1
  const pageId = route.params.pageId || 1

  try {
    await store.fetchProject(projectId)
  } catch (err) {
    console.error('Failed to load project details:', err)
    alert('Project not found or failed to load. Returning to dashboard.')
    router.push('/')
    return
  }

  // Verify that the page belongs to the project
  const projectPages = store.activeProject?.pages || []
  const pageExists = projectPages.some(p => String(p.id) === String(pageId))

  if (!pageExists) {
    if (projectPages.length > 0) {
      const firstPageId = projectPages[0].id
      router.push(`/editor/${projectId}/${firstPageId}`)
    } else {
      alert('No pages found in this project. Returning to dashboard.')
      router.push('/')
    }
    return
  }

  if (window.innerWidth < 1024) {
    store.setLeftSidebarCollapsed(true)
    store.setRightSidebarCollapsed(true)
  }

  // Wait for Vue DOM updates to finalize so custom sidebar target elements exist
  await nextTick()

  const editor = grapesjs.init({
    container: '#gjs-container',
    height: '100%',
    width: '100%',
    storageManager: false,
    panels: { defaults: [] },
    canvas: {
      styles: [
        'https://cdn.tailwindcss.com',
        'https://fonts.googleapis.com/css2?family=Geist:wght@400;500;700;800&display=swap',
        'https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&display=swap',
        'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap',
        '/canvas.css'
      ],
      scripts: [
        'https://cdn.tailwindcss.com'
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
          const res = await fetch(`${store.apiBaseUrl}/projects/${projectId}/collections`);
          if (res.ok) {
            const collections = await res.json();
            const selectedCol = collections.find(c => c.slug === colSlug);
            if (selectedCol) {
              const itemsRes = await fetch(`${store.apiBaseUrl}/collections/${selectedCol.id}/items`);
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
                  
                  this.append(rowHtml);
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

  editor.Components.addType('code-embed', {
    model: {
      defaults: {
        tagName: 'div',
        attributes: {
          class: 'code-embed-container w-full min-h-[150px]',
          'data-code-html': '<div style="padding:24px;background:#0B0E14;color:white">Custom embed</div>',
          'data-code-css': '',
          'data-code-js': ''
        },
        components: buildCodeEmbedFrame(),
      },
      init() {
        this.on('change:attributes', () => {
          this.components(buildCodeEmbedFrame(this.getAttributes()))
        })
      }
    }
  });

  store.setEditor(editor)

  const pendingTemplate = (() => {
    try {
      const raw = localStorage.getItem('studiopro.pendingTemplate')
      if (!raw) return null
      const parsed = JSON.parse(raw)
      if (String(parsed.projectId) !== String(projectId) || String(parsed.pageId) !== String(pageId)) return null
      localStorage.removeItem('studiopro.pendingTemplate')
      return parsed
    } catch (err) {
      localStorage.removeItem('studiopro.pendingTemplate')
      return null
    }
  })()

  if (pendingTemplate) {
    editor.setComponents(pendingTemplate.html || '')
    editor.setStyle(pendingTemplate.css || '')
    store.setActivePage({
      id: Number(pageId),
      project_id: Number(projectId),
      title: pendingTemplate.name || 'Home Page',
      slug: 'home',
      html: pendingTemplate.html || '',
      css: pendingTemplate.css || ''
    })
    store.markDirty()
    attachStudioProRuntime(editor, projectId, pageId)
  } else {
    // Fetch page data from backend
    try {
      const response = await fetch(`${store.apiBaseUrl}/projects/${projectId}/pages/${pageId}?t=${Date.now()}`)
      if (response.ok) {
        const data = await response.json()
        if (Array.isArray(data.components) && data.components.length) {
          editor.setComponents(data.components)
        } else if (data.html) {
          editor.setComponents(data.html)
        }
        if (Array.isArray(data.styles) && data.styles.length) {
          editor.setStyle(data.styles)
        } else if (data.css) {
          editor.setStyle(data.css)
        }
        store.setActivePage(data)
        store.markDirty(false)
      } else {
        loadStarterPage(editor)
      }
    } catch (err) {
      console.error('Failed to fetch page data:', err)
      loadStarterPage(editor)
    }
  }

  editor.on('component:selected', (component) => {
    store.setSelectedComponent(component)
    store.incrementStyleTrigger()
  })
  editor.on('component:deselected', () => {
    store.setSelectedComponent(null)
    store.incrementStyleTrigger()
  })
  editor.on('component:update:style', () => {
    store.incrementStyleTrigger()
    store.markDirty()
  })
  editor.on('component:update', () => {
    store.incrementStyleTrigger()
    store.markDirty()
  })
  editor.on('component:add component:remove canvas:drop', () => {
    store.markDirty()
  })
  editor.on('canvas:frame:load', () => {
    attachStudioProRuntime(editor, projectId, pageId)
  })
  runtimeFallbackTimer.value = setTimeout(() => attachStudioProRuntime(editor, projectId, pageId), 250)
})

onUnmounted(() => {
  if (runtimeFallbackTimer.value) {
    clearTimeout(runtimeFallbackTimer.value)
    runtimeFallbackTimer.value = null
  }
  if (store.editor) {
    store.editor.destroy()
    store.setEditor(null)
  }
})
</script>

<style>
.gjs-cv-canvas {
  background-color: #1F2937 !important;
  background-image: radial-gradient(#334155 1px, transparent 1px) !important;
  background-size: 24px 24px !important;
  top: 0 !important;
  left: 0 !important;
  width: 100% !important;
  height: 100% !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
}
.gjs-frame {
  border: none !important;
  background-color: #ffffff !important;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
  border-radius: 8px !important;
  min-height: 640px !important;
}
.gjs-hovered {
  outline: 1.5px solid #2E62FF !important;
  outline-offset: -2px;
}
.gjs-selected {
  outline: 2px solid #2E62FF !important;
  outline-offset: -2px;
}
.gjs-badge {
  background-color: #2E62FF !important;
  color: #ffffff !important;
  font-family: 'Geist', sans-serif !important;
  font-weight: 700 !important;
  font-size: 10px !important;
  text-transform: uppercase !important;
  padding: 4px 8px !important;
  border-radius: 2px !important;
  border: none !important;
  box-shadow: 0 2px 4px rgba(0,0,0,0.3) !important;
  top: -24px !important;
  left: 0 !important;
}
.gjs-resizer-hdl {
  background-color: #ffffff !important;
  border: 2px solid #2E62FF !important;
  width: 8px !important;
  height: 8px !important;
  border-radius: 0px !important;
}
.gjs-toolbar {
  background-color: #2E62FF !important;
}
</style>
