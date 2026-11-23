<template>
  <div class="studio-light studio-workspace">
    <TopBar />
    <main class="workspace-main">
      <header class="workspace-heading template-heading">
        <div>
          <h1>Templates</h1>
          <p>Preview an editable layout for your site, portfolio, blog, or store.</p>
        </div>
        <input v-model.trim="search" class="workspace-input" type="search" aria-label="Search templates" placeholder="Search templates…">
      </header>
      <div class="template-filters">
        <div class="studio-actions" aria-label="Template categories" role="group">
          <button v-for="option in categories" :key="option" class="studio-button"
            :aria-pressed="category === option" @click="category = option">{{ option }}</button>
        </div>
        <p role="status">{{ filtered.length }} of {{ templatesData.length }} templates</p>
      </div>
      <div class="template-grid">
        <article v-for="template in filtered" :key="template.id" class="workspace-panel template-card">
          <button class="template-cover" type="button" :aria-label="'Open ' + template.name + ' preview'" @click="openPreview(template)">
            <img :src="template.image" alt="" width="1000" height="625" loading="lazy" decoding="async">
          </button>
          <div class="template-card-body">
            <div>
              <p>{{ template.category }}</p>
              <h2>{{ template.name }}</h2>
            </div>
            <div class="studio-actions">
              <button class="studio-button" :aria-label="'Preview ' + template.name" @click="openPreview(template)">Preview</button>
              <button class="studio-button primary" :aria-label="'Use ' + template.name" @click="choose(template)">Use template</button>
            </div>
          </div>
        </article>
      </div>
      <section v-if="!filtered.length" class="workspace-panel workspace-empty">
        <h2>No templates match</h2>
        <button class="studio-button" @click="search = ''; category = categories[0]">Clear filters</button>
      </section>
    </main>

    <dialog ref="previewDialog" class="studio-dialog template-preview" aria-labelledby="template-preview-title" @close="previewing = null">
      <div class="template-preview-content">
        <header>
          <div><h2 id="template-preview-title">{{ previewing?.name }}</h2><p>{{ previewing?.category }}</p></div>
          <div class="studio-actions">
            <button class="studio-button" autofocus @click="previewDialog.close()">Close</button>
            <button class="studio-button primary" @click="chooseFromPreview">Use template</button>
          </div>
        </header>
        <iframe v-if="previewing" title="Template preview" sandbox="" :srcdoc="previewHtml" />
      </div>
    </dialog>
    <dialog ref="confirmDialog" class="studio-dialog" aria-labelledby="confirm-template-title" @cancel="guardCancel">
      <form class="workspace-form" @submit.prevent="applyTemplate">
        <h2 id="confirm-template-title">Use {{ selected?.name }}?</h2>
        <template v-if="projectId">
          <p>This replaces the selected page’s saved draft. Its published version stays live until you republish.</p>
          <p v-if="loadingPages" role="status">Loading pages…</p>
          <label v-else-if="pages.length">Page
            <select v-model="pageId" name="page" required>
              <option v-for="page in pages" :key="page.id" :value="page.id">{{ page.title }}</option>
            </select>
          </label>
        </template>
        <label v-else>New project name<input v-model.trim="projectName" name="project_name" maxlength="255" required></label>
        <p v-if="error" role="alert">{{ error }}</p>
        <div class="studio-actions">
          <button class="studio-button" type="button" autofocus :disabled="busy" @click="confirmDialog.close()">Cancel</button>
          <button class="studio-button primary" :disabled="busy || loadingPages || (projectId && !pageId)">
            {{ projectId ? 'Replace draft' : 'Create project' }}
          </button>
        </div>
      </form>
    </dialog>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { apiFetch } from '../api'
import { templatesData } from '../data/templates'
import { previewDocument, sanitizeHtml } from '../pageContent'
import { useProjectList } from '../stores/projects'
import TopBar from '../components/editor/TopBar.vue'

const route = useRoute()
const router = useRouter()
const projects = useProjectList()
const projectId = route.params.projectId
const categories = ['All templates', ...new Set(templatesData.map(template => template.category))]
const category = ref(categories[0])
const search = ref('')
const filtered = computed(() => templatesData.filter(template =>
  (category.value === categories[0] || template.category === category.value) &&
  [template.name, template.description, template.category].some(value => value.toLowerCase().includes(search.value.toLowerCase()))
))
const previewDialog = ref(null)
const confirmDialog = ref(null)
const previewing = ref(null)
const selected = ref(null)
const previewHtml = computed(() => previewDocument(previewing.value?.html, previewing.value?.css))
const projectName = ref('')
const pageId = ref(null)
const pages = ref([])
const loadingPages = ref(false)
const busy = ref(false)
const error = ref('')

function openPreview(template) {
  previewing.value = template
  previewDialog.value.showModal()
}

function chooseFromPreview() {
  const template = previewing.value
  previewDialog.value.close()
  choose(template)
}

function guardCancel(event) {
  if (busy.value) event.preventDefault()
}

async function choose(template) {
  selected.value = template
  error.value = ''
  projectName.value = template.name
  pageId.value = null
  pages.value = []
  confirmDialog.value.showModal()
  if (!projectId || loadingPages.value) return
  loadingPages.value = true
  try {
    const response = await apiFetch('/api/projects/' + projectId)
    if (!response.ok) throw new Error('The project could not be opened.')
    pages.value = (await response.json()).pages || []
    pageId.value = pages.value.find(page => String(page.id) === route.query.pageId)?.id ?? pages.value[0]?.id
    if (!pageId.value) error.value = 'This project has no pages.'
  } catch (failure) {
    error.value = failure.message
  } finally {
    loadingPages.value = false
  }
}

async function applyTemplate() {
  if (busy.value || !selected.value) return
  busy.value = true
  error.value = ''
  const template = selected.value
  const content = { html: sanitizeHtml(template.html), css: template.css || '' }
  try {
    let target = { projectId, pageId: pageId.value }
    if (projectId) {
      const response = await apiFetch('/api/projects/' + projectId + '/pages/' + pageId.value, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ...content, components: [], styles: [] })
      })
      if (!response.ok) throw new Error('Could not save the draft. Try again.')
    } else {
      const project = await projects.create(projectName.value, { ...content, title: template.name })
      target = { projectId: project.id, pageId: project.pages[0].id }
    }
    confirmDialog.value.close()
    await router.push({ name: 'editor', params: target })
  } catch (failure) {
    error.value = failure.message
  } finally {
    busy.value = false
  }
}
</script>
