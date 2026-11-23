<template>
  <button class="studio-button theme-toggle" type="button" aria-label="Dark mode"
    :aria-pressed="isDark" :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'" @click="toggleTheme">
    <span class="material-symbols-outlined" aria-hidden="true">{{ isDark ? 'light_mode' : 'dark_mode' }}</span>
  </button>
</template>

<script setup>
import { isDark, toggleTheme } from '../theme'
</script>
