<template>
  <label class="theme-control">
    Theme
    <select :value="scheme" @change="apply($event.target.value, true)">
      <option value="system">System ({{ system }})</option>
      <option value="light">Light</option>
      <option value="dark">Dark</option>
    </select>
  </label>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue'

const key = 'studiopro.color-scheme'
const root = document.documentElement
const meta = document.querySelector('meta[name="color-scheme"]')
const scheme = ref(root.dataset.studioTheme || 'system')
const media = matchMedia('(prefers-color-scheme: dark)')
const system = ref(media.matches ? 'dark' : 'light')

function updateSystem(event) {
  system.value = event.matches ? 'dark' : 'light'
}

function apply(value, persist = false) {
  const selected = ['light', 'dark'].includes(value) ? value : 'system'
  scheme.value = selected
  if (selected === 'system') delete root.dataset.studioTheme
  else root.dataset.studioTheme = selected
  meta.content = selected === 'system' ? 'light dark' : selected
  if (!persist) return
  try {
    if (selected === 'system') localStorage.removeItem(key)
    else localStorage.setItem(key, selected)
  } catch {
    // The selected theme can still be used in the current document.
  }
}

function sync(event) {
  if (event.key === key || event.key === null) apply(event.newValue)
}

onMounted(() => {
  media.addEventListener('change', updateSystem)
  window.addEventListener('storage', sync)
})
onUnmounted(() => {
  media.removeEventListener('change', updateSystem)
  window.removeEventListener('storage', sync)
})
</script>
