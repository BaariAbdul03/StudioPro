<template>
  <div class="fixed inset-0 z-[80] flex items-center justify-center bg-editor-bg/70 backdrop-blur-sm font-geist">
    <div class="w-[min(640px,calc(100vw-32px))] overflow-hidden rounded-xl border border-primary/40 bg-surface-container shadow-2xl shadow-primary/10">
      <header class="flex items-start justify-between border-b border-outline p-5">
        <div class="flex gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/20 text-primary">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
          </div>
          <div>
            <h2 class="text-lg font-bold text-on-surface">Generate with Google Stitch</h2>
            <p class="mt-1 text-xs text-on-surface-variant">Describe page structure, offer, sections, and visual direction.</p>
          </div>
        </div>
        <button @click="close" class="text-on-surface-variant hover:text-white">
          <span class="material-symbols-outlined">close</span>
        </button>
      </header>

      <section class="space-y-5 p-5">
        <div>
          <div class="mb-2 flex items-center justify-between">
            <label class="text-[10px] font-bold uppercase tracking-[0.18em] text-on-surface-variant">Prompt Strategy</label>
            <span class="font-mono text-[10px] text-on-surface-variant">{{ prompt.length }} / 500</span>
          </div>
          <textarea
            v-model="prompt"
            maxlength="500"
            class="h-32 w-full resize-none rounded-lg border border-outline bg-editor-bg p-4 text-sm leading-relaxed text-on-surface outline-none transition focus:border-primary focus:ring-1 focus:ring-[#2E62FF]"
            placeholder="Create a modern landing page for an organic matcha powder shop with a hero, 3 feature grids, and a pricing table."
          />
        </div>

        <div>
          <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.18em] text-on-surface-variant">Style Reference</label>
          <div class="flex flex-wrap gap-2">
            <button
              v-for="option in styleOptions"
              :key="option"
              @click="style = option"
              class="rounded-full border px-4 py-2 text-xs font-bold transition"
              :class="style === option ? 'border-primary bg-primary text-white' : 'border-outline bg-[#222a3d] text-on-surface-variant hover:border-[#8d90a2]'"
            >
              {{ option }}
            </button>
          </div>
        </div>

        <div class="rounded-lg border border-outline/70 bg-[#060e20] p-4">
          <div class="mb-3 flex items-center justify-between">
            <div class="text-xs font-bold text-on-surface">{{ statusLabel }}</div>
            <div class="font-mono text-[10px]" :class="generated ? 'text-[#10B981]' : 'text-primary'">{{ providerLabel }}</div>
          </div>
          <div class="h-1.5 overflow-hidden rounded-full bg-[#434656]/40">
            <div class="h-full rounded-full bg-primary transition-all" :style="{ width: progress + '%' }"></div>
          </div>
          <div class="mt-3 grid gap-2 text-[11px] text-on-surface-variant">
            <div v-for="step in visibleSteps" :key="step" class="flex items-center gap-2">
              <span class="material-symbols-outlined text-[14px] text-primary">check_circle</span>
              {{ step }}
            </div>
          </div>
        </div>

        <div v-if="error" class="rounded border border-[#EF4444]/40 bg-[#EF4444]/10 p-3 text-xs text-[#ffb4ab]">{{ error }}</div>
      </section>

      <footer class="flex items-center justify-between border-t border-outline bg-surface-container-light/50 p-5">
        <button class="flex items-center gap-2 text-xs font-bold text-on-surface-variant hover:text-white" @click="useExample">
          <span class="material-symbols-outlined text-[16px]">help</span>
          Prompting Guide
        </button>
        <div class="flex gap-3">
          <button @click="close" class="rounded px-5 py-2 text-xs font-bold text-on-surface-variant hover:bg-[#222a3d]">Cancel</button>
          <button
            v-if="!generated"
            @click="generate"
            :disabled="isGenerating || prompt.trim().length < 8"
            class="rounded bg-primary px-5 py-2 text-xs font-bold text-white shadow-lg shadow-primary/20 transition hover:brightness-110 disabled:cursor-not-allowed disabled:opacity-50"
          >
            {{ isGenerating ? 'Generating...' : 'Generate' }}
          </button>
          <button
            v-else
            @click="$emit('insert', generated)"
            class="flex items-center gap-2 rounded bg-[#10B981] px-5 py-2 text-xs font-bold text-white transition hover:brightness-110"
          >
            <span class="material-symbols-outlined text-[16px]">add_to_photos</span>
            Insert Page
          </button>
        </div>
      </footer>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onUnmounted } from 'vue'
import { useRoute } from 'vue-router'
import { useWorkspaceStore } from '../../stores/workspace'

const emit = defineEmits(['insert'])
const store = useWorkspaceStore()
const route = useRoute()

const prompt = ref('Create a modern landing page for an organic matcha powder shop with a hero, 3 feature grids, and a pricing table.')
const style = ref('Minimal')
const isGenerating = ref(false)
const generated = ref(null)
const error = ref('')
const progress = ref(0)
const steps = ref([])
const timer = ref(null)

const styleOptions = ['Minimal', 'Brutalist', 'Corporate', 'Neo-Retrospective', 'Glassmorphism', 'Cyberpunk']
const providerLabel = computed(() => generated.value ? `${generated.value.model} / ${generated.value.provider}` : 'Configured Google Stitch')
const statusLabel = computed(() => isGenerating.value ? 'Reasoning through layout structure...' : generated.value ? 'Layout ready for canvas insert.' : 'Ready to generate responsive page sections.')
const visibleSteps = computed(() => steps.value.length ? steps.value : ['Prompt mapped to hero, features, pricing, and SEO metadata.'])

const close = () => store.setAiGeneratorOpen(false)
const useExample = () => {
  prompt.value = 'Create a premium SaaS landing page for a visual editor with a full-bleed hero, feature cards, social proof, pricing, FAQ, and strong SEO copy.'
}

const generate = async () => {
  if (isGenerating.value) return
  isGenerating.value = true
  generated.value = null
  error.value = ''
  progress.value = 12
  steps.value = ['Reasoning through layout structure...']
  window.clearInterval(timer.value)
  timer.value = window.setInterval(() => {
    progress.value = Math.min(progress.value + 9, 86)
  }, 260)

  try {
    const projectId = route.params.projectId || 1
    const result = await store.generateAiPage(projectId, {
      prompt: prompt.value,
      style: style.value
    })
    generated.value = result
    steps.value = result.steps || steps.value
    progress.value = 100
  } catch (err) {
    error.value = 'AI generation failed. Check Laravel server or Stitch credentials.'
    progress.value = 0
  } finally {
    window.clearInterval(timer.value)
    timer.value = null
    isGenerating.value = false
  }
}

onUnmounted(() => {
  if (timer.value) {
    window.clearInterval(timer.value)
    timer.value = null
  }
})
</script>
