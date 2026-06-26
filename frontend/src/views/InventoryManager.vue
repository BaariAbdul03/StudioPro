<template>
<div class="inventorymanager font-geist relative min-h-screen bg-[#0B0E14] text-[#dae2fd]" @click="activeMenuId = null">

  <!-- TopNavBar Implementation -->
  <header class="bg-panel-surface flex justify-between items-center h-toolbar-height px-md w-full border-b border-[#222530] fixed top-0 z-50">
    <div class="flex items-center gap-xl">
      <span @click="goBackToEditor()" class="font-headline-md text-headline-md font-bold text-on-surface cursor-pointer hover:opacity-80 transition">StudioPro</span>
      <nav class="hidden md:flex items-center gap-lg">
        <a @click="goBackToEditor('layers')" class="text-on-surface-variant font-medium hover:bg-surface-container-highest transition-colors cursor-pointer px-sm py-1 rounded-sm font-label-sm text-label-sm">Pages</a>
        <a @click="goToCms()" class="text-on-surface-variant font-medium hover:bg-surface-container-highest transition-colors cursor-pointer px-sm py-1 rounded-sm font-label-sm text-label-sm">CMS</a>
        <a @click="goToTemplates()" class="text-on-surface-variant font-medium hover:bg-surface-container-highest transition-colors cursor-pointer px-sm py-1 rounded-sm font-label-sm text-label-sm">Templates</a>
        <a @click="goBackToEditor('assets')" class="text-on-surface-variant font-medium hover:bg-surface-container-highest transition-colors cursor-pointer px-sm py-1 rounded-sm font-label-sm text-label-sm">Assets</a>
        <a @click="goBackToEditor('settings')" class="text-on-surface-variant font-medium hover:bg-surface-container-highest transition-colors cursor-pointer px-sm py-1 rounded-sm font-label-sm text-label-sm">Settings</a>
      </nav>
    </div>
    <div class="flex items-center gap-md">
      <div class="flex gap-xs mr-md">
        <button @click="goBackToEditor()" class="p-1 hover:bg-surface-container-highest transition-colors rounded cursor-pointer active:scale-95 text-on-surface-variant">
          <span class="material-symbols-outlined">desktop_windows</span>
        </button>
        <button @click="goBackToEditor()" class="p-1 hover:bg-surface-container-highest transition-colors rounded cursor-pointer active:scale-95 text-on-surface-variant">
          <span class="material-symbols-outlined">tablet_mac</span>
        </button>
        <button @click="goBackToEditor()" class="p-1 hover:bg-surface-container-highest transition-colors rounded cursor-pointer active:scale-95 text-on-surface-variant">
          <span class="material-symbols-outlined">smartphone</span>
        </button>
      </div>
      <button @click="goBackToEditor()" class="bg-[#2E62FF] hover:bg-blue-600 text-white px-lg py-1.5 rounded-sm font-label-sm text-label-sm active:scale-95 transition-all cursor-pointer">
        Back to Editor
      </button>
    </div>
  </header>

  <div class="flex flex-1 pt-toolbar-height pb-8 h-full">
    <!-- Left SideNavBar -->
    <aside class="flex flex-col h-full w-panel-width fixed left-0 bg-panel-surface border-r border-[#222530] z-40">
      <div class="p-md border-b border-[#222530]">
        <div class="flex items-center gap-sm">
          <div class="w-8 h-8 rounded flex items-center justify-center" :style="{ backgroundColor: tableAccent }">
            <span class="material-symbols-outlined text-white">storefront</span>
          </div>
          <div>
            <div class="font-label-md text-label-md uppercase tracking-widest text-on-surface-variant">Project Alpha</div>
            <div class="font-body-sm text-body-sm text-outline opacity-70">V1.0.4</div>
          </div>
        </div>
      </div>
      <div class="flex-1 overflow-y-auto no-scrollbar py-sm">
        <div class="px-sm mb-xs">
          <span class="text-[10px] uppercase tracking-wider text-outline px-sm font-bold">Main Navigation</span>
        </div>
        <div class="space-y-px">
          <!-- Active Commerce Link -->
          <div class="flex items-center gap-sm px-md py-2 bg-[#2E62FF]/10 border-l-2 cursor-pointer group" :style="{ borderLeftColor: tableAccent }">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;" :style="{ color: tableAccent }">inventory_2</span>
            <span class="font-label-sm text-label-sm" :style="{ color: tableAccent }">Commerce</span>
          </div>
          <div @click="goToCms()" class="flex items-center gap-sm px-md py-2 text-on-surface-variant hover:bg-surface-container-highest cursor-pointer transition-all duration-150">
            <span class="material-symbols-outlined">database</span>
            <span class="font-label-sm text-label-sm">CMS</span>
          </div>
          <div @click="goToTemplates()" class="flex items-center gap-sm px-md py-2 text-on-surface-variant hover:bg-surface-container-highest cursor-pointer transition-all duration-150">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="font-label-sm text-label-sm">Templates</span>
          </div>
          <div @click="goBackToEditor('layers')" class="flex items-center gap-sm px-md py-2 text-on-surface-variant hover:bg-surface-container-highest cursor-pointer transition-all duration-150">
            <span class="material-symbols-outlined">layers</span>
            <span class="font-label-sm text-label-sm">Layers</span>
          </div>
          <div @click="goBackToEditor('blocks')" class="flex items-center gap-sm px-md py-2 text-on-surface-variant hover:bg-surface-container-highest cursor-pointer transition-all duration-150">
            <span class="material-symbols-outlined">add_box</span>
            <span class="font-label-sm text-label-sm">Blocks</span>
          </div>
          <div @click="goBackToEditor('symbols')" class="flex items-center gap-sm px-md py-2 text-on-surface-variant hover:bg-surface-container-highest cursor-pointer transition-all duration-150">
            <span class="material-symbols-outlined">widgets</span>
            <span class="font-label-sm text-label-sm">Symbols</span>
          </div>
          <div @click="goBackToEditor('settings')" class="flex items-center gap-sm px-md py-2 text-on-surface-variant hover:bg-surface-container-highest cursor-pointer transition-all duration-150">
            <span class="material-symbols-outlined">settings</span>
            <span class="font-label-sm text-label-sm">Config</span>
          </div>
        </div>
      </div>
      <div class="mt-auto border-t border-[#222530] p-md">
        <div class="flex flex-col gap-sm">
          <div @click="showHelp = true" class="flex items-center gap-sm text-on-surface-variant hover:text-on-surface cursor-pointer font-label-sm text-label-sm">
            <span class="material-symbols-outlined">help</span>
            Help
          </div>
          <div @click="showFeedback = true" class="flex items-center gap-sm text-on-surface-variant hover:text-on-surface cursor-pointer font-label-sm text-label-sm">
            <span class="material-symbols-outlined">chat_bubble</span>
            Feedback
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Canvas Content -->
    <main class="ml-panel-width xl:mr-panel-width flex-1 bg-editor-bg p-xl overflow-y-auto no-scrollbar">
      <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-end mb-xl">
          <div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface">Product Inventory</h1>
            <p class="text-on-surface-variant font-body-md text-body-md mt-1">Manage your digital products, stock levels, and commerce analytics.</p>
          </div>
          <button @click="openAddModal" class="flex items-center gap-sm text-white px-xl py-2.5 rounded-sm font-label-md text-label-md hover:brightness-110 transition-all active:scale-95 shadow-lg cursor-pointer" :style="{ backgroundColor: tableAccent, boxShadow: `0 4px 12px ${tableAccent}33` }">
            <span class="material-symbols-outlined">add</span>
            Add Product
          </button>
        </div>

        <!-- Filters & Search -->
        <div class="flex flex-wrap items-center gap-md mb-lg bg-panel-surface p-sm border border-[#222530] rounded">
          <div class="relative flex-1 min-w-[300px]">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input v-model="searchQuery" class="w-full bg-editor-bg border border-[#222530] rounded-sm py-2 pl-10 pr-md text-body-sm text-on-surface focus:border-electric-blue focus:ring-1 focus:ring-electric-blue outline-none transition-all" placeholder="Search products, SKUs, or categories..." type="text"/>
          </div>
          <div class="h-8 w-px bg-[#222530]"></div>

          <!-- Status Selector -->
          <div class="flex items-center gap-xs">
            <select v-model="selectedStatus" class="bg-editor-bg text-on-surface-variant hover:text-white rounded font-label-sm text-label-sm border border-[#222530] transition-colors px-md py-2 focus:ring-0 outline-none cursor-pointer">
              <option value="all">All Statuses</option>
              <option value="instock">In Stock</option>
              <option value="lowstock">Low Stock</option>
              <option value="outofstock">Out of Stock</option>
            </select>
          </div>

          <!-- Category Selector -->
          <div class="flex items-center gap-xs">
            <select v-model="selectedCategory" class="bg-editor-bg text-on-surface-variant hover:text-white rounded font-label-sm text-label-sm border border-[#222530] transition-colors px-md py-2 focus:ring-0 outline-none cursor-pointer">
              <option value="all">All Categories</option>
              <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
            </select>
          </div>

          <!-- Sort Options -->
          <div class="flex items-center gap-xs">
            <select v-model="currentSort" class="bg-editor-bg text-on-surface-variant hover:text-white rounded font-label-sm text-label-sm border border-[#222530] transition-colors px-md py-2 focus:ring-0 outline-none cursor-pointer">
              <option value="newest">Sort: Newest</option>
              <option value="price-low">Sort: Price Low-High</option>
              <option value="price-high">Sort: Price High-Low</option>
              <option value="name-asc">Sort: Name A-Z</option>
              <option value="sales-desc">Sort: Sales High-Low</option>
            </select>
          </div>
        </div>

        <!-- Table -->
        <div class="bg-panel-surface border border-[#222530] rounded-sm overflow-hidden">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-[#222530] bg-surface-container-low">
                <th class="px-xl py-md font-label-sm text-label-sm text-outline uppercase tracking-wider">Product</th>
                <th class="px-xl py-md font-label-sm text-label-sm text-outline uppercase tracking-wider">SKU</th>
                <th class="px-xl py-md font-label-sm text-label-sm text-outline uppercase tracking-wider">Price</th>
                <th class="px-xl py-md font-label-sm text-label-sm text-outline uppercase tracking-wider">Inventory Status</th>
                <th class="px-xl py-md font-label-sm text-label-sm text-outline uppercase tracking-wider">Sales</th>
                <th class="px-xl py-md font-label-sm text-label-sm text-outline uppercase tracking-wider"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-[#222530]">
              <tr v-if="loading">
                <td colspan="6" class="px-xl py-lg text-center text-on-surface-variant font-body-md text-body-md">Loading products...</td>
              </tr>
              <tr v-else-if="paginatedProducts.length === 0">
                <td colspan="6" class="px-xl py-lg text-center text-on-surface-variant font-body-md text-body-md">No products found.</td>
              </tr>
              <tr v-else v-for="product in paginatedProducts" :key="product.id" class="hover:bg-surface-container-highest/30 transition-colors group">
                <!-- Product Image & Name -->
                <td class="px-xl transition-all duration-150" :class="density === 'compact' ? 'py-2.5' : (density === 'spacious' ? 'py-6' : 'py-4')">
                  <div class="flex items-center gap-lg">
                    <div v-if="showImages" class="w-12 h-12 bg-[#0B0E14] overflow-hidden flex-shrink-0 border border-[#222530] transition-all duration-200" :style="{ borderRadius: borderRadius + 'px' }">
                      <img class="w-full h-full object-cover" :src="getProductImage(product)" />
                    </div>
                    <div>
                      <div class="font-body-md text-body-md text-on-surface font-medium">{{ product.title }}</div>
                      <div class="font-mono-label text-mono-label text-outline uppercase mt-1">{{ product.category || 'General' }}</div>
                    </div>
                  </div>
                </td>
                
                <!-- SKU -->
                <td class="px-xl font-mono-label text-mono-label text-on-surface-variant transition-all duration-150" :class="density === 'compact' ? 'py-2.5' : (density === 'spacious' ? 'py-6' : 'py-4')">
                  {{ product.sku || 'N/A' }}
                </td>
                
                <!-- Price -->
                <td class="px-xl font-body-sm text-body-sm text-on-surface transition-all duration-150" :class="density === 'compact' ? 'py-2.5' : (density === 'spacious' ? 'py-6' : 'py-4')">
                  ${{ Number(product.price).toFixed(2) }}
                </td>
                
                <!-- Inventory Status -->
                <td class="px-xl transition-all duration-150" :class="density === 'compact' ? 'py-2.5' : (density === 'spacious' ? 'py-6' : 'py-4')">
                  <div class="flex items-center gap-sm">
                    <span class="w-2 h-2 rounded-full" :class="getInventoryClass(product).dot"></span>
                    <span class="font-label-sm text-label-sm" :class="getInventoryClass(product).text">
                      {{ getInventoryLabel(product) }} {{ product.inventory_quantity > 0 ? '(' + product.inventory_quantity + ')' : '' }}
                    </span>
                  </div>
                </td>
                
                <!-- Sales -->
                <td class="px-xl font-body-sm text-body-sm text-on-surface transition-all duration-150" :class="density === 'compact' ? 'py-2.5' : (density === 'spacious' ? 'py-6' : 'py-4')">
                  {{ getMockSales(product.id) }}
                </td>
                
                <!-- Actions menu -->
                <td class="px-xl transition-all duration-150 text-right relative" :class="density === 'compact' ? 'py-2.5' : (density === 'spacious' ? 'py-6' : 'py-4')">
                  <button @click.stop="activeMenuId = activeMenuId === product.id ? null : product.id" class="opacity-0 group-hover:opacity-100 p-sm hover:bg-surface-container-highest rounded text-on-surface-variant transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">more_vert</span>
                  </button>

                  <div v-if="activeMenuId === product.id" class="absolute right-xl top-12 w-32 bg-[#161B22] border border-[#434656] rounded shadow-xl z-50 py-1 text-left">
                    <button @click.stop="openEditModal(product)" class="w-full px-4 py-2 text-xs text-white hover:bg-[#2d3449] transition text-left flex items-center gap-2 cursor-pointer">
                      <span class="material-symbols-outlined text-xs">edit</span> Edit
                    </button>
                    <button @click.stop="deleteProduct(product.id)" class="w-full px-4 py-2 text-xs text-error-red hover:bg-[#2d3449] transition text-left flex items-center gap-2 cursor-pointer">
                      <span class="material-symbols-outlined text-xs">delete</span> Delete
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          
          <div class="p-md bg-surface-container-low border-t border-[#222530] flex justify-between items-center">
            <span class="font-label-sm text-label-sm text-on-surface-variant">Showing {{ paginatedProducts.length }} of {{ filteredProducts.length }} products</span>
            <div class="flex gap-sm">
              <button @click="prevPage" :disabled="currentPage === 1" class="px-md py-1 border border-[#222530] rounded font-label-sm text-label-sm text-outline hover:text-on-surface transition-colors disabled:opacity-30 disabled:pointer-events-none cursor-pointer">Previous</button>
              <button @click="nextPage" :disabled="currentPage === totalPages" class="px-md py-1 border border-[#222530] rounded font-label-sm text-label-sm text-on-surface transition-colors disabled:opacity-30 disabled:pointer-events-none cursor-pointer" :class="currentPage === totalPages ? 'bg-transparent' : 'bg-surface-container-highest'">Next</button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Right SideNavBar (Properties Panel) -->
    <aside class="hidden xl:flex flex-col h-full w-panel-width fixed right-0 bg-panel-surface border-l border-[#222530] z-40">
      <div class="p-md border-b border-[#222530]">
        <div class="font-label-sm text-label-sm text-on-surface font-semibold">Element Properties</div>
        <div class="font-mono-label text-mono-label text-on-surface-variant mt-1">Selected: Table View</div>
      </div>
      
      <div class="flex flex-col overflow-y-auto no-scrollbar p-md gap-lg">
        <!-- Stock Settings -->
        <section>
          <div class="flex justify-between items-center mb-sm">
            <span class="font-label-sm text-label-sm text-outline uppercase">Stock Controls</span>
            <span class="material-symbols-outlined text-outline scale-75 cursor-pointer">expand_more</span>
          </div>
          <div class="space-y-md">
            <div class="flex items-center justify-between">
              <label class="font-label-sm text-label-sm text-on-surface-variant">Show Images</label>
              <div @click="showImages = !showImages" class="w-8 h-4 rounded-full relative cursor-pointer transition-all duration-200" :class="showImages ? 'bg-[#2E62FF]' : 'bg-surface-container-highest'" :style="showImages ? { backgroundColor: tableAccent } : {}">
                <div class="absolute top-0.5 w-3 h-3 bg-white rounded-full transition-all duration-200" :class="showImages ? 'right-0.5' : 'left-0.5'"></div>
              </div>
            </div>
            <button @click="autoStock" class="w-full py-2 bg-[#10B981] text-white rounded-sm text-xs font-bold hover:brightness-110">Replenish Low Stock to 50</button>
            <p class="text-[11px] text-outline leading-relaxed">Restock action protects sales pages by moving low/out items back to sellable quantity.</p>
          </div>
        </section>

        <!-- Style Options -->
        <section>
          <div class="flex justify-between items-center mb-sm">
            <span class="font-label-sm text-label-sm text-outline uppercase">Style Options</span>
            <span class="material-symbols-outlined text-outline scale-75 cursor-pointer">expand_more</span>
          </div>
          <div class="space-y-md">
            <div class="flex flex-col gap-xs">
              <label class="font-label-sm text-label-sm text-on-surface-variant">Border Radius</label>
              <div class="flex items-center gap-sm">
                <input v-model.number="borderRadius" class="flex-1 accent-electric-blue" type="range" min="0" max="16" :style="{ accentColor: tableAccent }"/>
                <span class="font-mono-label text-mono-label text-on-surface bg-editor-bg px-1 border border-[#222530]">{{ borderRadius }}px</span>
              </div>
            </div>
            <div class="flex flex-col gap-xs">
              <label class="font-label-sm text-label-sm text-on-surface-variant">Table Accent</label>
              <div class="flex gap-sm">
                <div @click="tableAccent = '#2E62FF'" class="w-6 h-6 rounded-sm bg-electric-blue cursor-pointer transition-all duration-150" :class="tableAccent === '#2E62FF' ? 'border-2 border-white' : 'border border-outline-variant'"></div>
                <div @click="tableAccent = '#8B5CF6'" class="w-6 h-6 rounded-sm bg-royal-purple cursor-pointer transition-all duration-150" :class="tableAccent === '#8B5CF6' ? 'border-2 border-white' : 'border border-outline-variant'"></div>
                <div @click="tableAccent = '#10B981'" class="w-6 h-6 rounded-sm bg-success-green cursor-pointer transition-all duration-150" :class="tableAccent === '#10B981' ? 'border-2 border-white' : 'border border-outline-variant'"></div>
                <div @click="tableAccent = '#8d90a2'" class="w-6 h-6 rounded-sm bg-on-surface-variant cursor-pointer transition-all duration-150" :class="tableAccent === '#8d90a2' ? 'border-2 border-white' : 'border border-outline-variant'"></div>
              </div>
            </div>
          </div>
        </section>

        <!-- Quick Actions -->
        <section>
          <div class="flex justify-between items-center mb-sm">
            <span class="font-label-sm text-label-sm text-outline uppercase">Quick Actions</span>
          </div>
          <div class="grid grid-cols-2 gap-sm">
            <button @click="exportCSV" class="flex flex-col items-center justify-center gap-xs p-md border border-[#222530] bg-surface-container-low hover:bg-surface-container-highest rounded transition-all cursor-pointer">
              <span class="material-symbols-outlined text-on-surface-variant">cloud_download</span>
              <span class="font-label-sm text-[9px] text-outline uppercase">Export CSV</span>
            </button>
            <button @click="printList" class="flex flex-col items-center justify-center gap-xs p-md border border-[#222530] bg-surface-container-low hover:bg-surface-container-highest rounded transition-all cursor-pointer">
              <span class="material-symbols-outlined text-on-surface-variant">print</span>
              <span class="font-label-sm text-[9px] text-outline uppercase">Print List</span>
            </button>
            <button @click="archiveAll" class="flex flex-col items-center justify-center gap-xs p-md border border-[#222530] bg-surface-container-low hover:bg-surface-container-highest rounded transition-all cursor-pointer">
              <span class="material-symbols-outlined text-on-surface-variant">archive</span>
              <span class="font-label-sm text-[9px] text-outline uppercase">Archive All</span>
            </button>
            <button @click="autoStock" class="flex flex-col items-center justify-center gap-xs p-md border border-[#222530] bg-surface-container-low hover:bg-surface-container-highest rounded transition-all cursor-pointer">
              <span class="material-symbols-outlined text-on-surface-variant">auto_awesome</span>
              <span class="font-label-sm text-[9px] text-outline uppercase">Restock</span>
            </button>
          </div>
        </section>
      </div>
    </aside>
  </div>

  <!-- Footer -->
  <footer class="fixed bottom-0 left-0 w-full h-8 flex justify-between items-center px-md z-40 bg-surface-container-lowest border-t border-[#222530]">
    <div class="flex items-center gap-xl">
      <span class="font-label-sm text-label-sm text-outline">© 2024 StudioPro Engine</span>
      <div class="flex gap-lg items-center">
        <span class="font-mono-label text-mono-label text-outline hover:text-primary transition-colors cursor-default">Breadcrumbs: Commerce &gt; Inventory</span>
        <div class="h-3 w-px bg-outline-variant/30"></div>
        <span class="font-mono-label text-mono-label text-outline hover:text-primary transition-colors cursor-default">Status: Connected</span>
      </div>
    </div>
    <div class="flex items-center gap-lg">
      <span class="font-mono-label text-mono-label text-outline hover:text-primary transition-colors cursor-default">Zoom 100%</span>
      <span class="font-mono-label text-mono-label text-outline hover:text-primary transition-colors cursor-default">Canvas Help</span>
    </div>
  </footer>

  <!-- Sliding Modal Dialog for Add/Edit Product -->
  <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-300">
    <div class="w-[500px] bg-[#161B22] border border-[#434656] p-6 rounded-sm shadow-2xl relative flex flex-col gap-4 font-geist">
      <div class="flex justify-between items-center pb-2 border-b border-[#434656]">
        <h3 class="text-sm font-bold text-white uppercase tracking-wider">{{ isEditing ? 'Edit Product' : 'Add Product' }}</h3>
        <button @click="isModalOpen = false" class="text-on-surface-variant hover:text-white cursor-pointer">
          <span class="material-symbols-outlined text-sm">close</span>
        </button>
      </div>
      
      <div class="space-y-4">
        <div class="flex flex-col gap-1.5">
          <label class="text-[11px] font-bold text-[#8d90a2] uppercase tracking-wider">Product Name *</label>
          <input v-model="currentProduct.title" type="text" class="bg-editor-bg border border-[#434656] rounded-sm py-2 px-3 text-xs text-white focus:border-electric-blue focus:ring-1 focus:ring-electric-blue outline-none" placeholder="e.g. Ergonomic Keyboard" />
        </div>

        <div class="flex gap-4">
          <div class="flex-1 flex flex-col gap-1.5">
            <label class="text-[11px] font-bold text-[#8d90a2] uppercase tracking-wider">SKU</label>
            <input v-model="currentProduct.sku" type="text" class="bg-editor-bg border border-[#434656] rounded-sm py-2 px-3 text-xs text-white focus:border-electric-blue focus:ring-1 focus:ring-electric-blue outline-none" placeholder="e.g. KBD-X1" />
          </div>
          <div class="flex-1 flex flex-col gap-1.5">
            <label class="text-[11px] font-bold text-[#8d90a2] uppercase tracking-wider">Category</label>
            <input v-model="currentProduct.category" type="text" class="bg-editor-bg border border-[#434656] rounded-sm py-2 px-3 text-xs text-white focus:border-electric-blue focus:ring-1 focus:ring-electric-blue outline-none" placeholder="e.g. Furniture / Office" />
          </div>
        </div>

        <div class="flex gap-4">
          <div class="flex-1 flex flex-col gap-1.5">
            <label class="text-[11px] font-bold text-[#8d90a2] uppercase tracking-wider">Price ($) *</label>
            <input v-model.number="currentProduct.price" type="number" step="0.01" class="bg-editor-bg border border-[#434656] rounded-sm py-2 px-3 text-xs text-white focus:border-electric-blue focus:ring-1 focus:ring-electric-blue outline-none" placeholder="0.00" />
          </div>
          <div class="flex-1 flex flex-col gap-1.5">
            <label class="text-[11px] font-bold text-[#8d90a2] uppercase tracking-wider">Quantity</label>
            <input v-model.number="currentProduct.inventory_quantity" type="number" class="bg-editor-bg border border-[#434656] rounded-sm py-2 px-3 text-xs text-white focus:border-electric-blue focus:ring-1 focus:ring-electric-blue outline-none" placeholder="0" />
          </div>
        </div>

        <div class="flex gap-4">
          <div class="flex-1 flex flex-col gap-1.5">
            <label class="text-[11px] font-bold text-[#8d90a2] uppercase tracking-wider">Stock Status</label>
            <select v-model="currentProduct.inventory_status" class="bg-editor-bg border border-[#434656] rounded-sm py-2 px-3 text-xs text-white focus:border-electric-blue focus:ring-1 focus:ring-electric-blue outline-none">
              <option value="in_stock">In Stock</option>
              <option value="low_stock">Low Stock</option>
              <option value="out_of_stock">Out of Stock</option>
            </select>
          </div>
          <div class="flex-1 flex flex-col gap-1.5">
            <label class="text-[11px] font-bold text-[#8d90a2] uppercase tracking-wider">Product Image</label>
            <label class="bg-editor-bg border border-[#434656] rounded-sm py-2 px-3 text-xs text-white cursor-pointer hover:border-electric-blue">
              Upload image
              <input type="file" accept="image/*" class="hidden" @change="attachProductImage">
            </label>
          </div>
        </div>
        <img v-if="currentProduct.images?.[0]" :src="currentProduct.images[0]" class="w-full h-32 object-cover rounded-sm border border-[#434656]">

        <div class="flex flex-col gap-1.5">
          <label class="text-[11px] font-bold text-[#8d90a2] uppercase tracking-wider">Description</label>
          <textarea v-model="currentProduct.description" rows="3" class="bg-editor-bg border border-[#434656] rounded-sm py-2 px-3 text-xs text-white focus:border-electric-blue focus:ring-1 focus:ring-electric-blue outline-none resize-none" placeholder="Describe your product..."></textarea>
        </div>
      </div>

      <div class="flex justify-end gap-3 pt-3 border-t border-[#434656]">
        <button @click="isModalOpen = false" class="px-4 py-2 border border-[#434656] text-on-surface-variant hover:text-white rounded-sm text-xs cursor-pointer">Cancel</button>
        <button @click="saveProduct" class="px-5 py-2 bg-[#2E62FF] hover:brightness-110 text-white rounded-sm text-xs font-bold cursor-pointer" :style="{ backgroundColor: tableAccent }">Save Product</button>
      </div>
    </div>
  </div>

  <!-- Toast Notification -->
  <div v-if="toastMessage" class="fixed top-[72px] right-6 z-50 border text-white text-xs font-bold uppercase tracking-wider px-4 py-3 rounded shadow-xl flex items-center gap-2 transition-all duration-300" :style="{ backgroundColor: tableAccent, borderColor: tableAccent }">
    <span>✓</span> {{ toastMessage }}
    <button v-if="toastMessage === 'All products archived'" @click.stop="undoArchiveLocal" class="ml-2 underline text-white hover:text-gray-200 cursor-pointer">Undo</button>
  </div>

  <div v-if="confirmState.open" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 backdrop-blur-sm">
    <div class="w-[380px] bg-[#161B22] border border-[#434656] rounded-sm shadow-2xl p-5">
      <div class="flex items-center gap-2 text-error-red mb-3">
        <span class="material-symbols-outlined">warning</span>
        <h3 class="text-sm font-bold text-white">{{ confirmState.title }}</h3>
      </div>
      <p class="text-sm text-on-surface-variant leading-relaxed">{{ confirmState.message }}</p>
      <div class="flex justify-end gap-2 mt-5">
        <button @click="confirmState.open = false" class="px-4 py-2 border border-[#434656] text-on-surface-variant rounded-sm text-xs hover:text-white">Cancel</button>
        <button @click="runConfirm" class="px-4 py-2 bg-error-red text-white rounded-sm text-xs font-bold">Delete</button>
      </div>
    </div>
  </div>

  <div v-if="showHelp || showFeedback" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 backdrop-blur-sm">
    <div class="w-[420px] bg-[#161B22] border border-[#434656] rounded-sm shadow-2xl p-5">
      <h3 class="text-white text-sm font-bold mb-2">{{ showHelp ? 'Inventory Help' : 'Send Feedback' }}</h3>
      <p v-if="showHelp" class="text-sm text-on-surface-variant leading-relaxed">Use explicit stock status for merchandising. Quantity powers operations; status powers storefront messaging.</p>
      <textarea v-else v-model="feedbackText" class="w-full h-28 bg-editor-bg border border-[#434656] rounded-sm text-sm text-white p-2 outline-none" placeholder="What should improve?"></textarea>
      <div class="flex justify-end gap-2 mt-5">
        <button @click="showHelp = false; showFeedback = false" class="px-4 py-2 border border-[#434656] text-on-surface-variant rounded-sm text-xs hover:text-white">Close</button>
        <button v-if="showFeedback" @click="showFeedback = false; showToast('Feedback saved locally')" class="px-4 py-2 bg-[#2E62FF] text-white rounded-sm text-xs font-bold">Send</button>
      </div>
    </div>
  </div>

</div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useWorkspaceStore } from '../stores/workspace'

const store = useWorkspaceStore()
const router = useRouter()
const route = useRoute()

const projectId = route.params.projectId || 1
const pageId = 1 // default page to route back to

// Products state
const products = ref([])
const loading = ref(true)

// Navigation logic
const goBackToEditor = (tab) => {
  if (tab) {
    store.setActiveLeftTab(tab)
  }
  router.push(`/editor/${projectId}/${pageId}`)
}

const goToCms = () => {
  router.push(`/cms/${projectId}`)
}

const goToTemplates = () => {
  router.push(`/templates/${projectId}`)
}

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
  if (label === 'In Stock') return { dot: 'bg-success-green', text: 'text-success-green' }
  if (label === 'Low Stock') return { dot: 'bg-warning-amber', text: 'text-warning-amber' }
  return { dot: 'bg-error-red', text: 'text-error-red' }
}

const attachProductImage = (event) => {
  const file = event.target.files?.[0]
  if (!file) return
  const reader = new FileReader()
  reader.onload = () => {
    currentProduct.value.images = [String(reader.result), ...(currentProduct.value.images || []).slice(1)]
    event.target.value = ''
  }
  reader.readAsDataURL(file)
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

const printList = () => {
  window.print()
}

const archiveAll = () => {
  const original = [...products.value]
  products.value = []
  showToast('All products archived')
  window.undoArchive = () => {
    products.value = original
    showToast('Archive restored')
  }
}

const undoArchiveLocal = () => {
  if (window.undoArchive) {
    window.undoArchive()
  }
}

const autoStock = async () => {
  let count = 0
  for (let p of products.value) {
    if (p.inventory_quantity <= 10) {
      try {
        await fetch(`${store.apiBaseUrl}/projects/${projectId}/products/${p.id}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ...p, inventory_quantity: 50, inventory_status: 'in_stock' })
        })
        count++
      } catch (err) {
        console.error(err)
      }
    }
  }
  showToast(`Restocked ${count} products to 50 items`)
  await loadProducts()
}
</script>

<style>
@media print {
  body {
    background-color: white !important;
    color: black !important;
  }
  aside, header, footer, button, select, input {
    display: none !important;
  }
  main {
    margin: 0 !important;
    padding: 0 !important;
    background-color: white !important;
  }
  table {
    border: 1px solid #ccc !important;
  }
  th {
    background-color: #f0f0f0 !important;
    color: black !important;
  }
  tr {
    border-bottom: 1px solid #ccc !important;
  }
}
</style>
