<template>
  <div class="h-screen w-full flex flex-col bg-surface text-on-surface overflow-hidden select-none font-geist mesh-bg relative">
    <TopBar />

    <main class="flex-1 flex flex-col px-8 py-6 overflow-hidden min-h-0">
      <div class="max-w-[1600px] mx-auto w-full flex flex-col flex-1 min-h-0 space-y-0">
        
        <!-- Header with Breadcrumbs & Actions -->
        <div class="flex items-end justify-between mb-6 relative shrink-0">
          <!-- Hidden Select is removed -->
          
          <div class="relative z-10 pointer-events-none">
            <div class="flex items-center gap-2 mb-1.5">
              <span class="font-mono text-[11px] text-on-surface-variant uppercase tracking-widest">CMS Manager</span>
              <span class="text-on-surface-variant text-[12px]">/</span>
              <span class="font-mono text-[11px] text-primary uppercase tracking-widest">{{ activeCollection ? activeCollection.name : 'Select Collection' }}</span>
            </div>
            
            <h1 class="text-[32px] font-semibold text-on-surface tracking-tight flex items-center gap-4">
              {{ activeCollection ? activeCollection.name : 'Collections' }}
              <span v-if="activeCollection" class="text-[13px] font-normal text-on-surface-variant px-3 py-1 border border-outline/50 rounded-full">
                {{ items.length }} Items Total
              </span>
            </h1>
          </div>

          <div class="flex items-center gap-4 relative z-30">
            <template v-if="activeCollection">
              <button 
                @click.stop="openAddFieldModal"
                class="px-6 py-3 border border-outline/30 text-on-surface text-[13px] font-medium rounded-xl bg-[#171f33]/40 hover:bg-[#222a3d]/50 transition-colors flex items-center gap-2 backdrop-blur-[12px]"
              >
                <span class="material-symbols-outlined text-[18px]">view_column_2</span>
                Add Field
              </button>
              
              <button 
                @click.stop="openNewItemModal"
                class="px-6 py-3 text-white text-[13px] font-medium rounded-xl hover:opacity-90 transition-all active:scale-95 flex items-center gap-2"
                style="background: linear-gradient(135deg, #2E62FF 0%, #1e40af 100%); box-shadow: 0 4px 15px rgba(46, 98, 255, 0.25);"
              >
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                New Item
              </button>
            </template>
          </div>
        </div>

        <!-- 2 Column Layout -->
        <div class="flex gap-6 flex-1 min-h-0 mb-6">
          <!-- Left Sidebar -->
          <div class="w-72 flex flex-col gap-4 shrink-0">
            <button 
              @click.stop="openNewCollectionModal" 
              class="w-full px-6 py-3 border border-outline/30 text-on-surface text-[13px] font-medium rounded-xl bg-[#171f33]/40 hover:bg-[#222a3d]/50 transition-colors flex items-center justify-center gap-2"
            >
              <span class="material-symbols-outlined text-[18px]">add</span>
              New Collection
            </button>
            
            <div class="flex-1 bg-surface-container/70 backdrop-blur-[12px] border border-outline/30 rounded-3xl p-4 flex flex-col overflow-hidden">
              <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 px-2 mt-2">Collections</div>
              <div class="flex-1 overflow-y-auto custom-scrollbar pr-2 space-y-1">
                <div 
                  v-for="col in collections" 
                  :key="col.id" 
                  @click="activeCollection = col; onCollectionChange()"
                  class="flex items-center justify-between px-4 py-3 rounded-xl cursor-pointer transition-all border group"
                  :class="activeCollection && activeCollection.id === col.id ? 'bg-primary/10 border-primary/50 text-primary' : 'bg-transparent border-transparent text-on-surface-variant hover:bg-white/5 hover:text-on-surface'"
                >
                  <span class="font-medium text-sm truncate max-w-[120px]">{{ col.name }}</span>
                  <div class="flex items-center gap-1.5">
                    <span v-if="activeCollection && activeCollection.id === col.id" class="text-[10px] font-bold bg-primary/20 px-2 rounded-full mr-1">{{ items.length }}</span>
                    <!-- Edit Collection -->
                    <button @click.stop="openEditCollectionModal(col)" class="opacity-0 group-hover:opacity-100 text-on-surface-variant hover:text-primary transition-colors p-1 flex items-center justify-center">
                      <span class="material-symbols-outlined text-[15px]">edit</span>
                    </button>
                    <!-- Delete Collection -->
                    <button @click.stop="deleteCollection(col)" class="opacity-0 group-hover:opacity-100 text-on-surface-variant hover:text-[#EF4444] transition-colors p-1 flex items-center justify-center">
                      <span class="material-symbols-outlined text-[15px]">delete</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

        <!-- Data Table Container -->
        <div class="rounded-3xl overflow-hidden bg-surface-container/70 backdrop-blur-[12px] border border-outline/30 flex flex-col flex-1 min-h-0">
          <div class="flex-1 overflow-auto custom-scrollbar">
            <div v-if="loadingItems" class="p-20 flex flex-col items-center justify-center gap-4 text-on-surface-variant">
              <span class="material-symbols-outlined text-4xl animate-spin">refresh</span>
              <span class="text-sm font-bold">Loading collection items...</span>
            </div>
            
            <div v-else-if="!activeCollection" class="p-24 flex flex-col items-center justify-center gap-4 text-on-surface-variant min-h-[350px]">
              <span class="material-symbols-outlined text-5xl opacity-30 text-primary">database</span>
              <span class="text-sm font-bold text-on-surface-variant">Select a collection above to manage database.</span>
            </div>

            <div v-else-if="items.length === 0" class="p-24 flex flex-col items-center justify-center gap-4 text-on-surface-variant min-h-[350px]">
              <span class="material-symbols-outlined text-5xl opacity-30">post_add</span>
              <span class="text-sm font-bold text-on-surface-variant">No content items found.</span>
              <button @click="openNewItemModal" class="mt-2 px-6 py-3 text-white text-[13px] font-medium rounded-xl hover:opacity-90 transition-all" style="background: linear-gradient(135deg, #2E62FF 0%, #1e40af 100%); box-shadow: 0 4px 15px rgba(46, 98, 255, 0.25);">Create First Item</button>
            </div>

            <table v-else class="w-full text-left border-collapse min-w-[900px]">
              <thead>
                <tr class="border-b border-outline/30 bg-surface-container-light/10">
                  <th class="p-6 w-16"><input class="appearance-none rounded-full w-[18px] h-[18px] border-2 border-outline bg-transparent text-primary checked:bg-primary checked:border-primary" type="checkbox"></th>
                  <th class="p-6 text-[12px] font-semibold text-on-surface-variant uppercase tracking-wider">Item Name</th>
                  <th class="p-6 text-[12px] font-semibold text-on-surface-variant uppercase tracking-wider">Slug</th>
                  <th class="p-6 text-[12px] font-semibold text-on-surface-variant uppercase tracking-wider">Status</th>
                  
                  <th 
                    v-for="field in dynamicFields" 
                    :key="field.key"
                    class="p-6 text-[12px] font-semibold text-on-surface-variant uppercase tracking-wider"
                  >
                    {{ field.name }}
                  </th>
                  <th class="p-6 w-20"></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#434656]/30">
                <tr 
                  v-for="item in items" 
                  :key="item.id" 
                  class="hover:bg-[#2d3449]/30 transition-colors group"
                >
                  <td class="p-6"><input class="appearance-none rounded-full w-[18px] h-[18px] border-2 border-outline bg-transparent text-primary cursor-pointer" type="checkbox"></td>
                  <td class="p-6">
                    <div class="flex items-center gap-4">
                      <div v-if="item.data.image || item.data.thumbnail" class="w-12 h-12 rounded-xl bg-[#171f33] overflow-hidden shrink-0 border border-outline/20">
                        <img :src="item.data.image || item.data.thumbnail" class="w-full h-full object-cover">
                      </div>
                      <div v-else class="w-12 h-12 rounded-xl bg-[#171f33] flex items-center justify-center shrink-0 border border-outline/20">
                        <span class="material-symbols-outlined text-[20px] text-[#434656]">article</span>
                      </div>
                      <span class="text-[15px] text-on-surface font-medium">{{ item.data.name || item.data.title || 'Unnamed Item' }}</span>
                    </div>
                  </td>
                  <td class="p-6 font-mono text-[12px] text-on-surface-variant">/{{ item.slug }}</td>
                  <td class="p-6">
                    <span 
                      class="inline-flex items-center px-4 py-1.5 rounded-full text-[12px] font-medium border capitalize"
                      :class="getStatusClass(item.status)"
                    >
                      {{ item.status }}
                    </span>
                  </td>
                  
                  <td 
                    v-for="field in dynamicFields" 
                    :key="field.key" 
                    class="p-6 text-[14px] text-on-surface-variant"
                  >
                    <!-- Display Category field specifically styled if key matches 'category' -->
                    <span v-if="field.key.toLowerCase() === 'category'" class="px-4 py-1.5 bg-[#222a3d] rounded-full text-[12px] font-medium text-on-surface border border-outline/30 block w-max">
                      {{ item.data[field.key] || 'Uncategorized' }}
                    </span>
                    <div v-else-if="field.type === 'asset'" class="truncate max-w-[200px] text-on-surface-variant">
                      <span class="material-symbols-outlined text-[16px] align-middle mr-1">image</span> Asset
                    </div>
                    <span v-else class="truncate max-w-[200px] block">{{ item.data[field.key] || '' }}</span>
                  </td>

                  <td class="p-6">
                    <div class="flex items-center justify-end opacity-0 group-hover:opacity-100 transition-opacity gap-2">
                      <button @click="openEditItemModal(item)" class="p-2 text-on-surface-variant hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                      <button @click="deleteItem(item)" class="p-2 text-on-surface-variant hover:text-[#EF4444] transition-colors"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          </div>
        </div>
      </div>
    </main>

    <Footer />
    <!-- MODALS & DRAWERS -->
    
    <!-- 1. Create Collection Modal -->
    <div v-if="showCollectionModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
      <div class="w-[400px] bg-surface-container border border-white/10 rounded-2xl p-6 shadow-2xl space-y-4">
        <h2 class="text-white text-base font-bold tracking-tight">Create Collection</h2>
        <div class="space-y-3">
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Collection Name</label>
            <input v-model="newCol.name" class="h-9 bg-editor-bg border border-white/10 rounded-xl text-xs px-3 text-white outline-none focus:border-primary" type="text" placeholder="e.g. Products">
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Slug Key</label>
            <input v-model="newCol.slug" class="h-9 bg-editor-bg border border-white/10 rounded-xl text-xs px-3 text-white outline-none focus:border-primary" type="text" placeholder="e.g. products">
          </div>
        </div>
        <div class="flex gap-2 justify-end pt-2">
          <button @click="showCollectionModal = false" class="px-4 py-2 border border-white/10 text-on-surface-variant rounded-xl text-xs font-semibold hover:text-white transition-colors">Cancel</button>
          <button @click="createCollection" class="px-5 py-2 bg-primary text-white rounded-xl text-xs font-bold hover:brightness-110 shadow-lg shadow-primary/20 active:scale-95 transition-all">Create</button>
        </div>
      </div>
    </div>

    <!-- Edit Collection Modal -->
    <div v-if="showEditCollectionModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
      <div class="w-[400px] bg-surface-container border border-white/10 rounded-2xl p-6 shadow-2xl space-y-4 font-geist">
        <h2 class="text-white text-base font-bold tracking-tight">Edit Collection</h2>
        <div class="space-y-3">
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Collection Name</label>
            <input v-model="editingCol.name" class="h-9 bg-editor-bg border border-white/10 rounded-xl text-xs px-3 text-white outline-none focus:border-primary" type="text">
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Slug Key</label>
            <input v-model="editingCol.slug" class="h-9 bg-editor-bg border border-white/10 rounded-xl text-xs px-3 text-white outline-none focus:border-primary" type="text">
          </div>
        </div>
        <div class="flex gap-2 justify-end pt-2">
          <button @click="showEditCollectionModal = false" class="px-4 py-2 border border-white/10 text-on-surface-variant rounded-xl text-xs font-semibold hover:text-white transition-colors">Cancel</button>
          <button @click="saveCollectionEdit" class="px-5 py-2 bg-primary text-white rounded-xl text-xs font-bold hover:brightness-110 shadow-lg shadow-primary/20 active:scale-95 transition-all">Save Changes</button>
        </div>
      </div>
    </div>

    <!-- 2. Add Field Modal -->
    <div v-if="showFieldModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
      <div class="w-[400px] bg-surface-container border border-white/10 rounded-2xl p-6 shadow-2xl space-y-4">
        <h2 class="text-white text-base font-bold tracking-tight">Add Schema Field</h2>
        <div class="space-y-3">
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Field Name</label>
            <input v-model="newField.name" class="h-9 bg-editor-bg border border-white/10 rounded-xl text-xs px-3 text-white outline-none focus:border-primary" type="text" placeholder="e.g. Price">
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Field Type</label>
            <select v-model="newField.type" class="h-9 bg-editor-bg border border-white/10 rounded-xl text-xs px-3 text-white outline-none focus:border-primary focus:ring-0 cursor-pointer">
              <option value="plain-text">Plain Text</option>
              <option value="rich-text">Rich Text</option>
              <option value="asset">Asset (Image URL)</option>
              <option value="number">Number</option>
              <option value="date">Date</option>
            </select>
          </div>
        </div>
        <div class="flex gap-2 justify-end pt-2">
          <button @click="showFieldModal = false" class="px-4 py-2 border border-white/10 text-on-surface-variant rounded-xl text-xs font-semibold hover:text-white transition-colors">Cancel</button>
          <button @click="addField" class="px-5 py-2 bg-primary text-white rounded-xl text-xs font-bold hover:brightness-110 shadow-lg shadow-primary/20 active:scale-95 transition-all">Add Field</button>
        </div>
      </div>
    </div>

    <!-- 3. New/Edit Item Drawer Modal -->
    <div v-if="showItemDrawer" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex justify-end">
      <div class="w-[450px] h-full bg-surface-container border-l border-white/10 p-6 flex flex-col justify-between shadow-2xl animate-slide-in">
        <div>
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-white text-base font-bold tracking-tight">{{ isEditingItem ? 'Edit Item' : 'New Item' }}</h2>
            <button @click="showItemDrawer = false" class="text-on-surface-variant hover:text-white">
              <span class="material-symbols-outlined">close</span>
            </button>
          </div>

          <div class="space-y-4 overflow-y-auto max-h-[calc(100vh-180px)] pr-2 custom-scrollbar">
            <div class="flex flex-col gap-1.5">
              <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Slug URL</label>
              <input v-model="itemForm.slug" class="h-9 bg-editor-bg border border-white/10 rounded-xl text-xs px-3 text-white outline-none focus:border-primary" type="text" placeholder="url-slug-key">
            </div>

            <div class="flex flex-col gap-1.5">
              <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Status</label>
              <select v-model="itemForm.status" class="h-9 bg-editor-bg border border-white/10 rounded-xl text-xs px-3 text-white outline-none focus:border-primary focus:ring-0 cursor-pointer">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
                <option value="scheduled">Scheduled</option>
              </select>
            </div>

            <div v-for="field in activeCollection.fields" :key="field.key" class="flex flex-col gap-1.5">
              <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">{{ field.name }} ({{ field.type }})</label>
              <textarea 
                v-if="field.type === 'rich-text'" 
                v-model="itemForm.data[field.key]" 
                class="bg-editor-bg border border-white/10 rounded-xl text-xs p-3 text-white outline-none focus:border-primary h-20 resize-none"
              ></textarea>
              <input 
                v-else-if="field.type === 'number'"
                type="number"
                v-model.number="itemForm.data[field.key]"
                class="h-9 bg-editor-bg border border-white/10 rounded-xl text-xs px-3 text-white outline-none focus:border-primary"
              >
              <input 
                v-else-if="field.type === 'date'"
                type="text"
                v-model="itemForm.data[field.key]"
                class="h-9 bg-editor-bg border border-white/10 rounded-xl text-xs px-3 text-white outline-none focus:border-primary"
                placeholder="Oct 12, 2024"
              >
              <div v-else-if="field.type === 'asset'" class="space-y-2">
                <div class="flex gap-2">
                  <input 
                    v-model="itemForm.data[field.key]" 
                    class="h-9 flex-1 bg-editor-bg border border-white/10 rounded-xl text-xs px-3 text-white outline-none focus:border-primary" 
                    type="text"
                    placeholder="https://... or upload local"
                  >
                  <label class="h-9 px-4 bg-primary text-white rounded-xl text-xs font-bold flex items-center justify-center cursor-pointer hover:brightness-110 transition-all select-none">
                    Upload
                    <input type="file" accept="image/*" class="hidden" @change="event => attachItemImage(event, field.key)">
                  </label>
                </div>
                <img v-if="itemForm.data[field.key]" :src="itemForm.data[field.key]" class="w-full h-28 object-cover rounded-xl border border-white/10">
              </div>
              <input 
                v-else
                v-model="itemForm.data[field.key]" 
                class="h-9 bg-editor-bg border border-white/10 rounded-xl text-xs px-3 text-white outline-none focus:border-primary" 
                type="text"
              >
            </div>
          </div>
        </div>

        <div class="flex gap-2 justify-end pt-6 border-t border-white/10">
          <button @click="showItemDrawer = false" class="px-4 py-2 border border-white/10 text-on-surface-variant rounded-xl text-xs font-semibold hover:text-white transition-colors">Cancel</button>
          <button @click="saveItem" class="px-6 py-2 bg-primary text-white rounded-xl text-xs font-bold hover:brightness-110 shadow-lg shadow-primary/20 active:scale-95 transition-all">Save Item</button>
        </div>
      </div>
    </div>

    <!-- 4. Schema Manager Modal -->
    <div v-if="showSchemaModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
      <div class="w-[500px] bg-surface-container border border-white/10 rounded-2xl p-6 shadow-2xl space-y-4">
        <div class="flex justify-between items-center">
          <h2 class="text-white text-base font-bold tracking-tight">Collection Schema Settings</h2>
          <button @click="showSchemaModal = false" class="text-on-surface-variant hover:text-white"><span class="material-symbols-outlined">close</span></button>
        </div>

        <div class="space-y-4 max-h-[350px] overflow-y-auto custom-scrollbar pr-1">
          <p class="text-xs text-gray-500">Configure collection fields. Items in database hold data mapped to these fields keys.</p>
          <div class="divide-y divide-white/5 border border-white/10 rounded-xl overflow-hidden bg-editor-bg">
            <div v-for="field in activeCollection.fields" :key="field.key" class="p-3 flex items-center justify-between">
              <div>
                <div class="text-xs font-bold text-white">{{ field.name }}</div>
                <div class="text-[10px] font-mono text-gray-500 uppercase tracking-widest mt-0.5">{{ field.key }} • {{ field.type }}</div>
              </div>
              <button 
                v-if="field.key !== 'name'"
                @click="removeField(field)" 
                class="p-1 hover:bg-white/5 border border-transparent hover:border-red-500/20 rounded text-red-400 hover:text-red-300 transition-all"
                title="Remove field"
              >
                <span class="material-symbols-outlined text-[16px]">remove_circle</span>
              </button>
            </div>
          </div>
        </div>

        <div class="flex gap-2 justify-end pt-2">
          <button @click="showSchemaModal = false" class="px-5 py-2 bg-primary text-white rounded-xl text-xs font-bold hover:brightness-110 transition-all shadow-lg active:scale-95">Done</button>
        </div>
      </div>
    </div>

    <!-- Confirm Modal -->
    <div v-if="confirmState.open" class="fixed inset-0 z-[60] bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
      <div class="w-[380px] bg-surface-container border border-white/10 rounded-2xl shadow-2xl p-5 space-y-4">
        <div class="flex items-center gap-2.5 text-[#EF4444]">
          <span class="material-symbols-outlined">warning</span>
          <h3 class="text-sm font-bold text-white tracking-tight">{{ confirmState.title }}</h3>
        </div>
        <p class="text-xs text-on-surface-variant leading-relaxed">{{ confirmState.message }}</p>
        <div class="flex justify-end gap-2 pt-2">
          <button @click="confirmState.open = false" class="px-4 py-2 border border-white/10 text-on-surface-variant rounded-xl text-xs font-semibold hover:text-white transition-colors">Cancel</button>
          <button @click="runConfirm" class="px-4 py-2 bg-[#EF4444] text-white rounded-xl text-xs font-bold hover:brightness-110 shadow-lg shadow-red-500/25 active:scale-95 transition-all">Delete</button>
        </div>
      </div>
    </div>

    <!-- Help Modal (Documentation) -->
    <div v-if="showHelp || showFeedback" class="fixed inset-0 z-[60] bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
      <div class="w-[420px] bg-surface-container border border-white/10 rounded-2xl shadow-2xl p-5 space-y-3">
        <h3 class="text-white text-sm font-bold tracking-tight">{{ showHelp ? 'CMS Help' : 'Send Feedback' }}</h3>
        <p v-if="showHelp" class="text-xs text-on-surface-variant leading-relaxed">
          Create collections, configure fields (schema), and manage items. Asset fields hold image URLs or uploaded assets. Bound items connect directly to the editor's Dynamic List blocks.
        </p>
        <textarea v-else v-model="feedbackText" class="w-full h-28 bg-editor-bg border border-white/10 rounded-xl text-xs text-white p-3 outline-none focus:border-primary resize-none" placeholder="What should improve?"></textarea>
        <div class="flex justify-end gap-2 pt-2">
          <button @click="showHelp = false; showFeedback = false" class="px-4 py-2 border border-white/10 text-on-surface-variant rounded-xl text-xs font-semibold hover:text-white transition-colors">Close</button>
          <button v-if="showFeedback" @click="showFeedback = false; showToast('Feedback saved locally')" class="px-4 py-2 bg-primary text-white rounded-xl text-xs font-bold hover:brightness-110 shadow-lg shadow-primary/20 active:scale-95 transition-all">Send</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import TopBar from '../components/editor/TopBar.vue'
import Footer from '../components/Footer.vue'
import { useWorkspaceStore } from '../stores/workspace'

const route = useRoute()
const store = useWorkspaceStore()
const projectId = route.params.projectId || 1

const collections = ref([])
const activeCollection = ref(null)
const items = ref([])
const loadingItems = ref(false)
const toastMessage = ref('')
const showHelp = ref(false)
const showFeedback = ref(false)
const feedbackText = ref('')
const confirmState = ref({ open: false, title: '', message: '', action: null })

// Modal/Drawer states
const showCollectionModal = ref(false)
const newCol = ref({ name: '', slug: '' })

const showEditCollectionModal = ref(false)
const editingCol = ref({ id: null, name: '', slug: '' })

const showFieldModal = ref(false)
const newField = ref({ name: '', type: 'plain-text', key: '' })

const showSchemaModal = ref(false)
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

// Compute dynamic fields without the standard Item Name (name) field to display in table columns
const dynamicFields = computed(() => {
  if (!activeCollection.value || !activeCollection.value.fields) return []
  return activeCollection.value.fields.filter(f => f.key !== 'name')
})

const onCollectionChange = () => {
  if (activeCollection.value) {
    selectCollection(activeCollection.value)
  } else {
    items.value = []
  }
}

// Fetch all collections
const loadCollections = async () => {
  try {
    const res = await fetch(`${store.apiBaseUrl}/projects/${projectId}/collections`)
    if (res.ok) {
      collections.value = await res.json()
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

const openEditCollectionModal = (col) => {
  editingCol.value = { id: col.id, name: col.name, slug: col.slug }
  showEditCollectionModal.value = true
}

const saveCollectionEdit = async () => {
  if (!editingCol.value.name || !editingCol.value.slug) {
    showToast('Name and Slug required')
    return
  }
  try {
    const res = await fetch(`${store.apiBaseUrl}/projects/${projectId}/collections/${editingCol.value.id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: editingCol.value.name,
        slug: editingCol.value.slug
      })
    })
    if (res.ok) {
      showToast('Collection Updated')
      showEditCollectionModal.value = false
      await loadCollections()
      if (activeCollection.value && activeCollection.value.id === editingCol.value.id) {
        const updated = collections.value.find(c => c.id === editingCol.value.id)
        if (updated) activeCollection.value = updated
      }
    } else {
      const err = await res.json()
      showToast(err.message || 'Update Failed')
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

// Items CRUD
const openNewItemModal = () => {
  isEditingItem.value = false
  itemForm.value = { slug: '', data: {}, status: 'draft' }
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
      await loadCollections()
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
          await loadCollections()
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

const attachItemImage = async (event, fieldKey) => {
  const file = event.target.files?.[0]
  if (!file) return
  const formData = new FormData()
  formData.append('file', file)

  try {
    const response = await fetch(`${store.apiBaseUrl}/projects/${projectId}/assets`, {
      method: 'POST',
      body: formData
    })
    if (response.ok) {
      const asset = await response.json()
      itemForm.value.data[fieldKey] = asset.url
      showToast('Image uploaded and linked')
    } else {
      showToast('Image upload failed')
    }
  } catch (err) {
    console.error('Upload error:', err)
    showToast('Upload error')
  } finally {
    event.target.value = ''
  }
}

// Helpers
const getStatusClass = (status) => {
  if (status === 'published') return 'bg-transparent text-[#10B981] border-[#10B981]/40'
  if (status === 'draft') return 'bg-transparent text-[#F59E0B] border-[#F59E0B]/40'
  return 'bg-transparent text-primary border-primary/40'
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
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: rgba(255,255,255,0.1);
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

.mesh-bg {
  background-color: #0b1326;
  background-image: 
    radial-gradient(at 0% 0%, rgba(46, 98, 255, 0.1) 0px, transparent 50%),
    radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.08) 0px, transparent 50%);
}

.glass-panel {
  background: rgba(23, 31, 51, 0.4);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(141, 144, 162, 0.15);
}

.animate-pulse-subtle {
  animation: pulse-subtle 2s infinite;
}
@keyframes pulse-subtle {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.85; }
}
</style>
