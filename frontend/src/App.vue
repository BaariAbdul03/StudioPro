<template>
  <div class="studio-app" :class="{ 'studio-app-editor': inEditor }">
    <router-view :key="$route.path" />
    <SiteFooter :compact="inEditor" :show-theme="!inEditor && !$route.meta.requiresAuth" />
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import SiteFooter from './components/SiteFooter.vue'
import { useWorkspaceStore } from './stores/workspace'

const store = useWorkspaceStore()
const route = useRoute()
const inEditor = computed(() => route.name === 'editor' || route.name === 'demo')

onMounted(() => {
  store.loadPreferences()
  store.applyPreferences()
})
</script>
