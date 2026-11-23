<template>
  <div class="studio-light studio-workspace">
    <TopBar />
    <main class="workspace-main">
      <header class="workspace-heading">
        <div><h1>Projects</h1><p>Open a draft, start with a template, or build a page from scratch.</p></div>
        <div class="studio-actions">
          <RouterLink class="studio-button" :to="templateLink">Browse templates</RouterLink>
          <button class="studio-button primary" @click="createDialog.showModal()">New project</button>
        </div>
      </header>

      <p v-if="projects.error" role="alert">
        {{ projects.error }} <button class="studio-button" @click="projects.load()">Retry</button>
      </p>
      <p v-else-if="projects.loading && !current" role="status">Loading projects…</p>

      <template v-if="current">
        <section class="workspace-feature workspace-panel" aria-labelledby="active-project-title">
          <div class="workspace-feature-copy">
            <p class="workspace-kicker">Active project</p>
            <h2 id="active-project-title">{{ current.name }}</h2>
            <p v-if="current.description">{{ current.description }}</p>
            <dl class="workspace-project-counts">
              <div><dt>Pages</dt><dd>{{ current.pages_count ?? current.pages?.length ?? 0 }}</dd></div>
              <div><dt>Collections</dt><dd>{{ current.collections_count ?? 0 }}</dd></div>
              <div><dt>Products</dt><dd>{{ current.products_count ?? 0 }}</dd></div>
            </dl>
            <RouterLink v-if="page" class="studio-button primary" :to="editorLink(current)">Continue editing</RouterLink>
            <RouterLink v-else class="studio-button" :to="templateLink">Browse templates</RouterLink>
          </div>
          <div v-if="page" class="workspace-preview">
            <div class="workspace-preview-window">
              <div class="workspace-preview-bar">
                <span>{{ page.title }}</span>
                <span>{{ page.is_published ? 'Published' : 'Draft' }}</span>
              </div>
              <div class="workspace-preview-frame">
                <iframe title="Active project page preview" sandbox="" tabindex="-1" inert :srcdoc="preview" />
              </div>
            </div>
          </div>
        </section>

        <section class="workspace-project-list" aria-labelledby="all-projects-title">
          <div class="workspace-section-heading">
            <h2 id="all-projects-title">All projects</h2>
            <RouterLink to="/docs">Workspace guide</RouterLink>
          </div>
          <ul class="workspace-panel">
            <li v-for="project in projects.projects" :key="project.id">
              <div>
                <strong>{{ project.name }}</strong>
                <span>{{ project.pages_count ?? project.pages?.length ?? 0 }} {{ (project.pages_count ?? project.pages?.length) === 1 ? 'page' : 'pages' }}</span>
              </div>
              <RouterLink v-if="project.pages?.length" class="studio-button" :aria-label="'Open editor for ' + project.name" :to="editorLink(project)">Open editor</RouterLink>
              <button v-else class="studio-button" @click="projects.select(project.id)">Select project</button>
            </li>
          </ul>
        </section>
      </template>

      <section v-else-if="!projects.loading && !projects.error" class="workspace-feature workspace-panel">
        <div class="workspace-feature-copy">
          <h2>Build your first page</h2>
          <p>Start with a blank canvas or use an editable layout from the template library.</p>
          <div class="studio-actions">
            <RouterLink class="studio-button primary" to="/templates">Choose a template</RouterLink>
            <RouterLink class="studio-button" to="/demo">Try the editor</RouterLink>
          </div>
        </div>
        <RouterLink class="workspace-preview workspace-sample" to="/showcase" aria-label="View the Fieldwork sample page">
          <span class="workspace-preview-window">
            <span class="workspace-preview-bar">Example project · Fieldwork</span>
            <img src="/images/arch_hero_01.png" alt="" width="1024" height="1024">
          </span>
        </RouterLink>
      </section>
    </main>

    <dialog ref="createDialog" class="studio-dialog" aria-labelledby="new-project-title">
      <form @submit.prevent="createProject">
        <h2 id="new-project-title">New project</h2>
        <label>Project name<input v-model.trim="name" name="name" required maxlength="255" autofocus /></label>
        <p v-if="createError" role="alert">{{ createError }}</p>
        <div class="studio-actions">
          <button type="button" class="studio-button" :disabled="creating" @click="createDialog.close()">Cancel</button>
          <button class="studio-button primary" :disabled="creating">Create project</button>
        </div>
      </form>
    </dialog>
  </div>
</template>

<script setup>
import TopBar from '../components/editor/TopBar.vue'
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { previewDocument } from '../pageContent'
import { useProjectList } from '../stores/projects'

const projects = useProjectList()
const route = useRoute()
const router = useRouter()
const name = ref('')
const creating = ref(false)
const createError = ref('')
const createDialog = ref(null)
const current = computed(() => projects.current)
const page = computed(() => current.value?.pages?.[0])
const preview = computed(() => previewDocument(page.value?.html, page.value?.css))
const templateLink = computed(() => current.value ? '/templates/' + current.value.id : '/templates')
const editorLink = project => ({ name: 'editor', params: { projectId: project.id, pageId: project.pages[0].id } })

onMounted(() => {
  projects.load()
  if (route.query.new === '1') {
    createDialog.value.showModal()
    router.replace({ name: 'dashboard' })
  }
})

async function createProject() {
  creating.value = true
  createError.value = ''
  try {
    const project = await projects.create(name.value)
    createDialog.value.close()
    await router.push(editorLink(project))
  } catch (error) {
    createError.value = error.message
  } finally {
    creating.value = false
  }
}
</script>
