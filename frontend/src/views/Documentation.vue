<template>
  <div class="studio-light">
    <TopBar />
    <main class="studio-dashboard studio-guide">
      <div class="studio-heading">
        <div><h1>Workspace guide</h1><p>Build a page and share it from StudioPro.</p></div>
      </div>
      <section>
        <h2>Build a draft</h2>
        <p>Open a project or choose a template. Drag a block onto the canvas, then double-click text to edit it. Use Layers to select and rearrange elements, and use Properties to change their appearance.</p>
      </section>
      <section>
        <h2>Save and preview</h2>
        <p>Drafts autosave after edits. Use Save draft to save immediately. Choose Desktop, Tablet, or Mobile to check the layout. In Version History, create a checkpoint you can return to later.</p>
      </section>
      <section>
        <h2>Publish or export</h2>
        <p>Publish creates a link to the saved draft. Later edits stay private until you republish. Use Copy link to share the release, or Unpublish to take it down. Download a ZIP from Version History to host a standalone copy.</p>
      </section>
    </main>
  </div>
</template>

<script setup>
import TopBar from '../components/editor/TopBar.vue'
</script>
