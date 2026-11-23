<template>
  <RouterLink class="studio-brand" :to="to">
    <img src="/favicon.svg" width="30" height="30" alt="" />
    <span>StudioPro</span>
  </RouterLink>
</template>

<script setup>
defineProps({ to: { type: String, default: '/' } })
</script>
