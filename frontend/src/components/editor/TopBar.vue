<template>
  <header class="sticky top-0 z-50 border-b border-white/5 bg-[#0a0b10]/80 backdrop-blur-[12px] px-8 py-3 flex items-center justify-between select-none h-14 font-geist">
    <div class="flex items-center gap-12">
      <!-- Logo Section -->
      <div @click="router.push('/')" class="flex items-center gap-2 cursor-pointer hover:opacity-80 transition">
        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center shadow-lg shadow-primary/20">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
          </svg>
        </div>
        <span class="text-lg font-bold tracking-tight text-white">StudioPro</span>
      </div>

      <!-- Navigation Menu -->
      <nav class="hidden md:flex items-center gap-1">
        <a 
          @click="selectNavbarTab('projects')"
          :class="activeNavbarTab === 'projects' ? 'bg-white/5 text-primary font-semibold' : 'text-on-surface-variant hover:text-white hover:bg-white/5'"
          class="px-4 py-2 rounded-lg text-xs font-semibold cursor-pointer transition-colors"
        >Projects</a>
        <a 
          @click="selectNavbarTab('templates')"
          :class="activeNavbarTab === 'templates' ? 'bg-white/5 text-primary font-semibold' : 'text-on-surface-variant hover:text-white hover:bg-white/5'"
          class="px-4 py-2 rounded-lg text-xs font-semibold cursor-pointer transition-colors"
        >Templates</a>
        <a 
          @click="selectNavbarTab('cms')"
          :class="activeNavbarTab === 'cms' ? 'bg-white/5 text-primary font-semibold' : 'text-on-surface-variant hover:text-white hover:bg-white/5'"
          class="px-4 py-2 rounded-lg text-xs font-semibold cursor-pointer transition-colors"
        >CMS</a>
        <a 
          @click="selectNavbarTab('commerce')"
          :class="activeNavbarTab === 'commerce' ? 'bg-white/5 text-primary font-semibold' : 'text-on-surface-variant hover:text-white hover:bg-white/5'"
          class="px-4 py-2 rounded-lg text-xs font-semibold cursor-pointer transition-colors"
        >Commerce</a>
        <a 
          @click="selectNavbarTab('settings')"
          :class="activeNavbarTab === 'settings' ? 'bg-white/5 text-primary font-semibold' : 'text-on-surface-variant hover:text-white hover:bg-white/5'"
          class="px-4 py-2 rounded-lg text-xs font-semibold cursor-pointer transition-colors"
        >Settings</a>
      </nav>
    </div>

    <!-- Right Header Actions -->
    <div class="flex items-center gap-4">
      <!-- Editor-specific Search/Tools -->
      <template v-if="route.name === 'editor'">
        <!-- Search Icon Button -->
        <div class="relative">
          <button 
            @click="isSearchOpen = !isSearchOpen"
            class="h-8 w-8 rounded bg-surface-container border border-[#222530] text-on-surface-variant hover:text-white flex items-center justify-center transition-all cursor-pointer mr-1"
            title="Search commands"
          >
            <span class="material-symbols-outlined text-[18px]">search</span>
          </button>
          <div v-if="isSearchOpen" class="absolute right-0 top-10 w-72 bg-[#060e20] border border-outline rounded shadow-xl z-50 p-2">
            <input v-model="commandQuery" @keydown.enter="runFirstCommand" class="w-full bg-editor-bg border border-outline rounded px-3 py-2 text-xs text-white outline-none focus:border-primary" placeholder="Search pages, assets, CMS...">
            <div class="mt-2 max-h-56 overflow-y-auto">
              <button v-for="command in filteredCommands" :key="command.id" @click="runCommand(command)" class="w-full px-3 py-2 text-left text-xs text-on-surface hover:bg-[#2d3449] rounded flex items-center gap-2">
                <span class="material-symbols-outlined text-[15px] text-on-surface-variant">{{ command.icon }}</span>
                {{ command.label }}
              </button>
            </div>
          </div>
        </div>

        <!-- Device Toggles -->
        <div class="flex bg-surface-container rounded p-1 border border-[#222530]">
          <button 
            class="p-1 rounded-sm transition-all cursor-pointer flex items-center justify-center"
            :class="activeDevice === 'desktop' ? 'bg-primary text-white' : 'text-on-surface-variant hover:text-white'"
            @click="setDevice('desktop')"
            title="Desktop"
          >
            <span class="material-symbols-outlined text-[16px]">desktop_windows</span>
          </button>
          
          <button 
            class="p-1 rounded-sm transition-all cursor-pointer flex items-center justify-center"
            :class="activeDevice === 'tablet' ? 'bg-primary text-white' : 'text-on-surface-variant hover:text-white'"
            @click="setDevice('tablet')"
            title="Tablet"
          >
            <span class="material-symbols-outlined text-[16px]">tablet_mac</span>
          </button>
          
          <button 
            class="p-1 rounded-sm transition-all cursor-pointer flex items-center justify-center"
            :class="activeDevice === 'mobile' ? 'bg-primary text-white' : 'text-on-surface-variant hover:text-white'"
            @click="setDevice('mobile')"
            title="Mobile"
          >
            <span class="material-symbols-outlined text-[16px]">smartphone</span>
          </button>
        </div>

        <button
          @click="store.setAiGeneratorOpen(true)"
          class="h-8 rounded border border-primary/50 bg-primary/15 px-3 text-[12px] font-bold text-on-surface hover:bg-primary hover:text-white transition flex items-center gap-1.5"
          title="Generate page with AI"
        >
          <span class="material-symbols-outlined text-[16px]">auto_awesome</span>
          AI
        </button>

        <button 
          @click="publish"
          :disabled="isPublishing"
          class="bg-primary hover:bg-blue-600 disabled:opacity-50 text-white px-5 py-1.5 rounded text-[13px] font-bold transition cursor-pointer"
        >
          {{ isPublishing ? 'Publishing...' : (store.isUnsaved ? 'Publish Changes' : 'Published') }}
        </button>
      </template>

      <!-- Non-Editor Workspace Toggles & Actions -->
      <template v-else>
        <!-- Open Editor Button -->
        <button @click="openEditor" class="px-5 py-2 bg-primary hover:bg-blue-600 text-white text-xs font-semibold rounded-xl transition-all shadow-lg shadow-primary/30 active:scale-95">
          Open Editor
        </button>
      </template>

      <!-- User Profile Dropdown -->
      <div class="relative">
        <button @click="isUserDropdownOpen = !isUserDropdownOpen" class="w-8 h-8 bg-white/5 border border-white/10 hover:border-white/20 rounded-full flex items-center justify-center text-white text-xs font-bold cursor-pointer transition-all active:scale-95">
          {{ userInitial }}
        </button>
        
        <div v-if="isUserDropdownOpen" class="absolute right-0 top-10 w-56 bg-[#0c0d12] border border-white/10 rounded-xl shadow-2xl z-50 p-2 text-left animate-fade-in">
          <div class="px-3 py-2 border-b border-white/5">
            <p class="text-xs font-bold text-white truncate">{{ userName }}</p>
            <p class="text-[10px] text-gray-500 truncate mt-0.5">{{ userEmail }}</p>
          </div>
          
          <div class="py-1">
            <button @click="navigateToSettings" class="w-full px-3 py-2 text-left text-xs text-on-surface hover:bg-white/5 rounded-lg flex items-center gap-2 transition-colors cursor-pointer">
              <span class="material-symbols-outlined text-[15px] text-gray-500">manage_accounts</span>
              Account Settings
            </button>
            <button @click="router.push('/')" v-if="route.name === 'editor'" class="w-full px-3 py-2 text-left text-xs text-on-surface hover:bg-white/5 rounded-lg flex items-center gap-2 transition-colors cursor-pointer">
              <span class="material-symbols-outlined text-[15px] text-gray-500">dashboard</span>
              Dashboard
            </button>
          </div>
          
          <div class="border-t border-white/5 pt-1 mt-1">
            <button @click="handleLogout" class="w-full px-3 py-2 text-left text-xs text-red-400 hover:bg-red-500/10 rounded-lg flex items-center gap-2 transition-colors font-semibold cursor-pointer">
              <span class="material-symbols-outlined text-[15px]">logout</span>
              Sign out
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Notification -->
    <div 
      v-if="toastMessage" 
      class="fixed top-[72px] right-6 z-50 bg-primary border border-primary/50 text-white text-xs font-bold uppercase tracking-wider px-4 py-3 rounded shadow-xl flex items-center gap-2 animate-fade-in"
    >
      <span>✓</span> {{ toastMessage }}
    </div>
  </header>
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useWorkspaceStore } from '../../stores/workspace'
import { useAuthStore } from '../../stores/auth'

const store = useWorkspaceStore()
const authStore = useAuthStore()
const route = useRoute()
const router = useRouter()

const isUserDropdownOpen = ref(false)

const userName = computed(() => authStore.user?.name || 'User')
const userEmail = computed(() => authStore.user?.email || '')
const userInitial = computed(() => {
  return userName.value.charAt(0).toUpperCase()
})

const navigateToSettings = () => {
  isUserDropdownOpen.value = false
  const projectId = route.params.projectId || localStorage.getItem('lastWorkingProject') || '1'
  router.push(`/settings/${projectId}?section=account`)
}

const handleLogout = async () => {
  isUserDropdownOpen.value = false
  await authStore.logout(store.apiBaseUrl)
  router.push('/login')
}



const activeDevice = ref('desktop')
const isPublishing = ref(false)
const toastMessage = ref('')
const isSearchOpen = ref(false)
const commandQuery = ref('')
const commands = computed(() => [
  { id: 'pages', label: 'Open Pages', icon: 'layers', run: () => selectNavbarTab('pages') },
  { id: 'blocks', label: 'Open Blocks', icon: 'add_box', run: () => { goEditorTab('blocks', 'style') } },
  { id: 'assets', label: 'Open Assets', icon: 'image', run: () => selectNavbarTab('assets') },
  { id: 'cms', label: 'Open CMS', icon: 'database', run: () => selectNavbarTab('cms') },
  { id: 'templates', label: 'Open Templates', icon: 'dashboard', run: () => selectNavbarTab('templates') },
  { id: 'settings', label: 'Open Settings', icon: 'settings', run: () => selectNavbarTab('settings') },
  { id: 'ai-generate', label: 'Generate Page with AI', icon: 'auto_awesome', run: () => { store.setAiGeneratorOpen(true) } },
  { id: 'ai-seo', label: 'Open AI SEO Optimizer', icon: 'travel_explore', run: () => goEditorTab('layers', 'seo') },
  { id: 'seo', label: 'Open SEO Settings', icon: 'travel_explore', run: () => goEditorTab('layers', 'seo') },
  { id: 'export', label: 'Open Export Code', icon: 'fact_check', run: () => goEditorTab('layers', 'code') }
])
const filteredCommands = computed(() => {
  const query = commandQuery.value.trim().toLowerCase()
  return commands.value.filter((command) => !query || command.label.toLowerCase().includes(query))
})

const activeNavbarTab = computed(() => {
  if (route.name === 'cms') return 'cms'
  if (route.name === 'templates') return 'templates'
  if (route.name === 'inventory') return 'commerce'
  if (route.name === 'settings') return 'settings'
  return 'projects'
})

const selectNavbarTab = (tab) => {
  const projectId = route.params.projectId || store.activeProject?.id || 1
  const pageId = route.params.pageId || store.activeProject?.pages?.[0]?.id
  if (tab === 'projects') {
    router.push('/')
  } else if (tab === 'cms') {
    router.push(`/cms/${projectId}`)
  } else if (tab === 'templates') {
    const query = pageId ? `?pageId=${pageId}` : ''
    router.push(`/templates/${projectId}${query}`)
  } else if (tab === 'commerce') {
    router.push(`/inventory/${projectId}`)
  } else if (tab === 'settings') {
    router.push(`/settings/${projectId}`)
  }
}

const openEditor = () => {
  const lastProject = localStorage.getItem('lastWorkingProject') || store.activeProject?.id || '1'
  const lastPage = localStorage.getItem('lastWorkingPage') || store.activeProject?.pages?.[0]?.id || '1'
  router.push(`/editor/${lastProject}/${lastPage}`)
}

const goEditorTab = (leftTab, rightTab = 'style') => {
  const projectId = route.params.projectId || store.activeProject?.id || 1
  const pageId = route.params.pageId || store.activeProject?.pages?.[0]?.id || 1
  if (route.name !== 'editor') {
    router.push(`/editor/${projectId}/${pageId}`).then(() => {
      store.setActiveLeftTab(leftTab)
      store.setActiveRightTab(rightTab)
    })
  } else {
    store.setActiveLeftTab(leftTab)
    store.setActiveRightTab(rightTab)
  }
}

const runCommand = (command) => {
  command.run()
  isSearchOpen.value = false
  commandQuery.value = ''
}

const runFirstCommand = () => {
  if (filteredCommands.value[0]) runCommand(filteredCommands.value[0])
}

const setDevice = (device) => {
  if (!store.editor) return
  activeDevice.value = device
  store.editor.setDevice(device)
}

// Sync device selection if updated elsewhere
watch(() => store.editor, (editor) => {
  if (editor) {
    editor.on('device:select', () => {
      activeDevice.value = editor.getDevice()
    })
  }
})

const publish = async () => {
  if (isPublishing.value) return
  isPublishing.value = true
  
  const projectId = route.params.projectId || 1
  const pageId = route.params.pageId || 1
  
  try {
    await store.savePage(projectId, pageId)
    try {
      await store.createPageVersion(projectId, pageId, `Published ${new Date().toLocaleString()}`)
    } catch (versionError) {
      console.warn('Publish succeeded, but version checkpoint failed', versionError)
    }
    toastMessage.value = 'Published Successfully'
    setTimeout(() => {
      toastMessage.value = ''
    }, 3000)
  } catch (err) {
    toastMessage.value = 'Publish Failed'
    setTimeout(() => {
      toastMessage.value = ''
    }, 3000)
  } finally {
    isPublishing.value = false
  }
}
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
  animation: fadeIn 0.2s ease-out forwards;
}
</style>
