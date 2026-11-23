<template>
  <div class="studio-app">
    <router-view :key="$route.path" />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useWorkspaceStore } from './stores/workspace'

const store = useWorkspaceStore()

onMounted(() => {
  store.loadPreferences()
  store.applyPreferences()
})
</script>
