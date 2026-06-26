<template>
  <div class="h-14 bg-[#0B0E14] border-b border-[#222530] flex items-center px-6 justify-between select-none font-geist">
    <!-- Left Logo & Menu -->
    <div class="flex items-center gap-8 h-full">
      <div 
        @click="goToInventory"
        class="text-white font-extrabold text-lg tracking-tight flex items-center cursor-pointer hover:opacity-80 transition"
      >
        StudioPro
      </div>
      <div class="flex gap-6 text-[13px] items-center h-full pt-1">
        <span 
          @click="selectNavbarTab('pages')"
          class="pb-1 cursor-pointer border-b-2 transition-all duration-150 select-none font-medium"
          :class="activeNavbarTab === 'pages' ? 'text-[#2E62FF] border-[#2E62FF]' : 'text-[#8d90a2] border-transparent hover:text-white'"
        >Pages</span>
        <span 
          @click="selectNavbarTab('cms')"
          class="pb-1 cursor-pointer border-b-2 transition-all duration-150 select-none font-medium"
          :class="activeNavbarTab === 'cms' ? 'text-[#2E62FF] border-[#2E62FF]' : 'text-[#8d90a2] border-transparent hover:text-white'"
        >CMS</span>
        <span 
          @click="selectNavbarTab('templates')"
          class="pb-1 cursor-pointer border-b-2 transition-all duration-150 select-none font-medium"
          :class="activeNavbarTab === 'templates' ? 'text-[#2E62FF] border-[#2E62FF]' : 'text-[#8d90a2] border-transparent hover:text-white'"
        >Templates</span>
        <span 
          @click="selectNavbarTab('assets')"
          class="pb-1 cursor-pointer border-b-2 transition-all duration-150 select-none font-medium"
          :class="activeNavbarTab === 'assets' ? 'text-[#2E62FF] border-[#2E62FF]' : 'text-[#8d90a2] border-transparent hover:text-white'"
        >Assets</span>
        <span 
          @click="selectNavbarTab('settings')"
          class="pb-1 cursor-pointer border-b-2 transition-all duration-150 select-none font-medium"
          :class="activeNavbarTab === 'settings' ? 'text-[#2E62FF] border-[#2E62FF]' : 'text-[#8d90a2] border-transparent hover:text-white'"
        >Settings</span>
      </div>
    </div>
    
    <!-- Right Actions -->
    <div class="flex items-center gap-4">
      <!-- Search Icon Button -->
      <div class="relative">
        <button 
          @click="isSearchOpen = !isSearchOpen"
          class="h-8 w-8 rounded bg-[#161B22] border border-[#222530] text-[#8d90a2] hover:text-white flex items-center justify-center transition-all cursor-pointer mr-1"
          title="Search commands"
        >
          <span class="material-symbols-outlined text-[18px]">search</span>
        </button>
        <div v-if="isSearchOpen" class="absolute right-0 top-10 w-72 bg-[#060e20] border border-[#434656] rounded shadow-xl z-50 p-2">
          <input v-model="commandQuery" @keydown.enter="runFirstCommand" class="w-full bg-[#0B0E14] border border-[#434656] rounded px-3 py-2 text-xs text-white outline-none focus:border-[#2E62FF]" placeholder="Search pages, assets, CMS...">
          <div class="mt-2 max-h-56 overflow-y-auto">
            <button v-for="command in filteredCommands" :key="command.id" @click="runCommand(command)" class="w-full px-3 py-2 text-left text-xs text-[#dae2fd] hover:bg-[#2d3449] rounded flex items-center gap-2">
              <span class="material-symbols-outlined text-[15px] text-[#8d90a2]">{{ command.icon }}</span>
              {{ command.label }}
            </button>
          </div>
        </div>
      </div>

      <!-- Device Toggles -->
      <div v-if="route.name === 'editor'" class="flex bg-[#161B22] rounded p-1 border border-[#222530]">
        <button 
          class="p-1 rounded-sm transition-all cursor-pointer flex items-center justify-center"
          :class="activeDevice === 'desktop' ? 'bg-[#2E62FF] text-white' : 'text-[#8d90a2] hover:text-white'"
          @click="setDevice('desktop')"
          title="Desktop"
        >
          <span class="material-symbols-outlined text-[16px]">desktop_windows</span>
        </button>
        
        <button 
          class="p-1 rounded-sm transition-all cursor-pointer flex items-center justify-center"
          :class="activeDevice === 'tablet' ? 'bg-[#2E62FF] text-white' : 'text-[#8d90a2] hover:text-white'"
          @click="setDevice('tablet')"
          title="Tablet"
        >
          <span class="material-symbols-outlined text-[16px]">tablet_mac</span>
        </button>
        
        <button 
          class="p-1 rounded-sm transition-all cursor-pointer flex items-center justify-center"
          :class="activeDevice === 'mobile' ? 'bg-[#2E62FF] text-white' : 'text-[#8d90a2] hover:text-white'"
          @click="setDevice('mobile')"
          title="Mobile"
        >
          <span class="material-symbols-outlined text-[16px]">smartphone</span>
        </button>
      </div>

      <button
        v-if="route.name === 'editor'"
        @click="store.setAiGeneratorOpen(true)"
        class="h-8 rounded border border-[#2E62FF]/50 bg-[#2E62FF]/15 px-3 text-[12px] font-bold text-[#dae2fd] hover:bg-[#2E62FF] hover:text-white transition flex items-center gap-1.5"
        title="Generate page with AI"
      >
        <span class="material-symbols-outlined text-[16px]">auto_awesome</span>
        AI
      </button>

      <button 
        v-if="route.name === 'editor'"
        @click="publish"
        :disabled="isPublishing"
        class="bg-[#2E62FF] hover:bg-blue-600 disabled:opacity-50 text-white px-5 py-1.5 rounded text-[13px] font-bold transition cursor-pointer"
      >
        {{ isPublishing ? 'Publishing...' : (store.isUnsaved ? 'Publish Changes' : 'Published') }}
      </button>
    </div>

    <!-- Toast Notification -->
    <div 
      v-if="toastMessage" 
      class="fixed top-[72px] right-6 z-50 bg-[#2E62FF] border border-[#2E62FF]/50 text-white text-xs font-bold uppercase tracking-wider px-4 py-3 rounded shadow-xl flex items-center gap-2 animate-fade-in"
    >
      <span>✓</span> {{ toastMessage }}
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useWorkspaceStore } from '../../stores/workspace'

const store = useWorkspaceStore()
const route = useRoute()
const router = useRouter()

const goToInventory = () => {
  const projectId = route.params.projectId || 1
  router.push(`/inventory/${projectId}`)
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
  if (store.activeLeftTab === 'assets') return 'assets'
  if (store.activeRightTab === 'settings') return 'settings'
  return 'pages'
})

const selectNavbarTab = (tab) => {
  const projectId = route.params.projectId || 1
  if (tab === 'pages') {
    if (route.name !== 'editor') {
      router.push(`/editor/${projectId}/1`)
    } else {
      store.setActiveLeftTab('layers')
      store.setActiveRightTab('style')
    }
  } else if (tab === 'cms') {
    router.push(`/cms/${projectId}`)
  } else if (tab === 'templates') {
    router.push(`/templates/${projectId}`)
  } else if (tab === 'assets') {
    if (route.name !== 'editor') {
      router.push(`/editor/${projectId}/1`).then(() => {
        store.setActiveLeftTab('assets')
        store.setActiveRightTab('settings')
      })
    } else {
      store.setActiveLeftTab('assets')
      store.setActiveRightTab('settings')
    }
  } else if (tab === 'settings') {
    if (route.name === 'editor') {
      store.setActiveLeftTab('config')
      store.setActiveRightTab('settings')
    } else {
      router.push(`/editor/${projectId}/1`).then(() => {
        store.setActiveLeftTab('config')
        store.setActiveRightTab('settings')
      })
    }
  }
}

const goEditorTab = (leftTab, rightTab = 'style') => {
  const projectId = route.params.projectId || 1
  if (route.name !== 'editor') {
    router.push(`/editor/${projectId}/1`).then(() => {
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
