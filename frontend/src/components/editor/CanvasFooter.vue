<template>
  <div class="h-8 bg-surface-container border-t border-outline flex items-center justify-between px-6 text-[10px] font-bold text-on-surface-variant select-none font-geist">
    <!-- Left side breadcrumbs path -->
    <div class="flex items-center gap-1">
      <template v-if="store.isIsolationMode">
        <span class="hover:text-white cursor-pointer transition uppercase">Project</span>
        <span class="material-symbols-outlined text-xs text-on-surface-variant px-0.5">chevron_right</span>
        <span class="hover:text-white cursor-pointer transition uppercase">Pages</span>
        <span class="material-symbols-outlined text-xs text-on-surface-variant px-0.5">chevron_right</span>
        <span class="text-white uppercase">{{ store.isolatedSymbolName }} (Isolated)</span>
      </template>
      <template v-else v-for="(node, index) in breadcrumbs" :key="index">
        <span class="hover:text-white cursor-pointer transition uppercase" @click="selectNode(node)">
          {{ node.getName() || node.get('type') }}
        </span>
        <span v-if="index < breadcrumbs.length - 1" class="material-symbols-outlined text-xs text-on-surface-variant px-0.5">chevron_right</span>
      </template>
      <span v-if="!store.isIsolationMode && breadcrumbs.length === 0" class="uppercase">Body</span>
    </div>

    <!-- Right side metadata labels -->
    <div class="flex items-center gap-6">
      <span class="uppercase tracking-wider">Breadcrumbs</span>
      <span class="text-white">Zoom {{ store.zoomLevel }}%</span>
      <button class="hover:text-white cursor-pointer uppercase transition">Canvas Help</button>
      <span class="text-on-surface-variant font-medium">© 2026 StudioPro Engine</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useWorkspaceStore } from '../../stores/workspace'

const store = useWorkspaceStore()

const breadcrumbs = computed(() => {
  if (!store.selectedComponent) return []
  const path = []
  let curr = store.selectedComponent
  while (curr && curr.get('type') !== 'wrapper') {
    path.unshift(curr)
    curr = curr.parent()
  }
  return path
})

const selectNode = (node) => {
  if (store.editor) {
    store.editor.select(node)
  }
}
</script>
