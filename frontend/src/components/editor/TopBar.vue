<template>
  <header class="workspace-bar">
    <div class="workspace-bar-inner">
      <StudioBrand />
      <nav class="workspace-tabs" aria-label="Workspace">
        <RouterLink v-for="[name, label] in tabs" :key="name" :to="destination(name)"
          :aria-current="route.name === name ? 'page' : undefined">{{ label }}</RouterLink>
      </nav>
      <div class="workspace-tools">
        <select v-if="projects.projects.length" aria-label="Active project" :value="activeId" @change="switchProject">
          <option v-for="project in projects.projects" :key="project.id" :value="project.id">{{ project.name }}</option>
        </select>
        <RouterLink class="studio-button" to="/account">Account</RouterLink>
        <ThemeToggle />
        <button class="workspace-signout" type="button" :disabled="signingOut" @click="signOut">Sign out</button>
      </div>
    </div>
    <p v-if="error" class="workspace-bar-error" role="alert">{{ error }}</p>
  </header>
</template>

<script setup>
import StudioBrand from '../StudioBrand.vue'
import ThemeToggle from '../ThemeToggle.vue'
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useProjectList } from '../../stores/projects'
import { useAuthStore } from '../../stores/auth'

const tabs = [['dashboard', 'Projects'], ['templates', 'Templates'], ['cms', 'CMS'], ['inventory', 'Inventory'], ['settings', 'Settings']]
const route = useRoute()
const router = useRouter()
const auth = useAuthStore()
const projects = useProjectList()
const projectId = computed(() => projects.projects.find(project => String(project.id) === route.params.projectId)?.id || projects.current?.id)
const activeId = computed(() => projectId.value ?? '')
const error = ref('')
const signingOut = ref(false)

function destination(name) {
  return name === 'dashboard' || !projectId.value ? { name } : { name, params: { projectId: projectId.value } }
}

onMounted(async () => {
  await projects.load()
  const matchingProject = projects.projects.find(project => String(project.id) === route.params.projectId)
  if (matchingProject) projects.select(matchingProject.id)
})

function switchProject(event) {
  const id = event.target.value
  projects.select(id)
  if (['templates', 'cms', 'inventory', 'settings'].includes(route.name)) {
    router.push({ name: route.name, params: { projectId: id } })
  }
}

async function signOut() {
  error.value = ''
  signingOut.value = true
  try {
    await auth.logout()
    await router.push('/login')
  } catch {
    error.value = 'Sign-out failed. Try again.'
  } finally {
    signingOut.value = false
  }
}
</script>
