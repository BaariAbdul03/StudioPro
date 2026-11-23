<template>
  <div class="studio-light studio-workspace">
    <TopBar />
    <main class="workspace-main">
      <header class="workspace-heading"><div>
        <h1>Account settings</h1>
        <p>Manage your profile and sign-in details.</p>
      </div></header>
      <div class="workspace-form-grid">
        <section class="workspace-panel workspace-form-panel" aria-labelledby="profile-title">
          <h2 id="profile-title">Profile</h2>
          <form class="workspace-form" @submit.prevent="saveProfile">
            <label>Full name<input v-model.trim="profile.name" name="name" autocomplete="name" maxlength="255" required></label>
            <label>Email<input v-model.trim="profile.email" name="email" type="email" autocomplete="username" maxlength="255" required></label>
            <label v-if="emailChanged">
              Current password
              <input v-model="profile.password" name="current_password" type="password" autocomplete="current-password" required>
              <small>Confirm your password to change your sign-in email.</small>
            </label>
            <button class="studio-button primary" :disabled="profileBusy">Save profile</button>
            <p v-if="profileError" role="alert">{{ profileError }}</p>
            <p v-if="profileNotice" role="status">{{ profileNotice }}</p>
          </form>
        </section>
        <section class="workspace-panel workspace-form-panel" aria-labelledby="password-title">
          <h2 id="password-title">Password</h2>
          <form class="workspace-form" @submit.prevent="savePassword">
            <input type="text" hidden name="username" autocomplete="username" :value="auth.user?.email">
            <label>Current password<input v-model="password.current" name="current_password" type="password" autocomplete="current-password" required></label>
            <label>New password
              <input v-model="password.next" name="password" type="password" autocomplete="new-password" minlength="12" maxlength="72" aria-describedby="new-password-help" required>
            </label>
            <small id="new-password-help">Use at least 12 characters.</small>
            <label>Confirm new password<input v-model="password.confirm" name="password_confirmation" type="password" autocomplete="new-password" required></label>
            <button class="studio-button primary" :disabled="passwordBusy">Change password</button>
            <p v-if="passwordError" role="alert">{{ passwordError }}</p>
            <p v-if="passwordNotice" role="status">{{ passwordNotice }}</p>
          </form>
        </section>
      </div>
    </main>
  </div>
</template>

<script setup>
import { computed, reactive, ref } from 'vue'
import TopBar from '../components/editor/TopBar.vue'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const profile = reactive({ name: auth.user?.name ?? '', email: auth.user?.email ?? '', password: '' })
const password = reactive({ current: '', next: '', confirm: '' })
const emailChanged = computed(() => profile.email !== auth.user?.email)
const profileBusy = ref(false)
const profileNotice = ref('')
const profileError = ref('')
const passwordBusy = ref(false)
const passwordNotice = ref('')
const passwordError = ref('')

async function saveProfile() {
  profileNotice.value = ''
  profileError.value = ''
  profileBusy.value = true
  try {
    const user = await auth.updateProfile(profile.name, profile.email, profile.password)
    Object.assign(profile, { name: user.name, email: user.email, password: '' })
    profileNotice.value = 'Profile saved.'
  } catch (error) {
    profileError.value = error.message
  } finally {
    profileBusy.value = false
  }
}

async function savePassword() {
  passwordNotice.value = ''
  passwordError.value = ''
  if (password.next !== password.confirm) {
    passwordError.value = 'New passwords do not match.'
    return
  }
  passwordBusy.value = true
  try {
    await auth.updatePassword(password.current, password.next, password.confirm)
    Object.assign(password, { current: '', next: '', confirm: '' })
    profile.password = ''
    passwordNotice.value = 'Password changed.'
  } catch (error) {
    passwordError.value = error.message
  } finally {
    passwordBusy.value = false
  }
}
</script>
