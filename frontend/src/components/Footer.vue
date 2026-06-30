<template>
  <footer class="w-full bg-editor-bg border-t border-white/5 text-gray-500 py-6 px-8 mt-auto font-geist">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-[20px]">code_blocks</span>
        <span class="font-bold text-white tracking-tight">StudioPro</span>
        <span class="text-[11px] font-mono bg-white/5 px-2 py-0.5 rounded text-on-surface-variant">v2.0.0</span>
      </div>
      
      <div class="flex items-center gap-6 text-[11px] font-bold uppercase tracking-wider">
        <router-link to="/docs" target="_blank" class="hover:text-white transition-colors flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[14px]">menu_book</span> Documentation
        </router-link>
        <button @click="showFeedback = true" class="hover:text-white transition-colors flex items-center gap-1.5 uppercase font-bold tracking-wider">
          <span class="material-symbols-outlined text-[14px]">chat_bubble</span> Feedback
        </button>
        <div class="flex items-center gap-2 px-3 py-1 bg-emerald-500/10 rounded-full border border-emerald-500/20">
          <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
          <span class="text-emerald-400 normal-case tracking-normal">System Operational</span>
        </div>
      </div>
    </div>

    <!-- Feedback Modal -->
    <div v-if="showFeedback" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
      <div class="bg-surface-container p-8 rounded-2xl w-full max-w-md shadow-2xl border border-outline">
        <h3 class="text-lg font-bold text-white mb-2">Submit Feedback</h3>
        <p class="text-sm text-on-surface-variant mb-6">Let us know how we can improve StudioPro.</p>
        <textarea v-model="feedbackText" class="w-full bg-editor-bg border border-outline rounded-xl p-4 text-white text-sm mb-6 h-32 focus:border-primary outline-none transition-colors resize-none" placeholder="What's on your mind?"></textarea>
        <div class="flex justify-end gap-3">
          <button @click="showFeedback = false" class="px-4 py-2 text-sm text-on-surface-variant hover:text-white transition-colors font-medium">Cancel</button>
          <button @click="submitFeedback" class="px-6 py-2 bg-primary hover:brightness-110 text-white rounded-xl text-sm font-bold transition-all active:scale-95" :disabled="!feedbackText.trim()">Submit</button>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref } from 'vue'

const showFeedback = ref(false)
const feedbackText = ref('')

const submitFeedback = () => {
  if (!feedbackText.value.trim()) return
  // Mock submission
  showFeedback.value = false
  feedbackText.value = ''
  // Dispatch a global event or use store to show a toast, or simple alert for now
  alert('Feedback submitted successfully!')
}
</script>
