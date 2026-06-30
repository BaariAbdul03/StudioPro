<template>
  <div class="min-h-screen w-full flex items-center justify-center bg-[#0a0b10] text-on-surface font-geist px-4 relative overflow-hidden mesh-bg select-none">
    <!-- Glow effects -->
    <div class="absolute top-1/4 left-1/4 w-[400px] h-[400px] bg-[#8B5CF6]/8 blur-[130px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-primary/10 blur-[130px] rounded-full pointer-events-none"></div>

    <div class="w-full max-w-[440px] z-10">
      <!-- Logo Header -->
      <div class="flex flex-col items-center mb-8">
        <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center shadow-lg shadow-primary/30 mb-4 animate-float">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
          </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">StudioPro</h1>
        <p class="text-xs text-gray-500 mt-1.5 uppercase tracking-widest font-semibold">Visual Design Engine</p>
      </div>

      <!-- Form Card -->
      <div class="glass-card rounded-3xl border border-white/10 p-8 shadow-2xl relative overflow-hidden">
        <div class="mb-6">
          <h2 class="text-lg font-bold text-white mb-1">Create an account</h2>
          <p class="text-xs text-gray-400">Join StudioPro to design high-performance sites.</p>
        </div>

        <form @submit.prevent="handleRegister" class="space-y-4">
          <!-- Error banner -->
          <div v-if="error" class="bg-red-500/10 border border-red-500/30 text-red-400 text-xs px-4 py-3 rounded-xl flex items-start gap-2">
            <span class="material-symbols-outlined text-[16px] shrink-0 mt-0.5">error</span>
            <span>{{ error }}</span>
          </div>

          <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Full Name</label>
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-[16px]">person</span>
              <input 
                v-model="name" 
                type="text" 
                required 
                placeholder="Jane Doe" 
                class="w-full bg-[#07090e] border border-white/8 focus:border-primary rounded-xl pl-10 pr-4 py-2.5 text-white text-xs outline-none transition-all"
              >
            </div>
          </div>

          <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Email address</label>
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-[16px]">mail</span>
              <input 
                v-model="email" 
                type="email" 
                required 
                placeholder="you@example.com" 
                class="w-full bg-[#07090e] border border-white/8 focus:border-primary rounded-xl pl-10 pr-4 py-2.5 text-white text-xs outline-none transition-all"
              >
            </div>
          </div>

          <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Password</label>
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-[16px]">lock</span>
              <input 
                v-model="password" 
                type="password" 
                required 
                placeholder="Minimum 8 characters" 
                class="w-full bg-[#07090e] border border-white/8 focus:border-primary rounded-xl pl-10 pr-4 py-2.5 text-white text-xs outline-none transition-all"
              >
            </div>
          </div>

          <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Confirm Password</label>
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-[16px]">lock</span>
              <input 
                v-model="passwordConfirmation" 
                type="password" 
                required 
                placeholder="Re-enter password" 
                class="w-full bg-[#07090e] border border-white/8 focus:border-primary rounded-xl pl-10 pr-4 py-2.5 text-white text-xs outline-none transition-all"
              >
            </div>
          </div>

          <button 
            type="submit" 
            :disabled="loading" 
            class="w-full mt-2 py-3 bg-primary hover:brightness-110 disabled:opacity-50 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all active:scale-[0.98] shadow-lg shadow-primary/25 flex items-center justify-center gap-2"
          >
            <span v-if="loading" class="material-symbols-outlined text-[14px] animate-spin">refresh</span>
            {{ loading ? 'Creating account...' : 'Create Account' }}
          </button>
        </form>

        <div class="mt-6 pt-6 border-t border-white/5 text-center">
          <p class="text-xs text-gray-500">
            Already have an account? 
            <router-link to="/login" class="text-primary hover:underline font-semibold ml-1">Sign In</router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useWorkspaceStore } from '../stores/workspace'

const router = useRouter()
const authStore = useAuthStore()
const workspaceStore = useWorkspaceStore()

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const loading = ref(false)
const error = ref('')

const handleRegister = async () => {
  if (password.value !== passwordConfirmation.value) {
    error.value = 'Passwords do not match'
    return
  }
  loading.value = true
  error.value = ''
  try {
    await authStore.register(
      name.value,
      email.value,
      password.value,
      passwordConfirmation.value,
      workspaceStore.apiBaseUrl
    )
    router.push('/')
  } catch (e) {
    error.value = e.message || 'Registration failed'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.glass-card {
  background: rgba(255, 255, 255, 0.02);
  backdrop-filter: blur(16px);
}
.mesh-bg {
  background-color: #0a0b10;
  background-image: 
    radial-gradient(at 15% 20%, rgba(46, 98, 255, 0.1) 0px, transparent 50%),
    radial-gradient(at 85% 5%, rgba(139, 92, 246, 0.08) 0px, transparent 50%);
}
@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-5px); }
  100% { transform: translateY(0px); }
}
.animate-float {
  animation: float 4s ease-in-out infinite;
}
</style>
