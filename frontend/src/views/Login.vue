<template>
  <main class="studio-light studio-signin">
    <div class="studio-signin-card">
      <StudioBrand />
      <h1>Sign in to your workspace</h1>
      <form @submit.prevent="submit">
        <label>Email <input v-model.trim="email" name="email" type="email" autocomplete="username" required /></label>
        <label>Password <input v-model="password" name="password" type="password" autocomplete="current-password" required /></label>
        <p v-if="error" role="alert">{{ error }}</p>
        <button class="studio-button primary" :disabled="busy">{{ busy ? 'Signing in…' : 'Sign in' }}</button>
      </form>
      <RouterLink class="studio-signin-link" to="/register">Create an account</RouterLink>
    </div>
  </main>
</template>

<script setup>
import StudioBrand from '../components/StudioBrand.vue'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const auth = useAuthStore()
const email = ref('')
const password = ref('')
const busy = ref(false)
const error = ref('')

async function submit() {
  busy.value = true
  error.value = ''
  try {
    await auth.login(email.value, password.value)
    await router.push('/')
  } catch (failure) {
    error.value = failure.message === 'Failed to fetch' ? 'Workspace is unavailable. Try again.' : failure.message
  } finally {
    busy.value = false
  }
}
</script>
