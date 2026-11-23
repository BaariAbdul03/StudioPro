<template>
  <div class="studio-light studio-workspace">
    <TopBar />
    <main class="workspace-main">
      <header class="workspace-heading">
        <div><h1>Settings</h1><p>Configure project details and editor preferences.</p></div>
        <RouterLink class="studio-button" to="/account">Account settings</RouterLink>
      </header>

      <section v-if="projectId" class="workspace-panel workspace-form-panel workspace-project-settings" aria-labelledby="project-details-title">
        <h2 id="project-details-title">Project details</h2>
        <p v-if="loading" role="status">Loading project…</p>
        <p v-if="projectError" role="alert">{{ projectError }}</p>
        <form v-if="project" class="workspace-form" @submit.prevent="saveProject">
          <label>Project name<input v-model.trim="project.name" name="name" maxlength="255" required></label>
          <label>Project slug<input v-model.trim="project.slug" name="slug" maxlength="255" required aria-describedby="slug-help">
            <small id="slug-help">A unique identifier for this project.</small></label>
          <label>Description<textarea v-model.trim="project.description" name="description" rows="3"></textarea></label>
          <button class="studio-button primary" :disabled="saving">Save project</button>
          <p v-if="projectNotice" role="status">{{ projectNotice }}</p>
        </form>
      </section>

      <div class="workspace-form-grid">
        <section class="workspace-panel workspace-form-panel" aria-labelledby="editor-preferences-title">
          <h2 id="editor-preferences-title">Editor preferences</h2>
          <p>Saved in this browser.</p>
          <form class="workspace-form" @submit.prevent="savePreferences('editor', editor)">
            <label v-for="[key, label] in editorChecks" :key="key" class="workspace-check">
              <input v-model="editor[key]" type="checkbox">{{ label }}
            </label>
            <label>Autosave interval (seconds)<input v-model.number="editor.autoSaveDelay" type="number" min="1" max="60" required></label>
            <label>Undo history limit<input v-model.number="editor.undoDepth" type="number" min="10" max="200" required></label>
            <button class="studio-button" type="submit">Save editor preferences</button>
            <p v-if="preferenceStatus.editor" role="status">{{ preferenceStatus.editor }}</p>
          </form>
        </section>

        <section class="workspace-panel workspace-form-panel" aria-labelledby="canvas-preferences-title">
          <h2 id="canvas-preferences-title">Canvas preferences</h2>
          <p>Saved in this browser.</p>
          <form class="workspace-form" @submit.prevent="savePreferences('canvas', canvas)">
            <label v-for="[key, label] in canvasChecks" :key="key" class="workspace-check">
              <input v-model="canvas[key]" type="checkbox">{{ label }}
            </label>
            <label>Canvas width<input v-model.trim="canvas.width" placeholder="1440px" required></label>
            <label>Canvas height<input v-model.trim="canvas.height" placeholder="auto" required></label>
            <label>Grid size (px)<input v-model.number="canvas.gridSize" type="number" min="4" max="64" required></label>
            <label>Default zoom (%)<input v-model.number="canvas.zoom" type="number" min="25" max="200" required></label>
            <button class="studio-button" type="submit">Save canvas preferences</button>
            <p v-if="preferenceStatus.canvas" role="status">{{ preferenceStatus.canvas }}</p>
          </form>
        </section>
      </div>
    </main>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { useRoute } from 'vue-router'
import TopBar from '../components/editor/TopBar.vue'
import { apiFetch } from '../api'
import { useProjectList } from '../stores/projects'
import { useWorkspaceStore } from '../stores/workspace'

const projectId = useRoute().params.projectId
const projects = useProjectList()
const store = useWorkspaceStore()
store.loadPreferences()
const canvas = reactive({ ...store.preferences.canvas })
const editor = reactive({ ...store.preferences.editor })
const preferenceStatus = reactive({ canvas: '', editor: '' })
const canvasChecks = [['showGrid', 'Show grid'], ['snapToGrid', 'Snap to grid'], ['showRulers', 'Show rulers'], ['highlightSelected', 'Highlight selected components']]
const editorChecks = [['autoSave', 'Autosave drafts'], ['realTimePreview', 'Live preview'], ['showLabels', 'Show component labels'], ['compactMode', 'Compact interface'], ['tooltips', 'Show tooltips']]
const project = ref(null)
const loading = ref(Boolean(projectId))
const saving = ref(false)
const projectError = ref('')
const projectNotice = ref('')

onMounted(async () => {
  if (!projectId) return
  try {
    const response = await apiFetch('/api/projects/' + projectId)
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
