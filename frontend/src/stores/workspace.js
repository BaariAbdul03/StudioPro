import { defineStore } from 'pinia'
import { markRaw } from 'vue'

export const useWorkspaceStore = defineStore('workspace', {
  state: () => ({
    editor: null,
    activeProject: null,
    activePage: null,
    zoomLevel: 100,
    selectedComponent: null,
    isUnsaved: false,
    styleTrigger: 0,
    activeLeftTab: 'layers',
    activeRightTab: 'style',
    isLeftSidebarCollapsed: false,
    isRightSidebarCollapsed: false,
    isIsolationMode: false,
    isolatedSymbolName: '',
    isCartDrawerOpen: false,
    isAiGeneratorOpen: false,
    pageMeta: {
      seoTitle: 'StudioPro | Professional Design Engine',
      seoDescription: 'Build, publish, and export high-performance visual pages with StudioPro.',
      ogTitle: 'StudioPro Visual Page Builder',
      ogDescription: 'A pro-grade editor for pages, commerce, CMS, interactions, and static exports.',
      headCode: '',
      bodyCode: ''
    },
    versions: [],
    formSubmissions: [],
    apiBaseUrl: import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000/api'
  }),
  actions: {
    toggleCartDrawer(state) {
      this.isCartDrawerOpen = state !== undefined ? state : !this.isCartDrawerOpen
    },
    setAiGeneratorOpen(state) {
      this.isAiGeneratorOpen = state
    },
    setActiveLeftTab(tab) {
      this.activeLeftTab = tab
    },
    setActiveRightTab(tab) {
      this.activeRightTab = tab
    },
    setLeftSidebarCollapsed(state) {
      this.isLeftSidebarCollapsed = state
    },
    setRightSidebarCollapsed(state) {
      this.isRightSidebarCollapsed = state
    },
    setIsolationMode(mode, name = '') {
      this.isIsolationMode = mode
      this.isolatedSymbolName = name
    },
    setEditor(editorInstance) {
      this.editor = editorInstance ? markRaw(editorInstance) : null
    },
    setActivePage(page) {
      this.activePage = page
      this.pageMeta = {
        ...this.pageMeta,
        ...(page?.meta || {})
      }
    },
    setPageMeta(meta) {
      this.pageMeta = {
        ...this.pageMeta,
        ...meta
      }
      this.markDirty()
    },
    setZoom(level) {
      this.zoomLevel = level
    },
    setSelectedComponent(component) {
      this.selectedComponent = component ? markRaw(component) : null;
    },
    markDirty(state = true) {
      this.isUnsaved = state
    },
    incrementStyleTrigger() {
      this.styleTrigger++
    },
    async savePage(projectId, pageId) {
      if (!this.editor) return
      const html = this.editor.getHtml()
      const css = this.editor.getCss()
      const components = this.editor.getComponents().toJSON()
      const styles = this.editor.getStyle()
      const meta = this.pageMeta
      
      try {
        const response = await fetch(`${this.apiBaseUrl}/projects/${projectId}/pages/${pageId}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            html,
            css,
            components,
            styles,
            meta,
            is_published: true
          })
        })
        if (!response.ok) throw new Error('Failed to save page')
        this.isUnsaved = false
        return true
      } catch (err) {
        console.error('Failed to save page:', err)
        throw err
      }
    },
    async createPageVersion(projectId, pageId, versionLabel = 'Manual checkpoint') {
      if (!this.editor) return null
      const payload = {
        version_label: versionLabel,
        html: this.editor.getHtml(),
        css: this.editor.getCss(),
        components: this.editor.getComponents().toJSON(),
        styles: this.editor.getStyle(),
        meta: this.pageMeta
      }

      const response = await fetch(`${this.apiBaseUrl}/projects/${projectId}/pages/${pageId}/versions`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      if (!response.ok) throw new Error('Failed to create page version')
      const version = await response.json()
      this.versions = [version, ...this.versions]
      return version
    },
    async fetchPageVersions(projectId, pageId) {
      const response = await fetch(`${this.apiBaseUrl}/projects/${projectId}/pages/${pageId}/versions`)
      if (!response.ok) throw new Error('Failed to load page versions')
      this.versions = await response.json()
      return this.versions
    },
    async restorePageVersion(projectId, pageId, versionId) {
      const response = await fetch(`${this.apiBaseUrl}/projects/${projectId}/pages/${pageId}/versions/${versionId}/restore`, {
        method: 'POST',
        headers: { 'Accept': 'application/json' }
      })
      if (!response.ok) throw new Error('Failed to restore page version')
      const page = await response.json()
      this.setActivePage(page)
      if (this.editor) {
        if (Array.isArray(page.components) && page.components.length) {
          this.editor.setComponents(page.components)
        } else {
          this.editor.setComponents(page.html || '')
        }
        if (Array.isArray(page.styles) && page.styles.length) {
          this.editor.setStyle(page.styles)
        } else {
          this.editor.setStyle(page.css || '')
        }
      }
      this.markDirty(false)
      return page
    },
    downloadStaticExport(projectId, pageId) {
      window.open(`${this.apiBaseUrl}/projects/${projectId}/pages/${pageId}/export`, '_blank', 'noopener,noreferrer')
    },
    async generateAiPage(projectId, payload) {
      const response = await fetch(`${this.apiBaseUrl}/projects/${projectId}/ai/generate-page`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(payload)
      })
      if (!response.ok) throw new Error('AI page generation failed')
      return response.json()
    },
    async rewriteAiCopy(projectId, payload) {
      const response = await fetch(`${this.apiBaseUrl}/projects/${projectId}/ai/copy`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(payload)
      })
      if (!response.ok) throw new Error('AI copy rewrite failed')
      return response.json()
    },
    async generateAiSeo(projectId, payload) {
      const response = await fetch(`${this.apiBaseUrl}/projects/${projectId}/ai/seo`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(payload)
      })
      if (!response.ok) throw new Error('AI SEO generation failed')
      return response.json()
    },
    async fetchFormSubmissions(projectId) {
      const response = await fetch(`${this.apiBaseUrl}/projects/${projectId}/form-submissions`)
      if (!response.ok) throw new Error('Failed to load form submissions')
      this.formSubmissions = await response.json()
      return this.formSubmissions
    }
  }
})
