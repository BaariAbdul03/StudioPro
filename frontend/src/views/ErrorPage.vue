<template>
  <main class="studio-light studio-signin">
    <div class="studio-signin-card studio-error">
      <StudioBrand />
      <p class="studio-error-code">{{ status }}</p>
      <h1>{{ problem.title }}</h1>
      <p>{{ problem.text }}</p>
      <div class="studio-actions">
        <RouterLink class="studio-button primary" to="/">Go home</RouterLink>
        <button v-if="status === '503' && returnPath" class="studio-button" @click="router.replace(returnPath)">Try again</button>
        <RouterLink v-else class="studio-button" to="/demo">Open demo</RouterLink>
      </div>
    </div>
  </main>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import StudioBrand from '../components/StudioBrand.vue'

const props = defineProps({ status: { type: String, required: true } })
const route = useRoute()
const router = useRouter()
const problems = {
  403: { title: 'Access denied', text: 'This page belongs to a workspace you do not have access to.' },
  404: { title: 'Page not found', text: 'The address may have changed, or the page may have been removed.' },
  500: { title: 'Something went wrong', text: 'StudioPro could not complete this request. Try opening the page again.' },
  503: { title: 'Workspace unavailable', text: 'The workspace may be starting up. Give it a moment and try again.' }
}
const problem = computed(() => problems[props.status] || problems[404])
const returnPath = computed(() => {
  const path = route.query.from
  return typeof path === 'string' && /^\/(?![\/\\])/.test(path) ? path : path === '/' ? path : ''
})
</script>
