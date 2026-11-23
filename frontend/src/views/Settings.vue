<template>
  <div class="studio-light studio-workspace">
    <TopBar />
    <main class="workspace-main settings-main">
      <header class="workspace-heading">
        <div><h1>Settings</h1><p>Manage your project and tailor the editing workspace.</p></div>
        <RouterLink class="studio-button" to="/account">Account settings</RouterLink>
      </header>

      <div class="settings-layout">
        <nav class="settings-index" aria-label="Settings sections">
          <a v-if="projectId" href="#project-details"><span aria-hidden="true" class="material-symbols-outlined">folder</span>Project</a>
          <a href="#appearance"><span aria-hidden="true" class="material-symbols-outlined">contrast</span>Appearance</a>
          <a href="#editor-preferences"><span aria-hidden="true" class="material-symbols-outlined">tune</span>Editor</a>
          <a href="#canvas-preferences"><span aria-hidden="true" class="material-symbols-outlined">grid_on</span>Canvas</a>
        </nav>

        <div class="settings-sections">
          <section v-if="projectId" id="project-details" class="workspace-panel settings-section" aria-labelledby="project-details-title">
            <header><h2 id="project-details-title">Project details</h2><p>Name and describe this project.</p></header>
            <p v-if="loading" class="settings-body" role="status">Loading project?</p>
            <p v-if="projectError && !project" class="settings-body" role="alert">{{ projectError }}</p>
            <form v-if="project" class="workspace-form settings-body" @submit.prevent="saveProject">
              <div class="settings-fields">
                <label>Project name<input v-model.trim="project.name" name="name" maxlength="255" required></label>
                <label>Project slug<input v-model.trim="project.slug" name="slug" maxlength="255" required aria-describedby="slug-help">
                  <small id="slug-help">Unique project identifier.</small></label>
              </div>
              <label>Description<textarea v-model.trim="project.description" name="description" rows="3"></textarea></label>
              <div class="settings-save">
                <button class="studio-button primary" :disabled="saving">{{ saving ? 'Saving?' : 'Save project' }}</button>
                <p v-if="projectError" role="alert">{{ projectError }}</p>
                <p v-if="projectNotice" role="status">{{ projectNotice }}</p>
              </div>
            </form>
          </section>

          <section id="appearance" class="workspace-panel settings-section" aria-labelledby="appearance-title">
            <header><h2 id="appearance-title">Appearance</h2><p>Choose how StudioPro looks in this browser.</p></header>
            <div class="settings-body"><ThemeControl cards /></div>
          </section>

          <section id="editor-preferences" class="workspace-panel settings-section" aria-labelledby="editor-preferences-title">
            <header><h2 id="editor-preferences-title">Editor preferences</h2><p>Set how drafts are saved and how much undo history to keep.</p></header>
            <form class="workspace-form settings-body" @submit.prevent="savePreferences('editor', editor)">
              <div class="settings-checks">
                <label class="workspace-check"><input v-model="editor.autoSave" type="checkbox">Autosave drafts</label>
                <label class="workspace-check"><input v-model="editor.compactMode" type="checkbox">Compact interface</label>
              </div>
              <div class="settings-fields">
                <label>Autosave interval (seconds)<input v-model.number="editor.autoSaveDelay" type="number" min="1" max="60" required></label>
                <label>Undo history limit<input v-model.number="editor.undoDepth" type="number" min="10" max="200" required></label>
              </div>
              <div class="settings-save">
                <button class="studio-button" type="submit">Save editor preferences</button>
                <p v-if="preferenceStatus.editor" role="status">{{ preferenceStatus.editor }}</p>
              </div>
            </form>
          </section>

          <section id="canvas-preferences" class="workspace-panel settings-section" aria-labelledby="canvas-preferences-title">
            <header><h2 id="canvas-preferences-title">Canvas preferences</h2><p>Set the grid around the page while you edit.</p></header>
            <form class="workspace-form settings-body" @submit.prevent="savePreferences('canvas', canvas)">
              <label class="workspace-check"><input v-model="canvas.showGrid" type="checkbox">Show grid around the page</label>
              <label class="settings-short-field">Grid size (px)<input v-model.number="canvas.gridSize" type="number" min="4" max="64" required></label>
              <div class="settings-save">
                <button class="studio-button" type="submit">Save canvas preferences</button>
                <p v-if="preferenceStatus.canvas" role="status">{{ preferenceStatus.canvas }}</p>
              </div>
            </form>
          </section>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import TopBar from '../components/editor/TopBar.vue'
import ThemeControl from '../components/ThemeControl.vue'
import { apiFetch } from '../api'
import { useProjectList } from '../stores/projects'
import { useWorkspaceStore } from '../stores/workspace'

const route = useRoute()
const router = useRouter()
const projectId = route.params.projectId
const projects = useProjectList()
const store = useWorkspaceStore()
store.loadPreferences()
const canvas = reactive({ ...store.preferences.canvas })
const editor = reactive({ ...store.preferences.editor })
const preferenceStatus = reactive({ canvas: '', editor: '' })
const project = ref(null)
const loading = ref(Boolean(projectId))
const saving = ref(false)
const projectError = ref('')
const projectNotice = ref('')

onMounted(async () => {
  if (!projectId) return
  try {
    const response = await apiFetch('/api/projects/' + projectId)
    if ([403, 404, 500, 503].includes(response.status)) {
      const query = response.status >= 500 ? { from: route.fullPath } : {}
      await router.replace({ name: 'error', params: { status: response.status }, query })
      return
    }
    if (!response.ok) throw new Error()
    const { name, slug, description } = await response.json()
    project.value = { name, slug, description: description || '' }
  } catch {
    projectError.value = 'Project settings could not be opened.'
  } finally {
    loading.value = false
  }
})

async function saveProject() {
  saving.value = true
  projectError.value = ''
  projectNotice.value = ''
  try {
    const response = await apiFetch('/api/projects/' + projectId, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(project.value)
    })
    if (!response.ok) {
      const data = await response.json()
      throw new Error(Object.values(data.errors || {})[0]?.[0] || 'Could not save project settings. Try again.')
    }
    projectNotice.value = 'Project settings saved.'
    await projects.load()
  } catch (error) {
    projectError.value = error.message
  } finally {
    saving.value = false
  }
}

function savePreferences(section, preferences) {
  store.savePreferences({ [section]: { ...preferences } })
  preferenceStatus[section] = 'Preferences saved in this browser.'
}
</script>
