<template>
  <div class="h-screen w-full flex flex-col bg-[#0B0E14] text-[#dae2fd] overflow-hidden select-none font-geist">
    <TopBar />

    <div class="flex-1 flex overflow-hidden pt-1">
      <!-- Left Sidebar: Collections List -->
      <aside class="w-[280px] bg-[#161B22] border-r border-[#434656] flex flex-col z-40">
        <div class="p-4 flex flex-col gap-4">
          <div class="flex items-center gap-3 mb-2">
            <div class="w-8 h-8 bg-[#2E62FF] rounded flex items-center justify-center">
              <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings: 'FILL' 1;">database</span>
            </div>
            <div>
              <div class="text-[12px] font-bold text-white uppercase tracking-wider">Collections</div>
              <div class="text-[13px] text-[#c3c5d8]">Project Alpha</div>
            </div>
          </div>

          <div class="space-y-1 overflow-y-auto max-h-[300px] custom-scrollbar">
            <div 
              v-for="col in collections" 
              :key="col.id"
              @click="selectCollection(col)"
              class="px-3 py-2 flex items-center justify-between group cursor-pointer transition-all rounded"
              :class="activeCollection && activeCollection.id === col.id ? 'bg-[#2E62FF]/15 text-[#2E62FF] border-l-2 border-[#2E62FF]' : 'text-[#c3c5d8] hover:bg-[#2d3449] border-l-2 border-transparent'"
            >
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">description</span>
                <span class="text-[13px] font-medium">{{ col.name }}</span>
              </div>
              <div class="flex items-center gap-1">
                <span class="text-[11px] font-mono text-[#8d90a2] group-hover:text-[#2E62FF]">{{ col.items_count || 0 }}</span>
                <span @click.stop="deleteCollection(col)" class="material-symbols-outlined text-[14px] text-red-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-opacity ml-1">delete</span>
              </div>
            </div>
          </div>

          <button 
            @click="openNewCollectionModal" 
            class="w-full py-2 border border-dashed border-[#434656] text-[#8d90a2] hover:text-white hover:border-white transition-colors text-[13px] flex items-center justify-center gap-2 rounded"
          >
            <span class="material-symbols-outlined text-[16px]">add</span>
            Create Collection
          </button>
        </div>

        <div class="mt-auto p-4 border-t border-[#434656] bg-[#060e20]/50">
          <div class="flex flex-col gap-2">
            <div @click="showHelp = true" class="flex items-center gap-2 text-[#c3c5d8] hover:text-white cursor-pointer text-[12px]">
              <span class="material-symbols-outlined text-[18px]">help</span>
              <span>Help Center</span>
            </div>
            <div @click="showFeedback = true" class="flex items-center gap-2 text-[#c3c5d8] hover:text-white cursor-pointer text-[12px]">
              <span class="material-symbols-outlined text-[18px]">chat_bubble</span>
              <span>Feedback</span>
            </div>
          </div>
        </div>
      </aside>

      <!-- Main Canvas Area -->
      <main class="flex-1 bg-[#0B0E14] flex flex-col overflow-hidden relative">
        <!-- Content Header -->
        <div class="h-16 flex items-center justify-between px-6 bg-[#161B22]/50 border-b border-[#434656] shrink-0">
          <div class="flex items-center gap-3">
            <h1 class="text-[18px] font-bold text-white">{{ activeCollection ? activeCollection.name : 'Select a Collection' }}</h1>
            <div v-if="activeCollection" class="bg-[#2d3449] px-2 py-0.5 rounded text-[11px] font-mono text-[#c3c5d8]">
              {{ items.length }} Items
            </div>
          </div>
          <div v-if="activeCollection" class="flex items-center gap-2">
            <button 
              @click="openAddFieldModal"
              class="flex items-center gap-1.5 px-3 py-1.5 border border-[#434656] text-[#c3c5d8] hover:text-white hover:bg-[#2d3449] text-[13px] rounded transition-colors"
            >
              <span class="material-symbols-outlined text-[18px]">add_column_right</span>
              Add Field
            </button>
            <button 
              @click="openNewItemModal"
              class="flex items-center gap-1.5 px-4 py-1.5 bg-[#2E62FF] text-white text-[13px] rounded hover:brightness-110 shadow-lg shadow-[#2E62FF]/20 transition-all active:scale-95"
            >
              <span class="material-symbols-outlined text-[18px]">add</span>
              New Item
            </button>
          </div>
        </div>

        <!-- Table View -->
        <div class="flex-1 overflow-auto bg-[#0B0E14]">
          <div v-if="loadingItems" class="p-12 text-center text-[#8d90a2] text-sm">
            Loading items...
          </div>
          <div v-else-if="!activeCollection" class="p-12 text-center text-[#8d90a2] text-sm">
            Select a collection from the sidebar to manage items.
          </div>
          <div v-else-if="items.length === 0" class="p-12 text-center text-[#8d90a2] text-sm">
            No items in this collection yet. Click "New Item" to get started.
          </div>
          <table v-else class="w-full text-left border-collapse min-w-[800px]">
            <thead class="sticky top-0 bg-[#161B22] border-b border-[#434656] z-10">
              <tr>
                <th class="p-3 w-12"><input class="rounded-xs bg-[#0B0E14] border-[#434656] text-[#2E62FF] focus:ring-[#2E62FF]" type="checkbox"></th>
                <th class="p-3 text-[11px] text-[#8d90a2] uppercase tracking-wider">Slug</th>
                <th class="p-3 text-[11px] text-[#8d90a2] uppercase tracking-wider">Status</th>
                <th 
                  v-for="field in activeCollection.fields" 
                  :key="field.key"
                  class="p-3 text-[11px] text-[#8d90a2] uppercase tracking-wider"
                >
                  {{ field.name }}
                </th>
                <th class="p-3 w-24"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-[#434656]/30">
              <tr 
                v-for="item in items" 
                :key="item.id" 
                class="hover:bg-[#2d3449]/30 transition-colors group"
              >
                <td class="p-3"><input class="rounded-xs bg-[#0B0E14] border-[#434656] text-[#2E62FF]" type="checkbox"></td>
                <td class="p-3 text-[13px] font-medium text-[#c3c5d8]">{{ item.slug }}</td>
                <td class="p-3">
                  <span 
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold"
                    :class="getStatusClass(item.status)"
                  >
                    {{ item.status }}
                  </span>
                </td>
                <td 
                  v-for="field in activeCollection.fields" 
                  :key="field.key" 
                  class="p-3 text-[13px]"
                >
                  <div v-if="field.type === 'asset'" class="w-8 h-8 rounded bg-[#171f33] overflow-hidden shrink-0">
                    <img v-if="item.data[field.key]" :src="item.data[field.key]" class="w-full h-full object-cover">
                    <div v-else class="w-full h-full flex items-center justify-center text-[10px] text-gray-500">N/A</div>
                  </div>
                  <span v-else class="text-[#dae2fd] truncate max-w-[200px] block">{{ item.data[field.key] || '' }}</span>
                </td>
                <td class="p-3">
                  <div class="flex items-center justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                    <button @click="openEditItemModal(item)" class="p-1 text-[#8d90a2] hover:text-[#2E62FF] transition-colors"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                    <button @click="deleteItem(item)" class="p-1 text-[#8d90a2] hover:text-[#EF4444] transition-colors"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </main>

      <!-- Right Sidebar: Collection Details / Schema -->
      <aside v-if="activeCollection" class="w-[280px] bg-[#161B22] border-l border-[#434656] flex flex-col z-40">
        <div class="p-4 border-b border-[#434656]">
          <div class="text-[12px] font-bold text-white mb-1">Collection Config</div>
          <div class="text-[11px] font-mono text-[#8d90a2] uppercase">Selected: Schema Manager</div>
        </div>
        <div class="p-4 flex flex-col gap-6 overflow-y-auto custom-scrollbar">
          <div class="flex flex-col gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-[11px] font-bold text-[#8d90a2] uppercase">COLLECTION NAME</label>
              <input 
                v-model="activeCollection.name" 
                @change="updateCollectionSchema"
                class="h-7 bg-[#0B0E14] border border-[#434656] rounded text-[13px] px-2 focus:border-[#2E62FF] focus:ring-0 outline-none text-white" 
                type="text"
              >
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[11px] font-bold text-[#8d90a2] uppercase">SLUG</label>
              <input 
                v-model="activeCollection.slug" 
                @change="updateCollectionSchema"
                class="h-7 bg-[#0B0E14] border border-[#434656] rounded font-mono text-[11px] px-2 focus:border-[#2E62FF] focus:ring-0 outline-none text-[#8d90a2]" 
                type="text"
              >
            </div>
          </div>

          <div class="border-t border-[#434656] pt-4">
            <div class="text-[12px] font-bold text-white mb-2">Field Map</div>
            <div class="flex flex-col gap-2">
              <div 
                v-for="field in activeCollection.fields" 
                :key="field.key"
                class="flex items-center justify-between bg-[#171f33] p-2 border border-[#434656] rounded"
              >
                <div class="flex items-center gap-2">
                  <span class="material-symbols-outlined text-[16px] text-[#2E62FF]">
                    {{ getFieldIcon(field.type) }}
                  </span>
                  <span class="text-[13px]">{{ field.name }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <span class="text-[11px] font-mono text-[#8d90a2]">{{ field.type }}</span>
                  <span @click="removeField(field)" class="material-symbols-outlined text-[14px] text-red-500 hover:text-red-400 cursor-pointer">close</span>
                </div>
              </div>
            </div>
          </div>

          <div class="border-t border-[#434656] pt-4">
            <div class="flex items-center justify-between mb-2">
              <div class="text-[12px] font-bold text-white">Data Integration</div>
              <div 
                @click="apiSync = !apiSync"
                class="w-8 h-4 rounded-full relative cursor-pointer transition-colors duration-200"
                :class="apiSync ? 'bg-[#2E62FF]' : 'bg-[#434656]'"
              >
                <div 
                  class="absolute top-0.5 w-3 h-3 bg-white rounded-full transition-all duration-200"
                  :class="apiSync ? 'right-0.5' : 'left-0.5'"
                ></div>
              </div>
            </div>
            <p class="text-[12px] text-[#c3c5d8] leading-relaxed">
              Connected to <span class="text-[#2E62FF] font-bold">API Sync</span>. Updates automatically every 15 minutes.
            </p>
          </div>
        </div>
      </aside>
    </div>

    <!-- Collection Sliding/Modal Forms -->
    <!-- 1. Create Collection Modal -->
    <div v-if="showCollectionModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center">
      <div class="w-[400px] bg-[#161B22] border border-[#434656] rounded p-6 shadow-2xl">
        <h2 class="text-white text-[15px] font-bold mb-4">Create Collection</h2>
        <div class="flex flex-col gap-4">
          <div class="flex flex-col gap-1">
            <label class="text-[11px] text-[#8d90a2] uppercase">Name</label>
            <input v-model="newCol.name" class="h-8 bg-[#0B0E14] border border-[#434656] rounded text-[13px] px-2 text-white outline-none focus:border-[#2E62FF]" type="text" placeholder="Blog Posts">
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-[11px] text-[#8d90a2] uppercase">Slug</label>
            <input v-model="newCol.slug" class="h-8 bg-[#0B0E14] border border-[#434656] rounded text-[13px] px-2 text-white outline-none focus:border-[#2E62FF]" type="text" placeholder="blog-posts">
          </div>
        </div>
        <div class="flex gap-2 justify-end mt-6">
          <button @click="showCollectionModal = false" class="px-4 py-2 border border-[#434656] text-[#c3c5d8] rounded text-[13px] hover:text-white">Cancel</button>
          <button @click="createCollection" class="px-4 py-2 bg-[#2E62FF] text-white rounded text-[13px] hover:brightness-110">Create</button>
        </div>
      </div>
    </div>

    <!-- 2. Add Field Modal -->
    <div v-if="showFieldModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center">
      <div class="w-[400px] bg-[#161B22] border border-[#434656] rounded p-6 shadow-2xl">
        <h2 class="text-white text-[15px] font-bold mb-4">Add Schema Field</h2>
        <div class="flex flex-col gap-4">
          <div class="flex flex-col gap-1">
            <label class="text-[11px] text-[#8d90a2] uppercase">Field Name</label>
            <input v-model="newField.name" class="h-8 bg-[#0B0E14] border border-[#434656] rounded text-[13px] px-2 text-white outline-none focus:border-[#2E62FF]" type="text" placeholder="Author">
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-[11px] text-[#8d90a2] uppercase">Field Type</label>
            <select v-model="newField.type" class="h-8 bg-[#0B0E14] border border-[#434656] rounded text-[13px] px-2 text-white outline-none focus:border-[#2E62FF] focus:ring-0">
              <option value="plain-text">Plain Text</option>
              <option value="rich-text">Rich Text</option>
              <option value="asset">Asset (Image URL)</option>
              <option value="number">Number</option>
              <option value="date">Date</option>
            </select>
          </div>
        </div>
        <div class="flex gap-2 justify-end mt-6">
          <button @click="showFieldModal = false" class="px-4 py-2 border border-[#434656] text-[#c3c5d8] rounded text-[13px] hover:text-white">Cancel</button>
          <button @click="addField" class="px-4 py-2 bg-[#2E62FF] text-white rounded text-[13px] hover:brightness-110">Add</button>
        </div>
      </div>
    </div>

    <!-- 3. New/Edit Item Drawer Modal -->
    <div v-if="showItemDrawer" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex justify-end">
      <div class="w-[450px] h-full bg-[#161B22] border-l border-[#434656] p-6 flex flex-col justify-between shadow-2xl animate-slide-in">
        <div>
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-white text-[16px] font-bold">{{ isEditingItem ? 'Edit Item' : 'New Item' }}</h2>
            <button @click="showItemDrawer = false" class="text-[#8d90a2] hover:text-white">
              <span class="material-symbols-outlined">close</span>
            </button>
          </div>

          <div class="space-y-4 overflow-y-auto max-h-[calc(100vh-180px)] pr-2 custom-scrollbar">
            <div class="flex flex-col gap-1">
              <label class="text-[11px] text-[#8d90a2] uppercase">Slug URL</label>
              <input v-model="itemForm.slug" class="h-8 bg-[#0B0E14] border border-[#434656] rounded text-[13px] px-2 text-white outline-none focus:border-[#2E62FF]" type="text" placeholder="url-slug-key">
            </div>

            <div class="flex flex-col gap-1">
              <label class="text-[11px] text-[#8d90a2] uppercase">Status</label>
              <select v-model="itemForm.status" class="h-8 bg-[#0B0E14] border border-[#434656] rounded text-[13px] px-2 text-white outline-none focus:border-[#2E62FF] focus:ring-0">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
                <option value="scheduled">Scheduled</option>
              </select>
            </div>

            <div v-for="field in activeCollection.fields" :key="field.key" class="flex flex-col gap-1">
              <label class="text-[11px] text-[#8d90a2] uppercase">{{ field.name }} ({{ field.type }})</label>
              <textarea 
                v-if="field.type === 'rich-text'" 
                v-model="itemForm.data[field.key]" 
                class="bg-[#0B0E14] border border-[#434656] rounded text-[13px] p-2 text-white outline-none focus:border-[#2E62FF] h-20"
              ></textarea>
              <input 
                v-else-if="field.type === 'number'"
                type="number"
                v-model="itemForm.data[field.key]"
                class="h-8 bg-[#0B0E14] border border-[#434656] rounded text-[13px] px-2 text-white outline-none focus:border-[#2E62FF]"
              >
              <input 
                v-else-if="field.type === 'date'"
                type="text"
                v-model="itemForm.data[field.key]"
                class="h-8 bg-[#0B0E14] border border-[#434656] rounded text-[13px] px-2 text-white outline-none focus:border-[#2E62FF]"
                placeholder="Oct 12, 2024"
              >
              <div v-else-if="field.type === 'asset'" class="space-y-2">
                <div class="flex gap-2">
                  <input 
                    v-model="itemForm.data[field.key]" 
                    class="h-8 flex-1 bg-[#0B0E14] border border-[#434656] rounded text-[13px] px-2 text-white outline-none focus:border-[#2E62FF]" 
                    type="text"
                    placeholder="https://... or uploaded image"
                  >
                  <label class="h-8 px-3 bg-[#2E62FF] text-white rounded text-xs font-bold flex items-center cursor-pointer">
                    Upload
                    <input type="file" accept="image/*" class="hidden" @change="event => attachItemImage(event, field.key)">
                  </label>
                </div>
                <img v-if="itemForm.data[field.key]" :src="itemForm.data[field.key]" class="w-full h-28 object-cover rounded border border-[#434656]">
              </div>
              <input 
                v-else
                v-model="itemForm.data[field.key]" 
                class="h-8 bg-[#0B0E14] border border-[#434656] rounded text-[13px] px-2 text-white outline-none focus:border-[#2E62FF]" 
                type="text"
              >
            </div>
          </div>
        </div>

        <div class="flex gap-2 justify-end pt-6 border-t border-[#434656]">
          <button @click="showItemDrawer = false" class="px-4 py-2 border border-[#434656] text-[#c3c5d8] rounded text-[13px] hover:text-white">Cancel</button>
          <button @click="saveItem" class="px-6 py-2 bg-[#2E62FF] text-white rounded text-[13px] font-bold hover:brightness-110">Save Item</button>
        </div>
      </div>
    </div>

    <!-- Footer Status Bar -->
    <footer class="bg-[#060e20] border-t border-[#434656] fixed bottom-0 left-0 w-full h-8 flex justify-between items-center px-6 z-40">
      <div class="flex items-center gap-4">
        <span class="text-[11px] text-[#8d90a2]">© 2024 StudioPro Engine</span>
        <div class="flex items-center gap-2">
          <span class="text-[11px] font-mono text-[#8d90a2] hover:text-[#2E62FF] cursor-default transition-colors">
            CMS &gt; {{ activeCollection ? activeCollection.name : 'Select a Collection' }}
          </span>
        </div>
      </div>
      <div class="flex items-center gap-4">
        <span class="text-[11px] font-mono text-[#8d90a2] hover:text-[#2E62FF] transition-colors cursor-default">Zoom 100%</span>
        <div class="flex items-center gap-1 text-[#8d90a2] hover:text-[#2E62FF] transition-colors cursor-default">
          <span class="material-symbols-outlined text-[14px]">help</span>
          <span class="text-[11px] font-mono">Canvas Help</span>
        </div>
      </div>
    </footer>

    <!-- Toast message -->
    <div 
      v-if="toastMessage" 
      class="fixed bottom-12 right-6 z-50 bg-[#2E62FF] border border-[#2E62FF]/50 text-white text-xs font-bold uppercase tracking-wider px-4 py-3 rounded shadow-xl flex items-center gap-2 animate-fade-in"
    >
      <span>✓</span> {{ toastMessage }}
    </div>

    <div v-if="confirmState.open" class="fixed inset-0 z-[60] bg-black/70 backdrop-blur-sm flex items-center justify-center">
      <div class="w-[380px] bg-[#161B22] border border-[#434656] rounded shadow-2xl p-5">
        <div class="flex items-center gap-2 text-[#EF4444] mb-3">
          <span class="material-symbols-outlined">warning</span>
          <h3 class="text-sm font-bold text-white">{{ confirmState.title }}</h3>
        </div>
        <p class="text-sm text-[#c3c5d8] leading-relaxed">{{ confirmState.message }}</p>
        <div class="flex justify-end gap-2 mt-5">
          <button @click="confirmState.open = false" class="px-4 py-2 border border-[#434656] text-[#c3c5d8] rounded text-xs hover:text-white">Cancel</button>
          <button @click="runConfirm" class="px-4 py-2 bg-[#EF4444] text-white rounded text-xs font-bold hover:brightness-110">Delete</button>
        </div>
      </div>
    </div>

    <div v-if="showHelp || showFeedback" class="fixed inset-0 z-[60] bg-black/70 backdrop-blur-sm flex items-center justify-center">
      <div class="w-[420px] bg-[#161B22] border border-[#434656] rounded shadow-2xl p-5">
        <h3 class="text-white text-sm font-bold mb-2">{{ showHelp ? 'CMS Help' : 'Send Feedback' }}</h3>
        <p v-if="showHelp" class="text-sm text-[#c3c5d8] leading-relaxed">Create collections, add fields, then add items. Asset fields accept URLs or uploaded local images.</p>
        <textarea v-else v-model="feedbackText" class="w-full h-28 bg-[#0B0E14] border border-[#434656] rounded text-sm text-white p-2 outline-none" placeholder="What should improve?"></textarea>
        <div class="flex justify-end gap-2 mt-5">
          <button @click="showHelp = false; showFeedback = false" class="px-4 py-2 border border-[#434656] text-[#c3c5d8] rounded text-xs hover:text-white">Close</button>
          <button v-if="showFeedback" @click="showFeedback = false; showToast('Feedback saved locally')" class="px-4 py-2 bg-[#2E62FF] text-white rounded text-xs font-bold">Send</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import TopBar from '../components/editor/TopBar.vue'
import { useWorkspaceStore } from '../stores/workspace'

const route = useRoute()
const store = useWorkspaceStore()
const projectId = route.params.projectId || 1

const collections = ref([])
const activeCollection = ref(null)
const items = ref([])
const loadingItems = ref(false)
const apiSync = ref(true)
const toastMessage = ref('')
const showHelp = ref(false)
const showFeedback = ref(false)
const feedbackText = ref('')
const confirmState = ref({ open: false, title: '', message: '', action: null })

// Modal/Drawer states
const showCollectionModal = ref(false)
const newCol = ref({ name: '', slug: '' })

const showFieldModal = ref(false)
const newField = ref({ name: '', type: 'plain-text', key: '' })

const showItemDrawer = ref(false)
const isEditingItem = ref(false)
const activeItem = ref(null)
const itemForm = ref({ slug: '', data: {}, status: 'draft' })

const showToast = (msg) => {
  toastMessage.value = msg
  setTimeout(() => {
    toastMessage.value = ''
  }, 3000)
}

// Fetch all collections
const loadCollections = async () => {
  try {
    const res = await fetch(`${store.apiBaseUrl}/projects/${projectId}/collections`)
    if (res.ok) {
      collections.value = await res.json()
      // If we don't have active collection, select the first one
      if (collections.value.length && !activeCollection.value) {
        selectCollection(collections.value[0])
      }
    }
  } catch (err) {
    console.error('Failed to load collections', err)
  }
}

const selectCollection = async (collection) => {
  activeCollection.value = collection
  loadingItems.value = true
  items.value = []
  try {
    const res = await fetch(`${store.apiBaseUrl}/collections/${collection.id}/items`)
    if (res.ok) {
      items.value = await res.json()
    }
  } catch (err) {
    console.error('Failed to load items', err)
  } finally {
    loadingItems.value = false
  }
}

// Collections creation
const openNewCollectionModal = () => {
  newCol.value = { name: '', slug: '' }
  showCollectionModal.value = true
}

const createCollection = async () => {
  if (!newCol.value.name || !newCol.value.slug) {
    showToast('Name and Slug required')
    return
  }
  // Initialize with Name field as default
  const startingFields = [
    { name: 'Item Name', type: 'plain-text', key: 'name' }
  ]
  try {
    const res = await fetch(`${store.apiBaseUrl}/projects/${projectId}/collections`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: newCol.value.name,
        slug: newCol.value.slug,
        fields: startingFields
      })
    })
    if (res.ok) {
      showToast('Collection Created')
      showCollectionModal.value = false
      await loadCollections()
      // Select the last created collection
      const newCreated = collections.value.find(c => c.slug === newCol.value.slug)
      if (newCreated) {
        selectCollection(newCreated)
      }
    } else {
      const err = await res.json()
      showToast(err.message || 'Create Failed')
    }
  } catch (err) {
    console.error(err)
    showToast('Network error')
  }
}

const deleteCollection = async (col) => {
  confirmState.value = {
    open: true,
    title: 'Delete Collection',
    message: `Delete ${col.name} collection and all its content?`,
    action: async () => {
  try {
    const res = await fetch(`${store.apiBaseUrl}/projects/${projectId}/collections/${col.id}`, {
      method: 'DELETE'
    })
    if (res.ok) {
      showToast('Collection Deleted')
      if (activeCollection.value && activeCollection.value.id === col.id) {
        activeCollection.value = null
      }
      await loadCollections()
    }
  } catch (err) {
    console.error(err)
  }
    }
  }
}

// Fields CRUD
const openAddFieldModal = () => {
  newField.value = { name: '', type: 'plain-text', key: '' }
  showFieldModal.value = true
}

const addField = async () => {
  if (!newField.value.name) return
  newField.value.key = newField.value.name.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_+|_+$/g, '')
  
  // Append to current fields
  const updatedFields = [...activeCollection.value.fields, { ...newField.value }]
  
  try {
    const res = await fetch(`${store.apiBaseUrl}/projects/${projectId}/collections/${activeCollection.value.id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        ...activeCollection.value,
        fields: updatedFields
      })
    })
    if (res.ok) {
      showToast('Field Added')
      showFieldModal.value = false
      await loadCollections()
      // Reload active collection
      const reloaded = collections.value.find(c => c.id === activeCollection.value.id)
      if (reloaded) {
        selectCollection(reloaded)
      }
    }
  } catch (err) {
    console.error(err)
  }
}

const removeField = async (field) => {
  confirmState.value = {
    open: true,
    title: 'Remove Field',
    message: `Remove field ${field.name}? Item data will stay in saved JSON but disappear from table columns.`,
    action: async () => {
  const updatedFields = activeCollection.value.fields.filter(f => f.key !== field.key)
  try {
    const res = await fetch(`${store.apiBaseUrl}/projects/${projectId}/collections/${activeCollection.value.id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        ...activeCollection.value,
        fields: updatedFields
      })
    })
    if (res.ok) {
      showToast('Field Removed')
      await loadCollections()
      const reloaded = collections.value.find(c => c.id === activeCollection.value.id)
      if (reloaded) {
        selectCollection(reloaded)
      }
    }
  } catch (err) {
    console.error(err)
  }
    }
  }
}

const updateCollectionSchema = async () => {
  try {
    await fetch(`${store.apiBaseUrl}/projects/${projectId}/collections/${activeCollection.value.id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(activeCollection.value)
    })
    showToast('Collection Schema Updated')
  } catch (err) {
    console.error(err)
  }
}

// Items CRUD
const openNewItemModal = () => {
  isEditingItem.value = false
  itemForm.value = { slug: '', data: {}, status: 'draft' }
  // Pre-populate fields keys in data
  activeCollection.value.fields.forEach(f => {
    itemForm.value.data[f.key] = ''
  })
  showItemDrawer.value = true
}

const openEditItemModal = (item) => {
  isEditingItem.value = true
  activeItem.value = item
  itemForm.value = {
    slug: item.slug,
    status: item.status,
    data: { ...item.data }
  }
  showItemDrawer.value = true
}

const saveItem = async () => {
  if (!itemForm.value.slug) {
    showToast('Slug is required')
    return
  }
  try {
    let res
    if (isEditingItem.value) {
      res = await fetch(`${store.apiBaseUrl}/collections/${activeCollection.value.id}/items/${activeItem.value.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(itemForm.value)
      })
    } else {
      res = await fetch(`${store.apiBaseUrl}/collections/${activeCollection.value.id}/items`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(itemForm.value)
      })
    }

    if (res.ok) {
      showToast(isEditingItem.value ? 'Item Updated' : 'Item Added')
      showItemDrawer.value = false
      await selectCollection(activeCollection.value)
      await loadCollections() // Reload counts
    } else {
      const err = await res.json()
      showToast(err.message || 'Operation Failed')
    }
  } catch (err) {
    console.error(err)
    showToast('Sync error')
  }
}

const deleteItem = async (item) => {
  confirmState.value = {
    open: true,
    title: 'Delete Item',
    message: `Delete content item "${item.slug}"?`,
    action: async () => {
  try {
    const res = await fetch(`${store.apiBaseUrl}/collections/${activeCollection.value.id}/items/${item.id}`, {
      method: 'DELETE'
    })
    if (res.ok) {
      showToast('Item Deleted')
      await selectCollection(activeCollection.value)
      await loadCollections() // Reload counts
    }
  } catch (err) {
    console.error(err)
  }
    }
  }
}

const runConfirm = async () => {
  const action = confirmState.value.action
  confirmState.value.open = false
  if (action) await action()
}

const attachItemImage = (event, fieldKey) => {
  const file = event.target.files?.[0]
  if (!file) return
  const reader = new FileReader()
  reader.onload = () => {
    itemForm.value.data[fieldKey] = String(reader.result)
    event.target.value = ''
  }
  reader.readAsDataURL(file)
}

// Helpers
const getStatusClass = (status) => {
  if (status === 'published') return 'bg-emerald-500/10 text-emerald-400'
  if (status === 'draft') return 'bg-amber-500/10 text-amber-400'
  return 'bg-[#2E62FF]/10 text-[#2E62FF]'
}

const getFieldIcon = (type) => {
  if (type === 'plain-text') return 'title'
  if (type === 'rich-text') return 'article'
  if (type === 'asset') return 'image'
  if (type === 'number') return 'tag'
  if (type === 'date') return 'calendar_month'
  return 'title'
}

onMounted(() => {
  loadCollections()
})
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: #161B22;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #434656;
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: #2E62FF;
}

@keyframes slideIn {
  from { transform: translateX(100%); }
  to { transform: translateX(0); }
}
.animate-slide-in {
  animation: slideIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
.animate-fade-in {
  animation: fadeIn 0.2s ease-out forwards;
}
</style>
