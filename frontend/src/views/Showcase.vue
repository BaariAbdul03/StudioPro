<template>
  <div class="showcase">
    <header class="showcase-nav">
      <StudioBrand class="showcase-back" />
      <RouterLink class="showcase-edit" to="/demo">Edit this sample</RouterLink>
    </header>
    <main class="showcase-content">
      <iframe :srcdoc="documentHtml" sandbox title="Fieldwork sample page"></iframe>
    </main>
  </div>
</template>

<script setup>
import StudioBrand from '../components/StudioBrand.vue'
import { onMounted, onUnmounted } from 'vue'
import { demoHtml, demoCss } from '../data/demo'

const title = 'Fieldwork — StudioPro Sample'
onMounted(() => { document.title = title })
onUnmounted(() => {
  if (document.title === title) document.title = 'StudioPro'
})

// The frame contains only the bundled project fixture and runs without scripts.
const documentHtml = `<!doctype html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Fieldwork Architecture Studio</title>
<style>body{margin:0;background:#f3f0e7}${demoCss}</style></head>
<body>${demoHtml}</body></html>`
</script>

<style scoped>
.showcase { background: var(--studio-bg); color: var(--studio-fg); }
.showcase-nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
  min-height: 64px;
  max-width: 1440px;
  margin-inline: auto;
  padding-inline: clamp(16px, 4vw, 48px);
  font-size: 14px;
  font-weight: 650;
}
.showcase-back span { margin-right: 8px; }
.showcase-edit { padding: 10px 14px; border-radius: 8px; background: var(--studio-action); color: white; }
.showcase-nav a:focus-visible { outline: 2px solid var(--studio-link); outline-offset: 4px; }
.showcase-content { padding: 0 clamp(8px, 2vw, 28px) 12px; }
.showcase-content iframe {
  display: block;
  width: min(100%, 1440px);
  height: calc(100svh - 76px);
  min-height: 300px;
  margin-inline: auto;
  border: 1px solid #d6dbe8;
  border-radius: 10px;
  background: #f3f0e7;
}
</style>
