<template>
  <header class="studio-bar workspace-navigation">
    <StudioBrand />
    <nav aria-label="Workspace">
      <RouterLink to="/">Projects</RouterLink>
      <template v-if="projectId">
        <RouterLink :to="'/templates/' + projectId">Templates</RouterLink>
        <RouterLink :to="'/cms/' + projectId">CMS</RouterLink>
        <RouterLink :to="'/inventory/' + projectId">Inventory</RouterLink>
        <RouterLink :to="'/settings/' + projectId">Settings</RouterLink>
      </template>
      <RouterLink to="/docs">Guide</RouterLink>
    </nav>
  </header>
</template>

<script setup>
import StudioBrand from '../StudioBrand.vue'
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const projectId = computed(() => route.params.projectId)
</script>
