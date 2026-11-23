<template>
  <footer class="site-footer" :class="{ 'site-footer-compact': compact }">
    <div class="site-footer-inner">
      <StudioBrand v-if="!compact" to="/welcome" />
      <nav aria-label="Site information">
        <RouterLink to="/welcome#faq">FAQ</RouterLink>
        <RouterLink to="/privacy">Privacy policy</RouterLink>
        <RouterLink to="/about">About us</RouterLink>
        <RouterLink to="/terms">Terms &amp; conditions</RouterLink>
      </nav>
      <ThemeToggle v-if="showTheme" />
    </div>
  </footer>
</template>

<script setup>
import StudioBrand from './StudioBrand.vue'
import ThemeToggle from './ThemeToggle.vue'

defineProps({ compact: Boolean, showTheme: Boolean })
</script>
