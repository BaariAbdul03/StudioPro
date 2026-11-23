import { defineStore } from 'pinia'
import { ref } from 'vue'
import { apiFetch } from '../api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)

  async function session() {
    const response = await apiFetch('/api/user')
    if (!response.ok && response.status !== 401) throw new Error('Workspace unavailable')
    user.value = response.ok ? await response.json() : null
    return user.value
  }

  async function send(path, method, body) {
    const response = await apiFetch('/api' + path, {
      method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body)
    })
    if (response.ok) return response.status === 204 ? null : response.json()

    const data = await response.json()
    throw new Error(Object.values(data.errors || {})[0]?.[0] || data.error || data.message || 'Request failed. Try again.')
  }

  const login = async (email, password) => {
    user.value = (await send('/login', 'POST', { email, password })).user
    return user.value
  }

  const register = async (name, email, password, password_confirmation) => {
    user.value = (await send('/register', 'POST', { name, email, password, password_confirmation })).user
    return user.value
  }

  const logout = async () => {
    await send('/logout', 'POST')
    user.value = null
  }

  const updateProfile = async (name, email) => {
    user.value = await send('/user/profile', 'PUT', { name, email })
    return user.value
  }

  const updatePassword = (current_password, password, password_confirmation) =>
    send('/user/password', 'PUT', { current_password, password, password_confirmation })

  return { user, session, login, register, logout, updateProfile, updatePassword }
})
