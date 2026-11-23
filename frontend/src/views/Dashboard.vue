<template>
  <div class="studio-light">
    <header class="studio-bar">
      <StudioBrand />
      <nav class="studio-actions" aria-label="Workspace">
        <RouterLink to="/docs">Guide</RouterLink>
        <RouterLink to="/welcome#faq">FAQ</RouterLink>
      </nav>
      <button class="studio-button" @click="signOut">Sign out</button>
    </header>

    <main class="studio-dashboard">
      <div class="studio-heading">
        <div><h1>Projects</h1><p>Pick up where you left off or start a new page.</p></div>
        <button class="studio-button primary" @click="createDialog.showModal()">New project</button>
      </div>
      <p v-if="notice" role="alert">{{ notice }}</p>
      <p v-if="loading" role="status">Loading projects?</p>
      <div v-else-if="!projects.length && !notice" class="studio-empty">
        <h2>Start with a page</h2><p>Create a project to open the editor and build your first draft.</p>
      </div>

      <section v-if="recent" class="studio-recent" aria-label="Continue editing">
        <div class="studio-recent-info">
          <p class="studio-recent-label">Recently edited</p>
          <h2>{{ recent.name }}</h2>
          <p>{{ recent.pages[0].title }}</p>
          <RouterLink class="studio-button primary" :to="editorLink(recent)">Continue editing</RouterLink>
        </div>
        <div class="studio-thumbnail">
          <iframe title="Recent page preview" sandbox="" tabindex="-1" loading="lazy" :srcdoc="preview" />
        </div>
      </section>

      <ul v-if="projects.length" class="studio-projects" aria-label="All projects">
        <li v-for="project in projects" :key="project.id">
          <div>
            <h2>{{ project.name }}</h2>
            <p>{{ project.pages_count || project.pages?.length || 0 }} {{ (project.pages_count || project.pages?.length) === 1 ? 'page' : 'pages' }}</p>
          </div>
          <nav :aria-label="project.name + ' tools'">
            <RouterLink v-if="project.pages?.length" :to="editorLink(project)">Edit</RouterLink>
            <RouterLink :to="'/templates/' + project.id">Templates</RouterLink>
            <RouterLink :to="'/cms/' + project.id">CMS</RouterLink>
            <RouterLink :to="'/inventory/' + project.id">Inventory</RouterLink>
            <RouterLink :to="'/settings/' + project.id">Settings</RouterLink>
          </nav>
        </li>
      </ul>
    </main>

    <dialog ref="createDialog" class="studio-dialog" aria-labelledby="new-project-title">
      <form @submit.prevent="createProject">
        <h2 id="new-project-title">New project</h2>
        <label>Project name<input v-model.trim="name" required maxlength="255" autofocus /></label>
        <p v-if="createError" role="alert">{{ createError }}</p>
        <div class="studio-actions">
          <button type="button" class="studio-button" @click="createDialog.close()">Cancel</button>
          <button class="studio-button primary" :disabled="creating">Create project</button>
        </div>
      </form>
    </dialog>
  </div>
</template>

<script setup>
import StudioBrand from '../components/StudioBrand.vue'
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { apiFetch } from '../api'
import { previewDocument } from '../pageContent'
import { useAuthStore } from '../stores/auth'

const projects = ref([])
const loading = ref(true)
const notice = ref('')
const name = ref('')
const creating = ref(false)
const createError = ref('')
const createDialog = ref(null)
const router = useRouter()
const auth = useAuthStore()
const recent = computed(() => projects.value.find(project => project.pages?.length))
const preview = computed(() => previewDocument(recent.value?.pages[0]?.html, recent.value?.pages[0]?.css))
const editorLink = project => ({ name: 'editor', params: { projectId: project.id, pageId: project.pages[0].id } })

onMounted(async () => {
  try {
    const response = await apiFetch('/api/projects')
    if (!response.ok) throw new Error()
    projects.value = await response.json()
  } catch {
    notice.value = 'Projects could not be loaded. Refresh to try again.'
  } finally {
    loading.value = false
  }
})

async function createProject() {
  creating.value = true
  createError.value = ''
  try {
    const slug = name.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '').slice(0, 230) || 'project'
    const response = await apiFetch('/api/projects', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ name: name.value, slug: slug + '-' + crypto.randomUUID().slice(0, 8) })
    })
    if (!response.ok) throw new Error()
    const project = await response.json()
    createDialog.value.close()
    await router.push(editorLink(project))
  } catch {
    createError.value = 'Project could not be created. Try again.'
  } finally {
    creating.value = false
  }
}

async function signOut() {
  try {
    await auth.logout()
    await router.push('/login')
  } catch {
    notice.value = 'Sign-out failed. Try again.'
  }
}
</script>
