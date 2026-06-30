<template>
  <div class="h-screen w-full flex flex-col bg-surface text-on-surface overflow-hidden select-none font-geist mesh-bg relative" @click="activeMenuId = null">
    <TopBar />

    <main class="flex-1 overflow-y-auto custom-scrollbar bg-surface relative">

      <div class="max-w-[1280px] mx-auto px-8 pt-8 pb-16 relative z-10 flex flex-col min-h-0 h-full gap-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-2">
              <span class="text-xs font-bold text-[#10B981] uppercase tracking-widest">Commerce</span>
            </div>
            <h1 class="text-[28px] font-bold text-white mb-1 tracking-tight">Product Inventory</h1>
            <p class="text-[13px] text-on-surface-variant max-w-xl">
              Manage your product catalog, track stock levels, and monitor inventory performance.
            </p>
          </div>
          <div class="flex items-center gap-3">
            <button @click="exportCSV" class="px-4 py-2.5 border border-outline text-on-surface rounded-xl text-sm font-medium flex items-center gap-2 hover:bg-white/5 transition-colors">
              <span class="material-symbols-outlined text-[16px]">download</span>
              Export CSV
            </button>
            <button @click="openAddModal" class="text-white px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 shadow-lg shadow-primary/25 active:scale-95 transition-all" style="background: linear-gradient(135deg, #2E62FF 0%, #1a4cd4 100%);">
              <span class="material-symbols-outlined text-[18px]">add</span>
              Add Product
            </button>
          </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="stat-card p-4 rounded-2xl">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-primary/15 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-[16px]">inventory_2</span>
              </div>
              <div>
                <span class="text-2xl font-black text-white block">{{ filteredProducts.length }}</span>
                <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Products</p>
              </div>
            </div>
          </div>
          <div class="stat-card p-4 rounded-2xl">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-[#10B981]/15 flex items-center justify-center">
                <span class="material-symbols-outlined text-[#10B981] text-[16px]">check_circle</span>
              </div>
              <div>
                <span class="text-2xl font-black text-white block">{{ inStockCount }}</span>
                <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">In Stock</p>
              </div>
            </div>
          </div>
          <div class="stat-card p-4 rounded-2xl">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-[#F59E0B]/15 flex items-center justify-center">
                <span class="material-symbols-outlined text-[#F59E0B] text-[16px]">warning</span>
              </div>
              <div>
                <span class="text-2xl font-black text-white block">{{ lowStockCount }}</span>
                <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Low Stock</p>
              </div>
            </div>
          </div>
          <div class="stat-card p-4 rounded-2xl">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-[#8B5CF6]/15 flex items-center justify-center">
                <span class="material-symbols-outlined text-[#8B5CF6] text-[16px]">payments</span>
              </div>
              <div>
                <span class="text-2xl font-black text-white block">${{ catalogValue }}</span>
                <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Catalog Value</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Search & Filters (Glassmorphic) -->
        <div class="bg-[#1f2937]/40 backdrop-blur-[12px] border border-[#334155]/50 p-3 rounded-2xl flex flex-wrap items-center gap-3">
          <div class="flex-1 min-w-[300px] relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            <input v-model="searchQuery" class="w-full bg-editor-bg border border-outline rounded-xl pl-12 pr-3 py-3 text-[14px] focus:border-primary focus:ring-1 focus:ring-[#2E62FF] transition-all outline-none" placeholder="Search inventory, SKUs, or categories..." type="text"/>
          </div>
          <div class="flex items-center gap-2">
            <div class="relative group">
              <select v-model="selectedStatus" class="appearance-none bg-[#222a3d] border border-outline px-3 py-3 rounded-xl text-[12px] font-medium flex items-center gap-2 hover:border-[#8d90a2] transition-colors pr-8 outline-none cursor-pointer">
                <option value="all">Status: All Assets</option>
                <option value="instock">Status: In Stock</option>
                <option value="lowstock">Status: Low Stock</option>
                <option value="outofstock">Status: Out of Stock</option>
              </select>
              <span class="absolute right-2 top-1/2 -translate-y-1/2 material-symbols-outlined text-[14px] text-on-surface-variant pointer-events-none">expand_more</span>
            </div>
            <div class="relative group">
              <select v-model="selectedCategory" class="appearance-none bg-[#222a3d] border border-outline px-3 py-3 rounded-xl text-[12px] font-medium flex items-center gap-2 hover:border-[#8d90a2] transition-colors pr-8 outline-none cursor-pointer">
                <option value="all">Category: All</option>
                <option v-for="cat in categories" :key="cat" :value="cat">Category: {{ cat }}</option>
              </select>
              <span class="absolute right-2 top-1/2 -translate-y-1/2 material-symbols-outlined text-[14px] text-on-surface-variant pointer-events-none">expand_more</span>
            </div>
            <div class="relative group">
              <select v-model="currentSort" class="appearance-none bg-[#222a3d] border border-outline px-3 py-3 rounded-xl text-[12px] font-medium flex items-center gap-2 hover:border-[#8d90a2] transition-colors pr-8 outline-none cursor-pointer">
                <option value="newest">Recently Added</option>
                <option value="price-low">Price Low-High</option>
                <option value="price-high">Price High-Low</option>
                <option value="name-asc">Name A-Z</option>
                <option value="sales-desc">Sales High-Low</option>
              </select>
              <span class="absolute right-2 top-1/2 -translate-y-1/2 material-symbols-outlined text-[14px] text-on-surface-variant pointer-events-none">sort</span>
            </div>
            <button @click="exportCSV" class="p-3 border border-outline rounded-xl hover:bg-[#222a3d] transition-colors">
              <span class="material-symbols-outlined">cloud_download</span>
            </button>
          </div>
        </div>

        <!-- Data Table (Premium Density) -->
        <div class="bg-surface-container rounded-2xl border border-outline overflow-hidden shadow-2xl flex flex-col flex-1 min-h-[400px]">
          <div class="flex-1 overflow-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
              <thead class="bg-surface-container-light border-b border-outline">
                <tr>
                  <th class="px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.05em] text-on-surface-variant">Product</th>
                  <th class="px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.05em] text-on-surface-variant">SKU</th>
                  <th class="px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.05em] text-on-surface-variant">Category</th>
                  <th class="px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.05em] text-on-surface-variant">Price</th>
                  <th class="px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.05em] text-on-surface-variant">Status</th>
                  <th class="px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.05em] text-on-surface-variant">Stock</th>
                  <th class="px-6 py-4"></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#434656]/30">
                <tr v-if="loading">
                  <td colspan="7" class="p-16 text-center text-on-surface-variant text-sm font-bold bg-transparent">
                    <span class="material-symbols-outlined text-4xl animate-spin mb-3">refresh</span><br/>
                    Loading products list...
                  </td>
                </tr>
                <tr v-else-if="paginatedProducts.length === 0">
                  <td colspan="7" class="p-16 text-center text-on-surface-variant text-sm font-bold bg-transparent">
                    <span class="material-symbols-outlined text-4xl opacity-50 mb-3">inventory_2</span><br/>
                    No product inventory found.
                  </td>
                </tr>
                <tr v-else v-for="product in paginatedProducts" :key="product.id" class="hover:bg-[#2d3449]/50 transition-colors group">
                  <td class="px-6 py-3">
                    <div class="flex items-center gap-3">
                      <div class="w-12 h-12 rounded-lg bg-[#2d3449] overflow-hidden border border-outline flex-shrink-0">
                        <img class="w-full h-full object-cover" :src="getProductImage(product)" />
                      </div>
                      <div>
                        <div class="text-[14px] font-bold text-white">{{ product.title }}</div>
                        <div class="text-[11px] font-semibold tracking-[0.05em] text-on-surface-variant mt-0.5">{{ product.category || 'General' }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-3 text-[11px] font-mono text-on-surface-variant">
                    {{ product.sku || '—' }}
                  </td>
                  <td class="px-6 py-3">
                    <span v-if="product.category" class="text-[11px] font-semibold text-on-surface-variant bg-white/5 px-2 py-0.5 rounded-full">{{ product.category }}</span>
                    <span v-else class="text-[11px] text-on-surface-variant">—</span>
                  </td>
                  <td class="px-6 py-3 text-[14px] text-white font-semibold">
                    ${{ Number(product.price).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                  </td>
                  <td class="px-6 py-3">
                    <span 
                      class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[11px] font-bold"
                      :class="getInventoryClass(product).text"
                    >
                      <span class="w-1.5 h-1.5 rounded-full" :class="getInventoryClass(product).dot"></span>
                      {{ getInventoryLabel(product) }}
                    </span>
                  </td>
                  <td class="px-6 py-3 text-[14px] text-on-surface">
                    <span class="font-semibold">{{ product.inventory_quantity }}</span> <span class="text-[11px] text-on-surface-variant">units</span>
                  </td>
                  <td class="px-6 py-3 text-right relative">
                    <button @click.stop="activeMenuId = activeMenuId === product.id ? null : product.id" class="p-2 text-on-surface-variant opacity-0 group-hover:opacity-100 hover:text-white transition-all cursor-pointer">
                      <span class="material-symbols-outlined text-[20px]">more_horiz</span>
                    </button>
                    
                    <div v-if="activeMenuId === product.id" class="absolute right-12 top-8 w-32 bg-editor-bg border border-outline/50 rounded-xl shadow-xl z-50 py-1 text-left">
                      <button @click.stop="openEditModal(product)" class="w-full px-4 py-2 text-xs font-bold text-white hover:bg-primary transition text-left flex items-center gap-2 cursor-pointer">
                        <span class="material-symbols-outlined text-[14px]">edit</span> Edit
                      </button>
                      <button @click.stop="deleteProduct(product.id)" class="w-full px-4 py-2 text-xs font-bold text-[#EF4444] hover:bg-[#EF4444] hover:text-white transition text-left flex items-center gap-2 cursor-pointer">
                        <span class="material-symbols-outlined text-[14px]">delete</span> Delete
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <!-- Table Footer Pagination (Matching the code.html design) -->
          <div class="bg-surface-container-light px-6 py-3 flex items-center justify-between border-t border-outline shrink-0">
            <span class="text-[11px] font-semibold tracking-[0.05em] text-on-surface-variant">Showing {{ Math.min(1 + (currentPage - 1) * 10, filteredProducts.length) }}–{{ Math.min(currentPage * 10, filteredProducts.length) }} of {{ filteredProducts.length }} products</span>
            <div class="flex items-center gap-2">
              <button @click="prevPage" :disabled="currentPage === 1" class="p-2 rounded border border-outline hover:bg-[#2d3449] transition-colors disabled:opacity-30 cursor-pointer flex items-center justify-center">
                <span class="material-symbols-outlined text-[14px]">chevron_left</span>
              </button>
              <div class="flex items-center gap-1">
                <span class="px-3 py-1 bg-primary/10 text-primary rounded text-[11px] font-bold">{{ currentPage }}</span>
                <span v-if="currentPage < totalPages" class="px-3 py-1 hover:bg-[#2d3449] rounded text-[11px] cursor-pointer transition-colors" @click="nextPage">{{ currentPage + 1 }}</span>
                <span v-if="currentPage < totalPages - 1" class="px-3 py-1 hover:bg-[#2d3449] rounded text-[11px] cursor-pointer transition-colors" @click="currentPage = currentPage + 2; nextPage();">{{ currentPage + 2 }}</span>
              </div>
              <button @click="nextPage" :disabled="currentPage === totalPages" class="p-2 rounded border border-outline hover:bg-[#2d3449] transition-colors disabled:opacity-30 cursor-pointer flex items-center justify-center">
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <Footer />

    <!-- Add/Edit Product Modal Dialog -->
    <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
      <div class="w-[500px] bg-surface-container border border-white/10 p-6 rounded-2xl shadow-2xl relative flex flex-col gap-4 font-geist">
        <div class="flex justify-between items-center pb-2 border-b border-white/10">
          <h3 class="text-sm font-bold text-white uppercase tracking-wider">{{ isEditing ? 'Edit Product' : 'Add Product' }}</h3>
          <button @click="isModalOpen = false" class="text-on-surface-variant hover:text-white cursor-pointer">
            <span class="material-symbols-outlined text-sm">close</span>
          </button>
        </div>
        
        <div class="space-y-4 overflow-y-auto max-h-[calc(100vh-180px)] pr-1 custom-scrollbar">
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Product Name *</label>
            <input v-model="currentProduct.title" type="text" class="bg-editor-bg border border-white/10 rounded-xl py-2 px-3 text-xs text-white focus:border-primary focus:ring-1 focus:ring-[#2E62FF] outline-none" placeholder="e.g. Ergonomic Chair" />
          </div>

          <div class="flex gap-4">
            <div class="flex-1 flex flex-col gap-1.5">
              <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">SKU</label>
              <input v-model="currentProduct.sku" type="text" class="bg-editor-bg border border-white/10 rounded-xl py-2 px-3 text-xs text-white focus:border-primary focus:ring-1 focus:ring-[#2E62FF] outline-none" placeholder="e.g. FURN-OAK-01" />
            </div>
            <div class="flex-1 flex flex-col gap-1.5">
              <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Category</label>
              <input v-model="currentProduct.category" type="text" class="bg-editor-bg border border-white/10 rounded-xl py-2 px-3 text-xs text-white focus:border-primary focus:ring-1 focus:ring-[#2E62FF] outline-none" placeholder="e.g. Furniture" />
            </div>
          </div>

          <div class="flex gap-4">
            <div class="flex-1 flex flex-col gap-1.5">
              <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Price ($) *</label>
              <input v-model.number="currentProduct.price" type="number" step="0.01" class="bg-editor-bg border border-white/10 rounded-xl py-2 px-3 text-xs text-white focus:border-primary focus:ring-1 focus:ring-[#2E62FF] outline-none" placeholder="0.00" />
            </div>
            <div class="flex-1 flex flex-col gap-1.5">
              <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Quantity</label>
              <input v-model.number="currentProduct.inventory_quantity" type="number" class="bg-editor-bg border border-white/10 rounded-xl py-2 px-3 text-xs text-white focus:border-primary focus:ring-1 focus:ring-[#2E62FF] outline-none" placeholder="0" />
            </div>
          </div>

          <div class="flex gap-4">
            <div class="flex-1 flex flex-col gap-1.5">
              <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Stock Status</label>
              <select v-model="currentProduct.inventory_status" class="bg-editor-bg border border-white/10 rounded-xl py-2 px-3 text-xs text-white focus:border-primary focus:ring-1 focus:ring-[#2E62FF] outline-none cursor-pointer">
                <option value="in_stock">In Stock</option>
                <option value="low_stock">Low Stock</option>
                <option value="out_of_stock">Out of Stock</option>
              </select>
            </div>
            <div class="flex-1 flex flex-col gap-1.5">
              <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Product Image</label>
              <label class="bg-editor-bg border border-white/10 rounded-xl py-2 px-3 text-xs text-white cursor-pointer hover:border-primary hover:bg-white/5 transition-all text-center">
                Upload image
                <input type="file" accept="image/*" class="hidden" @change="attachProductImage">
              </label>
            </div>
          </div>
          <img v-if="currentProduct.images?.[0]" :src="currentProduct.images[0]" class="w-full h-32 object-cover rounded-xl border border-white/10">

          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Description</label>
            <textarea v-model="currentProduct.description" rows="3" class="bg-editor-bg border border-white/10 rounded-xl py-2 px-3 text-xs text-white focus:border-primary focus:ring-1 focus:ring-[#2E62FF] outline-none resize-none" placeholder="Describe product details..."></textarea>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-3 border-t border-white/10">
          <button @click="isModalOpen = false" class="px-4 py-2 border border-white/10 text-on-surface-variant rounded-xl text-xs cursor-pointer hover:text-white">Cancel</button>
          <button @click="saveProduct" class="px-5 py-2 bg-primary hover:brightness-110 text-white rounded-xl text-xs font-bold cursor-pointer transition-all shadow-lg shadow-primary/20 active:scale-95">Save Product</button>
        </div>
      </div>
    </div>

    <!-- Confirm Modal -->
    <div v-if="confirmState.open" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
      <div class="w-[380px] bg-surface-container border border-white/10 rounded-2xl shadow-2xl p-5 space-y-4">
        <div class="flex items-center gap-2.5 text-[#EF4444] mb-3">
          <span class="material-symbols-outlined">warning</span>
          <h3 class="text-sm font-bold text-white tracking-tight">{{ confirmState.title }}</h3>
        </div>
        <p class="text-xs text-on-surface-variant leading-relaxed">{{ confirmState.message }}</p>
        <div class="flex justify-end gap-2 mt-5">
          <button @click="confirmState.open = false" class="px-4 py-2 border border-white/10 text-on-surface-variant rounded-xl text-xs hover:text-white">Cancel</button>
          <button @click="runConfirm" class="px-4 py-2 bg-red-500 text-white rounded-xl text-xs font-bold hover:brightness-110 transition-all shadow-lg">Delete</button>
        </div>
      </div>
    </div>

    <!-- Help Modal -->
    <div v-if="showHelp || showFeedback" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
      <div class="w-[420px] bg-surface-container border border-white/10 rounded-2xl shadow-2xl p-5 space-y-3">
        <h3 class="text-white text-sm font-bold tracking-tight">{{ showHelp ? 'Inventory Help' : 'Send Feedback' }}</h3>
        <p v-if="showHelp" class="text-xs text-on-surface-variant leading-relaxed">Use explicit stock status for merchandising. Quantity powers operations; status powers storefront messaging.</p>
        <textarea v-else v-model="feedbackText" class="w-full h-28 bg-editor-bg border border-white/10 rounded-xl text-xs text-white p-3 outline-none focus:border-primary resize-none" placeholder="What should improve?"></textarea>
        <div class="flex justify-end gap-2 pt-2">
          <button @click="showHelp = false; showFeedback = false" class="px-4 py-2 border border-white/10 text-on-surface-variant rounded-xl text-xs hover:text-white">Close</button>
          <button v-if="showFeedback" @click="showFeedback = false; showToast('Feedback saved locally')" class="px-4 py-2 bg-primary text-white rounded-xl text-xs font-bold hover:brightness-110 shadow-lg active:scale-95 transition-all">Send</button>
        </div>
      </div>
    </div>

    <!-- Toast Notification -->
    <div v-if="toastMessage" class="fixed top-[72px] right-6 z-50 border text-white text-xs font-bold uppercase tracking-wider px-4 py-3 rounded-xl shadow-xl flex items-center gap-2 transition-all duration-300 bg-primary border-primary/50 shadow-primary/20 animate-fade-in">
      <span>✓</span> {{ toastMessage }}
      <button v-if="toastMessage === 'All products archived'" @click.stop="undoArchiveLocal" class="ml-2 underline text-white hover:text-gray-200 cursor-pointer">Undo</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import TopBar from '../components/editor/TopBar.vue'
import Footer from '../components/Footer.vue'
import { useWorkspaceStore } from '../stores/workspace'

const store = useWorkspaceStore()
const router = useRouter()
const route = useRoute()

const projectId = route.params.projectId || 1

// Products state
const products = ref([])
const loading = ref(true)

// Search & Filter state
const searchQuery = ref('')
const selectedStatus = ref('all')
const selectedCategory = ref('all')
const currentSort = ref('newest')

// Pagination
const currentPage = ref(1)
const pageSize = 4

// Layout Settings & Style Options
const density = ref('default')
const showImages = ref(true)
const borderRadius = ref(4)
const tableAccent = ref('#2E62FF')

// Add/Edit Dialog visibility & product fields
const isModalOpen = ref(false)
const isEditing = ref(false)
const currentProduct = ref({
  id: null,
  title: '',
  sku: '',
  price: 0,
  inventory_quantity: 0,
  inventory_status: 'in_stock',
  images: [],
  category: '',
  description: ''
})

const activeMenuId = ref(null)
const toastMessage = ref('')
const showHelp = ref(false)
const showFeedback = ref(false)
const feedbackText = ref('')
const confirmState = ref({ open: false, title: '', message: '', action: null })
const archivedProducts = ref(null)

const showToast = (msg) => {
  toastMessage.value = msg
  setTimeout(() => {
    toastMessage.value = ''
  }, 3000)
}

// Load products from API
const loadProducts = async () => {
  loading.value = true
  try {
    const res = await fetch(`${store.apiBaseUrl}/projects/${projectId}/products`)
    if (res.ok) {
      products.value = await res.json()
    }
  } catch (err) {
    console.error('Failed to load products', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadProducts()
})

// Dynamic categories helper
const categories = computed(() => {
  const cats = new Set()
  products.value.forEach(p => {
    if (p.category) cats.add(p.category)
  })
  return Array.from(cats)
})

// Filter and sorting selector logic
const filteredProducts = computed(() => {
  let list = [...products.value]

  // 1. Search filter
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    list = list.filter(p => 
      p.title?.toLowerCase().includes(q) || 
      p.sku?.toLowerCase().includes(q) || 
      p.category?.toLowerCase().includes(q)
    )
  }

  // 2. Status filter
  if (selectedStatus.value !== 'all') {
    list = list.filter(p => {
      const qty = p.inventory_quantity
      if (selectedStatus.value === 'instock') return qty > 10
      if (selectedStatus.value === 'lowstock') return p.inventory_status === 'low_stock' || (qty > 0 && qty <= 10)
      if (selectedStatus.value === 'outofstock') return p.inventory_status === 'out_of_stock' || qty === 0
      return true
    })
  }

  // 3. Category filter
  if (selectedCategory.value !== 'all') {
    list = list.filter(p => p.category === selectedCategory.value)
  }

  // 4. Sort logic
  list.sort((a, b) => {
    if (currentSort.value === 'newest') {
      return b.id - a.id
    }
    if (currentSort.value === 'price-low') {
      return Number(a.price) - Number(b.price)
    }
    if (currentSort.value === 'price-high') {
      return Number(b.price) - Number(a.price)
    }
    if (currentSort.value === 'name-asc') {
      return a.title.localeCompare(b.title)
    }
    if (currentSort.value === 'sales-desc') {
      const salesA = (a.id * 144) % 1500
      const salesB = (b.id * 144) % 1500
      return salesB - salesA
    }
    return 0
  })

  return list
})

// Paginated view computing
const totalPages = computed(() => {
  return Math.ceil(filteredProducts.value.length / pageSize) || 1
})

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * pageSize
  return filteredProducts.value.slice(start, start + pageSize)
})

const inStockCount = computed(() => products.value.filter(p => getInventoryLabel(p) === 'In Stock').length)
const lowStockCount = computed(() => products.value.filter(p => getInventoryLabel(p) === 'Low Stock').length)
const catalogValue = computed(() => {
  const total = products.value.reduce((sum, p) => sum + Number(p.price || 0), 0)
  return total >= 1000 ? (total / 1000).toFixed(1) + 'k' : total.toFixed(0)
})

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
  }
}

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}

// Slugs & Image helpers
const getProductImage = (product) => {
  if (product.slug === 'minimalist-desk-lamp') return '/images/product_cam.png'
  if (product.slug === 'wireless-mechanical-keyboard') return '/images/vector_flat.png'
  if (product.slug === 'pro-audio-studio-monitor') return '/images/arch_hero_01.png'
  if (product.slug === 'ergonomic-task-chair') return '/images/texture_vibe.png'
  
  if (product.images && product.images.length && !product.images[0].includes('placeholder')) {
    return product.images[0]
  }
  return '/images/product_cam.png'
}

const getMockSales = (id) => {
  return ((id * 144) % 1500).toLocaleString()
}

// Add and Edit modals trigger
const openAddModal = () => {
  isEditing.value = false
  currentProduct.value = {
    id: null,
    title: '',
    sku: '',
    price: 0,
    inventory_quantity: 0,
    inventory_status: 'in_stock',
    images: [],
    category: '',
    description: ''
  }
  isModalOpen.value = true
}

const openEditModal = (product) => {
  isEditing.value = true
  currentProduct.value = { ...product }
  isModalOpen.value = true
  activeMenuId.value = null
}

const saveProduct = async () => {
  if (!currentProduct.value.title || currentProduct.value.price === undefined) {
    showToast('Name and Price are required')
    return
  }

  // Create slug from title
  const slug = currentProduct.value.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')
  currentProduct.value.slug = slug
  if (!currentProduct.value.inventory_status) {
    currentProduct.value.inventory_status = currentProduct.value.inventory_quantity === 0 ? 'out_of_stock' : (currentProduct.value.inventory_quantity <= 10 ? 'low_stock' : 'in_stock')
  }

  try {
    let res
    if (isEditing.value) {
      res = await fetch(`${store.apiBaseUrl}/projects/${projectId}/products/${currentProduct.value.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(currentProduct.value)
      })
    } else {
      res = await fetch(`${store.apiBaseUrl}/projects/${projectId}/products`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(currentProduct.value)
      })
    }

    if (res.ok) {
      showToast(isEditing.value ? 'Product Updated' : 'Product Added')
      isModalOpen.value = false
      await loadProducts()
    } else {
      const errData = await res.json()
      showToast(errData.message || 'Operation Failed')
    }
  } catch (err) {
    console.error(err)
    showToast('Network error saving product')
  }
}

const deleteProduct = async (id) => {
  confirmState.value = {
    open: true,
    title: 'Delete Product',
    message: 'Delete this product from inventory and commerce blocks?',
    action: async () => {
      try {
        const res = await fetch(`${store.apiBaseUrl}/projects/${projectId}/products/${id}`, {
          method: 'DELETE'
        })
        if (res.ok) {
          showToast('Product Deleted')
          await loadProducts()
        } else {
          showToast('Deletion Failed')
        }
      } catch (err) {
        console.error(err)
        showToast('Network error deleting product')
      }
    }
  }
}

const runConfirm = async () => {
  const action = confirmState.value.action
  confirmState.value.open = false
  if (action) await action()
}

const getInventoryLabel = (product) => {
  if (product.inventory_status === 'out_of_stock') return 'Out of Stock'
  if (product.inventory_status === 'low_stock') return 'Low Stock'
  if (product.inventory_status === 'in_stock') return 'In Stock'
  if (product.inventory_quantity <= 0) return 'Out of Stock'
  if (product.inventory_quantity <= 10) return 'Low Stock'
  return 'In Stock'
}

const getInventoryClass = (product) => {
  const label = getInventoryLabel(product)
  if (label === 'In Stock') return { dot: 'bg-[#10B981] animate-pulse', text: 'bg-[#10B981]/10 text-[#10B981]' }
  if (label === 'Low Stock') return { dot: 'bg-[#F59E0B]', text: 'bg-[#F59E0B]/10 text-[#F59E0B]' }
  return { dot: 'bg-[#EF4444]', text: 'bg-[#EF4444]/10 text-[#EF4444]' }
}

const attachProductImage = async (event) => {
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
      currentProduct.value.images = [asset.url, ...(currentProduct.value.images || []).slice(1)]
      showToast('Image uploaded')
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

// Quick actions logic
const exportCSV = () => {
  let csv = 'Product,SKU,Price,Inventory,Category\n'
  filteredProducts.value.forEach(p => {
    csv += `"${p.title}","${p.sku || 'N/A'}",${p.price},${p.inventory_quantity},"${p.category || 'General'}"\n`
  })
  
  const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' })
  const link = document.createElement('a')
  link.href = URL.createObjectURL(blob)
  link.setAttribute('download', `inventory_export_${projectId}.csv`)
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  showToast('Exported CSV Successfully')
}
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

.stat-card {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.07);
  transition: all 0.2s ease;
}
.stat-card:hover {
  background: rgba(255, 255, 255, 0.05);
  border-color: rgba(46, 98, 255, 0.25);
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

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
.animate-fade-in {
  animation: fadeIn 0.25s ease-out forwards;
}
</style>
