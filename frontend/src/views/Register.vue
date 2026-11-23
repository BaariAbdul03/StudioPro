<template>
  <main class="studio-light studio-signin">
    <div class="studio-signin-card">
      <StudioBrand />
      <h1>Create an account</h1>
      <form @submit.prevent="submit">
        <label>Full name <input v-model.trim="name" autocomplete="name" maxlength="255" required /></label>
        <label>Email <input v-model.trim="email" type="email" autocomplete="email" maxlength="255" required /></label>
        <label>Password
          <input v-model="password" type="password" autocomplete="new-password" minlength="8" maxlength="72" aria-describedby="password-help" required />
        </label>
        <p id="password-help" class="text-sm">Use at least 8 characters.</p>
        <label>Confirm password
          <input v-model="confirmation" type="password" autocomplete="new-password" required />
        </label>
        <p v-if="error" role="alert">{{ error }}</p>
        <button class="studio-button primary" :disabled="busy">{{ busy ? 'Creating account…' : 'Create account' }}</button>
      </form>
      <RouterLink to="/login">Sign in</RouterLink>
      <nav class="studio-auth-links" aria-label="Site information">
        <RouterLink to="/privacy">Privacy policy</RouterLink>
        <RouterLink to="/terms">Terms &amp; conditions</RouterLink>
      </nav>
    </div>
  </main>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import StudioBrand from '../components/StudioBrand.vue'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const auth = useAuthStore()
const name = ref('')
const email = ref('')
const password = ref('')
const confirmation = ref('')
const busy = ref(false)
const error = ref('')

async function submit() {
  error.value = ''
  if (password.value !== confirmation.value) {
    error.value = 'Passwords do not match.'
    return
  }
  busy.value = true
  try {
    await auth.register(name.value, email.value, password.value, confirmation.value)
    await router.push('/')
  } catch (failure) {
    error.value = failure.message || 'Account could not be created. Try again.'
  } finally {
    busy.value = false
  }
}
</script>
