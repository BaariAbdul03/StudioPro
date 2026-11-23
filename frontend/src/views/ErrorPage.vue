<template>
  <main class="studio-light error-main">
    <section class="error-frame" aria-labelledby="error-title">
      <div class="error-frame-code" aria-hidden="true">{{ status }}</div>
      <div class="error-frame-copy">
        <StudioBrand />
        <h1 id="error-title">{{ problem.title }}</h1>
        <p>{{ problem.text }}</p>
        <div class="studio-actions">
          <button v-if="canRetry" class="studio-button primary" type="button" @click="router.replace(returnPath)">Try again</button>
          <RouterLink class="studio-button" :class="{ primary: !canRetry }" to="/">Go home</RouterLink>
          <RouterLink v-if="!canRetry" class="studio-button" to="/demo">Open demo</RouterLink>
        </div>
      </div>
    </section>
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
  403: { title: 'Access denied', text: 'Your account does not have permission to open this page.' },
  404: { title: 'Page not found', text: 'The address may have changed, or the page may have been removed.' },
  500: { title: 'Something went wrong', text: 'StudioPro could not complete the request. Try opening the page again.' },
  503: { title: 'Workspace unavailable', text: 'The workspace may be starting up. Give it a moment and try again.' }
}
const problem = computed(() => problems[props.status] || problems[404])
const returnPath = computed(() => {
  const path = route.query.from
  return typeof path === 'string' && /^\/(?!\/)/.test(path) && !/[\\\x00-\x20]/.test(path) && !path.startsWith('/error/')
    ? path
    : ''
})
const canRetry = computed(() => Number(props.status) >= 500 && Boolean(returnPath.value))
</script>
