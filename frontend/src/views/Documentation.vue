<template>
  <div class="min-h-screen bg-editor-bg text-white flex flex-col font-geist">
    <header class="h-16 border-b border-outline bg-editor-bg flex items-center justify-between px-8 z-10 sticky top-0">
      <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-primary text-[24px]">menu_book</span>
        <h1 class="text-xl font-bold tracking-tight">StudioPro Documentation</h1>
      </div>
      <div class="flex gap-4 items-center">
        <router-link to="/dashboard" class="text-xs font-bold text-on-surface-variant hover:text-white uppercase tracking-wider transition-colors">Back to App</router-link>
      </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <aside class="w-64 border-r border-outline bg-editor-bg flex flex-col overflow-y-auto shrink-0">
        <nav class="p-4 space-y-8">
          <div>
            <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3 px-3">Getting Started</h3>
            <ul class="space-y-1">
              <li>
                <button @click="activeSection = 0" class="w-full text-left px-3 py-1.5 text-sm font-medium rounded transition-colors" :class="activeSection === 0 ? 'text-white bg-surface-container' : 'text-on-surface-variant hover:text-white'">Introduction</button>
              </li>
              <li>
                <button @click="activeSection = 1" class="w-full text-left px-3 py-1.5 text-sm font-medium rounded transition-colors" :class="activeSection === 1 ? 'text-white bg-surface-container' : 'text-on-surface-variant hover:text-white'">Quickstart</button>
              </li>
            </ul>
          </div>
          <div>
            <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3 px-3">Core Concepts</h3>
            <ul class="space-y-1">
              <li>
                <button @click="activeSection = 2" class="w-full text-left px-3 py-1.5 text-sm font-medium rounded transition-colors" :class="activeSection === 2 ? 'text-white bg-surface-container' : 'text-on-surface-variant hover:text-white'">Projects & Pages</button>
              </li>
              <li>
                <button @click="activeSection = 3" class="w-full text-left px-3 py-1.5 text-sm font-medium rounded transition-colors" :class="activeSection === 3 ? 'text-white bg-surface-container' : 'text-on-surface-variant hover:text-white'">Visual Editor</button>
              </li>
              <li>
                <button @click="activeSection = 4" class="w-full text-left px-3 py-1.5 text-sm font-medium rounded transition-colors" :class="activeSection === 4 ? 'text-white bg-surface-container' : 'text-on-surface-variant hover:text-white'">Components</button>
              </li>
            </ul>
          </div>
          <div>
            <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3 px-3">Advanced</h3>
            <ul class="space-y-1">
              <li>
                <button @click="activeSection = 5" class="w-full text-left px-3 py-1.5 text-sm font-medium rounded transition-colors" :class="activeSection === 5 ? 'text-white bg-surface-container' : 'text-on-surface-variant hover:text-white'">CMS Collections</button>
              </li>
              <li>
                <button @click="activeSection = 6" class="w-full text-left px-3 py-1.5 text-sm font-medium rounded transition-colors" :class="activeSection === 6 ? 'text-white bg-surface-container' : 'text-on-surface-variant hover:text-white'">E-commerce API</button>
              </li>
              <li>
                <button @click="activeSection = 7" class="w-full text-left px-3 py-1.5 text-sm font-medium rounded transition-colors" :class="activeSection === 7 ? 'text-white bg-surface-container' : 'text-on-surface-variant hover:text-white'">Workspace Settings</button>
              </li>
            </ul>
          </div>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 overflow-y-auto bg-[#0a0d14] p-12 scroll-smooth" ref="mainContentRef">
        <div class="max-w-3xl space-y-16">
          
          <div v-if="activeSection === 0" class="animate-fade-in">
            <h2 class="text-4xl font-extrabold mb-4 tracking-tight">Introduction to StudioPro</h2>
            <p class="text-on-surface-variant text-lg leading-relaxed mb-6">
              StudioPro is a high-performance visual website builder tailored for professional developers and agencies. It bridges the gap between drag-and-drop interfaces and raw code, allowing you to build highly customized, data-driven applications without touching source files unless you want to.
            </p>
            <div class="bg-surface-container border border-outline p-6 rounded-xl flex gap-4 items-start mb-6">
              <span class="material-symbols-outlined text-primary">info</span>
              <div>
                <h4 class="text-white font-bold mb-1">Notice</h4>
                <p class="text-sm text-on-surface-variant">StudioPro v2.0 introduces the new Canvas Grid engine and real-time live preview generation, powering the fastest visual DOM manipulation available on the web.</p>
              </div>
            </div>
            <h3 class="text-xl font-bold mb-3 mt-8">Why StudioPro?</h3>
            <ul class="list-disc list-inside text-on-surface-variant space-y-2">
              <li>No vendor lock-in: Export pure HTML/CSS code effortlessly.</li>
              <li>Build faster with 20+ responsive premium templates.</li>
              <li>Fully integrated headless CMS for dynamic content generation.</li>
            </ul>
          </div>

          <div v-if="activeSection === 1" class="animate-fade-in">
            <h2 class="text-3xl font-extrabold mb-4 tracking-tight">Quickstart</h2>
            <p class="text-on-surface-variant mb-6">To begin your journey with StudioPro, follow these simple steps:</p>
            <div class="space-y-6">
              <div class="bg-surface-container p-5 border border-white/5 rounded-xl">
                <h4 class="text-white font-bold mb-2">1. Create a Project</h4>
                <p class="text-sm text-on-surface-variant">Navigate to the Dashboard and click "New Project". Give your project a name and URL slug.</p>
              </div>
              <div class="bg-surface-container p-5 border border-white/5 rounded-xl">
                <h4 class="text-white font-bold mb-2">2. Pick a Template</h4>
                <p class="text-sm text-on-surface-variant">Browse our extensive Template Library of 20+ professional templates, ranging from Corporate SaaS to E-commerce. Click "Preview" to inspect a template before committing.</p>
              </div>
              <div class="bg-surface-container p-5 border border-white/5 rounded-xl">
                <h4 class="text-white font-bold mb-2">3. Enter the Editor</h4>
                <p class="text-sm text-on-surface-variant">Once a project is created, click "Open Editor" to start manipulating the DOM visually. Use the style manager on the right to tweak Tailwind utility classes.</p>
              </div>
              <div class="bg-surface-container p-5 border border-white/5 rounded-xl">
                <h4 class="text-white font-bold mb-2">4. Publish</h4>
                <p class="text-sm text-on-surface-variant">Hit the Publish button in the Editor's top bar to trigger your webhook deployment to Netlify or Vercel.</p>
              </div>
            </div>
          </div>

          <div v-if="activeSection === 2" class="animate-fade-in">
            <h2 class="text-3xl font-extrabold mb-4 tracking-tight">Projects & Pages</h2>
            <p class="text-on-surface-variant leading-relaxed mb-6">
              The fundamental architectural layer of StudioPro is divided into <strong>Projects</strong> and <strong>Pages</strong>.
            </p>
            
            <h3 class="text-xl font-bold mb-3">Projects</h3>
            <p class="text-on-surface-variant text-sm mb-4">A Project represents a single website or web application. It acts as a container for all pages, CMS collections, commerce products, and global settings. Projects share global CSS classes and assets.</p>
            
            <h3 class="text-xl font-bold mb-3 mt-8">Pages & Routing</h3>
            <p class="text-on-surface-variant text-sm mb-4">Inside a project, you create Pages. Each page automatically maps to a route based on its URL Slug. For example, a page named "About Us" with the slug `about` will be available at `/about` when exported.</p>
            
            <div class="bg-surface-container border border-outline p-6 rounded-xl mt-6">
              <h4 class="text-white font-bold mb-3">SEO Management</h4>
              <p class="text-sm text-on-surface-variant mb-4">StudioPro allows granular control over SEO and Open Graph data on a per-page basis. Open the Page Settings inside the Editor to define:</p>
              <ul class="list-disc list-inside text-sm text-on-surface-variant space-y-2">
                <li><code>&lt;title&gt;</code> and Meta Description tags</li>
                <li>OG:Image for rich social sharing</li>
                <li>Custom <code>&lt;head&gt;</code> code injections (Google Analytics, scripts)</li>
              </ul>
            </div>
          </div>

          <div v-if="activeSection === 3" class="animate-fade-in">
            <h2 class="text-3xl font-extrabold mb-4 tracking-tight">The Visual Editor</h2>
            <p class="text-on-surface-variant leading-relaxed mb-6">
              The core of StudioPro is the Canvas Visual Editor. It utilizes a custom high-performance engine tailored to support modern utility-first CSS frameworks.
            </p>
            <div class="grid grid-cols-2 gap-6">
              <div class="bg-surface-container p-6 border border-outline rounded-xl hover:border-primary/50 transition-colors">
                <span class="material-symbols-outlined text-[#8B5CF6] mb-3 text-3xl">layers</span>
                <h4 class="font-bold text-white mb-2">Layer Management</h4>
                <p class="text-sm text-on-surface-variant leading-relaxed">Select, reorder, and group DOM elements exactly like a vector design tool using the Left Sidebar Layer Tree. Drag and drop elements seamlessly.</p>
              </div>
              <div class="bg-surface-container p-6 border border-outline rounded-xl hover:border-primary/50 transition-colors">
                <span class="material-symbols-outlined text-[#10B981] mb-3 text-3xl">style</span>
                <h4 class="font-bold text-white mb-2">Advanced Styling</h4>
                <p class="text-sm text-on-surface-variant leading-relaxed">Edit flexbox, CSS grid, typography, sizing, spacing, and raw CSS properties via the Right Sidebar Style Manager.</p>
              </div>
              <div class="bg-surface-container p-6 border border-outline rounded-xl hover:border-primary/50 transition-colors">
                <span class="material-symbols-outlined text-[#F59E0B] mb-3 text-3xl">devices</span>
                <h4 class="font-bold text-white mb-2">Responsive Design</h4>
                <p class="text-sm text-on-surface-variant leading-relaxed">Toggle between Desktop, Tablet, and Mobile breakpoints in the top bar to apply media-query specific styles.</p>
              </div>
              <div class="bg-surface-container p-6 border border-outline rounded-xl hover:border-primary/50 transition-colors">
                <span class="material-symbols-outlined text-[#EF4444] mb-3 text-3xl">code_blocks</span>
                <h4 class="font-bold text-white mb-2">Custom Code</h4>
                <p class="text-sm text-on-surface-variant leading-relaxed">Need more? Use the Custom Code block to inject raw HTML/JS/CSS directly into the canvas with a built-in syntax highlighter.</p>
              </div>
            </div>
          </div>

          <div v-if="activeSection === 4" class="animate-fade-in">
            <h2 class="text-3xl font-extrabold mb-4 tracking-tight">Components</h2>
            <p class="text-on-surface-variant leading-relaxed mb-6">
              To keep your design dry and scalable, StudioPro relies heavily on modular Components.
            </p>
            
            <h3 class="text-xl font-bold mb-3">Pre-built Blocks</h3>
            <p class="text-on-surface-variant text-sm mb-4">Open the Components panel in the Editor's left sidebar to drag and drop pre-built UI primitives like Navigation Bars, Hero Sections, Feature Grids, and Footers directly into your canvas.</p>
            
            <h3 class="text-xl font-bold mb-3 mt-8">Symbols (Reusable Components)</h3>
            <p class="text-on-surface-variant text-sm mb-4">You can convert any group of elements on the canvas into a Symbol. Symbols are synced instances. If you update the Master Symbol (e.g., changing the Navbar color), every instance across all pages will instantly update.</p>
            
            <div class="bg-surface-container-light border border-primary/30 p-5 rounded-xl flex items-center gap-4">
              <span class="material-symbols-outlined text-primary text-2xl">diamond</span>
              <p class="text-sm text-on-surface"><strong>Pro Tip:</strong> Enter "Isolation Mode" by double-clicking a Symbol to edit it without distractions from the surrounding page context.</p>
            </div>
          </div>

          <div v-if="activeSection === 5" class="animate-fade-in">
            <h2 class="text-3xl font-extrabold mb-4 tracking-tight">CMS Collections</h2>
            <p class="text-on-surface-variant leading-relaxed mb-6">
              The built-in Headless CMS allows you to define flexible schemas for dynamic content like blogs, team members, or case studies.
            </p>
            
            <h3 class="text-xl font-bold mb-3">Schema Definition</h3>
            <p class="text-on-surface-variant text-sm mb-4">In the CMS tab, you can create a new Collection (e.g., "Blog Posts") and define its fields. Supported field types include Plain Text, Rich Text (Markdown), Image URLs, Dates, and Booleans.</p>
            
            <div class="bg-editor-bg border border-outline rounded-xl overflow-hidden font-mono text-sm mb-8">
              <div class="bg-surface-container px-4 py-2 border-b border-outline flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                <span class="ml-4 text-gray-500 text-xs">schema.json</span>
              </div>
              <div class="p-6 text-gray-300">
<pre>
{
  "collection": "Blog Posts",
  "fields": [
    { "name": "Title", "type": "string", "required": true },
    { "name": "Content", "type": "richtext" },
    { "name": "Cover Image", "type": "image" },
    { "name": "Published Date", "type": "date" }
  ]
}
</pre>
              </div>
            </div>

            <h3 class="text-xl font-bold mb-3">Data Binding</h3>
            <p class="text-on-surface-variant text-sm mb-4">Once your Collection contains items, you can bind them to the UI in the Visual Editor. Drop a "Collection List" component onto the canvas, select your Collection, and visually map elements (like a Heading) to specific fields (like "Title").</p>
          </div>

          <div v-if="activeSection === 6" class="animate-fade-in">
            <h2 class="text-3xl font-extrabold mb-4 tracking-tight">E-commerce API</h2>
            <p class="text-on-surface-variant leading-relaxed mb-6">
              StudioPro offers a robust Commerce layer to sell digital or physical goods without relying on external dashboard clunkiness.
            </p>
            
            <h3 class="text-xl font-bold mb-3">Products & Inventory</h3>
            <p class="text-on-surface-variant text-sm mb-4">Manage products, SKUs, pricing, and stock levels from the Commerce tab. Products can be organized into Categories and Collections for easier front-end filtering.</p>

            <h3 class="text-xl font-bold mb-3 mt-8">Stripe Integration</h3>
            <p class="text-on-surface-variant text-sm mb-4">StudioPro natively supports Stripe Checkout. In the Workspace Settings, paste your Stripe API Keys. The Cart Drawer component handles state management, and the checkout button securely redirects users to a Stripe-hosted payment session.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
              <div class="bg-surface-container border border-white/5 p-5 rounded-xl">
                <h4 class="text-white font-bold text-sm mb-1">Add to Cart Button</h4>
                <p class="text-xs text-on-surface-variant">Bind this button to a product context to trigger cart additions.</p>
              </div>
              <div class="bg-surface-container border border-white/5 p-5 rounded-xl">
                <h4 class="text-white font-bold text-sm mb-1">Cart Drawer State</h4>
                <p class="text-xs text-on-surface-variant">Controlled globally via Pinia store `useWorkspaceStore().toggleCartDrawer()`.</p>
              </div>
            </div>
          </div>

          <div v-if="activeSection === 7" class="animate-fade-in">
            <h2 class="text-3xl font-extrabold mb-4 tracking-tight">Workspace Settings</h2>
            <p class="text-on-surface-variant leading-relaxed mb-6">
              Fine-tune your development environment and project-wide configurations from the Settings tab.
            </p>
            
            <ul class="space-y-6">
              <li class="bg-surface-container p-6 rounded-xl border border-white/5">
                <h3 class="text-lg font-bold text-white mb-2">Canvas Preferences</h3>
                <p class="text-sm text-on-surface-variant mb-3">Control the physical constraints of the editor. Toggle Grid snapping (default 24px), show/hide rulers, and set the default viewport width.</p>
              </li>
              <li class="bg-surface-container p-6 rounded-xl border border-white/5">
                <h3 class="text-lg font-bold text-white mb-2">Editor Preferences</h3>
                <p class="text-sm text-on-surface-variant mb-3">Enable or disable Auto-Save (interval settings), toggle UI tooltips, and activate Compact Mode for smaller laptop screens.</p>
              </li>
              <li class="bg-surface-container p-6 rounded-xl border border-white/5">
                <h3 class="text-lg font-bold text-white mb-2">Project Settings</h3>
                <p class="text-sm text-on-surface-variant mb-3">Configure global metadata: Project Name, Site URL, and Deployment Webhook URL (used to trigger automated builds when you hit Publish).</p>
              </li>
            </ul>
          </div>

          <!-- Navigation Footer -->
          <div class="pt-12 mt-12 border-t border-outline flex justify-between items-center pb-24">
            <button 
              v-if="activeSection > 0" 
              @click="goToSection(activeSection - 1)" 
              class="flex items-center gap-2 px-5 py-2.5 rounded-lg border border-outline text-gray-300 hover:text-white hover:bg-surface-container transition-colors"
            >
              <span class="material-symbols-outlined text-[18px]">arrow_back</span>
              <div class="text-left">
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Previous</div>
                <div class="text-sm font-medium">{{ sectionNames[activeSection - 1] }}</div>
              </div>
            </button>
            <div v-else></div>

            <button 
              v-if="activeSection < sectionNames.length - 1" 
              @click="goToSection(activeSection + 1)" 
              class="flex items-center gap-2 px-5 py-2.5 rounded-lg border border-primary text-primary hover:text-white hover:bg-primary transition-colors group"
            >
              <div class="text-right">
                <div class="text-[10px] font-bold uppercase tracking-widest opacity-70 group-hover:text-white group-hover:opacity-100">Next</div>
                <div class="text-sm font-medium">{{ sectionNames[activeSection + 1] }}</div>
              </div>
              <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </button>
          </div>

        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const activeSection = ref(0)
const mainContentRef = ref(null)

const sectionNames = [
  'Introduction',
  'Quickstart',
  'Projects & Pages',
  'Visual Editor',
  'Components',
  'CMS Collections',
  'E-commerce API',
  'Workspace Settings'
]

const goToSection = (index) => {
  activeSection.value = index
  if (mainContentRef.value) {
    mainContentRef.value.scrollTop = 0
  }
}
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.3s ease-out forwards;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Scrollbar Styles */
::-webkit-scrollbar {
  width: 8px;
}
::-webkit-scrollbar-track {
  background: transparent;
}
::-webkit-scrollbar-thumb {
  background-color: #434656;
  border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
  background-color: #8d90a2;
}
</style>
