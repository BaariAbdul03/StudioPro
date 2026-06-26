<template>
  <div class="absolute inset-0 z-40 flex justify-end pointer-events-none">
    <!-- Overlay Layer -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px] transition-all duration-500 pointer-events-auto" @click="closeDrawer"></div>
    
    <!-- CART DRAWER (The Active Component) -->
    <div 
      class="h-full w-[360px] bg-white shadow-2xl flex flex-col relative z-50 transition-transform duration-500 translate-x-0 border-l border-slate-200 pointer-events-auto font-geist cursor-pointer"
      @click="selectDrawer"
    >
      <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h2 class="text-slate-900 font-bold text-lg">Your Cart (2)</h2>
        <button @click.stop="closeDrawer" class="p-1 hover:bg-slate-50 rounded-full text-slate-400 cursor-pointer">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      
      <div class="flex-1 overflow-y-auto p-6 space-y-6">
        <!-- Item 1 -->
        <div class="flex gap-4">
          <div class="w-20 h-24 bg-slate-50 flex-shrink-0 rounded-sm bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBcgsYjinwOEfp23pwURkRC0sT1El6LJqyxaqdM58554J6mAodvozE4PJSVytcM6mvp97cxQambLT-H7N-9NwsnbtR0Jd0Q02BooVmE-2dMNW9IC2UINMISSOWln2GlvzqpcxvDtK3fuCjZB2Rty8w06MNQXUqxDfK4AduynlLg1P4BBjAvNwD-K06QuxLItf0qxcxfCSgmcjP6CJRe6nW4KVx-CHRYiaTmg-6piuA1z0jDLlvKSgegT848hbPxlmY1DB-MWA-8F2s8')"></div>
          <div class="flex-1 flex flex-col justify-between py-1">
            <div>
              <h3 class="text-slate-900 font-bold text-sm">Tech-Canvas Parka v2</h3>
              <p class="text-slate-400 text-xs">Size: XL / Color: Stealth Black</p>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center border border-slate-200 rounded-sm">
                <button class="px-2 py-1 text-slate-400 hover:bg-slate-50">-</button>
                <span class="px-2 text-xs font-bold text-slate-700">1</span>
                <button class="px-2 py-1 text-slate-400 hover:bg-slate-50">+</button>
              </div>
              <span class="font-bold text-slate-900 text-sm">$249.00</span>
            </div>
          </div>
        </div>
        
        <!-- Item 2 -->
        <div class="flex gap-4">
          <div class="w-20 h-24 bg-slate-50 flex-shrink-0 rounded-sm bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuB3STnpaP06UBPxU6jbIWn-6R612kxWpJi9Nb6deKoZUD8fGTjq8N5c-R3aXYjup5-Oal0d1sXewCa5XOlBJi07ZkXTkK-K5X-Fli_QfFwL-GCd1VCj31Q3f8Gjwnmw1kAOGGJw1uXLZ7JDkVb1fVkeSPudaYuzShpe0K55iFrXfx2MnmKPAiJOSAI_7DA_FdZYlya_zL4jQogUwQ1gAPVqRUZ7Sl2-FcohsIJGrGi4bITYWcJPM6ymh2h4NRCk7zy2c6BMIpH5X5Xh')"></div>
          <div class="flex-1 flex flex-col justify-between py-1">
            <div>
              <h3 class="text-slate-900 font-bold text-sm">Aero-Step Sneakers</h3>
              <p class="text-slate-400 text-xs">Size: 11 / Color: Arctic</p>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center border border-slate-200 rounded-sm">
                <button class="px-2 py-1 text-slate-400 hover:bg-slate-50">-</button>
                <span class="px-2 text-xs font-bold text-slate-700">1</span>
                <button class="px-2 py-1 text-slate-400 hover:bg-slate-50">+</button>
              </div>
              <span class="font-bold text-slate-900 text-sm">$185.00</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="p-6 border-t border-slate-100 bg-slate-50/50">
        <div class="flex justify-between items-center mb-4">
          <span class="text-slate-500 text-sm">Subtotal</span>
          <span class="text-slate-900 font-bold text-xl">$434.00</span>
        </div>
        <button class="w-full bg-slate-900 text-white h-12 rounded-sm font-bold flex items-center justify-center gap-2 hover:bg-slate-800 transition-colors">
          <span>Checkout</span>
          <span class="material-symbols-outlined text-sm">lock</span>
        </button>
        <p class="text-[10px] text-center text-slate-400 mt-2">Secure encrypted checkout powered by Stripe</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useWorkspaceStore } from '../../stores/workspace'

const store = useWorkspaceStore()

const closeDrawer = () => {
  store.toggleCartDrawer(false)
  if (store.selectedComponent?.get && store.selectedComponent.get('type') === 'CartDrawer') {
    store.setSelectedComponent(null)
  }
}

const selectDrawer = () => {
  store.setSelectedComponent({
    isVirtual: true,
    get: (prop) => {
      if (prop === 'type') return 'CartDrawer'
      if (prop === 'name') return 'CartDrawer'
      return null
    },
    getName: () => 'CartDrawer',
    getId: () => 'cart-drawer-preview',
    getAttributes: () => ({ id: 'cart-drawer-preview', role: 'dialog' }),
    getStyle: () => ({}),
    setStyle: () => {},
    setAttributes: () => {},
    setId: () => {},
    toHTML: () => '<div role="dialog" id="cart-drawer-preview">Cart drawer preview</div>',
    parent: () => null
  })
}
</script>
