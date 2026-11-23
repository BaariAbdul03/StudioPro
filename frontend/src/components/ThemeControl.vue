<template>
  <fieldset v-if="cards" class="settings-theme">
    <legend class="sr-only">Theme</legend>
    <label v-for="option in ['system', 'light', 'dark']" :key="option" class="settings-theme-option">
      <input type="radio" name="theme" :value="option" :checked="themePreference === option" @change="setTheme(option)">
      <span class="settings-theme-sample" :data-sample="option === 'system' ? systemTheme : option" aria-hidden="true">
        <span></span><span></span>
      </span>
      <strong>{{ option }}</strong>
      <small v-if="option === 'system'">Follows device</small>
    </label>
  </fieldset>
  <label v-else class="theme-control">
    Theme
    <select :value="themePreference" @change="setTheme($event.target.value)">
      <option value="system">System ({{ systemTheme }})</option>
      <option value="light">Light</option>
      <option value="dark">Dark</option>
    </select>
  </label>
</template>

<script setup>
import { setTheme, systemTheme, themePreference } from '../theme'

defineProps({ cards: Boolean })
</script>
