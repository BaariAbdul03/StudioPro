import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(JSON.parse(localStorage.getItem('studiopro_user')) || null)
  const token = ref(localStorage.getItem('studiopro_token') || null)

  const isAuthenticated = () => !!token.value

  const saveAuth = (userData, userToken) => {
    user.value = userData
    token.value = userToken
    localStorage.setItem('studiopro_user', JSON.stringify(userData))
    localStorage.setItem('studiopro_token', userToken)
  }

  const clearAuth = () => {
    user.value = null
    token.value = null
    localStorage.removeItem('studiopro_user')
    localStorage.removeItem('studiopro_token')
  }

  const login = async (email, password, apiBaseUrl) => {
    const response = await window.originalFetch(`${apiBaseUrl}/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ email, password })
    })

    if (!response.ok) {
      const errData = await response.json()
      // Extract first field-level error from Laravel validation response
      const firstError = errData.errors ? Object.values(errData.errors)[0]?.[0] : null
      throw new Error(firstError || errData.message || 'Login failed')
    }

    const data = await response.json()
    saveAuth(data.user, data.token)
    return data.user
  }

  const register = async (name, email, password, passwordConfirmation, apiBaseUrl) => {
    const response = await window.originalFetch(`${apiBaseUrl}/register`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        name,
        email,
        password,
        password_confirmation: passwordConfirmation
      })
    })

    if (!response.ok) {
      const errData = await response.json()
      // Extract first field-level error from Laravel validation response
      const firstError = errData.errors ? Object.values(errData.errors)[0]?.[0] : null
      throw new Error(firstError || errData.message || 'Registration failed')
    }

    const data = await response.json()
    saveAuth(data.user, data.token)
    return data.user
  }

  const logout = async (apiBaseUrl) => {
    try {
      await fetch(`${apiBaseUrl}/logout`, {
        method: 'POST',
        headers: {
          'Accept': 'application/json'
        }
      })
    } catch (e) {
      console.warn('Backend logout failed', e)
    } finally {
      clearAuth()
    }
  }

  const updateProfile = async (name, email, apiBaseUrl) => {
    const response = await fetch(`${apiBaseUrl}/user/profile`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ name, email })
    })

    if (!response.ok) {
      const errData = await response.json()
      throw new Error(errData.message || 'Profile update failed')
    }

    const updatedUser = await response.json()
    user.value = updatedUser
    localStorage.setItem('studiopro_user', JSON.stringify(updatedUser))
    return updatedUser
  }

  const updatePassword = async (currentPassword, password, passwordConfirmation, apiBaseUrl) => {
    const response = await fetch(`${apiBaseUrl}/user/password`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        current_password: currentPassword,
        password,
        password_confirmation: passwordConfirmation
      })
    })

    if (!response.ok) {
      const errData = await response.json()
      throw new Error(errData.message || 'Password update failed')
    }

    return response.json()
  }

  return {
    user,
    token,
    isAuthenticated,
    login,
    register,
    logout,
    updateProfile,
    updatePassword,
    clearAuth
  }
})
