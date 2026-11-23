<template>
  <dialog ref="dialog" class="studio-dialog studio-generator" aria-labelledby="generator-title" @close="$emit('close')">
    <h2 id="generator-title">Generate a page</h2>
    <p>Describe the page you want to build. Inserting a result replaces the current canvas.</p>
    <form @submit.prevent="generate">
      <label v-if="stitchEnabled">Provider
        <select v-model="provider" :disabled="busy">
          <option value="gemini">Gemini</option>
          <option value="stitch">Google Stitch</option>
        </select>
      </label>
      <label>Page prompt
        <textarea v-model.trim="prompt" rows="4" minlength="8" maxlength="500" required autofocus :disabled="busy"
          placeholder="Create a landing page for a neighborhood bakery." />
      </label>
      <label>Visual style
        <select v-model="style" :disabled="busy">
          <option v-for="option in styles" :key="option">{{ option }}</option>
        </select>
      </label>
      <p v-if="error" role="alert">{{ error }}</p>
      <p v-if="generated?.html" role="status">{{ source }} draft ready.</p>
      <p v-else-if="generated?.stitch_url" role="status">Screen created. Open Stitch to view it. The HTML download was unavailable.</p>
      <p v-if="generated?.stitch_url">
        <a :href="generated.stitch_url" target="_blank" rel="noopener noreferrer">Open Stitch project</a>
      </p>
      <div class="studio-actions">
        <button type="button" class="studio-button" @click="dialog.close()">Cancel</button>
        <button class="studio-button" :class="{ primary: !generated }" :disabled="busy || prompt.length < 8">
          {{ busy ? 'Generating…' : generated ? 'Generate again' : 'Generate draft' }}
        </button>
        <button v-if="generated?.html" type="button" class="studio-button primary" @click="$emit('insert', generated)">
          Replace canvas
        </button>
      </div>
    </form>
  </dialog>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue'
import { useWorkspaceStore } from '../../stores/workspace'

const props = defineProps({ projectId: { type: String, required: true }, stitchEnabled: Boolean })
defineEmits(['close', 'insert'])

const store = useWorkspaceStore()
const dialog = ref(null)
const prompt = ref('')
const style = ref('Minimal')
const provider = ref('gemini')
const busy = ref(false)
const generated = ref(null)
const error = ref('')
const styles = ['Minimal', 'Brutalist', 'Corporate', 'Neo-Retrospective', 'Glassmorphism', 'Cyberpunk']
const source = computed(() => ({
  gemini: 'Gemini',
  stitch: 'Stitch',
  'local-fallback': 'Built-in'
})[generated.value?.provider] || 'Generated')
let request

onMounted(() => dialog.value.showModal())
onBeforeUnmount(() => request?.abort())
watch([prompt, style, provider], () => {
  generated.value = null
  error.value = ''
})

async function generate() {
  if (busy.value) return
  busy.value = true
  generated.value = null
  error.value = ''
  const input = { prompt: prompt.value, style: style.value }
  request = new AbortController()
  try {
    generated.value = provider.value === 'stitch'
      ? await store.generateStitchDraft(props.projectId, input, request.signal)
      : await store.generateAiPage(props.projectId, input, request.signal)
  } catch (failure) {
    if (!request.signal.aborted) error.value = failure.message || 'Could not generate a draft. Try again.'
  } finally {
    busy.value = false
  }
}
</script>
