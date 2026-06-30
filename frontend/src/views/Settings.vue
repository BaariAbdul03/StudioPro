<template>
  <div class="h-screen w-full flex flex-col bg-surface text-on-surface overflow-hidden select-none font-geist mesh-bg relative">
    <TopBar />

    <main class="flex-1 flex flex-col px-8 py-6 overflow-hidden min-h-0">
      <div class="max-w-[1400px] mx-auto w-full flex flex-col flex-1 min-h-0">
        
        <!-- Header -->
        <div class="flex items-end justify-between mb-6 shrink-0">
          <div>
            <div class="flex items-center gap-2 mb-1.5">
              <span class="font-mono text-[11px] text-on-surface-variant uppercase tracking-widest">Workspace</span>
              <span class="text-on-surface-variant text-[12px]">/</span>
              <span class="font-mono text-[11px] text-primary uppercase tracking-widest">Settings</span>
            </div>
            <h1 class="text-[28px] font-bold text-white tracking-tight">System Settings</h1>
            <p class="text-xs text-gray-500 mt-0.5">Configure canvas, editor, and project-level preferences.</p>
          </div>
        </div>

        <!-- 2 Column Layout -->
        <div class="flex gap-6 flex-1 min-h-0">
          <!-- Left Sidebar -->
          <div class="w-64 flex flex-col gap-4 shrink-0">
            <div class="flex-1 bg-surface-container/70 backdrop-blur-[12px] border border-outline/30 rounded-2xl p-4 flex flex-col overflow-hidden">
              <!-- Preferences Section -->
              <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 px-1 mt-1">Preferences</div>
              <div class="space-y-0.5">
                <div 
                  @click="activeSection = 'canvas'"
                  class="flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer transition-all"
                  :class="activeSection === 'canvas' ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:bg-white/5 hover:text-on-surface'"
                >
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" :class="activeSection === 'canvas' ? 'bg-primary/20' : 'bg-white/5'">
                    <span class="material-symbols-outlined text-[15px]">aspect_ratio</span>
                  </div>
                  <span class="font-medium text-xs">Canvas Settings</span>
                </div>
                <div 
                  @click="activeSection = 'editor'"
                  class="flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer transition-all"
                  :class="activeSection === 'editor' ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:bg-white/5 hover:text-on-surface'"
                >
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" :class="activeSection === 'editor' ? 'bg-primary/20' : 'bg-white/5'">
                    <span class="material-symbols-outlined text-[15px]">tune</span>
                  </div>
                  <span class="font-medium text-xs">Editor Preferences</span>
                </div>

                <div 
                  @click="activeSection = 'account'"
                  class="flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer transition-all"
                  :class="activeSection === 'account' ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:bg-white/5 hover:text-on-surface'"
                >
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" :class="activeSection === 'account' ? 'bg-primary/20' : 'bg-white/5'">
                    <span class="material-symbols-outlined text-[15px]">manage_accounts</span>
                  </div>
                  <span class="font-medium text-xs">Account Settings</span>
                </div>
              </div>

              <!-- Projects Section -->
              <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 px-1 mt-5">Projects</div>
              <div class="flex-1 overflow-y-auto custom-scrollbar pr-1 space-y-0.5">
                <div v-if="loadingProjects" class="text-[11px] text-gray-500 px-3 py-2">Loading...</div>
                <div 
                  v-else
                  v-for="project in projects"
                  :key="project.id"
                  @click="selectProject(project)"
                  class="flex items-center justify-between px-3 py-2.5 rounded-xl cursor-pointer transition-all"
                  :class="activeSection === 'project' && activeProjectId === project.id ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:bg-white/5 hover:text-on-surface'"
                >
                  <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-md flex items-center justify-center shrink-0" :class="activeSection === 'project' && activeProjectId === project.id ? 'bg-primary/20' : 'bg-white/5'">
                      <span class="material-symbols-outlined text-[11px]">folder</span>
                    </div>
                    <span class="font-medium text-[11px] truncate max-w-[120px]">{{ project.name }}</span>
                  </div>
                  <span class="text-[9px] text-gray-600 font-mono shrink-0">#{{ project.id }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Content Panel -->
          <div class="flex-1 bg-surface-container/70 backdrop-blur-[12px] border border-outline/30 rounded-2xl p-8 flex flex-col overflow-y-auto custom-scrollbar">
            
            <!-- Canvas Settings -->
            <div v-if="activeSection === 'canvas'" class="space-y-6 max-w-2xl">
              <div class="flex items-start justify-between pb-4 border-b border-white/5">
                <div>
                  <h2 class="text-base font-bold text-white mb-1">Canvas Preferences</h2>
                  <p class="text-xs text-on-surface-variant">Configure workspace layout constraints, visual guides, and grid metrics.</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-primary/15 flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-primary text-[16px]">aspect_ratio</span>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5">
                  <label class="settings-label">Default Canvas Width</label>
                  <input v-model="canvasForm.width" class="settings-input" type="text" placeholder="e.g. 1440px">
                </div>
                <div class="flex flex-col gap-1.5">
                  <label class="settings-label">Default Canvas Height</label>
                  <input v-model="canvasForm.height" class="settings-input" type="text" placeholder="e.g. auto">
                </div>
                <div class="flex flex-col gap-1.5">
                  <label class="settings-label">Grid Size (px)</label>
                  <input v-model.number="canvasForm.gridSize" class="settings-input" type="number" min="4" max="64">
                </div>
                <div class="flex flex-col gap-1.5">
                  <label class="settings-label">Default Zoom (%)</label>
                  <input v-model.number="canvasForm.zoom" class="settings-input" type="number" min="25" max="200" step="25">
                </div>
              </div>

              <div class="border border-white/5 rounded-xl overflow-hidden">
                <div class="settings-toggle">
                  <div>
                    <span class="text-xs font-semibold text-white">Show Grid Lines</span>
                    <span class="text-[10px] text-gray-500 block">Render structural grid overlay on canvas</span>
                  </div>
                  <label class="toggle-switch">
                    <input v-model="canvasForm.showGrid" type="checkbox">
                    <span class="toggle-slider"></span>
                  </label>
                </div>
                <div class="settings-toggle border-t border-white/5">
                  <div>
                    <span class="text-xs font-semibold text-white">Snap to Grid</span>
                    <span class="text-[10px] text-gray-500 block">Align elements to nearest grid boundary</span>
                  </div>
                  <label class="toggle-switch">
                    <input v-model="canvasForm.snapToGrid" type="checkbox">
                    <span class="toggle-slider"></span>
                  </label>
                </div>
                <div class="settings-toggle border-t border-white/5">
                  <div>
                    <span class="text-xs font-semibold text-white">Show Rulers</span>
                    <span class="text-[10px] text-gray-500 block">Display pixel rulers on canvas edges</span>
                  </div>
                  <label class="toggle-switch">
                    <input v-model="canvasForm.showRulers" type="checkbox">
                    <span class="toggle-slider"></span>
                  </label>
                </div>
                <div class="settings-toggle border-t border-white/5">
                  <div>
                    <span class="text-xs font-semibold text-white">Highlight Selected Elements</span>
                    <span class="text-[10px] text-gray-500 block">Glow highlight around selected canvas elements</span>
                  </div>
                  <label class="toggle-switch">
                    <input v-model="canvasForm.highlightSelected" type="checkbox">
                    <span class="toggle-slider"></span>
                  </label>
                </div>
              </div>

              <div>
                <button @click="saveCanvasSettings" class="px-5 py-2.5 bg-primary hover:brightness-110 text-white text-xs font-bold rounded-xl active:scale-95 transition-all shadow-lg shadow-primary/20">Save Canvas Preferences</button>
              </div>
            </div>

            <!-- Editor Preferences -->
            <div v-if="activeSection === 'editor'" class="space-y-6 max-w-2xl">
              <div class="flex items-start justify-between pb-4 border-b border-white/5">
                <div>
                  <h2 class="text-base font-bold text-white mb-1">Editor Preferences</h2>
                  <p class="text-xs text-on-surface-variant">Configure interface controls, panels, sync speeds, and interaction behaviour.</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-[#8B5CF6]/15 flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-[#8B5CF6] text-[16px]">tune</span>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5">
                  <label class="settings-label">Auto-Save Delay (seconds)</label>
                  <input v-model.number="editorForm.autoSaveDelay" class="settings-input" type="number" min="1" max="60">
                </div>
                <div class="flex flex-col gap-1.5">
                  <label class="settings-label">Undo History Depth</label>
                  <input v-model.number="editorForm.undoDepth" class="settings-input" type="number" min="10" max="200" step="10">
                </div>
              </div>

              <div class="border border-white/5 rounded-xl overflow-hidden">
                <div class="settings-toggle">
                  <div>
                    <span class="text-xs font-semibold text-white">Enable Auto-Save</span>
                    <span class="text-[10px] text-gray-500 block">Periodically save canvas changes to server</span>
                  </div>
                  <label class="toggle-switch">
                    <input v-model="editorForm.autoSave" type="checkbox">
                    <span class="toggle-slider"></span>
                  </label>
                </div>
                <div class="settings-toggle border-t border-white/5">
                  <div>
                    <span class="text-xs font-semibold text-white">Real-time Preview</span>
                    <span class="text-[10px] text-gray-500 block">Update preview iframe on each change</span>
                  </div>
                  <label class="toggle-switch">
                    <input v-model="editorForm.realTimePreview" type="checkbox">
                    <span class="toggle-slider"></span>
                  </label>
                </div>
                <div class="settings-toggle border-t border-white/5">
                  <div>
                    <span class="text-xs font-semibold text-white">Show Component Labels</span>
                    <span class="text-[10px] text-gray-500 block">Display type labels on hovered canvas elements</span>
                  </div>
                  <label class="toggle-switch">
                    <input v-model="editorForm.showLabels" type="checkbox">
                    <span class="toggle-slider"></span>
                  </label>
                </div>
                <div class="settings-toggle border-t border-white/5">
                  <div>
                    <span class="text-xs font-semibold text-white">Compact Panel Mode</span>
                    <span class="text-[10px] text-gray-500 block">Reduce padding in sidebar property panels</span>
                  </div>
                  <label class="toggle-switch">
                    <input v-model="editorForm.compactMode" type="checkbox">
                    <span class="toggle-slider"></span>
                  </label>
                </div>
                <div class="settings-toggle border-t border-white/5">
                  <div>
                    <span class="text-xs font-semibold text-white">Enable Tooltips</span>
                    <span class="text-[10px] text-gray-500 block">Show contextual tooltips on toolbar icons</span>
                  </div>
                  <label class="toggle-switch">
                    <input v-model="editorForm.tooltips" type="checkbox">
                    <span class="toggle-slider"></span>
                  </label>
                </div>
              </div>

              <div>
                <button @click="saveEditorSettings" class="px-5 py-2.5 bg-primary hover:brightness-110 text-white text-xs font-bold rounded-xl active:scale-95 transition-all shadow-lg shadow-primary/20">Save Editor Preferences</button>
              </div>
            </div>


            <!-- Account Settings -->
            <div v-if="activeSection === 'account'" class="space-y-6 max-w-2xl">
              <div class="flex items-start justify-between pb-4 border-b border-white/5">
                <div>
                  <h2 class="text-base font-bold text-white mb-1">Account Profile</h2>
                  <p class="text-xs text-on-surface-variant">Update your personal details and change your password.</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-primary/15 flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-primary text-[16px]">manage_accounts</span>
                </div>
              </div>

              <!-- Profile Details Form -->
              <form @submit.prevent="saveProfile" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                  <div class="flex flex-col gap-1.5">
                    <label class="settings-label">Full Name</label>
                    <input v-model="profileForm.name" class="settings-input" type="text" required>
                  </div>
                  <div class="flex flex-col gap-1.5">
                    <label class="settings-label">Email Address</label>
                    <input v-model="profileForm.email" class="settings-input" type="email" required>
                  </div>
                </div>
                <div>
                  <button type="submit" :disabled="savingProfile" class="px-5 py-2.5 bg-primary hover:brightness-110 disabled:opacity-50 text-white text-xs font-bold rounded-xl active:scale-95 transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                    <span v-if="savingProfile" class="material-symbols-outlined text-[14px] animate-spin">refresh</span>
                    Save Profile Details
                  </button>
                </div>
              </form>

              <!-- Change Password Form -->
              <div class="border-t border-white/5 pt-6 mt-6">
                <h3 class="text-sm font-bold text-white mb-4">Change Password</h3>
                <form @submit.prevent="savePassword" class="space-y-4">
                  <div v-if="passwordError" class="bg-red-500/10 border border-red-500/30 text-red-400 text-xs px-4 py-3 rounded-xl flex items-start gap-2 max-w-md">
                    <span class="material-symbols-outlined text-[16px] shrink-0 mt-0.5">error</span>
                    <span>{{ passwordError }}</span>
                  </div>

                  <div class="grid grid-cols-1 gap-4 max-w-md">
                    <div class="flex flex-col gap-1.5">
                      <label class="settings-label">Current Password</label>
                      <input v-model="passwordForm.current_password" class="settings-input" type="password" required>
                    </div>
                    <div class="flex flex-col gap-1.5">
                      <label class="settings-label">New Password</label>
                      <input v-model="passwordForm.password" class="settings-input" type="password" required placeholder="Minimum 8 characters">
                    </div>
                    <div class="flex flex-col gap-1.5">
                      <label class="settings-label">Confirm New Password</label>
                      <input v-model="passwordForm.password_confirmation" class="settings-input" type="password" required>
                    </div>
                  </div>
                  <div>
                    <button type="submit" :disabled="savingPassword" class="px-5 py-2.5 bg-primary hover:brightness-110 disabled:opacity-50 text-white text-xs font-bold rounded-xl active:scale-95 transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                      <span v-if="savingPassword" class="material-symbols-outlined text-[14px] animate-spin">refresh</span>
                      Update Password
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Project Details Form -->
            <div v-if="activeSection === 'project'" class="space-y-6 max-w-2xl">
              <div class="flex items-start justify-between pb-4 border-b border-white/5">
                <div>
                  <h2 class="text-base font-bold text-white mb-1">Project: {{ projectForm.name }}</h2>
                  <p class="text-xs text-on-surface-variant">Modify name, URL slug, description, and deploy webhook configuration.</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-[#10B981]/15 flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-[#10B981] text-[16px]">folder_open</span>
                </div>
              </div>

              <form @submit.prevent="saveProjectSettings" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                  <div class="flex flex-col gap-1.5">
                    <label class="settings-label">Project Name *</label>
                    <input v-model="projectForm.name" class="settings-input" type="text" required>
                  </div>
                  <div class="flex flex-col gap-1.5">
                    <label class="settings-label">URL Slug *</label>
                    <input v-model="projectForm.slug" class="settings-input" type="text" required>
                  </div>
                </div>

                <div class="flex flex-col gap-1.5">
                  <label class="settings-label">Description</label>
                  <textarea v-model="projectForm.description" rows="3" class="settings-input resize-none"></textarea>
                </div>

                <div class="flex flex-col gap-1.5">
                  <label class="settings-label">Deploy Webhook URL</label>
                  <input v-model="projectForm.settings.webhook" class="settings-input" type="url" placeholder="https://api.netlify.com/build_hooks/...">
                  <span class="text-[10px] text-gray-600 mt-0.5">Called automatically when project pages are published.</span>
                </div>

                <div class="pt-2 border-t border-white/5">
                  <button type="submit" :disabled="savingProject" class="px-5 py-2.5 bg-primary hover:brightness-110 disabled:opacity-50 text-white text-xs font-bold rounded-xl active:scale-95 transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                    <span v-if="savingProject" class="material-symbols-outlined text-[14px] animate-spin">refresh</span>
                    {{ savingProject ? 'Saving...' : 'Save Project Details' }}
                  </button>
                </div>
              </form>

              <!-- Danger Zone -->
              <div class="border border-red-500/20 rounded-xl p-5 mt-2 bg-red-500/3">
                <h3 class="text-sm font-bold text-red-400 mb-1 flex items-center gap-2">
                  <span class="material-symbols-outlined text-[16px]">warning</span>
                  Danger Zone
                </h3>
                <p class="text-[11px] text-gray-500 mb-4">These actions are irreversible. Proceed with extreme caution.</p>
                <div class="flex items-center justify-between py-3 border-t border-red-500/10">
                  <div>
                    <span class="text-xs font-semibold text-white">Delete Project</span>
                    <span class="text-[10px] text-gray-500 block mt-0.5">Permanently remove this project and all associated data</span>
                  </div>
                  <button type="button" class="px-4 py-2 border border-red-500/40 text-red-400 text-xs font-bold rounded-xl hover:bg-red-500/10 transition-colors">Delete</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <Footer />

    <!-- Toast Notification -->
    <div v-if="toastMessage" class="fixed top-[72px] right-6 z-50 bg-primary border border-primary/50 text-white text-xs font-bold uppercase tracking-wider px-4 py-3 rounded-xl shadow-xl flex items-center gap-2 animate-fade-in">
      <span>✓</span> {{ toastMessage }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useWorkspaceStore } from '../stores/workspace'
import { useAuthStore } from '../stores/auth'
import TopBar from '../components/editor/TopBar.vue'
import Footer from '../components/Footer.vue'
import axios from 'axios'

const route = useRoute()
const store = useWorkspaceStore()
const authStore = useAuthStore()

const activeSection = ref('canvas')

const savingProfile = ref(false)
const savingPassword = ref(false)
const passwordError = ref('')

const profileForm = ref({
  name: authStore.user?.name || '',
  email: authStore.user?.email || '',
})

const passwordForm = ref({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const saveProfile = async () => {
  savingProfile.value = true
  try {
    await authStore.updateProfile(profileForm.value.name, profileForm.value.email, store.apiBaseUrl)
    showToast('Profile details updated successfully')
  } catch (err) {
    showToast(err.message || 'Failed to update profile')
  } finally {
    savingProfile.value = false
  }
}

const savePassword = async () => {
  if (passwordForm.value.password !== passwordForm.value.password_confirmation) {
    passwordError.value = 'Passwords do not match'
    return
  }
  savingPassword.value = true
  passwordError.value = ''
  try {
    await authStore.updatePassword(
      passwordForm.value.current_password,
      passwordForm.value.password,
      passwordForm.value.password_confirmation,
      store.apiBaseUrl
    )
    showToast('Password updated successfully')
    passwordForm.value = {
      current_password: '',
      password: '',
      password_confirmation: '',
    }
  } catch (err) {
    passwordError.value = err.message || 'Failed to update password'
  } finally {
    savingPassword.value = false
  }
}
const activeProjectId = ref(null)
const projects = ref([])
const loadingProjects = ref(true)
const savingProject = ref(false)
const toastMessage = ref('')


const canvasForm = ref({
  width: '1440px',
  height: 'auto',
  showGrid: true,
  snapToGrid: true,
  showRulers: true,
  highlightSelected: true,
  gridSize: 24,
  zoom: 100,
})

const editorForm = ref({
  autoSave: true,
  autoSaveDelay: 5,
  undoDepth: 50,
  realTimePreview: true,
  showLabels: true,
  compactMode: false,
  tooltips: true,
})


const projectForm = ref({
  name: '',
  slug: '',
  description: '',
  settings: { webhook: '' }
})

const showToast = (msg) => {
  toastMessage.value = msg
  setTimeout(() => { toastMessage.value = '' }, 3000)
}

onMounted(() => {
  loadProjects()
  loadPrefs()
  if (route.query.section) {
    activeSection.value = route.query.section
  }
})

const loadPrefs = () => {
  store.loadPreferences()
  // Deep clone to forms to avoid direct mutation without explicit save
  canvasForm.value = JSON.parse(JSON.stringify(store.preferences.canvas))
  editorForm.value = JSON.parse(JSON.stringify(store.preferences.editor))

}

const loadProjects = async () => {
  loadingProjects.value = true
  try {
    const res = await axios.get(`${store.apiBaseUrl}/projects`)
    projects.value = res.data
    const routeProjId = Number(route.params.projectId)
    if (routeProjId) {
      const match = projects.value.find(p => p.id === routeProjId)
      if (match) selectProject(match)
    }
  } catch (err) {
    console.error('Failed to fetch projects', err)
  } finally {
    loadingProjects.value = false
  }
}

const selectProject = (project) => {
  activeSection.value = 'project'
  activeProjectId.value = project.id
  projectForm.value.name = project.name
  projectForm.value.slug = project.slug
  projectForm.value.description = project.description || ''
  projectForm.value.settings = project.settings || { webhook: '' }
}

const saveCanvasSettings = () => {
  store.savePreferences({ canvas: canvasForm.value })
  showToast('Canvas preferences saved')
}

const saveEditorSettings = () => {
  store.savePreferences({ editor: editorForm.value })
  showToast('Editor preferences saved')
}


const saveProjectSettings = async () => {
  if (!activeProjectId.value) return
  savingProject.value = true
  try {
    const res = await axios.put(`${store.apiBaseUrl}/projects/${activeProjectId.value}`, projectForm.value)
    showToast('Project details saved')
    const index = projects.value.findIndex(p => p.id === activeProjectId.value)
    if (index !== -1) projects.value[index] = res.data
  } catch (err) {
    console.error(err)
    showToast('Failed to save project settings')
  } finally {
    savingProject.value = false
  }
}
</script>

<style scoped>
.settings-label {
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #6b7280;
}

.settings-input {
  background: #0B0E14;
  border: 1px solid rgba(255,255,255,0.08);
  border-radius: 10px;
  padding: 8px 12px;
  font-size: 12px;
  color: #dae2fd;
  outline: none;
  width: 100%;
  transition: border-color 0.2s;
  font-family: inherit;
}
.settings-input:focus {
  border-color: #2E62FF;
}

.settings-toggle {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 16px;
  background: transparent;
}

.toggle-switch {
  position: relative;
  display: inline-block;
  width: 36px;
  height: 20px;
  flex-shrink: 0;
}
.toggle-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}
.toggle-slider {
  position: absolute;
  cursor: pointer;
  inset: 0;
  background: rgba(255,255,255,0.1);
  border-radius: 20px;
  transition: 0.2s;
}
.toggle-slider::before {
  content: '';
  position: absolute;
  width: 14px;
  height: 14px;
  left: 3px;
  bottom: 3px;
  background: #8d90a2;
  border-radius: 50%;
  transition: 0.2s;
}
.toggle-switch input:checked + .toggle-slider {
  background: #2E62FF;
}
.toggle-switch input:checked + .toggle-slider::before {
  transform: translateX(16px);
  background: white;
}

.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(255,255,255,0.1); border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #2E62FF; }

.mesh-bg {
  background-color: #0b1326;
  background-image: 
    radial-gradient(at 0% 0%, rgba(46, 98, 255, 0.1) 0px, transparent 50%),
    radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.08) 0px, transparent 50%);
}

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
.animate-fade-in { animation: fadeIn 0.25s ease-out forwards; }
</style>
