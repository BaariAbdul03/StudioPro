<template>
  <footer class="site-footer">
    <StudioBrand />
    <nav aria-label="Site information">
      <RouterLink to="/about">About us</RouterLink>
      <RouterLink to="/privacy">Privacy policy</RouterLink>
      <RouterLink to="/terms">Terms &amp; conditions</RouterLink>
    </nav>
  </footer>
</template>

<script setup>
import StudioBrand from './StudioBrand.vue'
</script>
