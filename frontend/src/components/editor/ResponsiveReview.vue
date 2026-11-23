<template>
  <dialog ref="dialog" class="studio-dialog responsive-review" aria-labelledby="review-title" @close="clear">
    <header>
      <div>
        <h2 id="review-title">Responsive review</h2>
        <p>Compare the current canvas at three widths before you publish.</p>
      </div>
      <form method="dialog"><button class="studio-button" autofocus>Close review</button></form>
    </header>
    <ul v-if="snapshot" class="review-checks" aria-label="Draft checks">
      <li :data-issue="missingAlt > 0">{{ missingAlt ? missingAlt + ' image(s) missing alt attributes' : 'All images have alt attributes' }}</li>
      <li :data-issue="headings !== 1">{{ headings === 1 ? 'One main heading' : headings + ' main headings' }}</li>
      <li :data-issue="!hasTitle">{{ hasTitle ? 'SEO title set' : 'SEO title missing' }}</li>
    </ul>
    <div v-if="snapshot" ref="grid" class="review-grid">
      <section v-for="{ label, width } in devices" :key="label">
        <h3>{{ label }} <span>{{ width }} px</span></h3>
        <div class="review-viewport">
          <iframe :title="label + ' draft preview'" sandbox="" :srcdoc="snapshot"
            :width="width" :height="Math.ceil(280 / scaleFor(width))"
            :style="{ transform: 'scale(' + scaleFor(width) + ')' }" />
        </div>
      </section>
    </div>
  </dialog>
</template>

<script setup>
import { nextTick, onBeforeUnmount, ref } from 'vue'
import { previewDocument } from '../../pageContent'

const devices = [{ label: 'Desktop', width: 1280 }, { label: 'Tablet', width: 768 }, { label: 'Mobile', width: 390 }]
const dialog = ref(null)
const grid = ref(null)
const snapshot = ref('')
const columnWidth = ref(300)
const missingAlt = ref(0)
const headings = ref(0)
const hasTitle = ref(false)
let resize

function scaleFor(width) {
  return Math.min(1, columnWidth.value / width)
}

async function open(html, css, title) {
  snapshot.value = previewDocument(html, css)
  const markup = new DOMParser().parseFromString(snapshot.value, 'text/html')
  missingAlt.value = markup.querySelectorAll('img:not([alt])').length
  headings.value = markup.querySelectorAll('h1').length
  hasTitle.value = Boolean(title?.trim())
  dialog.value.showModal()
  await nextTick()
  const viewport = grid.value?.querySelector('.review-viewport')
  if (!viewport) return
  columnWidth.value = Math.max(1, viewport.clientWidth)
  resize = new ResizeObserver(([entry]) => {
    columnWidth.value = Math.max(1, entry.contentRect.width)
  })
  resize.observe(viewport)
}

function clear() {
  resize?.disconnect()
  snapshot.value = ''
}

defineExpose({ open })
onBeforeUnmount(clear)
</script>

<style scoped>
.responsive-review { width: min(1100px, calc(100vw - 1.5rem)); max-height: calc(100dvh - 1.5rem); }
.responsive-review header { display: flex; align-items: start; justify-content: space-between; flex-wrap: wrap; gap: .8rem; }
.responsive-review h2 { margin-bottom: .25rem; }
.responsive-review p { color: #4b5670; }
.review-checks { display: flex; flex-wrap: wrap; gap: .5rem; margin-block: 1.1rem; padding: 0; list-style: none; }
.review-checks li { background: #e3f5ed; color: #143e32; border-radius: 5px; padding: .25rem .5rem; font-size: .85rem; }
.review-checks [data-issue="true"] { background: #fff2d8; color: #603800; }
.review-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .8rem; }
.review-grid section { border: 1px solid #d5dce7; border-radius: 7px; overflow: hidden; }
.review-grid h3 { display: flex; justify-content: space-between; gap: .5rem; padding: .45rem .6rem; background: #f6f8fc; font-size: .85rem; font-weight: 700; }
.review-grid h3 span { color: #4b5670; font-weight: 400; }
.review-viewport { height: 280px; overflow: hidden; }
.review-viewport iframe { display: block; max-width: none; transform-origin: top left; border: 0; }
@media (max-width: 640px) { .review-grid { grid-template-columns: minmax(0, 1fr); } }
</style>
