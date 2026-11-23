import { sanitizeHtml, sanitizeProjectData } from '../pageContent'
import { apiFetch } from '../api'
import { defineStore } from 'pinia'
import { markRaw } from 'vue'

const pendingSaves = new WeakMap()
const defaultPageMeta = {
  seoTitle: '',
  seoDescription: '',
  ogTitle: '',
  ogDescription: '',
  headCode: '',
  bodyCode: ''
}

export const useWorkspaceStore = defineStore('workspace', {
  state: () => ({
    editor: null,
    activeProject: null,
    activePage: null,
    zoomLevel: 100,
    selectedComponent: null,
    isUnsaved: false,
    revision: 0,
    saveState: '',
    styleTrigger: 0,
    activeLeftTab: 'layers',
    activeRightTab: 'style',
    isLeftSidebarCollapsed: false,
    isRightSidebarCollapsed: false,
    isIsolationMode: false,
    isolatedSymbolName: '',
    isCartDrawerOpen: false,
    isAiGeneratorOpen: false,
    pageMeta: { ...defaultPageMeta },
    versions: [],
    formSubmissions: [],
    apiBaseUrl: '/api',
    preferences: {
      canvas: {
        width: '1440px',
        height: 'auto',
        showGrid: true,
        snapToGrid: true,
        showRulers: true,
        highlightSelected: true,
        gridSize: 24,
        zoom: 100
      },
      editor: {
        autoSave: true,
        autoSaveDelay: 5,
        undoDepth: 50,
        realTimePreview: true,
        showLabels: true,
        compactMode: false,
        tooltips: true
      },
      appearance: {
        theme: 'dark',
        accent: '#2E62FF',
        fontSize: '13'
      }
    }
  }),
  actions: {
    loadPreferences() {
      const saved = localStorage.getItem('studiopro.preferences')
      if (saved) {
        try {
          this.preferences = JSON.parse(saved)
        } catch (e) {
          console.error('Failed to load preferences')
        }
      }
    },
    savePreferences(prefs) {
      if (prefs) {
        this.preferences = { ...this.preferences, ...prefs }
      }
      localStorage.setItem('studiopro.preferences', JSON.stringify(this.preferences))
      this.applyPreferences()
    },
    applyPreferences() {
      document.documentElement.style.setProperty('--primary-accent', this.preferences.appearance.accent)
      const isCompact = this.preferences.editor.compactMode
      if (isCompact) {
        document.body.classList.add('compact-mode')
      } else {
        document.body.classList.remove('compact-mode')
      }
    },
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
        ...defaultPageMeta,
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
      if (state) this.revision++
      this.isUnsaved = state
    },
    incrementStyleTrigger() {
      this.styleTrigger++
    },
    draftPayload() {
      return {
        project_data: sanitizeProjectData(this.editor.getProjectData()),
        html: sanitizeHtml(this.editor.getHtml()),
        css: this.editor.getCss() || '',
        meta: this.pageMeta
      }
    },
    loadDraft(editor, page) {
      if (page.project_data) {
        editor.loadProjectData(sanitizeProjectData(page.project_data))
      } else {
        editor.setComponents(sanitizeHtml(page.html))
        editor.setStyle(page.css || '')
      }
    },
    savePage(projectId, pageId) {
      if (!this.editor || !this.activePage) return
      const editor = this.editor
      const save = async () => {
        if (this.editor !== editor) return
        const revision = this.revision
        this.saveState = 'Saving…'
        try {
          const response = await apiFetch(this.apiBaseUrl + '/projects/' + projectId + '/pages/' + pageId, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(this.draftPayload())
          })
          if (!response.ok) throw new Error('Draft could not be saved. Try again.')
          if (this.editor === editor) {
            if (revision === this.revision) this.markDirty(false)
            this.saveState = ''
          }
        } catch (error) {
          if (this.editor === editor) this.saveState = 'Save failed. Retry.'
          throw error
        }
      }
      const previous = pendingSaves.get(this) || Promise.resolve()
      const current = previous.catch(() => {}).then(save)
      pendingSaves.set(this, current)
      return current
    },
    async publishPage(projectId, pageId, publish) {
      if (publish) await this.savePage(projectId, pageId)
      const response = await apiFetch(this.apiBaseUrl + '/projects/' + projectId + '/pages/' + pageId + '/publish', {
        method: publish ? 'POST' : 'DELETE'
      })
      if (!response.ok) {
        const data = await response.json()
        throw new Error(data.message || 'Could not update the public link. Try again.')
      }
      this.activePage = await response.json()
    },
    async createPageVersion(projectId, pageId, versionLabel = 'Manual checkpoint') {
      if (!this.editor) return null
      const payload = {
        ...this.draftPayload(),
        version_label: versionLabel,
        components: this.editor.getComponents().toJSON(),
        styles: this.editor.getStyle(),
        meta: this.pageMeta
      }

      const response = await apiFetch(`${this.apiBaseUrl}/projects/${projectId}/pages/${pageId}/versions`, {
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
      const response = await apiFetch(`${this.apiBaseUrl}/projects/${projectId}/pages/${pageId}/versions`, {
        headers: {
          'Accept': 'application/json'
        }
      })
      if (!response.ok) throw new Error('Failed to load page versions')
      this.versions = await response.json()
      return this.versions
    },
    async restorePageVersion(projectId, pageId, versionId) {
      await pendingSaves.get(this)?.catch(() => {})
      const response = await apiFetch(`${this.apiBaseUrl}/projects/${projectId}/pages/${pageId}/versions/${versionId}/restore`, {
        method: 'POST',
        headers: { 'Accept': 'application/json' }
      })
      if (!response.ok) throw new Error('Failed to restore page version')
      const page = await response.json()
      this.setActivePage(page)
      if (this.editor) this.loadDraft(this.editor, page)
      this.markDirty(false)
      this.saveState = ''
      return page
    },
    async downloadStaticExport(projectId, pageId) {
      const response = await apiFetch(`${this.apiBaseUrl}/projects/${projectId}/pages/${pageId}/export`)
      if (!response.ok) throw new Error('Could not export the draft.')

      const download = document.createElement('a')
      const file = URL.createObjectURL(await response.blob())
      download.href = file
      download.download = response.headers.get('Content-Disposition')?.match(/filename="?([a-z0-9-]+\.zip)"?/i)?.[1] || 'studio-page.zip'
      download.click()
      setTimeout(() => URL.revokeObjectURL(file), 1000)
    },
    async generateAiPage(projectId, payload, signal) {
      const response = await apiFetch(`${this.apiBaseUrl}/projects/${projectId}/ai/generate-page`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(payload),
        signal
      })
      if (!response.ok) throw new Error('AI page generation failed')
      return response.json()
    },
    async generateStitchDraft(projectId, payload, signal) {
      const url = `${this.apiBaseUrl}/projects/${projectId}/ai/stitch`
      const submission = await apiFetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
        signal
      })
      if (submission.status === 429) throw new Error('Too many Stitch requests. Wait a few minutes and try again.')
      if (!submission.ok) throw new Error('Could not start Stitch generation.')

      const { id } = await submission.json()
      for (let attempt = 0; attempt < 42; attempt++) {
        await new Promise(resolve => setTimeout(resolve, 5000))
        signal.throwIfAborted()
        const response = await apiFetch(url + '/' + encodeURIComponent(id), { signal })
        if (!response.ok) throw new Error('Could not check the Stitch draft.')
        const draft = await response.json()
        if (draft.status === 'ready') return draft
        if (draft.status === 'failed') throw new Error('Stitch could not create the screen. Try Gemini or check the Stitch setup.')
      }
      throw new Error('Stitch is still working. Check your Stitch project before retrying.')
    },
    async rewriteAiCopy(projectId, payload) {
      const response = await apiFetch(`${this.apiBaseUrl}/projects/${projectId}/ai/copy`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(payload)
      })
      if (!response.ok) throw new Error('AI copy rewrite failed')
      return response.json()
    },
    async generateAiSeo(projectId, payload) {
      const response = await apiFetch(`${this.apiBaseUrl}/projects/${projectId}/ai/seo`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(payload)
      })
      if (!response.ok) throw new Error('AI SEO generation failed')
      return response.json()
    },
    async fetchFormSubmissions(projectId) {
      const response = await apiFetch(`${this.apiBaseUrl}/projects/${projectId}/form-submissions`, {
        headers: {
          'Accept': 'application/json'
        }
      })
      if (!response.ok) throw new Error('Failed to load form submissions')
      this.formSubmissions = await response.json()
      return this.formSubmissions
    },
    async fetchProject(projectId) {
      const response = await apiFetch(`${this.apiBaseUrl}/projects/${projectId}`, {
        headers: {
          'Accept': 'application/json'
        }
      })
      if (!response.ok) throw Object.assign(new Error('Failed to load project'), { status: response.status })
      this.activeProject = await response.json()
      return this.activeProject
    },
    async updateProjectSettings(projectId, settings) {
      const response = await apiFetch(`${this.apiBaseUrl}/projects/${projectId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ settings })
      })
      if (!response.ok) throw new Error('Failed to update project settings')
      this.activeProject = await response.json()
      return this.activeProject
    }
  }
})
