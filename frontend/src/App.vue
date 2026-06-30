<template>
  <div class="min-h-screen bg-editor-bg text-white">
    <router-view></router-view>
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
