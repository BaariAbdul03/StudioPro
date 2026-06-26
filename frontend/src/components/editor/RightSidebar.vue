<template>
  <div class="relative w-[280px] bg-[#161B22] border-l border-[#434656] flex flex-col select-none h-full font-geist">
    <!-- Header -->
    <div class="p-4 border-b border-[#434656] flex items-center justify-between">
      <div>
        <span class="text-white text-xs font-bold uppercase tracking-wider text-[#dae2fd]">Element Properties</span>
        <p class="text-[#2E62FF] text-[11px] font-mono font-bold mt-1 uppercase tracking-wider bg-[#2E62FF]/10 px-2 py-0.5 rounded inline-block">
          Selected: {{ store.selectedComponent ? store.selectedComponent.get('type') : 'wrapper' }}
        </p>
      </div>
      <button @click="showPanelMenu = !showPanelMenu" class="relative text-[#8d90a2] cursor-pointer hover:text-white">
        <span class="material-symbols-outlined">more_vert</span>
        <div v-if="showPanelMenu" class="absolute right-0 top-7 w-44 bg-[#060e20] border border-[#434656] rounded shadow-xl z-50 py-1 text-left">
          <button @click.stop="selectWrapper" class="w-full px-3 py-2 text-xs text-[#dae2fd] hover:bg-[#2d3449] flex items-center gap-2">
            <span class="material-symbols-outlined text-[14px]">select_all</span>Select Page
          </button>
          <button @click.stop="duplicateSelected" class="w-full px-3 py-2 text-xs text-[#dae2fd] hover:bg-[#2d3449] flex items-center gap-2">
            <span class="material-symbols-outlined text-[14px]">content_copy</span>Duplicate
          </button>
          <button @click.stop="removeSelected" class="w-full px-3 py-2 text-xs text-[#EF4444] hover:bg-[#2d3449] flex items-center gap-2">
            <span class="material-symbols-outlined text-[14px]">delete</span>Delete
          </button>
        </div>
      </button>
      <button
        @click="store.setRightSidebarCollapsed(true)"
        class="ml-2 h-7 w-7 rounded bg-[#0B0E14] border border-[#434656] text-[#8d90a2] hover:text-white hover:border-[#2E62FF] flex items-center justify-center"
        title="Collapse right sidebar"
      >
        <span class="material-symbols-outlined text-[16px]">right_panel_close</span>
      </button>
    </div>

    <!-- Property Tabs -->
    <div class="flex border-b border-[#434656]">
      <button 
        @click="activeSubTab = 'style'"
        class="flex-1 py-2 flex justify-center hover:text-white border-b-2 transition cursor-pointer"
        :class="activeSubTab === 'style' ? 'bg-[#2E62FF]/10 text-[#2E62FF] border-[#2E62FF]' : 'border-transparent text-[#c3c5d8] hover:bg-[#2d3449]'"
      >
        <span class="material-symbols-outlined">palette</span>
      </button>
      
      <button 
        @click="activeSubTab = 'settings'"
        class="flex-1 py-2 flex justify-center hover:text-white border-b-2 transition cursor-pointer"
        :class="activeSubTab === 'settings' ? 'bg-[#2E62FF]/10 text-[#2E62FF] border-[#2E62FF]' : 'border-transparent text-[#c3c5d8] hover:bg-[#2d3449]'"
      >
        <span class="material-symbols-outlined">settings_applications</span>
      </button>

      <button 
        @click="activeSubTab = 'events'"
        class="flex-1 py-2 flex justify-center hover:text-white border-b-2 transition cursor-pointer"
        :class="activeSubTab === 'events' ? 'bg-[#2E62FF]/10 text-[#2E62FF] border-[#2E62FF]' : 'border-transparent text-[#c3c5d8] hover:bg-[#2d3449]'"
      >
        <span class="material-symbols-outlined">bolt</span>
      </button>

      <button 
        @click="activeSubTab = 'data'"
        class="flex-1 py-2 flex justify-center hover:text-white border-b-2 transition cursor-pointer"
        :class="activeSubTab === 'data' ? 'bg-[#2E62FF]/10 text-[#2E62FF] border-[#2E62FF]' : 'border-transparent text-[#c3c5d8] hover:bg-[#2d3449]'"
      >
        <span class="material-symbols-outlined">database</span>
      </button>

      <button 
        @click="activeSubTab = 'code'"
        class="flex-1 py-2 flex justify-center hover:text-white border-b-2 transition cursor-pointer"
        :class="activeSubTab === 'code' ? 'bg-[#2E62FF]/10 text-[#2E62FF] border-[#2E62FF]' : 'border-transparent text-[#c3c5d8] hover:bg-[#2d3449]'"
      >
        <span class="material-symbols-outlined">fact_check</span>
      </button>

      <button 
        @click="activeSubTab = 'seo'"
        class="flex-1 py-2 flex justify-center hover:text-white border-b-2 transition cursor-pointer"
        :class="activeSubTab === 'seo' ? 'bg-[#2E62FF]/10 text-[#2E62FF] border-[#2E62FF]' : 'border-transparent text-[#c3c5d8] hover:bg-[#2d3449]'"
      >
        <span class="material-symbols-outlined">travel_explore</span>
      </button>

      <button 
        @click="activeSubTab = 'versions'"
        class="flex-1 py-2 flex justify-center hover:text-white border-b-2 transition cursor-pointer"
        :class="activeSubTab === 'versions' ? 'bg-[#2E62FF]/10 text-[#2E62FF] border-[#2E62FF]' : 'border-transparent text-[#c3c5d8] hover:bg-[#2d3449]'"
      >
        <span class="material-symbols-outlined">history</span>
      </button>
    </div>

    <!-- Content Panel -->
    <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-6">
      <!-- Style Panel -->
      <div v-show="activeSubTab === 'style'" class="flex flex-col gap-6">
        <!-- Cart Settings (Only visible if CartDrawer is selected) -->
        <section v-if="isCartDrawerSelected" class="space-y-4 border-b border-[#222530] pb-6">
          <div class="flex items-center justify-between cursor-pointer group">
            <h4 class="text-xs font-bold text-[#8d90a2] uppercase tracking-wide group-hover:text-white transition-colors">Cart Settings</h4>
            <span class="material-symbols-outlined text-[#8d90a2] text-[16px] group-hover:text-white transition-colors">expand_more</span>
          </div>
          
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <label class="text-[11px] text-[#c3c5d8]">Drawer Width</label>
              <div class="flex items-center bg-[#131b2e] rounded border border-[#434656] px-2 py-1 w-20">
                <input class="bg-transparent border-none text-[11px] text-white w-full focus:outline-none text-right" value="360" />
                <span class="text-[10px] text-[#8d90a2] ml-1">px</span>
              </div>
            </div>
            
            <div class="flex items-center justify-between">
              <label class="text-[11px] text-[#c3c5d8]">Overlay Opacity</label>
              <div class="flex items-center bg-[#131b2e] rounded border border-[#434656] px-2 py-1 w-20">
                <input class="bg-transparent border-none text-[11px] text-white w-full focus:outline-none text-right" value="50" />
                <span class="text-[10px] text-[#8d90a2] ml-1">%</span>
              </div>
            </div>

            <div class="space-y-1.5">
              <label class="text-[11px] text-[#c3c5d8]">Redirect URL</label>
              <div class="flex items-center bg-[#131b2e] rounded border border-[#434656] px-2 py-1.5 hover:border-[#8d90a2] transition-colors cursor-pointer">
                <span class="text-[11px] font-mono text-[#c3c5d8] truncate flex-1">/checkout/stripe-sec</span>
                <span class="material-symbols-outlined text-[14px] text-[#8d90a2]">link</span>
              </div>
            </div>
          </div>
        </section>

        <!-- Layout Accordion -->
        <section>
          <div 
            @click="sections.layout = !sections.layout" 
            class="flex items-center justify-between mb-3 cursor-pointer select-none text-[#8d90a2] hover:text-white"
          >
            <span class="text-xs font-bold uppercase tracking-wider">Layout</span>
            <span class="material-symbols-outlined text-xs transition-transform" :class="sections.layout ? 'transform rotate-180' : ''">keyboard_arrow_down</span>
          </div>
          
          <div v-show="sections.layout" class="grid grid-cols-2 gap-3 p-1">
            <div class="col-span-2">
              <label class="text-[10px] font-bold text-[#8d90a2] mb-1.5 block uppercase tracking-wide">Display</label>
              <div class="flex bg-[#060e20] rounded p-0.5 border border-[#434656]">
                <button 
                  v-for="mode in ['flex', 'grid', 'block', 'none']" 
                  :key="mode" 
                  @click="display = mode" 
                  class="flex-1 py-1 text-xs rounded-sm capitalize font-bold transition-all cursor-pointer"
                  :class="display === mode ? 'bg-[#2E62FF] text-white' : 'text-[#c3c5d8] hover:text-white'"
                >
                  {{ mode }}
                </button>
              </div>
            </div>

            <!-- Direction -->
            <div class="flex flex-col gap-1.5">
              <label class="text-[10px] font-bold text-[#8d90a2] uppercase tracking-wide">Direction</label>
              <div class="flex bg-[#060e20] rounded p-0.5 gap-1 border border-[#434656]">
                <button 
                  @click="flexDirection = 'column'" 
                  class="flex-1 py-1 flex justify-center rounded-sm transition-all cursor-pointer"
                  :class="flexDirection === 'column' ? 'bg-[#222a3d] text-white' : 'text-[#c3c5d8] hover:text-white'"
                >
                  <span class="material-symbols-outlined font-bold text-sm">south</span>
                </button>
                <button 
                  @click="flexDirection = 'row'" 
                  class="flex-1 py-1 flex justify-center rounded-sm transition-all cursor-pointer"
                  :class="flexDirection === 'row' ? 'bg-[#222a3d] text-white' : 'text-[#c3c5d8] hover:text-white'"
                >
                  <span class="material-symbols-outlined font-bold text-sm">east</span>
                </button>
              </div>
            </div>

            <!-- Align -->
            <div class="flex flex-col gap-1.5">
              <label class="text-[10px] font-bold text-[#8d90a2] uppercase tracking-wide">Align</label>
              <div class="flex bg-[#060e20] rounded p-0.5 gap-1 border border-[#434656]">
                <button 
                  @click="alignItems = 'stretch'" 
                  class="flex-1 py-1 flex justify-center rounded-sm transition-all cursor-pointer"
                  :class="alignItems === 'stretch' ? 'bg-[#222a3d] text-white' : 'text-[#c3c5d8] hover:text-white'"
                >
                  <span class="material-symbols-outlined text-sm">align_items_stretch</span>
                </button>
                <button 
                  @click="alignItems = 'center'" 
                  class="flex-1 py-1 flex justify-center rounded-sm transition-all cursor-pointer"
                  :class="alignItems === 'center' ? 'bg-[#222a3d] text-white' : 'text-[#c3c5d8] hover:text-white'"
                >
                  <span class="material-symbols-outlined text-sm">align_center</span>
                </button>
              </div>
            </div>
          </div>
        </section>

        <!-- Spacing Accordion -->
        <section>
          <div 
            @click="sections.spacing = !sections.spacing" 
            class="flex items-center justify-between mb-3 cursor-pointer select-none text-[#8d90a2] hover:text-white"
          >
            <span class="text-xs font-bold uppercase tracking-wider">Spacing</span>
            <span class="material-symbols-outlined text-xs transition-transform" :class="sections.spacing ? 'transform rotate-180' : ''">keyboard_arrow_down</span>
          </div>
          
          <div v-show="sections.spacing" class="relative w-full aspect-[4/3] bg-[#060e20] border border-dashed border-[#434656] rounded p-8 flex items-center justify-center select-none font-mono">
            <!-- M Label -->
            <span class="absolute top-1 left-1.5 text-[8px] text-[#8d90a2] font-bold uppercase">M</span>
            
            <!-- Margin Inputs -->
            <input v-model="marginTop" placeholder="0" class="absolute top-1.5 left-1/2 -translate-x-1/2 w-12 bg-transparent text-center text-[#dae2fd] text-[10px] focus:outline-none border-none p-0 focus:text-white" />
            <input v-model="marginLeft" placeholder="0" class="absolute left-1.5 top-1/2 -translate-y-1/2 w-12 bg-transparent text-center text-[#dae2fd] text-[10px] focus:outline-none border-none p-0 focus:text-white" />
            <input v-model="marginRight" placeholder="0" class="absolute right-1.5 top-1/2 -translate-y-1/2 w-12 bg-transparent text-center text-[#dae2fd] text-[10px] focus:outline-none border-none p-0 focus:text-white" />
            <input v-model="marginBottom" placeholder="0" class="absolute bottom-1.5 left-1/2 -translate-x-1/2 w-12 bg-transparent text-center text-[#dae2fd] text-[10px] focus:outline-none border-none p-0 focus:text-white" />

            <!-- Inner Padding Box -->
            <div class="w-full h-full bg-[#2E62FF]/5 border border-[#2E62FF]/30 relative flex items-center justify-center rounded-sm">
              <!-- P Label -->
              <span class="absolute top-1 left-1.5 text-[8px] text-[#2E62FF] font-bold uppercase">P</span>

              <!-- Padding Inputs -->
              <input v-model="paddingTop" placeholder="0" class="absolute top-1 left-1/2 -translate-x-1/2 w-12 bg-transparent text-center text-[#2E62FF] text-[10px] focus:outline-none border-none p-0 focus:text-white" />
              <input v-model="paddingLeft" placeholder="0" class="absolute left-1.5 top-1/2 -translate-y-1/2 w-12 bg-transparent text-center text-[#2E62FF] text-[10px] focus:outline-none border-none p-0 focus:text-white" />
              <input v-model="paddingRight" placeholder="0" class="absolute right-1.5 top-1/2 -translate-y-1/2 w-12 bg-transparent text-center text-[#2E62FF] text-[10px] focus:outline-none border-none p-0 focus:text-white" />
              <input v-model="paddingBottom" placeholder="0" class="absolute bottom-1 left-1/2 -translate-x-1/2 w-12 bg-transparent text-center text-[#2E62FF] text-[10px] focus:outline-none border-none p-0 focus:text-white" />

              <!-- Center Box -->
              <div class="w-5 h-5 bg-[#2E62FF]/10 border border-[#2E62FF]/20 rounded-sm"></div>
            </div>
          </div>
        </section>

        <!-- Size Accordion -->
        <section>
          <div 
            @click="sections.size = !sections.size" 
            class="flex items-center justify-between mb-3 cursor-pointer select-none text-[#8d90a2] hover:text-white"
          >
            <span class="text-xs font-bold uppercase tracking-wider">Size</span>
            <span class="material-symbols-outlined text-xs transition-transform" :class="sections.size ? 'transform rotate-180' : ''">keyboard_arrow_down</span>
          </div>

          <div v-show="sections.size" class="grid grid-cols-2 gap-3">
            <div class="flex flex-col gap-1">
              <label class="text-[9px] text-[#8d90a2] uppercase font-bold tracking-wider">Width</label>
              <div class="flex items-center bg-[#0B0E14] border border-[#434656] rounded h-7 focus-within:border-[#2E62FF] overflow-hidden">
                <input v-model="widthValue" placeholder="100" class="min-w-0 flex-1 bg-transparent border-none text-[#dae2fd] text-xs py-1.5 px-2 focus:outline-none font-semibold font-mono" />
                <select v-model="widthUnit" @change="commitUnitSize('width')" class="editor-select w-12 h-full border-l border-[#434656] bg-[#060e20] text-[#8d90a2] text-[10px] font-bold outline-none">
                  <option value="%">%</option>
                  <option value="px">px</option>
                  <option value="vw">vw</option>
                  <option value="auto">auto</option>
                </select>
              </div>
            </div>
            
            <div class="flex flex-col gap-1">
              <label class="text-[9px] text-[#8d90a2] uppercase font-bold tracking-wider">Height</label>
              <div class="flex items-center bg-[#0B0E14] border border-[#434656] rounded h-7 focus-within:border-[#2E62FF] overflow-hidden">
                <input v-model="heightValue" placeholder="Auto" class="min-w-0 flex-1 bg-transparent border-none text-[#dae2fd] text-xs py-1.5 px-2 focus:outline-none font-semibold font-mono" />
                <select v-model="heightUnit" @change="commitUnitSize('height')" class="editor-select w-12 h-full border-l border-[#434656] bg-[#060e20] text-[#8d90a2] text-[10px] font-bold outline-none">
                  <option value="auto">auto</option>
                  <option value="px">px</option>
                  <option value="%">%</option>
                  <option value="vh">vh</option>
                </select>
              </div>
            </div>
          </div>
        </section>

        <!-- Typography Accordion -->
        <section>
          <div 
            @click="sections.typography = !sections.typography" 
            class="flex items-center justify-between mb-3 cursor-pointer select-none text-[#8d90a2] hover:text-white"
          >
            <span class="text-xs font-bold uppercase tracking-wider">Typography</span>
            <span class="material-symbols-outlined text-xs transition-transform" :class="sections.typography ? 'transform rotate-180' : ''">keyboard_arrow_down</span>
          </div>

          <div v-show="sections.typography" class="space-y-3">
            <!-- Font Family -->
            <div>
              <label class="text-[10px] font-bold text-[#8d90a2] mb-1 block uppercase tracking-wide">Font Family</label>
              <div class="relative">
                <button
                  @click="openFontMenu = !openFontMenu; openWeightMenu = false"
                  class="w-full flex items-center justify-between bg-[#060e20] p-2 rounded border border-[#434656] text-white text-xs font-semibold hover:border-[#2E62FF] transition"
                >
                  <span>{{ currentFontLabel }}</span>
                  <span class="material-symbols-outlined text-xs text-[#8d90a2]">unfold_more</span>
                </button>
                <div v-if="openFontMenu" class="absolute left-0 right-0 top-full mt-1 z-50 bg-[#0B0E14] border border-[#434656] rounded shadow-xl overflow-hidden">
                  <button
                    v-for="font in fontFamilyOptions"
                    :key="font.value"
                    @click="fontFamily = font.value; openFontMenu = false"
                    class="w-full px-3 py-2 text-left text-xs hover:bg-[#2d3449] transition"
                    :class="fontFamily === font.value ? 'text-[#2E62FF] bg-[#2E62FF]/10' : 'text-[#dae2fd]'"
                  >
                    {{ font.label }}
                  </button>
                </div>
              </div>
            </div>

            <!-- Weight and Size -->
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="text-[10px] font-bold text-[#8d90a2] mb-1 block uppercase tracking-wide">Weight</label>
                <div class="relative">
                  <button
                    @click="openWeightMenu = !openWeightMenu; openFontMenu = false"
                    class="w-full flex items-center justify-between bg-[#060e20] p-2 rounded border border-[#434656] text-white text-xs font-semibold hover:border-[#2E62FF] transition"
                  >
                    <span>{{ currentWeightLabel }}</span>
                    <span class="material-symbols-outlined text-xs text-[#8d90a2]">unfold_more</span>
                  </button>
                  <div v-if="openWeightMenu" class="absolute left-0 right-0 top-full mt-1 z-50 bg-[#0B0E14] border border-[#434656] rounded shadow-xl overflow-hidden">
                    <button
                      v-for="weight in fontWeightOptions"
                      :key="weight.value"
                      @click="fontWeight = weight.value; openWeightMenu = false"
                      class="w-full px-3 py-2 text-left text-xs hover:bg-[#2d3449] transition"
                      :class="fontWeight === weight.value ? 'text-[#2E62FF] bg-[#2E62FF]/10' : 'text-[#dae2fd]'"
                    >
                      {{ weight.label }}
                    </button>
                  </div>
                </div>
              </div>
              
              <div>
                <label class="text-[10px] font-bold text-[#8d90a2] mb-1 block uppercase tracking-wide">Size</label>
                <div class="flex items-center bg-[#060e20] rounded border border-[#434656] px-2 focus-within:border-[#2E62FF]">
                  <input v-model="fontSize" placeholder="16" class="w-full bg-transparent border-none text-right text-white text-xs py-2 focus:outline-none font-semibold font-mono" />
                  <span class="text-[#8d90a2] pl-1 text-[10px] font-bold">PX</span>
                </div>
              </div>
            </div>

            <!-- Text Align -->
            <div class="grid grid-cols-4 gap-1 bg-[#060e20] p-0.5 rounded border border-[#434656]">
              <button 
                v-for="align in ['left', 'center', 'right', 'justify']" 
                :key="align"
                @click="textAlign = align" 
                class="py-1 flex justify-center rounded-sm transition-all cursor-pointer"
                :class="textAlign === align ? 'bg-[#222a3d] text-white' : 'text-[#c3c5d8] hover:text-white'"
              >
                <span class="material-symbols-outlined text-base">
                  {{ align === 'left' ? 'format_align_left' : align === 'center' ? 'format_align_center' : align === 'right' ? 'format_align_right' : 'format_align_justify' }}
                </span>
              </button>
            </div>
          </div>
        </section>

        <!-- Effects Accordion -->
        <section class="border-t border-[#434656]/30 pt-3">
          <div 
            @click="sections.effects = !sections.effects" 
            class="flex items-center justify-between mb-3 cursor-pointer select-none text-[#8d90a2] hover:text-white"
          >
            <span class="text-xs font-bold uppercase tracking-wider">Effects</span>
            <span class="material-symbols-outlined text-xs">
              {{ sections.effects ? 'remove' : 'add' }}
            </span>
          </div>
          
          <div v-show="sections.effects" class="space-y-3">
            <!-- Border Radius -->
            <div>
              <label class="text-[10px] font-bold text-[#8d90a2] mb-1 block uppercase tracking-wide">Border Radius</label>
              <div class="flex items-center bg-[#060e20] rounded border border-[#434656] px-2 focus-within:border-[#2E62FF]">
                <input v-model="borderRadius" placeholder="0" class="w-full bg-transparent border-none text-right text-white text-xs py-2 focus:outline-none font-semibold font-mono" />
                <span class="text-[#8d90a2] pl-1 text-[10px] font-bold">px</span>
              </div>
            </div>

            <!-- Background Color -->
            <div>
              <label class="text-[10px] font-bold text-[#8d90a2] mb-1 block uppercase tracking-wide">Bg Color</label>
              <div class="flex bg-[#060e20] border border-[#434656] rounded-lg items-center px-2 focus-within:border-[#2E62FF]">
                <input type="color" v-model="backgroundColor" class="w-6 h-6 bg-transparent border-none focus:outline-none cursor-pointer rounded" />
                <input v-model="backgroundColor" placeholder="#ffffff" class="w-full bg-transparent border-none text-white text-xs py-2 px-2 focus:outline-none uppercase font-semibold font-mono" />
              </div>
            </div>
          </div>
        </section>

        <!-- Hidden container to satisfy GrapesJS style manager init -->
        <div id="styles-container" class="hidden"></div>
        <div id="traits-container" class="hidden"></div>
      </div>

      <!-- Settings Panel (Attributes and Visibility) -->
      <div v-show="activeSubTab === 'settings'" class="flex flex-col gap-6">
        <section class="relative overflow-hidden rounded-lg border border-[#2E62FF]/30 bg-[#060e20] p-3 shadow-lg shadow-[#2E62FF]/5">
          <div class="absolute left-0 top-0 h-full w-1 bg-[#2E62FF]"></div>
          <div class="flex items-center justify-between pl-2">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-[18px] text-[#2E62FF]" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
              <span class="text-[11px] font-bold uppercase tracking-wider text-[#2E62FF]">Gemini 3.5 Flash Active</span>
            </div>
            <button @click="loadSelectedCopy" class="text-[10px] font-bold text-[#8d90a2] hover:text-white">Load</button>
          </div>
          <textarea
            v-model="aiCopyInput"
            class="mt-3 h-20 w-full resize-none rounded border border-[#434656] bg-[#0B0E14] p-2 text-xs leading-relaxed text-[#dae2fd] outline-none focus:border-[#2E62FF]"
            placeholder="Select text on canvas or type copy here."
          ></textarea>
          <div class="mt-2 grid grid-cols-3 gap-1.5">
            <button @click="runCopyAssist('rewrite')" class="rounded border border-[#434656] py-1.5 text-[10px] font-bold text-[#dae2fd] hover:border-[#2E62FF]">Rewrite</button>
            <button @click="runCopyAssist('shorten')" class="rounded border border-[#2E62FF] bg-[#2E62FF]/10 py-1.5 text-[10px] font-bold text-[#dae2fd] hover:bg-[#2E62FF]/20">Shorten</button>
            <button @click="runCopyAssist('professional')" class="rounded border border-[#434656] py-1.5 text-[10px] font-bold text-[#dae2fd] hover:border-[#2E62FF]">Prof.</button>
          </div>
          <div v-if="aiCopyStatus" class="mt-2 font-mono text-[10px]" :class="aiCopyError ? 'text-[#EF4444]' : 'text-[#8d90a2]'">{{ aiCopyStatus }}</div>
          <div v-if="aiCopyOutput" class="mt-3 rounded border border-[#434656] bg-[#0B0E14] p-2">
            <p class="text-xs leading-relaxed text-[#dae2fd]">{{ aiCopyOutput }}</p>
            <button @click="applyCopyAssist" class="mt-2 w-full rounded bg-[#2E62FF] py-1.5 text-[10px] font-bold uppercase tracking-wider text-white hover:brightness-110">Apply to Selected</button>
          </div>
        </section>

        <!-- Attribute Configuration -->
        <div class="space-y-4 border-b border-[#434656] pb-5">
          <div class="space-y-1.5">
            <label class="text-[#8d90a2] text-[10px] font-bold uppercase tracking-wider">Element ID</label>
            <input 
              v-model="elementId" 
              class="w-full h-7 bg-[#0B0E14] border border-[#434656] focus:border-[#2E62FF] focus:ring-1 focus:ring-[#2E62FF] rounded-sm text-xs font-mono text-[#dae2fd] px-2 outline-none" 
              type="text" 
            />
          </div>
          
          <div class="space-y-1.5">
            <label class="text-[#8d90a2] text-[10px] font-bold uppercase tracking-wider">Alt Text</label>
            <textarea 
              v-model="elementAlt" 
              class="w-full h-16 bg-[#0B0E14] border border-[#434656] focus:border-[#2E62FF] focus:ring-1 focus:ring-[#2E62FF] rounded-sm text-xs text-[#dae2fd] px-2 py-1 outline-none resize-none font-semibold"
            ></textarea>
          </div>
          
          <div class="space-y-1.5">
            <label class="text-[#8d90a2] text-[10px] font-bold uppercase tracking-wider">Link (Href)</label>
            <div class="flex rounded-sm overflow-hidden">
              <div class="bg-[#2d3449] border border-r-0 border-[#434656] px-2 flex items-center">
                <span class="material-symbols-outlined text-[14px] text-[#dae2fd]">link</span>
              </div>
              <input 
                v-model="elementHref" 
                class="flex-1 h-7 bg-[#0B0E14] border border-[#434656] focus:border-[#2E62FF] focus:ring-0 rounded-r-sm text-xs font-mono text-[#dae2fd] px-2 outline-none" 
                placeholder="https://" 
                type="text" 
              />
            </div>
          </div>
        </div>

        <!-- Responsive Visibility Section -->
        <div class="space-y-4">
          <label class="text-[#8d90a2] text-[10px] font-bold uppercase tracking-wider flex items-center justify-between">
            Responsive Visibility
            <span class="material-symbols-outlined text-sm">visibility</span>
          </label>
          
          <div class="space-y-2">
            <!-- Desktop Toggle -->
            <div class="flex items-center justify-between p-2 bg-[#131b2e] border border-[#434656] rounded-sm">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#c3c5d8] text-sm">desktop_windows</span>
                <span class="text-xs font-bold text-[#dae2fd] uppercase tracking-wide">Desktop</span>
              </div>
              <div 
                @click="visibilityDesktop = !visibilityDesktop"
                class="w-8 h-4 rounded-full relative cursor-pointer transition-colors"
                :class="visibilityDesktop ? 'bg-[#2E62FF]' : 'bg-[#434656]'"
              >
                <div class="absolute top-1 w-2 h-2 bg-white rounded-full transition-all" :class="visibilityDesktop ? 'right-1' : 'left-1'"></div>
              </div>
            </div>

            <!-- Tablet Toggle -->
            <div class="flex items-center justify-between p-2 bg-[#131b2e] border border-[#434656] rounded-sm">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#c3c5d8] text-sm">tablet_mac</span>
                <span class="text-xs font-bold text-[#dae2fd] uppercase tracking-wide">Tablet</span>
              </div>
              <div 
                @click="visibilityTablet = !visibilityTablet"
                class="w-8 h-4 rounded-full relative cursor-pointer transition-colors"
                :class="visibilityTablet ? 'bg-[#2E62FF]' : 'bg-[#434656]'"
              >
                <div class="absolute top-1 w-2 h-2 bg-white rounded-full transition-all" :class="visibilityTablet ? 'right-1' : 'left-1'"></div>
              </div>
            </div>

            <!-- Mobile Toggle -->
            <div class="flex items-center justify-between p-2 bg-[#131b2e] border border-[#434656] rounded-sm">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#c3c5d8] text-sm">smartphone</span>
                <span class="text-xs font-bold text-[#dae2fd] uppercase tracking-wide">Mobile</span>
              </div>
              <div 
                @click="visibilityMobile = !visibilityMobile"
                class="w-8 h-4 rounded-full relative cursor-pointer transition-colors"
                :class="visibilityMobile ? 'bg-[#2E62FF]' : 'bg-[#434656]'"
              >
                <div class="absolute top-1 w-2 h-2 bg-white rounded-full transition-all" :class="visibilityMobile ? 'right-1' : 'left-1'"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Discard / Apply Footer Buttons -->
        <div class="pt-4 flex gap-2 border-t border-[#434656]">
          <button @click="discardAttributes" class="flex-1 py-1.5 border border-[#434656] rounded text-xs font-bold uppercase tracking-wider text-white hover:bg-[#2d3449] transition-colors cursor-pointer text-center">Discard</button>
          <button @click="applyAttributes" class="flex-1 py-1.5 bg-[#2E62FF] text-white rounded text-xs font-bold uppercase tracking-wider hover:brightness-110 transition-all cursor-pointer text-center">Apply</button>
        </div>
      </div>

      <!-- Events/Interactions Panel (Bolt) -->
      <div v-show="activeSubTab === 'events'" class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
          <span class="text-[#c3c5d8] text-xs font-bold uppercase tracking-wider">Triggers</span>
          <span class="material-symbols-outlined text-[#2E62FF] cursor-pointer hover:text-white">add</span>
        </div>
        
        <!-- Trigger list -->
        <div class="space-y-3">
          <!-- On Click Trigger (Expanded) -->
          <div class="bg-[#060e20] border border-[#434656] rounded-sm overflow-hidden">
            <div 
              @click="sections.timeline = !sections.timeline"
              class="p-2 flex items-center justify-between border-b border-[#434656] bg-[#222a3d]/50 cursor-pointer"
            >
              <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm text-[#2E62FF]">mouse</span>
                <span class="text-xs font-bold text-white uppercase tracking-wider">On Click</span>
              </div>
              <span class="material-symbols-outlined text-sm text-[#8d90a2] transition-transform" :class="sections.timeline ? 'transform rotate-180' : ''">expand_more</span>
            </div>
            
            <div v-show="sections.timeline" class="p-2 flex flex-col gap-3">
              <div class="flex items-center justify-between text-[9px] font-mono text-[#8d90a2]">
                <span>Timeline (0.8s)</span>
                <div class="flex gap-1.5">
                  <button @click.stop="interactionTrigger = 'click'; previewInteraction()" class="material-symbols-outlined text-sm cursor-pointer hover:text-white" title="Preview click interaction">play_arrow</button>
                  <button @click.stop="loopInteraction = !loopInteraction" class="material-symbols-outlined text-sm cursor-pointer" :class="loopInteraction ? 'text-[#2E62FF]' : 'hover:text-white'" title="Loop preview">loop</button>
                </div>
              </div>
              
              <!-- Timeline Tracks box -->
              <div @click="setTimelineFromPointer" class="relative h-24 bg-[#0B0E14] border border-[#434656] rounded-sm overflow-hidden cursor-pointer">
                <!-- Ruler grid mockup background -->
                <div class="absolute inset-0 opacity-10 bg-repeat-x" style="background-image: repeating-linear-gradient(90deg, #334156 0px, #334156 1px, transparent 1px, transparent 40px); background-size: 40px 100%;"></div>
                
                <!-- Playhead -->
                <div class="absolute top-0 bottom-0 w-[1px] bg-[#2E62FF] z-10 shadow-[0_0_8px_#2E62FF]" :style="{ left: `${timelinePosition}%` }">
                  <div class="absolute -top-1 -left-1 w-2 h-2 bg-[#2E62FF] rotate-45"></div>
                </div>
                
                <!-- Timeline Tracks list -->
                <div class="flex flex-col gap-2 p-2 relative z-0">
                  <!-- Move track -->
                  <div class="flex items-center gap-2">
                    <div class="w-12 text-[9px] font-mono text-gray-500 uppercase">Move</div>
                    <div class="flex-1 h-1 bg-[#2d3449] rounded-full relative">
                      <div class="absolute left-0 w-[40%] h-full bg-[#2E62FF] rounded-full"></div>
                      <button @click.stop="setActionFromTimeline('move', 0)" class="absolute left-0 w-2 h-2 -top-[2px] bg-white rounded-full hover:bg-[#2E62FF]"></button>
                      <button @click.stop="setActionFromTimeline('move', 40)" class="absolute left-[40%] w-2 h-2 -top-[2px] bg-white rounded-full hover:bg-[#2E62FF]"></button>
                    </div>
                  </div>
                  <!-- Scale track -->
                  <div class="flex items-center gap-2">
                    <div class="w-12 text-[9px] font-mono text-gray-500 uppercase">Scale</div>
                    <div class="flex-1 h-1 bg-[#2d3449] rounded-full relative">
                      <div class="absolute left-[20%] w-[50%] h-full bg-[#8B5CF6] rounded-full"></div>
                      <button @click.stop="setActionFromTimeline('scale', 20)" class="absolute left-[20%] w-2 h-2 -top-[2px] bg-white rounded-full hover:bg-[#8B5CF6]"></button>
                      <button @click.stop="setActionFromTimeline('scale', 70)" class="absolute left-[70%] w-2 h-2 -top-[2px] bg-white rounded-full hover:bg-[#8B5CF6]"></button>
                    </div>
                  </div>
                  <!-- Opacity track -->
                  <div class="flex items-center gap-2">
                    <div class="w-12 text-[9px] font-mono text-gray-500 uppercase">Opacity</div>
                    <div class="flex-1 h-1 bg-[#2d3449] rounded-full relative">
                      <div class="absolute left-[10%] w-[60%] h-full bg-[#10B981] rounded-full"></div>
                      <button @click.stop="setActionFromTimeline('opacity', 10)" class="absolute left-[10%] w-2 h-2 -top-[2px] bg-white rounded-full hover:bg-[#10B981]"></button>
                      <button @click.stop="setActionFromTimeline('opacity', 70)" class="absolute left-[70%] w-2 h-2 -top-[2px] bg-white rounded-full hover:bg-[#10B981]"></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- On Hover Trigger (Collapsed) -->
          <div class="bg-[#060e20] border border-[#434656] rounded-sm overflow-hidden">
            <div @click="sections.hover = !sections.hover" class="p-2 flex items-center justify-between cursor-pointer hover:bg-[#2d3449] transition-colors">
              <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm" :class="interactionTrigger === 'hover' ? 'text-[#2E62FF]' : 'text-[#8d90a2]'">ads_click</span>
                <span class="text-xs font-bold text-white uppercase tracking-wider">On Hover</span>
              </div>
              <span class="material-symbols-outlined text-sm text-[#8d90a2] transition-transform" :class="sections.hover ? 'transform rotate-180' : ''">expand_more</span>
            </div>
            <div v-show="sections.hover" class="p-3 border-t border-[#434656] space-y-2">
              <button @click="interactionTrigger = 'hover'; previewInteraction()" class="w-full py-1.5 bg-[#131b2e] border border-[#434656] text-[#dae2fd] rounded-sm text-xs hover:border-[#2E62FF]">Preview Hover Animation</button>
              <p class="text-[11px] text-[#8d90a2] leading-relaxed">Hover trigger uses same Action Settings below and is saved into selected element attributes.</p>
            </div>
          </div>
          <div class="space-y-1">
            <label class="text-[9px] font-mono text-[#8d90a2] uppercase font-bold tracking-wider">Timeline Position</label>
            <input v-model.number="timelinePosition" type="range" min="0" max="100" class="w-full accent-[#2E62FF]">
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-[9px] font-mono text-[#8d90a2] uppercase font-bold tracking-wider">Delay</label>
            <div class="h-7 bg-[#0B0E14] border border-[#434656] rounded px-2 flex items-center justify-between">
              <input v-model="interactionDelay" class="bg-transparent border-none p-0 text-xs font-mono text-[#dae2fd] w-14 focus:ring-0 outline-none font-semibold" />
              <span class="text-[9px] font-mono text-[#8d90a2]">ms</span>
            </div>
          </div>
        </div>

        <!-- Action Settings -->
        <div class="p-3 border border-[#434656] rounded-sm bg-[#060e20]/30 space-y-3">
          <span class="text-xs font-bold text-[#c3c5d8] uppercase tracking-wider block">Action Settings</span>
          
          <div class="grid grid-cols-2 gap-3">
            <div class="flex flex-col gap-1 col-span-2">
              <label class="text-[9px] font-mono text-[#8d90a2] uppercase font-bold tracking-wider">Trigger</label>
              <select v-model="interactionTrigger" class="editor-select h-7 bg-[#0B0E14] border border-[#434656] rounded px-2 text-xs text-[#dae2fd] outline-none">
                <option value="hover">Hover</option>
                <option value="click">Click</option>
                <option value="scroll-into-view">Scroll Into View</option>
                <option value="page-load">Page Load</option>
              </select>
            </div>
            <div class="flex flex-col gap-1 col-span-2">
              <label class="text-[9px] font-mono text-[#8d90a2] uppercase font-bold tracking-wider">Action</label>
              <select v-model="interactionAction" class="editor-select h-7 bg-[#0B0E14] border border-[#434656] rounded px-2 text-xs text-[#dae2fd] outline-none">
                <option value="move">Move</option>
                <option value="scale">Scale</option>
                <option value="rotate">Rotate</option>
                <option value="opacity">Opacity</option>
                <option value="blur">Blur</option>
              </select>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-[9px] font-mono text-[#8d90a2] uppercase font-bold tracking-wider">Duration</label>
              <div class="h-7 bg-[#0B0E14] border border-[#434656] rounded px-2 flex items-center justify-between">
                <input v-model="moveDuration" class="bg-transparent border-none p-0 text-xs font-mono text-[#dae2fd] w-12 focus:ring-0 outline-none font-semibold" />
                <span class="text-[9px] font-mono text-[#8d90a2]">ms</span>
              </div>
            </div>
            
            <div class="flex flex-col gap-1">
              <label class="text-[9px] font-mono text-[#8d90a2] uppercase font-bold tracking-wider">Easing</label>
              <div class="h-7 bg-[#0B0E14] border border-[#434656] rounded px-2 flex items-center justify-between cursor-pointer relative">
                <select v-model="moveEasing" class="editor-select w-full bg-transparent border-none text-[#dae2fd] text-xs font-semibold focus:outline-none appearance-none cursor-pointer font-mono pr-4">
                  <option value="ease-out">Ease-Out</option>
                  <option value="linear">Linear</option>
                  <option value="ease-in-out">Ease-In-Out</option>
                </select>
                <span class="material-symbols-outlined text-xs text-[#8d90a2] absolute right-1 pointer-events-none">unfold_more</span>
              </div>
            </div>

            <div class="flex flex-col gap-1">
              <label class="text-[9px] font-mono text-[#8d90a2] uppercase font-bold tracking-wider">X Offset</label>
              <div class="h-7 bg-[#0B0E14] border border-[#434656] rounded px-2 flex items-center justify-between">
                <input v-model="moveX" class="bg-transparent border-none p-0 text-xs font-mono text-[#dae2fd] w-12 focus:ring-0 outline-none font-semibold" />
                <span class="text-[9px] font-mono text-[#8d90a2]">px</span>
              </div>
            </div>

            <div class="flex flex-col gap-1">
              <label class="text-[9px] font-mono text-[#8d90a2] uppercase font-bold tracking-wider">Y Offset</label>
              <div class="h-7 bg-[#0B0E14] border border-[#434656] rounded px-2 flex items-center justify-between">
                <input v-model="moveY" class="bg-transparent border-none p-0 text-xs font-mono text-[#dae2fd] w-12 focus:ring-0 outline-none font-semibold" />
                <span class="text-[9px] font-mono text-[#8d90a2]">px</span>
              </div>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-2 pt-2">
            <button @click="previewInteraction" class="py-1.5 border border-[#434656] text-white rounded-sm text-xs font-bold hover:bg-[#2d3449]">Preview</button>
            <button @click="applyInteraction" class="py-1.5 bg-[#2E62FF] text-white rounded-sm text-xs font-bold hover:brightness-110">Apply</button>
          </div>
        </div>
      </div>

      <!-- Data Binding Panel -->
      <div v-show="activeSubTab === 'data'" class="flex flex-col gap-6">
        <!-- Data Binding -->
        <section class="space-y-4">
          <div class="flex items-center justify-between">
            <h4 class="text-xs font-bold text-white tracking-wide">Data Binding</h4>
            <button @click="showDataInfo = !showDataInfo" class="material-symbols-outlined text-[#2E62FF] text-[16px] cursor-pointer hover:brightness-125">info</button>
          </div>
          <p v-if="showDataInfo" class="text-[11px] text-[#8d90a2] bg-[#060e20] border border-[#434656] p-2 rounded leading-relaxed">Binding writes `data-cms-source`, `data-cms-field`, and optional visibility rules to selected element. Export keeps attributes for runtime integration.</p>
          
          <div class="space-y-4">
            <div class="space-y-2">
              <label class="text-[10px] text-[#8d90a2] uppercase tracking-wide font-medium">COLLECTION SOURCE</label>
              <select v-model="dataSource" class="w-full bg-[#131b2e] rounded border border-[#434656] px-3 py-2 text-[11px] text-[#c3c5d8] outline-none">
                <option value="products">Products</option>
                <option v-for="collection in cmsCollections" :key="collection.id" :value="collection.slug">{{ collection.name }}</option>
              </select>
            </div>
            
            <div class="space-y-2">
              <label class="text-[10px] text-[#8d90a2] uppercase tracking-wide font-medium">FIELD MAPPING</label>
              <select v-model="dataField" class="w-full bg-[#131b2e] rounded border border-[#434656] px-3 py-2 text-[11px] text-[#c3c5d8] outline-none">
                <option v-for="field in availableDataFields" :key="field.key" :value="field.key">{{ field.name }}</option>
              </select>
            </div>
            <button @click="applyDataBinding" class="w-full py-2 bg-[#2E62FF] text-white rounded-sm text-xs font-bold">Apply Binding</button>
          </div>
        </section>

        <!-- Conditional Visibility -->
        <section class="space-y-3">
          <h4 class="text-xs font-bold text-white tracking-wide">Conditional Visibility</h4>
          <div class="flex items-center justify-between bg-[#131b2e] rounded border border-[#434656] px-3 py-2 cursor-pointer hover:border-[#8d90a2] transition-colors group">
            <span class="text-[11px] text-[#c3c5d8]">Only show if 'in_stock'</span>
            <div class="w-4 h-4 rounded bg-[#2E62FF] flex items-center justify-center">
              <span class="material-symbols-outlined text-white text-[12px] font-bold">check</span>
            </div>
          </div>
        </section>

        <!-- Data Healthy -->
        <section class="bg-[#131b2e] rounded border border-[#434656] p-3 space-y-2">
          <div class="flex items-center gap-2 text-[#2E62FF]">
            <span class="material-symbols-outlined text-[16px]">verified</span>
            <span class="text-[11px] font-bold tracking-wide">Data Healthy</span>
          </div>
          <p class="text-[11px] text-[#8d90a2] leading-relaxed">
            All bound fields are correctly resolved in the current workspace.
          </p>
        </section>
      </div>

      <!-- Code Panel -->
      <div v-show="activeSubTab === 'code'" class="p-2 space-y-4">
        <span class="text-white text-xs font-bold uppercase tracking-wider block">Export Code</span>
        <section v-if="isCodeEmbedSelected" class="p-3 bg-[#060e20] border border-[#434656] rounded space-y-3">
          <div class="text-xs font-bold text-white uppercase tracking-wider">Code Embed</div>
          <textarea v-model="codeEmbedHtml" class="w-full h-24 bg-[#0B0E14] border border-[#434656] rounded text-xs text-[#dae2fd] font-mono p-2 outline-none" placeholder="HTML"></textarea>
          <textarea v-model="codeEmbedCss" class="w-full h-20 bg-[#0B0E14] border border-[#434656] rounded text-xs text-[#dae2fd] font-mono p-2 outline-none" placeholder="CSS"></textarea>
          <textarea v-model="codeEmbedJs" class="w-full h-20 bg-[#0B0E14] border border-[#434656] rounded text-xs text-[#dae2fd] font-mono p-2 outline-none" placeholder="JavaScript"></textarea>
          <button @click="applyCodeEmbed" class="w-full py-2 bg-[#2E62FF] text-white rounded-sm text-xs font-bold">Apply Embed</button>
        </section>
        <section class="p-3 bg-[#060e20] border border-[#434656] rounded space-y-3">
          <div class="text-xs font-bold text-white uppercase tracking-wider">Page Scripts</div>
          <textarea v-model="headCode" class="w-full h-20 bg-[#0B0E14] border border-[#434656] rounded text-xs text-[#dae2fd] font-mono p-2 outline-none" placeholder="Custom <head> code"></textarea>
          <textarea v-model="bodyCode" class="w-full h-20 bg-[#0B0E14] border border-[#434656] rounded text-xs text-[#dae2fd] font-mono p-2 outline-none" placeholder="Custom before </body> code"></textarea>
        </section>
        <div class="p-3 bg-[#060e20] border border-[#434656] rounded text-[10px] font-mono text-gray-400 space-y-2 overflow-x-auto">
          <div>// HTML Code Snippet</div>
          <pre class="text-[#10B981] whitespace-pre-wrap">{{ selectedHtml }}</pre>
          <div class="pt-2 border-t border-[#434656]/30">// CSS Code Snippet</div>
          <pre class="text-[#8B5CF6] whitespace-pre-wrap">{{ selectedCss }}</pre>
        </div>
      </div>

      <!-- SEO Panel -->
      <div v-show="activeSubTab === 'seo'" class="flex flex-col gap-5">
        <section class="relative overflow-hidden rounded-lg border border-[#2E62FF]/30 bg-[#060e20] p-3">
          <div class="absolute left-0 top-0 h-full w-1 bg-[#2E62FF]"></div>
          <div class="flex items-center justify-between pl-2">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-[18px] text-[#2E62FF]" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
              <span class="text-[11px] font-bold uppercase tracking-wider text-[#2E62FF]">AI SEO Optimizer</span>
            </div>
            <span class="font-mono text-[9px] text-[#10B981]">FLASH</span>
          </div>
          <p class="mt-2 pl-2 text-[11px] leading-relaxed text-[#8d90a2]">Scan canvas copy and fill title, description, Open Graph text, keywords, and image alt suggestions.</p>
          <button @click="runSeoAssist" class="mt-3 w-full rounded border border-[#2E62FF]/50 bg-[#2E62FF]/10 py-2 text-xs font-bold text-[#dae2fd] hover:bg-[#2E62FF] hover:text-white">
            {{ seoAssistLoading ? 'Optimizing...' : 'Generate SEO Suggestions' }}
          </button>
          <div v-if="seoAssistStatus" class="mt-2 font-mono text-[10px]" :class="seoAssistError ? 'text-[#EF4444]' : 'text-[#8d90a2]'">{{ seoAssistStatus }}</div>
          <div v-if="seoKeywords.length" class="mt-3 flex flex-wrap gap-1">
            <span v-for="keyword in seoKeywords" :key="keyword" class="rounded bg-[#222a3d] px-2 py-1 font-mono text-[10px] text-[#c3c5d8]">#{{ keyword }}</span>
          </div>
        </section>
        <section class="space-y-3">
          <div class="flex items-center justify-between">
            <h4 class="text-xs font-bold text-white uppercase tracking-wider">SEO Settings</h4>
            <span class="text-[10px] font-mono text-[#10B981]">INDEXABLE</span>
          </div>
          <input v-model="seoTitle" class="w-full h-8 bg-[#0B0E14] border border-[#434656] rounded-sm text-xs text-white px-2 outline-none focus:border-[#2E62FF]" placeholder="Page title">
          <textarea v-model="seoDescription" class="w-full h-20 bg-[#0B0E14] border border-[#434656] rounded-sm text-xs text-white p-2 outline-none resize-none focus:border-[#2E62FF]" placeholder="Meta description"></textarea>
        </section>
        <section class="bg-[#060e20] border border-[#434656] rounded p-3">
          <div class="text-[11px] text-[#8d90a2] mb-2">Google Preview</div>
          <div class="text-[#8AB4F8] text-sm truncate">{{ seoTitle || 'Untitled page' }}</div>
          <div class="text-[#10B981] text-[11px] mt-1">https://project-alpha.local/{{ activeSlug }}</div>
          <p class="text-[#c3c5d8] text-xs mt-2 leading-relaxed">{{ seoDescription || 'Add a meta description to improve search snippets.' }}</p>
        </section>
        <section class="space-y-3 border-t border-[#434656] pt-4">
          <h4 class="text-xs font-bold text-white uppercase tracking-wider">Social Share</h4>
          <input v-model="ogTitle" class="w-full h-8 bg-[#0B0E14] border border-[#434656] rounded-sm text-xs text-white px-2 outline-none" placeholder="Open Graph title">
          <textarea v-model="ogDescription" class="w-full h-16 bg-[#0B0E14] border border-[#434656] rounded-sm text-xs text-white p-2 outline-none resize-none" placeholder="Open Graph description"></textarea>
          <div class="bg-[#0B0E14] border border-[#434656] rounded overflow-hidden">
            <div class="h-24 bg-[#1F2937] flex items-center justify-center text-[#8d90a2]">
              <span class="material-symbols-outlined">image</span>
            </div>
            <div class="p-3">
              <div class="text-white text-sm font-bold truncate">{{ ogTitle || seoTitle || 'Social preview title' }}</div>
              <div class="text-[#8d90a2] text-xs mt-1 line-clamp-2">{{ ogDescription || seoDescription || 'Social description preview.' }}</div>
            </div>
          </div>
        </section>
      </div>

      <!-- Versions / Export Panel -->
      <div v-show="activeSubTab === 'versions'" class="flex flex-col gap-5">
        <section class="space-y-3">
          <div class="flex items-center justify-between">
            <h4 class="text-xs font-bold text-white uppercase tracking-wider">Version History</h4>
            <button @click="loadVersions" class="text-[#2E62FF] text-[11px] font-bold">Refresh</button>
          </div>
          <input v-model="versionLabel" class="w-full h-8 bg-[#0B0E14] border border-[#434656] rounded-sm text-xs text-white px-2 outline-none" placeholder="Version label">
          <button @click="createVersion" class="w-full py-2 bg-[#2E62FF] text-white rounded-sm text-xs font-bold hover:brightness-110">Create Checkpoint</button>
        </section>
        <section class="space-y-2 max-h-64 overflow-y-auto custom-scrollbar">
          <div v-if="versionStatus" class="text-[11px] text-[#8d90a2]">{{ versionStatus }}</div>
          <div v-for="version in store.versions" :key="version.id" class="bg-[#060e20] border border-[#434656] rounded p-3">
            <div class="flex items-start justify-between gap-2">
              <div>
                <div class="text-white text-xs font-bold">{{ version.version_label || 'Checkpoint' }}</div>
                <div class="text-[#8d90a2] text-[10px] font-mono mt-1">{{ formatDate(version.created_at) }}</div>
              </div>
              <button @click="restoreVersion(version)" class="text-[#2E62FF] text-[11px] font-bold">Restore</button>
            </div>
          </div>
        </section>
        <section class="border-t border-[#434656] pt-4 space-y-3">
          <h4 class="text-xs font-bold text-white uppercase tracking-wider">Static Self-Hosting</h4>
          <button @click="downloadExport" class="w-full py-2 bg-[#10B981] text-white rounded-sm text-xs font-bold hover:brightness-110 flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[16px]">download</span>
            Download Static ZIP
          </button>
          <div class="bg-[#060e20] border border-[#434656] rounded p-3 text-[11px] text-[#8d90a2] leading-relaxed">
            Export includes `index.html`, `style.css`, interaction/runtime JS, form submission wiring, and Stripe checkout wrapper.
          </div>
        </section>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useWorkspaceStore } from '../../stores/workspace'

const store = useWorkspaceStore()
const route = useRoute()
const projectId = computed(() => route.params.projectId || 1)
const pageId = computed(() => route.params.pageId || 1)

const activeSubTab = computed({
  get: () => store.activeRightTab,
  set: (val) => store.setActiveRightTab(val)
})

const sections = reactive({
  spacing: true,
  layout: true,
  size: true,
  typography: true,
  effects: false,
  timeline: true,
  hover: false
})

const selectedComponentType = computed(() => {
  const comp = store.selectedComponent
  return comp?.get?.('type') || ''
})

const selectedComponentName = computed(() => {
  const comp = store.selectedComponent
  return comp?.getName?.() || comp?.get?.('name') || ''
})

const isCartDrawerSelected = computed(() => {
  return selectedComponentType.value.toLowerCase() === 'cartdrawer' || selectedComponentName.value === 'CartDrawer'
})

const isCodeEmbedSelected = computed(() => selectedComponentType.value === 'code-embed')

const selectedHtml = computed(() => {
  const comp = store.selectedComponent
  if (!comp?.toHTML) return '<!-- Select an element to inspect HTML -->'
  return comp.toHTML()
})

const selectedCss = computed(() => {
  const comp = store.selectedComponent
  if (!comp?.getStyle) return '/* Select an element to inspect CSS */'
  const style = comp.getStyle()
  const selector = comp.getId?.() ? `#${comp.getId()}` : (comp.getName?.() || comp.get?.('type') || 'selected')
  const declarations = Object.entries(style)
    .filter(([, value]) => value !== undefined && value !== '')
    .map(([key, value]) => `  ${key}: ${value};`)
    .join('\n')
  return declarations ? `${selector} {\n${declarations}\n}` : '/* No inline styles on selected element */'
})

// Settings attributes models (temp edit state)
const editId = ref('')
const editAlt = ref('')
const editHref = ref('')

// Responsive toggles state
const visibilityDesktop = ref(true)
const visibilityTablet = ref(true)
const visibilityMobile = ref(false)

// Interactions move variables (local mock state)
const moveDuration = ref('400')
const moveEasing = ref('ease-out')
const moveX = ref('20')
const moveY = ref('-10')
const interactionDelay = ref('0')
const interactionTrigger = ref('hover')
const interactionAction = ref('move')
const loopInteraction = ref(false)
const openFontMenu = ref(false)
const openWeightMenu = ref(false)

const codeEmbedHtml = ref('')
const codeEmbedCss = ref('')
const codeEmbedJs = ref('')

const versionLabel = ref('')
const versionStatus = ref('')
const showPanelMenu = ref(false)
const showDataInfo = ref(false)
const timelinePosition = ref(30)
let timelineTimer = null
const dataSource = ref('products')
const dataField = ref('title')
const cmsCollections = ref([])
const aiCopyInput = ref('')
const aiCopyOutput = ref('')
const aiCopyStatus = ref('')
const aiCopyError = ref(false)
const seoAssistLoading = ref(false)
const seoAssistStatus = ref('')
const seoAssistError = ref(false)
const seoKeywords = ref([])

const makeMetaComputed = (key) => computed({
  get: () => store.pageMeta[key] || '',
  set: (value) => store.setPageMeta({ [key]: value })
})

const seoTitle = makeMetaComputed('seoTitle')
const seoDescription = makeMetaComputed('seoDescription')
const ogTitle = makeMetaComputed('ogTitle')
const ogDescription = makeMetaComputed('ogDescription')
const headCode = makeMetaComputed('headCode')
const bodyCode = makeMetaComputed('bodyCode')
const activeSlug = computed(() => store.activePage?.slug || 'home')
const fontFamilyOptions = [
  { value: 'Geist, sans-serif', label: 'Geist Sans' },
  { value: 'Inter, sans-serif', label: 'Inter' },
  { value: 'Roboto, sans-serif', label: 'Roboto' },
  { value: 'Outfit, sans-serif', label: 'Outfit' }
]
const fontWeightOptions = [
  { value: '400', label: '400 (Normal)' },
  { value: '500', label: '500 (Medium)' },
  { value: '600', label: '600 (Bold)' },
  { value: '700', label: '700 (Extra)' }
]
const availableDataFields = computed(() => {
  if (dataSource.value === 'products') {
    return [
      { key: 'title', name: 'Product Title' },
      { key: 'description', name: 'Description' },
      { key: 'price', name: 'Price' },
      { key: 'inventory_status', name: 'Stock Status' },
      { key: 'images.0', name: 'Primary Image' }
    ]
  }

  const collection = cmsCollections.value.find((item) => item.slug === dataSource.value)
  return collection?.fields?.length ? collection.fields : [{ key: 'name', name: 'Name' }]
})
const currentFontLabel = computed(() => fontFamilyOptions.find((font) => font.value === fontFamily.value)?.label || fontFamily.value || 'Geist Sans')
const currentWeightLabel = computed(() => fontWeightOptions.find((weight) => weight.value === fontWeight.value)?.label || fontWeight.value || '400 (Normal)')

const getTargetComponent = () => {
  return store.selectedComponent || store.editor?.getWrapper?.()
}

const stripHtml = (value = '') => String(value)
  .replace(/<style[\s\S]*?<\/style>/gi, ' ')
  .replace(/<script[\s\S]*?<\/script>/gi, ' ')
  .replace(/<[^>]+>/g, ' ')
  .replace(/\s+/g, ' ')
  .trim()

const extractSelectedCopy = () => {
  const target = getTargetComponent()
  if (!target) return ''
  const direct = target.get?.('content')
  if (typeof direct === 'string' && stripHtml(direct)) return stripHtml(direct)
  if (target.toHTML) return stripHtml(target.toHTML())
  return ''
}

const loadSelectedCopy = () => {
  aiCopyInput.value = extractSelectedCopy()
  aiCopyOutput.value = ''
  aiCopyStatus.value = aiCopyInput.value ? 'Selected copy loaded.' : 'Select text on canvas, or type copy manually.'
  aiCopyError.value = false
}

const runCopyAssist = async (mode) => {
  aiCopyError.value = false
  aiCopyOutput.value = ''
  const text = aiCopyInput.value.trim() || extractSelectedCopy()
  if (!text) {
    aiCopyStatus.value = 'No copy found.'
    aiCopyError.value = true
    return
  }
  aiCopyStatus.value = 'Analyzing context...'
  try {
    const result = await store.rewriteAiCopy(projectId.value, { text, mode })
    aiCopyOutput.value = result.text || ''
    aiCopyStatus.value = `${result.model} / ${result.provider}`
  } catch (err) {
    aiCopyStatus.value = 'AI copy assist failed.'
    aiCopyError.value = true
  }
}

const applyCopyAssist = () => {
  const target = getTargetComponent()
  if (!target || !aiCopyOutput.value) return
  if (target.components) {
    target.components(aiCopyOutput.value)
  } else if (target.set) {
    target.set('content', aiCopyOutput.value)
  }
  aiCopyInput.value = aiCopyOutput.value
  aiCopyStatus.value = 'Applied to selected element.'
  store.incrementStyleTrigger()
  store.markDirty()
}

// Load attributes from selected component when GrapesJS selection changes
watch(() => store.selectedComponent, (comp) => {
  if (comp?.getAttributes) {
    editId.value = comp.getId?.() || ''
    const attrs = comp.getAttributes()
    editAlt.value = attrs.alt || ''
    editHref.value = attrs.href || ''
    interactionTrigger.value = attrs['data-interaction-trigger'] || 'hover'
    interactionAction.value = attrs['data-interaction-action'] || 'move'
    moveDuration.value = attrs['data-interaction-duration'] || '400'
    moveEasing.value = attrs['data-interaction-easing'] || 'ease-out'
    moveX.value = attrs['data-interaction-move-x'] || '20'
    moveY.value = attrs['data-interaction-move-y'] || '-10'
    interactionDelay.value = attrs['data-interaction-delay'] || '0'
    codeEmbedHtml.value = attrs['data-code-html'] || ''
    codeEmbedCss.value = attrs['data-code-css'] || ''
    codeEmbedJs.value = attrs['data-code-js'] || ''
    aiCopyInput.value = extractSelectedCopy()
    aiCopyOutput.value = ''
    aiCopyStatus.value = aiCopyInput.value ? 'Selected copy loaded.' : ''
  } else {
    editId.value = ''
    editAlt.value = ''
    editHref.value = ''
    codeEmbedHtml.value = ''
    codeEmbedCss.value = ''
    codeEmbedJs.value = ''
    aiCopyInput.value = ''
    aiCopyOutput.value = ''
    aiCopyStatus.value = ''
  }
}, { immediate: true })

// Attributes getter/setter binding helpers
const elementId = computed({
  get: () => editId.value,
  set: (val) => { editId.value = val }
})
const elementAlt = computed({
  get: () => editAlt.value,
  set: (val) => { editAlt.value = val }
})
const elementHref = computed({
  get: () => editHref.value,
  set: (val) => { editHref.value = val }
})

const discardAttributes = () => {
  if (store.selectedComponent?.getAttributes) {
    editId.value = store.selectedComponent.getId?.() || ''
    const attrs = store.selectedComponent.getAttributes()
    editAlt.value = attrs.alt || ''
    editHref.value = attrs.href || ''
  }
}

const applyAttributes = () => {
  const target = getTargetComponent()
  if (!target?.setAttributes) return
  
  // Set ID
  if (editId.value && target.setId) {
    target.setId(editId.value)
  }
  
  // Set Attributes (alt, href)
  const currentAttrs = target.getAttributes()
  target.setAttributes({
    ...currentAttrs,
    alt: editAlt.value,
    href: editHref.value
  })
  
  store.incrementStyleTrigger()
  store.markDirty()
}

const applyInteraction = () => {
  const target = getTargetComponent()
  if (!target?.setAttributes) return
  const attrs = target.getAttributes()
  target.setAttributes({
    ...attrs,
    'data-interaction-trigger': interactionTrigger.value,
    'data-interaction-action': interactionAction.value,
    'data-interaction-duration': moveDuration.value || '400',
    'data-interaction-delay': interactionDelay.value || '0',
    'data-interaction-easing': moveEasing.value || 'ease-out',
    'data-interaction-move-x': moveX.value || '0',
    'data-interaction-move-y': moveY.value || '-16'
  })
  store.markDirty()
  store.incrementStyleTrigger()
}

const previewInteraction = () => {
  applyInteraction()
  const target = getTargetComponent()
  if (!store.editor || !target?.getEl) return
  const element = target.getEl()
  animateTimeline()
  const keyframes = {
    move: [{ transform: 'translateY(0)' }, { transform: `translate(${moveX.value || 0}px, ${moveY.value || -16}px)` }, { transform: 'translateY(0)' }],
    scale: [{ transform: 'scale(1)' }, { transform: 'scale(1.05)' }, { transform: 'scale(1)' }],
    rotate: [{ transform: 'rotate(0deg)' }, { transform: 'rotate(3deg)' }, { transform: 'rotate(0deg)' }],
    opacity: [{ opacity: 1 }, { opacity: 0.45 }, { opacity: 1 }],
    blur: [{ filter: 'blur(0)' }, { filter: 'blur(3px)' }, { filter: 'blur(0)' }]
  }[interactionAction.value]
  element?.animate?.(keyframes, {
    duration: Number(moveDuration.value || 400),
    delay: Number(interactionDelay.value || 0),
    easing: moveEasing.value || 'ease-out'
  })
}

const animateTimeline = () => {
  if (timelineTimer) window.clearInterval(timelineTimer)
  const duration = Math.max(Number(moveDuration.value || 400), 100)
  const delay = Math.max(Number(interactionDelay.value || 0), 0)
  const startAt = Date.now() + delay
  timelinePosition.value = 0
  timelineTimer = window.setInterval(() => {
    const elapsed = Date.now() - startAt
    if (elapsed < 0) return
    const progress = Math.min(100, Math.round((elapsed / duration) * 100))
    timelinePosition.value = progress
    if (progress >= 100) {
      if (loopInteraction.value) {
        timelinePosition.value = 0
      } else {
        window.clearInterval(timelineTimer)
        timelineTimer = null
      }
    }
  }, 30)
}

const setTimelineFromPointer = (event) => {
  const rect = event.currentTarget.getBoundingClientRect()
  const next = Math.round(((event.clientX - rect.left) / rect.width) * 100)
  timelinePosition.value = Math.max(0, Math.min(100, next))
  interactionDelay.value = String(Math.round((Number(moveDuration.value || 400) * timelinePosition.value) / 100))
}

const setActionFromTimeline = (action, position) => {
  interactionTrigger.value = 'click'
  interactionAction.value = action
  timelinePosition.value = position
  applyInteraction()
}

const escapeAttr = (value = '') => String(value)
  .replaceAll('&', '&amp;')
  .replaceAll('"', '&quot;')
  .replaceAll('<', '&lt;')
  .replaceAll('>', '&gt;')

const applyCodeEmbed = () => {
  if (!store.selectedComponent?.setAttributes || !isCodeEmbedSelected.value) return
  const attrs = store.selectedComponent.getAttributes()
  const srcdoc = `<!doctype html><html><head><style>body{margin:0;font-family:Geist,Arial,sans-serif}${codeEmbedCss.value}</style></head><body>${codeEmbedHtml.value}<script>${codeEmbedJs.value}<\\/script></body></html>`
  store.selectedComponent.setAttributes({
    ...attrs,
    'data-code-html': codeEmbedHtml.value,
    'data-code-css': codeEmbedCss.value,
    'data-code-js': codeEmbedJs.value
  })
  store.selectedComponent.components(`<iframe sandbox="allow-scripts allow-same-origin" class="w-full min-h-[150px] border border-[#434656] rounded bg-white" srcdoc="${escapeAttr(srcdoc)}"></iframe>`)
  store.markDirty()
}

const applyDataBinding = () => {
  const target = getTargetComponent()
  if (!target?.setAttributes) return
  target.setAttributes({
    ...target.getAttributes(),
    'data-cms-source': dataSource.value,
    'data-cms-field': dataField.value,
    'data-cms-visible-if': dataSource.value === 'products' ? 'inventory_status != out_of_stock' : ''
  })
  store.markDirty()
  store.incrementStyleTrigger()
}

const runSeoAssist = async () => {
  seoAssistLoading.value = true
  seoAssistError.value = false
  seoAssistStatus.value = 'Scanning canvas copy...'
  try {
    const html = store.editor?.getHtml?.() || ''
    const result = await store.generateAiSeo(projectId.value, {
      html,
      text: stripHtml(html)
    })
    seoTitle.value = result.title || seoTitle.value
    seoDescription.value = result.description || seoDescription.value
    ogTitle.value = result.og_title || result.title || ogTitle.value
    ogDescription.value = result.og_description || result.description || ogDescription.value
    seoKeywords.value = Array.isArray(result.keywords) ? result.keywords : []

    const target = getTargetComponent()
    const altSuggestion = Array.isArray(result.alt_texts) ? result.alt_texts[0] : ''
    if (altSuggestion && target?.getAttributes?.()?.src) {
      editAlt.value = altSuggestion
      target.setAttributes({ ...target.getAttributes(), alt: altSuggestion })
    }

    seoAssistStatus.value = `${result.model} / ${result.provider}. Suggestions applied.`
    store.markDirty()
  } catch (err) {
    seoAssistStatus.value = 'AI SEO optimizer failed.'
    seoAssistError.value = true
  } finally {
    seoAssistLoading.value = false
  }
}

const selectWrapper = () => {
  const wrapper = store.editor?.getWrapper?.()
  if (wrapper) {
    store.editor.select(wrapper)
    store.setSelectedComponent(wrapper)
  }
  showPanelMenu.value = false
}

const duplicateSelected = () => {
  const target = store.selectedComponent
  if (target?.clone && target.collection) {
    target.collection.add(target.clone(), { at: target.index() + 1 })
    store.markDirty()
  }
  showPanelMenu.value = false
}

const removeSelected = () => {
  const target = store.selectedComponent
  if (target && target !== store.editor?.getWrapper?.()) {
    target.remove()
    store.setSelectedComponent(null)
    store.markDirty()
  }
  showPanelMenu.value = false
}

const loadVersions = async () => {
  versionStatus.value = 'Loading versions...'
  try {
    await store.fetchPageVersions(projectId.value, pageId.value)
    versionStatus.value = store.versions.length ? '' : 'No checkpoints yet.'
  } catch (err) {
    versionStatus.value = 'Version API unavailable. Start the Laravel backend to sync history.'
  }
}

const createVersion = async () => {
  versionStatus.value = 'Creating checkpoint...'
  try {
    await store.createPageVersion(projectId.value, pageId.value, versionLabel.value || 'Manual checkpoint')
    versionLabel.value = ''
    versionStatus.value = 'Checkpoint created.'
  } catch (err) {
    versionStatus.value = 'Could not create checkpoint. Save backend connection and try again.'
  }
}

const restoreVersion = async (version) => {
  versionStatus.value = 'Restoring version...'
  try {
    await store.restorePageVersion(projectId.value, pageId.value, version.id)
    versionStatus.value = 'Version restored.'
  } catch (err) {
    versionStatus.value = 'Restore failed.'
  }
}

const downloadExport = async () => {
  versionStatus.value = 'Preparing static export...'
  try {
    await store.savePage(projectId.value, pageId.value)
    versionStatus.value = 'Opening download...'
  } catch (err) {
    versionStatus.value = 'Exporting last saved version.'
  }

  store.downloadStaticExport(projectId.value, pageId.value)
}

const formatDate = (date) => {
  if (!date) return 'Unknown date'
  return new Date(date).toLocaleString()
}

const makeStyleComputed = (prop) => {
  return computed({
    get: () => {
      const _ = store.styleTrigger
      const target = getTargetComponent()
      if (!target?.getStyle) return ''
      const style = target.getStyle()
      return style[prop] || ''
    },
    set: (val) => {
      const target = getTargetComponent()
      if (!target?.getStyle || !target?.setStyle) return
      let formattedVal = val ? val.trim() : ''
      if (formattedVal && /^\d+(\.\d+)?$/.test(formattedVal)) {
        if (prop === 'width') formattedVal += '%'
        else if (['height', 'font-size', 'border-radius', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left'].includes(prop)) formattedVal += 'px'
      }
      
      const currentStyles = target.getStyle()
      target.setStyle({
        ...currentStyles,
        [prop]: formattedVal
      })
      store.incrementStyleTrigger()
      store.markDirty()
    }
  })
}

const parseCssUnit = (value = '', fallbackUnit = 'px') => {
  const normalized = String(value || '').trim()
  if (!normalized) return { value: '', unit: fallbackUnit }
  if (normalized === 'auto') return { value: 'Auto', unit: 'auto' }
  const match = normalized.match(/^(-?\d+(?:\.\d+)?)([a-z%]*)$/i)
  if (!match) return { value: normalized, unit: fallbackUnit }
  return { value: match[1], unit: match[2] || fallbackUnit }
}

const widthUnit = ref('%')
const heightUnit = ref('px')

const setUnitStyle = (prop, value, unit) => {
  const target = getTargetComponent()
  if (!target?.getStyle || !target?.setStyle) return
  const raw = String(value || '').trim()
  const formatted = unit === 'auto'
    ? 'auto'
    : raw
      ? (/^-?\d+(\.\d+)?$/.test(raw) ? `${raw}${unit}` : raw)
      : ''
  target.setStyle({
    ...target.getStyle(),
    [prop]: formatted
  })
  store.incrementStyleTrigger()
  store.markDirty()
}

const makeUnitValueComputed = (prop, unitRef, fallbackUnit) => computed({
  get: () => {
    const _ = store.styleTrigger
    const target = getTargetComponent()
    if (!target?.getStyle) return ''
    const parsed = parseCssUnit(target.getStyle()[prop], fallbackUnit)
    unitRef.value = parsed.unit
    return parsed.value
  },
  set: (value) => setUnitStyle(prop, value, unitRef.value)
})

const commitUnitSize = (prop) => {
  if (prop === 'width') setUnitStyle('width', widthValue.value, widthUnit.value)
  if (prop === 'height') setUnitStyle('height', heightValue.value, heightUnit.value)
}

// Spacing properties
const marginTop = makeStyleComputed('margin-top')
const marginRight = makeStyleComputed('margin-right')
const marginBottom = makeStyleComputed('margin-bottom')
const marginLeft = makeStyleComputed('margin-left')
const paddingTop = makeStyleComputed('padding-top')
const paddingRight = makeStyleComputed('padding-right')
const paddingBottom = makeStyleComputed('padding-bottom')
const paddingLeft = makeStyleComputed('padding-left')

// Layout properties
const display = makeStyleComputed('display')
const flexDirection = makeStyleComputed('flex-direction')
const alignItems = makeStyleComputed('align-items')

// Size properties
const widthValue = makeUnitValueComputed('width', widthUnit, '%')
const heightValue = makeUnitValueComputed('height', heightUnit, 'px')

// Typography properties
const fontFamily = makeStyleComputed('font-family')
const fontWeight = makeStyleComputed('font-weight')
const fontSize = makeStyleComputed('font-size')
const textAlign = makeStyleComputed('text-align')

// Effects properties
const borderRadius = makeStyleComputed('border-radius')
const backgroundColor = makeStyleComputed('background-color')

onMounted(async () => {
  loadVersions()
  try {
    const response = await fetch(`${store.apiBaseUrl}/projects/${projectId.value}/collections`)
    if (response.ok) cmsCollections.value = await response.json()
  } catch (err) {
    cmsCollections.value = []
  }
})
</script>

<style>
/* Traits Overrides */
.gjs-trt-traits {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.gjs-trt-trait {
  color: #ccc !important;
  font-family: 'Geist', sans-serif !important;
  font-size: 11px !important;
}
.gjs-field {
  background: #060e20 !important;
  border: 1px solid #434656 !important;
  color: #fff !important;
  border-radius: 4px !important;
  padding: 6px 8px !important;
}
.gjs-field input {
  color: #fff !important;
}
.editor-select,
.editor-select option,
select,
select option {
  background-color: #0B0E14 !important;
  color: #dae2fd !important;
}
.custom-scrollbar {
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.custom-scrollbar::-webkit-scrollbar {
  width: 0 !important;
  height: 0 !important;
  display: none !important;
}
.gjs-label {
  font-weight: 700 !important;
  text-transform: uppercase !important;
  letter-spacing: 0.05em !important;
  font-size: 9px !important;
  color: #8d90a2 !important;
  margin-bottom: 4px !important;
}
</style>
