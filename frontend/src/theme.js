import { computed, ref } from 'vue'

const key = 'studiopro.color-scheme'
const root = document.documentElement
const media = window.matchMedia('(prefers-color-scheme: dark)')
const system = ref(media.matches ? 'dark' : 'light')
const preference = ref(root.dataset.studioTheme || 'system')

function applyTheme(value) {
  preference.value = value === 'dark' || value === 'light' ? value : 'system'
  if (preference.value === 'system') delete root.dataset.studioTheme
  else root.dataset.studioTheme = preference.value
  document.querySelector('meta[name="color-scheme"]').content =
    preference.value === 'system' ? 'light dark' : preference.value
}

export function setTheme(value) {
  applyTheme(value)
  try {
    if (preference.value === 'system') localStorage.removeItem(key)
    else localStorage.setItem(key, preference.value)
  } catch {
    // The preference still applies in the current document.
  }
}

media.addEventListener('change', event => { system.value = event.matches ? 'dark' : 'light' })
window.addEventListener('storage', event => {
  if (event.key === key || event.key === null) applyTheme(event.newValue)
})

export const themePreference = computed(() => preference.value)
export const systemTheme = computed(() => system.value)
export const isDark = computed(() => (preference.value === 'system' ? system.value : preference.value) === 'dark')

export function toggleTheme() {
  const next = isDark.value ? 'light' : 'dark'
  setTheme(next === system.value ? 'system' : next)
}
