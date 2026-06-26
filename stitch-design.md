<!-- CMS Collection Manager -->
<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>StudioPro CMS - Collection Manager</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        @import url('https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap');
        
        body {
            font-family: 'Geist', sans-serif;
            background-color: #0B0E14;
            color: #dae2fd;
            overflow: hidden;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 20;
            vertical-align: middle;
        }

        /* Custom Scrollbar for Pro-UI */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #161B22;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #2E62FF;
        }

        .canvas-area {
            height: calc(100vh - 48px - 32px); /* Viewport - TopBar - Footer */
            margin-top: 48px;
            margin-left: 280px;
            margin-right: 280px;
        }

        .layers-row:hover .row-actions {
            opacity: 1;
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "surface-container": "#171f33",
                      "error": "#ffb4ab",
                      "on-error": "#690005",
                      "surface-container-lowest": "#060e20",
                      "success-green": "#10B981",
                      "royal-purple": "#8B5CF6",
                      "on-secondary-fixed-variant": "#5516be",
                      "on-primary-fixed-variant": "#0039b5",
                      "electric-blue": "#2E62FF",
                      "inverse-on-surface": "#283044",
                      "on-error-container": "#ffdad6",
                      "on-background": "#dae2fd",
                      "tertiary-container": "#007c91",
                      "outline-variant": "#434656",
                      "secondary-container": "#571bc1",
                      "on-tertiary": "#003640",
                      "primary-fixed": "#dce1ff",
                      "surface-container-highest": "#2d3449",
                      "surface-bright": "#31394d",
                      "secondary-fixed": "#e9ddff",
                      "surface-container-low": "#131b2e",
                      "on-tertiary-fixed-variant": "#004e5c",
                      "tertiary-fixed": "#acedff",
                      "background": "#0b1326",
                      "on-primary-container": "#f7f6ff",
                      "outline": "#8d90a2",
                      "on-primary-fixed": "#001552",
                      "tertiary": "#4cd7f6",
                      "error-red": "#EF4444",
                      "secondary-fixed-dim": "#d0bcff",
                      "on-tertiary-fixed": "#001f26",
                      "on-tertiary-container": "#e9f9ff",
                      "editor-bg": "#0B0E14",
                      "surface-dim": "#0b1326",
                      "surface": "#0b1326",
                      "warning-amber": "#F59E0B",
                      "primary-fixed-dim": "#b7c4ff",
                      "primary": "#b7c4ff",
                      "inverse-primary": "#024cec",
                      "secondary": "#d0bcff",
                      "panel-surface": "#161B22",
                      "on-primary": "#002682",
                      "on-secondary-container": "#c4abff",
                      "surface-container-high": "#222a3d",
                      "error-container": "#93000a",
                      "on-secondary-fixed": "#23005c",
                      "primary-container": "#2e62ff",
                      "inverse-surface": "#dae2fd",
                      "canvas-bg": "#1F2937",
                      "tertiary-fixed-dim": "#4cd7f6",
                      "on-surface-variant": "#c3c5d8",
                      "surface-variant": "#2d3449",
                      "surface-tint": "#b7c4ff",
                      "on-surface": "#dae2fd",
                      "on-secondary": "#3c0091"
              },
              "borderRadius": {
                      "DEFAULT": "0.125rem",
                      "lg": "0.25rem",
                      "xl": "0.5rem",
                      "full": "0.75rem"
              },
              "spacing": {
                      "toolbar-height": "48px",
                      "panel-width": "280px",
                      "4xl": "64px",
                      "md": "12px",
                      "xl": "24px",
                      "lg": "16px",
                      "base": "4px",
                      "2xl": "32px",
                      "3xl": "48px",
                      "xs": "4px",
                      "sm": "8px"
              },
              "fontFamily": {
                      "headline-md": ["Geist"],
                      "label-md": ["Geist"],
                      "headline-sm": ["Geist"],
                      "headline-lg": ["Geist"],
                      "body-lg": ["Geist"],
                      "body-md": ["Geist"],
                      "mono-label": ["JetBrains Mono"],
                      "body-sm": ["Geist"],
                      "label-sm": ["Geist"]
              },
              "fontSize": {
                      "headline-md": ["24px", {"lineHeight": "1.3", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                      "label-md": ["12px", {"lineHeight": "1", "letterSpacing": "0.02em", "fontWeight": "500"}],
                      "headline-sm": ["18px", {"lineHeight": "1.4", "fontWeight": "500"}],
                      "headline-lg": ["32px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "600"}],
                      "body-lg": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                      "body-md": ["14px", {"lineHeight": "1.5", "fontWeight": "400"}],
                      "mono-label": ["11px", {"lineHeight": "1", "fontWeight": "400"}],
                      "body-sm": ["13px", {"lineHeight": "1.5", "fontWeight": "400"}],
                      "label-sm": ["11px", {"lineHeight": "1", "letterSpacing": "0.05em", "fontWeight": "600"}]
              }
            },
          },
        }
    </script>
</head>
<body class="bg-editor-bg">
<!-- TopAppBar -->
<header class="bg-panel-surface dark:bg-panel-surface border-b border-outline-variant flex justify-between items-center h-toolbar-height px-md w-full fixed top-0 z-50">
<div class="flex items-center gap-xl">
<span class="font-headline-md text-headline-md font-bold text-on-surface dark:text-on-surface">StudioPro</span>
<nav class="flex items-center gap-lg">
<a class="font-label-sm text-label-sm hover:bg-surface-container-highest transition-colors cursor-pointer active:scale-95 text-on-surface-variant font-medium" href="#">Pages</a>
<a class="font-label-sm text-label-sm hover:bg-surface-container-highest transition-colors cursor-pointer active:scale-95 text-electric-blue font-bold border-b-2 border-electric-blue pb-1" href="#">CMS</a>
<a class="font-label-sm text-label-sm hover:bg-surface-container-highest transition-colors cursor-pointer active:scale-95 text-on-surface-variant font-medium" href="#">Assets</a>
<a class="font-label-sm text-label-sm hover:bg-surface-container-highest transition-colors cursor-pointer active:scale-95 text-on-surface-variant font-medium" href="#">Settings</a>
</nav>
</div>
<div class="flex items-center gap-md">
<div class="flex items-center bg-surface-container-lowest px-sm py-xs border border-outline-variant rounded-sm">
<span class="material-symbols-outlined text-outline text-[18px] mr-2">search</span>
<input class="bg-transparent border-none focus:ring-0 text-label-sm w-48 text-on-surface" placeholder="Search items..." type="text"/>
</div>
<div class="flex gap-xs">
<button class="w-8 h-8 flex items-center justify-center hover:bg-surface-container-highest transition-colors rounded-sm">
<span class="material-symbols-outlined text-[18px]">desktop_windows</span>
</button>
<button class="w-8 h-8 flex items-center justify-center hover:bg-surface-container-highest transition-colors rounded-sm">
<span class="material-symbols-outlined text-[18px]">tablet_mac</span>
</button>
<button class="w-8 h-8 flex items-center justify-center hover:bg-surface-container-highest transition-colors rounded-sm">
<span class="material-symbols-outlined text-[18px]">smartphone</span>
</button>
</div>
<button class="bg-primary-container text-on-primary-container px-lg py-xs font-label-sm text-label-sm rounded-sm hover:bg-opacity-90 transition-all active:scale-95">
                Publish
            </button>
</div>
</header>
<!-- Left SideNavBar (Collections) -->
<aside class="flex flex-col h-full w-panel-width fixed left-0 top-toolbar-height bg-panel-surface border-r border-outline-variant z-40">
<div class="p-md flex flex-col gap-base">
<div class="flex items-center gap-sm mb-md">
<div class="w-8 h-8 bg-electric-blue rounded-sm flex items-center justify-center">
<span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings: 'FILL' 1;">database</span>
</div>
<div>
<div class="font-label-md text-label-md text-on-surface">Collections</div>
<div class="font-body-sm text-body-sm text-on-surface-variant">Project Alpha</div>
</div>
</div>
<div class="space-y-1">
<div class="px-sm py-xs flex items-center justify-between bg-primary-container/15 text-electric-blue border-l-2 border-electric-blue group cursor-pointer transition-all">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-[18px]">description</span>
<span class="font-label-sm text-label-sm">Blog Posts</span>
</div>
<span class="font-mono-label text-mono-label text-outline group-hover:text-electric-blue">24</span>
</div>
<div class="px-sm py-xs flex items-center justify-between hover:bg-surface-container-highest text-on-surface-variant cursor-pointer transition-all">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-[18px]">group</span>
<span class="font-label-sm text-label-sm">Team</span>
</div>
<span class="font-mono-label text-mono-label text-outline">12</span>
</div>
<div class="px-sm py-xs flex items-center justify-between hover:bg-surface-container-highest text-on-surface-variant cursor-pointer transition-all">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-[18px]">shopping_bag</span>
<span class="font-label-sm text-label-sm">Products</span>
</div>
<span class="font-mono-label text-mono-label text-outline">156</span>
</div>
<div class="px-sm py-xs flex items-center justify-between hover:bg-surface-container-highest text-on-surface-variant cursor-pointer transition-all">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-[18px]">work</span>
<span class="font-label-sm text-label-sm">Portfolio</span>
</div>
<span class="font-mono-label text-mono-label text-outline">48</span>
</div>
<div class="px-sm py-xs flex items-center justify-between hover:bg-surface-container-highest text-on-surface-variant cursor-pointer transition-all">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-[18px]">category</span>
<span class="font-label-sm text-label-sm">Categories</span>
</div>
<span class="font-mono-label text-mono-label text-outline">8</span>
</div>
</div>
<button class="mt-xl w-full py-sm border border-dashed border-outline-variant text-outline hover:text-on-surface hover:border-on-surface transition-colors font-label-sm text-label-sm flex items-center justify-center gap-sm">
<span class="material-symbols-outlined text-[16px]">add</span>
                Create Collection
            </button>
</div>
<div class="mt-auto p-md border-t border-outline-variant bg-surface-container-low/50">
<div class="flex items-center justify-between mb-md">
<div class="font-label-sm text-label-sm text-royal-purple font-bold">PRO PLAN</div>
<button class="font-label-sm text-label-sm text-electric-blue hover:underline">Upgrade</button>
</div>
<div class="flex flex-col gap-sm">
<div class="flex items-center gap-sm text-on-surface-variant hover:text-on-surface cursor-pointer">
<span class="material-symbols-outlined text-[18px]">help</span>
<span class="font-label-sm text-label-sm">Help Center</span>
</div>
<div class="flex items-center gap-sm text-on-surface-variant hover:text-on-surface cursor-pointer">
<span class="material-symbols-outlined text-[18px]">chat_bubble</span>
<span class="font-label-sm text-label-sm">Feedback</span>
</div>
</div>
</div>
</aside>
<!-- Main Canvas Area -->
<main class="canvas-area bg-editor-bg flex flex-col overflow-hidden relative">
<!-- Content Header -->
<div class="h-16 flex items-center justify-between px-xl bg-panel-surface/50 border-b border-outline-variant shrink-0">
<div class="flex items-center gap-md">
<h1 class="font-headline-sm text-headline-sm text-on-surface">Blog Posts</h1>
<div class="bg-surface-container-highest px-sm py-xs rounded-sm font-mono-label text-mono-label text-outline-variant">
                    24 Items
                </div>
</div>
<div class="flex items-center gap-sm">
<button class="flex items-center gap-xs px-md py-sm border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-sm hover:bg-surface-container-highest transition-colors">
<span class="material-symbols-outlined text-[18px]">add_column_right</span>
                    Add Field
                </button>
<button class="flex items-center gap-xs px-md py-sm bg-electric-blue text-white font-label-sm text-label-sm rounded-sm hover:bg-opacity-90 shadow-lg shadow-electric-blue/20 transition-all active:scale-95">
<span class="material-symbols-outlined text-[18px]">add</span>
                    New Item
                </button>
</div>
</div>
<!-- Table View -->
<div class="flex-1 overflow-auto bg-editor-bg">
<table class="w-full text-left border-collapse min-w-[1000px]">
<thead class="sticky top-0 bg-panel-surface border-b border-outline-variant z-10">
<tr>
<th class="p-md w-12"><input class="rounded-xs bg-editor-bg border-outline-variant text-electric-blue focus:ring-electric-blue" type="checkbox"/></th>
<th class="p-md font-label-sm text-label-sm text-outline uppercase tracking-wider">Item Name</th>
<th class="p-md font-label-sm text-label-sm text-outline uppercase tracking-wider">Status</th>
<th class="p-md font-label-sm text-label-sm text-outline uppercase tracking-wider">Author</th>
<th class="p-md font-label-sm text-label-sm text-outline uppercase tracking-wider">Publish Date</th>
<th class="p-md font-label-sm text-label-sm text-outline uppercase tracking-wider">Category</th>
<th class="p-md w-24"></th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/30">
<!-- Row 1 -->
<tr class="hover:bg-surface-container-highest/30 transition-colors group">
<td class="p-md"><input class="rounded-xs bg-editor-bg border-outline-variant text-electric-blue" type="checkbox"/></td>
<td class="p-md">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-sm bg-surface-container overflow-hidden shrink-0">
<img class="w-full h-full object-cover" data-alt="A clean, professional-grade architectural photography shot of a modern glass office building during the blue hour. The lighting is crisp and cool, reflecting the electric blue brand accents. The composition is minimalist and high-contrast, suitable for a tech-focused blog header." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAl894T_XYY6ZOvs0l3ngbcQ3ayUFkrdjoqrdB8qUV8dKL-ixMuL2uxXwXteNRkxK_TFt7bUUOhJf4L-n39xo3mjErfacUr4BjcUx_taaI7-ozXMJp_g5jjBLcD75LVrXR9b8nEB2tqWWuEdAhc5IB23WgM2kRo9b5vDrZg9ZuJ6-rOwn0O7OEqjtV1Qj4uwt7oqODGMnGLngyKJ0ZFRLkP0aq_GddoJ9OIB0UKTgz5u4W9atnpepQ-7LadoAHXM5mKhzKUxgyfr2ki"/>
</div>
<span class="font-body-sm text-body-sm text-on-surface font-medium">Future of Design Systems in 2025</span>
</div>
</td>
<td class="p-md">
<span class="inline-flex items-center px-sm py-xs rounded-full bg-success-green/10 text-success-green font-label-sm text-[10px]">Published</span>
</td>
<td class="p-md font-body-sm text-body-sm text-on-surface-variant">Alex Rivera</td>
<td class="p-md font-mono-label text-mono-label text-outline">Oct 12, 2024</td>
<td class="p-md">
<span class="px-sm py-xs bg-surface-container-highest rounded-sm font-mono-label text-mono-label text-on-surface">Design</span>
</td>
<td class="p-md">
<div class="flex items-center justify-end opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs text-outline hover:text-electric-blue transition-colors"><span class="material-symbols-outlined text-[18px]">edit</span></button>
<button class="p-xs text-outline hover:text-error-red transition-colors"><span class="material-symbols-outlined text-[18px]">delete</span></button>
</div>
</td>
</tr>
<!-- Row 2 -->
<tr class="hover:bg-surface-container-highest/30 transition-colors group">
<td class="p-md"><input class="rounded-xs bg-editor-bg border-outline-variant text-electric-blue" type="checkbox"/></td>
<td class="p-md">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-sm bg-surface-container overflow-hidden shrink-0">
<img class="w-full h-full object-cover" data-alt="Close-up macro shot of electronic circuit board with glowing neon pathways. The lighting is dramatic and cinematic, focusing on high-tech precision. The color palette consists of deep charcoal and vibrant electric blue glowing elements, embodying a futuristic pro-grade engineering aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDvfYiw6V8AeuXyeU2NLvSzH8hQu4qtA863Zw1qmjzZ3Fg3_Z11qiCbX-oxIl8TziaEsDGNjocY-YuNKJPA3yAMe7cKVqqWrKQaba9LMqz8B73vP86vs9PEQwH6IEYYfSLcc2HjsazgLIBwFzEGv-u5H5y-CK0irAkga5t1xMipSU_FxeAqhuZJGMGYNBnXo5qV73qA7twMYm3BflnPIMtnwKLy7udM_ARi5OO8Vo_c_-YbKX5F5YTsOgn6pe2ZqOTeVK6h_iNlRGEh"/>
</div>
<span class="font-body-sm text-body-sm text-on-surface font-medium">Optimizing WebGL Renderers</span>
</div>
</td>
<td class="p-md">
<span class="inline-flex items-center px-sm py-xs rounded-full bg-warning-amber/10 text-warning-amber font-label-sm text-[10px]">Draft</span>
</td>
<td class="p-md font-body-sm text-body-sm text-on-surface-variant">Sarah Chen</td>
<td class="p-md font-mono-label text-mono-label text-outline">Pending</td>
<td class="p-md">
<span class="px-sm py-xs bg-surface-container-highest rounded-sm font-mono-label text-mono-label text-on-surface">Engineering</span>
</td>
<td class="p-md text-right">
<div class="flex items-center justify-end opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs text-outline hover:text-electric-blue transition-colors"><span class="material-symbols-outlined text-[18px]">edit</span></button>
<button class="p-xs text-outline hover:text-error-red transition-colors"><span class="material-symbols-outlined text-[18px]">delete</span></button>
</div>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-surface-container-highest/30 transition-colors group">
<td class="p-md"><input class="rounded-xs bg-editor-bg border-outline-variant text-electric-blue" type="checkbox"/></td>
<td class="p-md">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-sm bg-surface-container overflow-hidden shrink-0">
<img class="w-full h-full object-cover" data-alt="Abstract 3D render of soft, flowing fabric-like shapes in a minimalist studio setting. The lighting is diffused and ethereal, creating gentle shadows. The mood is calm and sophisticated, using a palette of off-white and subtle electric blue tones to communicate luxury and modern design quality." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAyXG1dqwI4SpbLvovtZBXhRlqVw2ayzmwdAQAoQQyOu9y0wv4HSBN1I6v5bXSc1S6Aj4w3EJEBgt0aU7zxiz9pjuLNSgn1_zqNJyeohPhZGo43P-KNxkFu0MpmAygdj7nGTVf0wvNfTHQ_xV6tyDfJ-ZvcqUZI0XMM5fEH-512LBGPE_y38Fd1S3dUfusQBPZ80Ipkf9s2t2qEHB_tnfBdBG0wVrcbwyD83w5Hw87xPBgyPbinDtbX8UVD9xin6ANcrUClnTIY6pAy"/>
</div>
<span class="font-body-sm text-body-sm text-on-surface font-medium">Designing for Emotional Depth</span>
</div>
</td>
<td class="p-md">
<span class="inline-flex items-center px-sm py-xs rounded-full bg-success-green/10 text-success-green font-label-sm text-[10px]">Published</span>
</td>
<td class="p-md font-body-sm text-body-sm text-on-surface-variant">Alex Rivera</td>
<td class="p-md font-mono-label text-mono-label text-outline">Oct 01, 2024</td>
<td class="p-md">
<span class="px-sm py-xs bg-surface-container-highest rounded-sm font-mono-label text-mono-label text-on-surface">Design</span>
</td>
<td class="p-md text-right">
<div class="flex items-center justify-end opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs text-outline hover:text-electric-blue transition-colors"><span class="material-symbols-outlined text-[18px]">edit</span></button>
<button class="p-xs text-outline hover:text-error-red transition-colors"><span class="material-symbols-outlined text-[18px]">delete</span></button>
</div>
</td>
</tr>
<!-- Row 4 -->
<tr class="hover:bg-surface-container-highest/30 transition-colors group">
<td class="p-md"><input class="rounded-xs bg-editor-bg border-outline-variant text-electric-blue" type="checkbox"/></td>
<td class="p-md">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-sm bg-surface-container overflow-hidden shrink-0">
<img class="w-full h-full object-cover" data-alt="Sophisticated industrial workspace with a dual-monitor setup and high-end peripheral equipment. The environment is dark and moody with a single focused spotlight. The aesthetic is pro-grade and technical, featuring sharp details and a clean, organized layout that reflects a high-performance creative process." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBWeWcvdpzMS5FDw8BePn8QnUC1hshWsvU53VQhvtrIGOOQeE0DP7k9acmq08_2bCfTtTxY_FMuNhbsxtiCkyQCIT3wHixVsviYLFZFI2sAwRyDG0_5YSAXn6VKplCDBaqNdN_Ja41v4ozEfJY_4tl2h0ZWdpIoa6XcseIW82MAXUwcyCGNKuMwQ-18t5L-MRKVPjnPT4B42iBDp9KPZ0jnY53gFLAAcfF0DUoznVwsgDgV8bn0fZOCHvN01D38k39CglWXnCmtZ1OI"/>
</div>
<span class="font-body-sm text-body-sm text-on-surface font-medium">StudioPro V2: What's New?</span>
</div>
</td>
<td class="p-md">
<span class="inline-flex items-center px-sm py-xs rounded-full bg-electric-blue/10 text-electric-blue font-label-sm text-[10px]">Scheduled</span>
</td>
<td class="p-md font-body-sm text-body-sm text-on-surface-variant">Jordan Peak</td>
<td class="p-md font-mono-label text-mono-label text-outline">Nov 15, 2024</td>
<td class="p-md">
<span class="px-sm py-xs bg-surface-container-highest rounded-sm font-mono-label text-mono-label text-on-surface">Announcements</span>
</td>
<td class="p-md text-right">
<div class="flex items-center justify-end opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs text-outline hover:text-electric-blue transition-colors"><span class="material-symbols-outlined text-[18px]">edit</span></button>
<button class="p-xs text-outline hover:text-error-red transition-colors"><span class="material-symbols-outlined text-[18px]">delete</span></button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</main>
<!-- Right SideNavBar (Properties) -->
<aside class="flex flex-col h-full w-panel-width fixed right-0 top-toolbar-height bg-panel-surface border-l border-outline-variant z-40">
<div class="h-10 border-b border-outline-variant flex">
<button class="flex-1 font-label-sm text-label-sm text-on-primary-container bg-primary-container rounded-sm flex items-center justify-center">Style</button>
<button class="flex-1 font-label-sm text-label-sm text-on-surface-variant hover:text-on-surface transition-opacity flex items-center justify-center">Interactions</button>
<button class="flex-1 font-label-sm text-label-sm text-on-surface-variant hover:text-on-surface transition-opacity flex items-center justify-center">Audit</button>
</div>
<div class="p-md flex flex-col gap-lg">
<div>
<div class="font-label-sm text-label-sm text-on-surface mb-sm flex items-center justify-between">
<span>Collection Config</span>
<span class="material-symbols-outlined text-[16px] text-outline">settings</span>
</div>
<div class="space-y-md">
<div class="flex flex-col gap-xs">
<label class="font-label-sm text-label-sm text-outline">COLLECTION NAME</label>
<input class="h-7 bg-editor-bg border border-outline-variant rounded-sm text-body-sm px-sm focus:border-electric-blue focus:ring-0" type="text" value="Blog Posts"/>
</div>
<div class="flex flex-col gap-xs">
<label class="font-label-sm text-label-sm text-outline">SLUG</label>
<input class="h-7 bg-editor-bg border border-outline-variant rounded-sm font-mono-label text-mono-label px-sm focus:border-electric-blue focus:ring-0" type="text" value="/blog"/>
</div>
</div>
</div>
<div class="border-t border-outline-variant pt-lg">
<div class="font-label-sm text-label-sm text-on-surface mb-sm">Field Map</div>
<div class="flex flex-col gap-sm">
<div class="flex items-center justify-between bg-surface-container-low p-sm border border-outline-variant rounded-sm">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-[16px] text-electric-blue">title</span>
<span class="font-body-sm text-body-sm">Name</span>
</div>
<span class="font-mono-label text-mono-label text-outline">Plain Text</span>
</div>
<div class="flex items-center justify-between bg-surface-container-low p-sm border border-outline-variant rounded-sm">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-[16px] text-royal-purple">image</span>
<span class="font-body-sm text-body-sm">Main Image</span>
</div>
<span class="font-mono-label text-mono-label text-outline">Asset</span>
</div>
<div class="flex items-center justify-between bg-surface-container-low p-sm border border-outline-variant rounded-sm">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-[16px] text-success-green">article</span>
<span class="font-body-sm text-body-sm">Post Body</span>
</div>
<span class="font-mono-label text-mono-label text-outline">Rich Text</span>
</div>
</div>
</div>
<div class="border-t border-outline-variant pt-lg">
<div class="flex items-center justify-between mb-sm">
<div class="font-label-sm text-label-sm text-on-surface">Data Integration</div>
<div class="w-8 h-4 bg-primary-container rounded-full relative cursor-pointer">
<div class="absolute right-0.5 top-0.5 w-3 h-3 bg-white rounded-full"></div>
</div>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                    Connected to <span class="text-electric-blue font-bold">API Sync</span>. Updates automatically every 15 minutes.
                </p>
</div>
</div>
</aside>
<!-- Footer -->
<footer class="bg-surface-container-lowest dark:bg-surface-container-lowest border-t border-outline-variant fixed bottom-0 left-0 w-full h-8 flex justify-between items-center px-md z-40">
<div class="flex items-center gap-lg">
<span class="font-label-sm text-label-sm text-outline">© 2024 StudioPro Engine</span>
<div class="flex items-center gap-md">
<span class="font-mono-label text-mono-label text-outline hover:text-primary cursor-default transition-colors">CMS &gt; Blog Posts</span>
</div>
</div>
<div class="flex items-center gap-lg">
<span class="font-mono-label text-mono-label text-outline hover:text-primary transition-colors cursor-default">Zoom 100%</span>
<div class="flex items-center gap-xs text-outline hover:text-primary transition-colors cursor-default">
<span class="material-symbols-outlined text-[14px]">help</span>
<span class="font-mono-label text-mono-label">Canvas Help</span>
</div>
</div>
</footer>
<script>
        // Micro-interaction for table rows
        document.querySelectorAll('tr').forEach(row => {
            row.addEventListener('click', (e) => {
                if (e.target.tagName !== 'INPUT') {
                    // Logic for selecting a row and updating the right panel
                    console.log('Row clicked');
                }
            });
        });

        // Search highlight logic (Visual only)
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.classList.add('border-electric-blue');
        });
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.classList.remove('border-electric-blue');
        });
    </script>
</body></html>

<!-- Template Library -->
<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>StudioPro | Template Library</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.cdnfonts.com/css/geist" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 20;
            vertical-align: middle;
        }
        body {
            background-color: #0B0E14;
            color: #dae2fd;
            overflow-x: hidden;
        }
        /* Custom scrollbar for pro-grade feel */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #161B22; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #2E62FF; }

        .template-card-gradient {
            background: linear-gradient(180deg, rgba(22, 27, 34, 0) 0%, rgba(22, 27, 34, 0.9) 100%);
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "surface-container": "#171f33",
                      "error": "#ffb4ab",
                      "on-error": "#690005",
                      "surface-container-lowest": "#060e20",
                      "success-green": "#10B981",
                      "royal-purple": "#8B5CF6",
                      "on-secondary-fixed-variant": "#5516be",
                      "on-primary-fixed-variant": "#0039b5",
                      "electric-blue": "#2E62FF",
                      "inverse-on-surface": "#283044",
                      "on-error-container": "#ffdad6",
                      "on-background": "#dae2fd",
                      "tertiary-container": "#007c91",
                      "outline-variant": "#434656",
                      "secondary-container": "#571bc1",
                      "on-tertiary": "#003640",
                      "primary-fixed": "#dce1ff",
                      "surface-container-highest": "#2d3449",
                      "surface-bright": "#31394d",
                      "secondary-fixed": "#e9ddff",
                      "surface-container-low": "#131b2e",
                      "on-tertiary-fixed-variant": "#004e5c",
                      "tertiary-fixed": "#acedff",
                      "background": "#0b1326",
                      "on-primary-container": "#f7f6ff",
                      "outline": "#8d90a2",
                      "on-primary-fixed": "#001552",
                      "tertiary": "#4cd7f6",
                      "error-red": "#EF4444",
                      "secondary-fixed-dim": "#d0bcff",
                      "on-tertiary-fixed": "#001f26",
                      "on-tertiary-container": "#e9f9ff",
                      "editor-bg": "#0B0E14",
                      "surface-dim": "#0b1326",
                      "surface": "#0b1326",
                      "warning-amber": "#F59E0B",
                      "primary-fixed-dim": "#b7c4ff",
                      "primary": "#b7c4ff",
                      "inverse-primary": "#024cec",
                      "secondary": "#d0bcff",
                      "panel-surface": "#161B22",
                      "on-primary": "#002682",
                      "on-secondary-container": "#c4abff",
                      "surface-container-high": "#222a3d",
                      "error-container": "#93000a",
                      "on-secondary-fixed": "#23005c",
                      "primary-container": "#2e62ff",
                      "inverse-surface": "#dae2fd",
                      "canvas-bg": "#1F2937",
                      "tertiary-fixed-dim": "#4cd7f6",
                      "on-surface-variant": "#c3c5d8",
                      "surface-variant": "#2d3449",
                      "surface-tint": "#b7c4ff",
                      "on-surface": "#dae2fd",
                      "on-secondary": "#3c0091"
              },
              "borderRadius": {
                      "DEFAULT": "0.125rem",
                      "lg": "0.25rem",
                      "xl": "0.5rem",
                      "full": "0.75rem"
              },
              "spacing": {
                      "toolbar-height": "48px",
                      "panel-width": "280px",
                      "4xl": "64px",
                      "md": "12px",
                      "xl": "24px",
                      "lg": "16px",
                      "base": "4px",
                      "2xl": "32px",
                      "3xl": "48px",
                      "xs": "4px",
                      "sm": "8px"
              },
              "fontFamily": {
                      "headline-md": ["Geist"],
                      "label-md": ["Geist"],
                      "headline-sm": ["Geist"],
                      "headline-lg": ["Geist"],
                      "body-lg": ["Geist"],
                      "body-md": ["Geist"],
                      "mono-label": ["JetBrains Mono"],
                      "body-sm": ["Geist"],
                      "label-sm": ["Geist"]
              },
              "fontSize": {
                      "headline-md": ["24px", {"lineHeight": "1.3", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                      "label-md": ["12px", {"lineHeight": "1", "letterSpacing": "0.02em", "fontWeight": "500"}],
                      "headline-sm": ["18px", {"lineHeight": "1.4", "fontWeight": "500"}],
                      "headline-lg": ["32px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "600"}],
                      "body-lg": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                      "body-md": ["14px", {"lineHeight": "1.5", "fontWeight": "400"}],
                      "mono-label": ["11px", {"lineHeight": "1", "fontWeight": "400"}],
                      "body-sm": ["13px", {"lineHeight": "1.5", "fontWeight": "400"}],
                      "label-sm": ["11px", {"lineHeight": "1", "letterSpacing": "0.05em", "fontWeight": "600"}]
              }
            },
          },
        }
    </script>
</head>
<body class="font-body-md text-on-surface">
<!-- TopNavBar -->
<header class="flex justify-between items-center h-toolbar-height px-md w-full bg-panel-surface dark:bg-panel-surface docked full-width top-0 border-b border-outline-variant fixed z-50">
<div class="flex items-center gap-xl">
<span class="font-headline-md text-headline-md font-bold text-on-surface dark:text-on-surface">StudioPro</span>
<nav class="hidden md:flex gap-lg items-center">
<a class="font-label-sm text-label-sm text-on-surface-variant font-medium hover:bg-surface-container-highest transition-colors px-sm py-1 rounded" href="#">Pages</a>
<a class="font-label-sm text-label-sm text-electric-blue font-bold border-b-2 border-electric-blue pb-1 cursor-pointer active:scale-95" href="#">Assets</a>
<a class="font-label-sm text-label-sm text-on-surface-variant font-medium hover:bg-surface-container-highest transition-colors px-sm py-1 rounded" href="#">Settings</a>
</nav>
</div>
<div class="flex items-center gap-md">
<div class="flex items-center gap-xs">
<button class="w-8 h-8 flex items-center justify-center hover:bg-surface-container-highest transition-colors rounded text-on-surface-variant">
<span class="material-symbols-outlined text-[20px]">desktop_windows</span>
</button>
<button class="w-8 h-8 flex items-center justify-center hover:bg-surface-container-highest transition-colors rounded text-on-surface-variant">
<span class="material-symbols-outlined text-[20px]">tablet_mac</span>
</button>
<button class="w-8 h-8 flex items-center justify-center hover:bg-surface-container-highest transition-colors rounded text-on-surface-variant">
<span class="material-symbols-outlined text-[20px]">smartphone</span>
</button>
</div>
<div class="h-6 w-[1px] bg-outline-variant mx-2"></div>
<button class="bg-primary-container text-on-primary-container px-lg py-1.5 rounded font-label-sm text-label-sm font-bold hover:brightness-110 active:scale-95 transition-all">Publish</button>
</div>
</header>
<!-- SideNavBar (Left) -->
<aside class="flex flex-col h-[calc(100vh-48px)] w-panel-width fixed left-0 top-toolbar-height bg-panel-surface dark:bg-panel-surface border-r border-outline-variant z-40">
<div class="p-md flex flex-col gap-base border-b border-outline-variant">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full bg-surface-container-highest overflow-hidden">
<img class="w-full h-full object-cover" data-alt="A professional user profile avatar in a minimalist digital art style, featuring a sleek silhouette against a dark navy background with subtle electric blue circular accents, conveying a sense of high-tech expertise and creative precision consistent with the pro-grade StudioPro branding." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDnPXAr2i9QgLP0rw4YrtIPrxWSlRFATrdAJJy7p857zKFodbw27-d5Lqql9RD1e26GDA2EO4Q0rlaJ62jaeWwVJ8H4qGWGFwANYcc4056E8AtgD_E6snDUSwKNE-hVFpcnDNtZJlxabhHQ0P9mEN9vY93B72D5RgNNjDAfu1vVzZtwjQ8e40d0r115eIbVRjUuv2tgMjCaXe2AAoAPMTMZ79urXcVlhd1ysmsRiZEED48My8iQkkME5pviUWOrlG-lBzkQgj1cyymd"/>
</div>
<div>
<div class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant">Project Alpha</div>
<div class="font-body-sm text-body-sm text-outline">V1.0.4</div>
</div>
</div>
</div>
<nav class="flex-1 py-md">
<div class="px-sm mb-sm text-outline font-label-sm text-label-sm uppercase tracking-tighter">Navigator</div>
<div class="flex flex-col">
<a class="flex items-center gap-sm px-md py-2 text-on-surface-variant hover:bg-surface-container-highest transition-all duration-150 font-label-sm text-label-sm" href="#">
<span class="material-symbols-outlined">layers</span> Layers
                </a>
<a class="flex items-center gap-sm px-md py-2 bg-primary-container/15 text-electric-blue border-l-2 border-electric-blue transition-all duration-150 font-label-sm text-label-sm" href="#">
<span class="material-symbols-outlined">add_box</span> Blocks
                </a>
<a class="flex items-center gap-sm px-md py-2 text-on-surface-variant hover:bg-surface-container-highest transition-all duration-150 font-label-sm text-label-sm" href="#">
<span class="material-symbols-outlined">widgets</span> Symbols
                </a>
<a class="flex items-center gap-sm px-md py-2 text-on-surface-variant hover:bg-surface-container-highest transition-all duration-150 font-label-sm text-label-sm" href="#">
<span class="material-symbols-outlined">image</span> Assets
                </a>
<a class="flex items-center gap-sm px-md py-2 text-on-surface-variant hover:bg-surface-container-highest transition-all duration-150 font-label-sm text-label-sm" href="#">
<span class="material-symbols-outlined">settings</span> Config
                </a>
</div>
</nav>
<div class="p-md mt-auto flex flex-col gap-sm">
<button class="w-full py-2 bg-surface-container-highest text-electric-blue border border-electric-blue/30 rounded font-label-sm text-label-sm hover:bg-electric-blue hover:text-white transition-all">Upgrade Plan</button>
<div class="flex flex-col gap-base mt-md">
<a class="flex items-center gap-sm px-md py-1 text-on-surface-variant font-label-sm text-label-sm hover:text-on-surface transition-colors" href="#">
<span class="material-symbols-outlined text-[16px]">help</span> Help
                </a>
<a class="flex items-center gap-sm px-md py-1 text-on-surface-variant font-label-sm text-label-sm hover:text-on-surface transition-colors" href="#">
<span class="material-symbols-outlined text-[16px]">chat_bubble</span> Feedback
                </a>
</div>
</div>
</aside>
<!-- SideNavBar (Right) - Contextual for Templates -->
<aside class="hidden xl:flex flex-col h-[calc(100vh-48px)] w-panel-width fixed right-0 top-toolbar-height bg-panel-surface dark:bg-panel-surface border-l border-outline-variant z-40">
<div class="p-md border-b border-outline-variant">
<div class="font-label-sm text-label-sm text-on-surface mb-1">Element Properties</div>
<div class="font-mono-label text-mono-label text-outline uppercase">Selected: Template Filter</div>
</div>
<div class="p-md flex flex-col gap-lg">
<div class="flex flex-col gap-sm">
<span class="font-label-sm text-label-sm text-on-surface-variant">Categories</span>
<div class="grid grid-cols-2 gap-xs">
<button class="text-on-primary-container bg-primary-container rounded-sm py-1 font-label-sm text-label-sm">All</button>
<button class="text-on-surface-variant hover:text-on-surface transition-opacity py-1 font-label-sm text-label-sm bg-surface-container-highest rounded-sm">Business</button>
<button class="text-on-surface-variant hover:text-on-surface transition-opacity py-1 font-label-sm text-label-sm bg-surface-container-highest rounded-sm">Portfolio</button>
<button class="text-on-surface-variant hover:text-on-surface transition-opacity py-1 font-label-sm text-label-sm bg-surface-container-highest rounded-sm">Blog</button>
</div>
</div>
<div class="flex flex-col gap-sm">
<span class="font-label-sm text-label-sm text-on-surface-variant">Layout Density</span>
<div class="flex items-center bg-editor-bg border border-outline-variant rounded p-1">
<div class="flex-1 text-center py-1 bg-surface-container-highest rounded-xs font-mono-label text-mono-label">Compact</div>
<div class="flex-1 text-center py-1 font-mono-label text-mono-label text-outline">Relaxed</div>
</div>
</div>
<div class="flex flex-col gap-sm">
<span class="font-label-sm text-label-sm text-on-surface-variant">Color Profile</span>
<div class="flex gap-xs">
<div class="w-6 h-6 rounded-full bg-electric-blue cursor-pointer ring-2 ring-white ring-offset-2 ring-offset-panel-surface"></div>
<div class="w-6 h-6 rounded-full bg-royal-purple cursor-pointer"></div>
<div class="w-6 h-6 rounded-full bg-success-green cursor-pointer"></div>
<div class="w-6 h-6 rounded-full bg-error-red cursor-pointer"></div>
</div>
</div>
</div>
</aside>
<!-- Main Canvas Area -->
<main class="ml-panel-width mr-0 xl:mr-panel-width pt-toolbar-height min-h-screen bg-editor-bg">
<div class="p-2xl max-w-7xl mx-auto">
<!-- Hero / Header Section -->
<div class="mb-3xl relative">
<div class="flex justify-between items-end mb-xl">
<div>
<h1 class="font-headline-lg text-headline-lg text-on-surface mb-sm">Template Library</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">
                            Kickstart your creative workflow with our curated selection of high-performance website templates. Optimized for speed and precision.
                        </p>
</div>
<div class="flex gap-sm">
<div class="relative group">
<span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline text-[18px]">search</span>
<input class="bg-editor-bg border border-outline-variant text-on-surface text-body-sm h-[32px] pl-10 pr-4 rounded-lg focus:border-electric-blue focus:ring-1 focus:ring-electric-blue outline-none transition-all w-64" placeholder="Search templates..." type="text"/>
</div>
</div>
</div>
<!-- Category Pills -->
<div class="flex gap-sm overflow-x-auto pb-base no-scrollbar">
<button class="px-lg py-1.5 bg-electric-blue text-white rounded-full font-label-sm text-label-sm flex items-center gap-xs">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">grid_view</span> All Templates
                    </button>
<button class="px-lg py-1.5 bg-surface-container-high text-on-surface-variant hover:text-on-surface rounded-full font-label-sm text-label-sm transition-colors">Business</button>
<button class="px-lg py-1.5 bg-surface-container-high text-on-surface-variant hover:text-on-surface rounded-full font-label-sm text-label-sm transition-colors">Portfolio</button>
<button class="px-lg py-1.5 bg-surface-container-high text-on-surface-variant hover:text-on-surface rounded-full font-label-sm text-label-sm transition-colors">Blog</button>
<button class="px-lg py-1.5 bg-surface-container-high text-on-surface-variant hover:text-on-surface rounded-full font-label-sm text-label-sm transition-colors">E-commerce</button>
</div>
</div>
<!-- Bento Grid Gallery -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-xl">
<!-- Template Card 1 (Large Feature) -->
<div class="group relative bg-panel-surface border border-outline-variant rounded-xl overflow-hidden md:col-span-2 hover:border-electric-blue/50 transition-all duration-300 shadow-xl">
<div class="aspect-[16/9] w-full relative overflow-hidden bg-surface-container-lowest">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" data-alt="A sophisticated business landing page template preview for StudioPro, featuring a bold asymmetric layout, high-contrast typography in electric blue and crisp white, and a background of soft-focus architectural photography in cool slate tones. The design is modern, professional, and exudes technical precision." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDPgnCltFznEKuryWolGQhL-HevusVbdKtnGDv0jppQjP-pIJZ6EEYgZ8-gcmgLFE6LuOXJPxhigjwkqG2TG-5TuM_gGoZlb6lFU8hbdrkr28oGiuYBcKglJlPla6_MNYsUQg8EEGUzemm_IE9FGy5ORtgdjyjNNbzaOb3qkxMDLO43gMSD5CLK9CqSeFJp973KfmgpX8hR9s1IfHbDoVqj9BSWgAQFb8oUfa5qsUK5eXVdKskRmELtCeLke12tHhqdMYAq6DUY4ta9"/>
<div class="absolute top-md right-md bg-electric-blue text-white font-mono-label text-mono-label px-sm py-1 rounded">FEATURED</div>
<div class="absolute inset-0 template-card-gradient opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-xl">
<div class="flex gap-sm w-full">
<button class="flex-1 py-2.5 bg-white text-editor-bg font-label-sm text-label-sm font-bold rounded-lg hover:bg-on-surface transition-colors">Use This Template</button>
<button class="px-xl py-2.5 bg-editor-bg/80 backdrop-blur-md text-white border border-white/20 font-label-sm text-label-sm font-bold rounded-lg hover:bg-editor-bg transition-colors">Preview</button>
</div>
</div>
</div>
<div class="p-lg flex justify-between items-center">
<div>
<h3 class="font-headline-sm text-headline-sm text-on-surface">Nexus Corporate</h3>
<p class="font-body-sm text-body-sm text-outline">Multi-page business solution</p>
</div>
<div class="flex gap-xs">
<span class="material-symbols-outlined text-outline text-[18px]">responsive_layout</span>
<span class="material-symbols-outlined text-outline text-[18px]">bolt</span>
</div>
</div>
</div>
<!-- Template Card 2 -->
<div class="group bg-panel-surface border border-outline-variant rounded-xl overflow-hidden hover:border-electric-blue/50 transition-all duration-300">
<div class="aspect-square relative overflow-hidden bg-surface-container-lowest">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" data-alt="A minimalist portfolio website template preview showing a clean white grid of art photography, using StudioPro's pro-grade minimal aesthetic. The interface includes subtle electric blue highlights on hover, thin slate borders, and generous negative space to emphasize creative work. Elegant Geist typography is prominent." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAN3B6P1lPaWHYNA3Csa28TbYlRJhTOFDjQhCr40QSi-qUfpdbrBUhxgRl_qMXV-ngRAoZcntWm4eox3K1z_tFXCB4CbtT5s2vEtvzx1ARnporM-K4T1RmgQ6pJYHaVh-P2914m2jNcq97SHOl47eNayCHC7bCERj3TcPmOhZZQNZe1MXTue3e_qCdlukNy1Evi6DI5hBbUSyT1l3hsvqbcsaOzh321xJhgLZiJSvqSYvrTmgO3v6CCMk7HNR3SJ22kONEyicBy6SqK"/>
<div class="absolute inset-0 bg-editor-bg/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-center items-center gap-md p-lg">
<button class="w-full py-2 bg-electric-blue text-white font-label-sm text-label-sm font-bold rounded hover:brightness-110 transition-all">Use This Template</button>
<button class="w-full py-2 bg-transparent text-white border border-white/30 font-label-sm text-label-sm font-bold rounded hover:bg-white/10 transition-all">Preview</button>
</div>
</div>
<div class="p-md">
<h3 class="font-label-md text-label-md text-on-surface">Mono Minimalist</h3>
<p class="font-body-sm text-body-sm text-outline">Portfolio / Creative</p>
</div>
</div>
<!-- Template Card 3 -->
<div class="group bg-panel-surface border border-outline-variant rounded-xl overflow-hidden hover:border-electric-blue/50 transition-all duration-300">
<div class="aspect-square relative overflow-hidden bg-surface-container-lowest">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" data-alt="An editorial style blog template preview with high-density typography, a deep navy background, and royal purple accent colors. The layout features a magazine-inspired grid with sharp lines and technical-looking data overlays, reflecting the StudioPro systematic minimalism design philosophy." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAQt6rBKDKEIr-oLhJNkZZ7WmSGz6yCb2Ty-NqpDq6d7sQ-QzV3SyUKNF35rdMicyIJSCkpKxunWdu3d0I5Lbi20r0uhLAbDpGNI74-EnTHzXCEbkHa_bYDnGa7vn96o35i5PzjCk_h4WmS67DpQbv63VmgZyd37wrmA4i5U8z_lE8UkqmvfuLikRRkn7DrIWhWzsnvhYUpaghmDYh645ziA6wpQLBR5ad-RfTszloIp6kKBdbJfmqMhBYgGDnDrrEzz2Q3O_bgQp8O"/>
<div class="absolute inset-0 bg-editor-bg/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-center items-center gap-md p-lg">
<button class="w-full py-2 bg-electric-blue text-white font-label-sm text-label-sm font-bold rounded hover:brightness-110 transition-all">Use This Template</button>
<button class="w-full py-2 bg-transparent text-white border border-white/30 font-label-sm text-label-sm font-bold rounded hover:bg-white/10 transition-all">Preview</button>
</div>
</div>
<div class="p-md">
<h3 class="font-label-md text-label-md text-on-surface">Chronicle Press</h3>
<p class="font-body-sm text-body-sm text-outline">Blog / Magazine</p>
</div>
</div>
<!-- Template Card 4 -->
<div class="group bg-panel-surface border border-outline-variant rounded-xl overflow-hidden hover:border-electric-blue/50 transition-all duration-300">
<div class="aspect-square relative overflow-hidden bg-surface-container-lowest">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" data-alt="A high-end e-commerce store template preview featuring luxurious fashion items in a clean, minimalist shop interface. The design uses a sophisticated palette of deep charcoal, off-white, and electric blue status icons. High-fidelity glassmorphism effects are visible on the navigation and cart overlays." src="https://lh3.googleusercontent.com/aida-public/AB6AXuD-NdTkuoH99UfYKjIQJSgRjka2lfkUXxz1SgQUmAkLZtVGKA3FsZDh120oiI38ZGkI_jCz206NCG8586S4LCNOVSg1H--loAkr4YAY-3N8RZkzyQtyTiEWkt-hXo3rNADCOwFFEUbOz6ATuXs2vppTrWAV_V85s0SHW5_ixU9fxhN_LGXtXVFtpmGBS2Va2p1mDNAAeqw57m76Ak8AajrEBCa6nHdPfB_kP1p9gGeOvt4OeYZ2IM27dRAglsjylsEJIAcf_npiTf3z"/>
<div class="absolute inset-0 bg-editor-bg/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-center items-center gap-md p-lg">
<button class="w-full py-2 bg-electric-blue text-white font-label-sm text-label-sm font-bold rounded hover:brightness-110 transition-all">Use This Template</button>
<button class="w-full py-2 bg-transparent text-white border border-white/30 font-label-sm text-label-sm font-bold rounded hover:bg-white/10 transition-all">Preview</button>
</div>
</div>
<div class="p-md">
<h3 class="font-label-md text-label-md text-on-surface">Vogue Store</h3>
<p class="font-body-sm text-body-sm text-outline">E-commerce / Retail</p>
</div>
</div>
<!-- Template Card 5 -->
<div class="group bg-panel-surface border border-outline-variant rounded-xl overflow-hidden hover:border-electric-blue/50 transition-all duration-300">
<div class="aspect-square relative overflow-hidden bg-surface-container-lowest">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" data-alt="A creative tech startup template preview with vibrant gradients in royal purple and electric blue, featuring 3D geometric illustrations and a modern glassmorphism UI. The layout is technical and high-energy, perfect for a SaaS or engineering brand landing page." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCt_Lucm-5_pEgDacphyIgdstoYm7D67kOb2EX7whiNnUZiAT_XWU0OR-o62SM_uyRSmrPTqUr_gYF9b_eUX-ZmlxhKYpnr9sLAjd9X3U2hooKiOnQsYFj3JFLyjMFMn2uuuApxa2BMTeGTQJM0UGlFLHMtxcbhvQVlqmus1XNa-qN4XVvUDvBFHzgWXtnux0poE2Uew0T-fPlPC_94sgWsQsA2O87lhSQXEuKo3aMwJtGcmAe0YiAidSMjG67yWWBtxOTJjYD0e870"/>
<div class="absolute inset-0 bg-editor-bg/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-center items-center gap-md p-lg">
<button class="w-full py-2 bg-electric-blue text-white font-label-sm text-label-sm font-bold rounded hover:brightness-110 transition-all">Use This Template</button>
<button class="w-full py-2 bg-transparent text-white border border-white/30 font-label-sm text-label-sm font-bold rounded hover:bg-white/10 transition-all">Preview</button>
</div>
</div>
<div class="p-md">
<h3 class="font-label-md text-label-md text-on-surface">Ignite SaaS</h3>
<p class="font-body-sm text-body-sm text-outline">Software / Tech</p>
</div>
</div>
</div>
<!-- Empty State / Load More -->
<div class="mt-4xl border-t border-outline-variant pt-2xl text-center">
<button class="px-3xl py-md bg-surface-container-high hover:bg-surface-container-highest text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest rounded-lg transition-all">
                    Load More Templates
                </button>
<div class="mt-xl text-outline font-body-sm text-body-sm">
                    Showing 6 of 124 available templates
                </div>
</div>
</div>
</main>
<!-- Footer Status Bar -->
<footer class="fixed bottom-0 left-0 w-full h-8 flex justify-between items-center px-md z-40 bg-surface-container-lowest dark:bg-surface-container-lowest border-t border-outline-variant">
<div class="flex items-center gap-lg">
<span class="font-label-sm text-label-sm text-outline font-mono-label">© 2024 StudioPro Engine</span>
<div class="flex gap-md">
<a class="font-mono-label text-mono-label text-outline hover:text-primary transition-colors" href="#">Breadcrumbs</a>
<span class="text-outline-variant">/</span>
<a class="font-mono-label text-mono-label text-on-surface" href="#">Assets</a>
<span class="text-outline-variant">/</span>
<a class="font-mono-label text-mono-label text-outline hover:text-primary transition-colors" href="#">Templates</a>
</div>
</div>
<div class="flex items-center gap-xl">
<div class="flex items-center gap-xs font-mono-label text-mono-label text-on-surface-variant">
<span class="material-symbols-outlined text-[14px]">search</span>
                Zoom 100%
            </div>
<a class="font-mono-label text-mono-label text-outline hover:text-primary transition-colors flex items-center gap-xs" href="#">
<span class="material-symbols-outlined text-[14px]">help</span>
                Canvas Help
            </a>
</div>
</footer>
<script>
        // Micro-interactions for template cards
        document.querySelectorAll('.group').forEach(card => {
            card.addEventListener('mouseenter', () => {
                // Subtle scale effect handled by Tailwind group-hover
            });
        });

        // Search bar interaction
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.querySelector('span').classList.replace('text-outline', 'text-electric-blue');
        });
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.querySelector('span').classList.replace('text-electric-blue', 'text-outline');
        });
    </script>
</body></html>

<!-- Visual Editor Canvas -->
<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>StudioPro Engine | Editor</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&amp;family=JetBrains+Mono:wght@100..800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "surface-container": "#171f33",
                    "error": "#ffb4ab",
                    "on-error": "#690005",
                    "surface-container-lowest": "#060e20",
                    "success-green": "#10B981",
                    "royal-purple": "#8B5CF6",
                    "on-secondary-fixed-variant": "#5516be",
                    "on-primary-fixed-variant": "#0039b5",
                    "electric-blue": "#2E62FF",
                    "inverse-on-surface": "#283044",
                    "on-error-container": "#ffdad6",
                    "on-background": "#dae2fd",
                    "tertiary-container": "#007c91",
                    "outline-variant": "#434656",
                    "secondary-container": "#571bc1",
                    "on-tertiary": "#003640",
                    "primary-fixed": "#dce1ff",
                    "surface-container-highest": "#2d3449",
                    "surface-bright": "#31394d",
                    "secondary-fixed": "#e9ddff",
                    "surface-container-low": "#131b2e",
                    "on-tertiary-fixed-variant": "#004e5c",
                    "tertiary-fixed": "#acedff",
                    "background": "#0b1326",
                    "on-primary-container": "#f7f6ff",
                    "outline": "#8d90a2",
                    "on-primary-fixed": "#001552",
                    "tertiary": "#4cd7f6",
                    "error-red": "#EF4444",
                    "secondary-fixed-dim": "#d0bcff",
                    "on-tertiary-fixed": "#001f26",
                    "on-tertiary-container": "#e9f9ff",
                    "editor-bg": "#0B0E14",
                    "surface-dim": "#0b1326",
                    "surface": "#0b1326",
                    "warning-amber": "#F59E0B",
                    "primary-fixed-dim": "#b7c4ff",
                    "primary": "#b7c4ff",
                    "inverse-primary": "#024cec",
                    "secondary": "#d0bcff",
                    "panel-surface": "#161B22",
                    "on-primary": "#002682",
                    "on-secondary-container": "#c4abff",
                    "surface-container-high": "#222a3d",
                    "error-container": "#93000a",
                    "on-secondary-fixed": "#23005c",
                    "primary-container": "#2e62ff",
                    "inverse-surface": "#dae2fd",
                    "canvas-bg": "#1F2937",
                    "tertiary-fixed-dim": "#4cd7f6",
                    "on-surface-variant": "#c3c5d8",
                    "surface-variant": "#2d3449",
                    "surface-tint": "#b7c4ff",
                    "on-surface": "#dae2fd",
                    "on-secondary": "#3c0091"
            },
            "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
            },
            "spacing": {
                    "toolbar-height": "48px",
                    "panel-width": "280px",
                    "4xl": "64px",
                    "md": "12px",
                    "xl": "24px",
                    "lg": "16px",
                    "base": "4px",
                    "2xl": "32px",
                    "3xl": "48px",
                    "xs": "4px",
                    "sm": "8px"
            },
            "fontFamily": {
                    "headline-md": ["Geist"],
                    "label-md": ["Geist"],
                    "headline-sm": ["Geist"],
                    "headline-lg": ["Geist"],
                    "body-lg": ["Geist"],
                    "body-md": ["Geist"],
                    "mono-label": ["JetBrains Mono"],
                    "body-sm": ["Geist"],
                    "label-sm": ["Geist"]
            },
            "fontSize": {
                    "headline-md": ["24px", {"lineHeight": "1.3", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                    "label-md": ["12px", {"lineHeight": "1", "letterSpacing": "0.02em", "fontWeight": "500"}],
                    "headline-sm": ["18px", {"lineHeight": "1.4", "fontWeight": "500"}],
                    "headline-lg": ["32px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "600"}],
                    "body-lg": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "body-md": ["14px", {"lineHeight": "1.5", "fontWeight": "400"}],
                    "mono-label": ["11px", {"lineHeight": "1", "fontWeight": "400"}],
                    "body-sm": ["13px", {"lineHeight": "1.5", "fontWeight": "400"}],
                    "label-sm": ["11px", {"lineHeight": "1", "letterSpacing": "0.05em", "fontWeight": "600"}]
            }
          },
        },
      }
    </script>
<style>
        body {
            background-color: #0B0E14;
            color: #dae2fd;
            overflow: hidden;
            font-family: 'Geist', sans-serif;
        }
        .material-symbols-outlined {
            font-size: 20px;
            user-select: none;
        }
        .canvas-container {
            height: calc(100vh - 48px - 32px);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #434656;
            border-radius: 10px;
        }
        .active-blue-line::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #2E62FF;
        }
        .spacing-box-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(3, 1fr);
            gap: 4px;
        }
    </style>
</head>
<body class="bg-editor-bg">
<!-- TopNavBar (Shared Component) -->
<header class="bg-panel-surface dark:bg-panel-surface flex justify-between items-center h-toolbar-height px-md w-full border-b border-outline-variant docked full-width top-0 z-50">
<div class="flex items-center gap-xl">
<span class="font-headline-md text-headline-md font-bold text-on-surface dark:text-on-surface">StudioPro</span>
<nav class="flex gap-lg">
<span class="text-electric-blue font-bold border-b-2 border-electric-blue pb-1 font-label-sm text-label-sm cursor-pointer active:scale-95 transition-colors">Pages</span>
<span class="text-on-surface-variant font-medium font-label-sm text-label-sm cursor-pointer active:scale-95 hover:bg-surface-container-highest transition-colors">Assets</span>
<span class="text-on-surface-variant font-medium font-label-sm text-label-sm cursor-pointer active:scale-95 hover:bg-surface-container-highest transition-colors">Settings</span>
</nav>
</div>
<div class="flex items-center bg-surface-container-lowest rounded-lg px-sm h-8">
<span class="material-symbols-outlined text-electric-blue px-2 cursor-pointer" data-icon="desktop_windows">desktop_windows</span>
<span class="material-symbols-outlined text-on-surface-variant px-2 cursor-pointer hover:text-on-surface" data-icon="tablet_mac">tablet_mac</span>
<span class="material-symbols-outlined text-on-surface-variant px-2 cursor-pointer hover:text-on-surface" data-icon="smartphone">smartphone</span>
</div>
<div class="flex items-center gap-md">
<button class="bg-electric-blue text-white px-lg py-1.5 rounded-sm font-label-sm text-label-sm font-bold uppercase tracking-wider active:scale-95 transition-all">Publish</button>
</div>
</header>
<main class="flex h-[calc(100vh-48px)]">
<!-- Left Sidebar: Navigator & Blocks (Shared SideNavBar) -->
<aside class="flex flex-col h-full w-panel-width fixed left-0 top-toolbar-height bg-panel-surface border-r border-outline-variant z-40">
<div class="p-md border-b border-outline-variant">
<span class="font-label-md text-label-md uppercase tracking-widest text-on-surface-variant">Project Alpha</span>
<p class="font-body-sm text-body-sm text-outline opacity-60">V1.0.4</p>
</div>
<!-- Navigation Tabs -->
<div class="flex-1 custom-scrollbar overflow-y-auto">
<div class="flex flex-col">
<div class="bg-primary-container/15 text-electric-blue border-l-2 border-electric-blue px-md py-sm flex items-center gap-md cursor-pointer transition-all duration-150">
<span class="material-symbols-outlined" data-icon="layers">layers</span>
<span class="font-label-sm text-label-sm">Layers</span>
</div>
<div class="text-on-surface-variant px-md py-sm flex items-center gap-md cursor-pointer hover:bg-surface-container-highest transition-all duration-150">
<span class="material-symbols-outlined" data-icon="add_box">add_box</span>
<span class="font-label-sm text-label-sm">Blocks</span>
</div>
<div class="text-on-surface-variant px-md py-sm flex items-center gap-md cursor-pointer hover:bg-surface-container-highest transition-all duration-150">
<span class="material-symbols-outlined" data-icon="widgets">widgets</span>
<span class="font-label-sm text-label-sm">Symbols</span>
</div>
<div class="text-on-surface-variant px-md py-sm flex items-center gap-md cursor-pointer hover:bg-surface-container-highest transition-all duration-150">
<span class="material-symbols-outlined" data-icon="image">image</span>
<span class="font-label-sm text-label-sm">Assets</span>
</div>
</div>
<!-- Tree View Mockup -->
<div class="mt-xl px-md">
<div class="flex items-center justify-between mb-sm">
<span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Navigator</span>
<span class="material-symbols-outlined text-outline text-sm" data-icon="search">search</span>
</div>
<div class="flex flex-col gap-1">
<div class="flex items-center gap-sm py-1 px-2 hover:bg-surface-container-highest rounded cursor-pointer">
<span class="material-symbols-outlined text-xs" data-icon="arrow_drop_down">arrow_drop_down</span>
<span class="material-symbols-outlined text-sm text-royal-purple" data-icon="web">web</span>
<span class="font-mono-label text-mono-label">Body</span>
</div>
<div class="flex items-center gap-sm py-1 px-2 pl-6 hover:bg-surface-container-highest rounded cursor-pointer">
<span class="material-symbols-outlined text-xs" data-icon="arrow_drop_down">arrow_drop_down</span>
<span class="material-symbols-outlined text-sm text-electric-blue" data-icon="view_agenda">view_agenda</span>
<span class="font-mono-label text-mono-label">Section_Hero</span>
</div>
<div class="flex items-center gap-sm py-1 px-2 pl-10 bg-primary-container/20 border-l-2 border-electric-blue rounded-r cursor-pointer">
<span class="material-symbols-outlined text-sm text-electric-blue" data-icon="view_quilt">view_quilt</span>
<span class="font-mono-label text-mono-label text-on-surface">Container</span>
</div>
<div class="flex items-center gap-sm py-1 px-2 pl-14 hover:bg-surface-container-highest rounded cursor-pointer opacity-70">
<span class="material-symbols-outlined text-sm text-success-green" data-icon="title">title</span>
<span class="font-mono-label text-mono-label">Heading_H1</span>
</div>
</div>
</div>
</div>
<!-- Footer Section of SideNav -->
<div class="mt-auto border-t border-outline-variant p-md bg-surface-container-lowest/50">
<div class="flex flex-col gap-sm">
<div class="flex items-center gap-md text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer">
<span class="material-symbols-outlined" data-icon="help">help</span>
<span class="font-label-sm text-label-sm">Help</span>
</div>
<div class="flex items-center gap-md text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer">
<span class="material-symbols-outlined" data-icon="chat_bubble">chat_bubble</span>
<span class="font-label-sm text-label-sm">Feedback</span>
</div>
</div>
</div>
</aside>
<!-- Center Canvas Area -->
<section class="flex-1 ml-panel-width mr-panel-width bg-editor-bg p-xl flex items-center justify-center relative">
<div class="w-full max-w-[1200px] aspect-video bg-white rounded-lg shadow-2xl overflow-hidden relative group">
<!-- Preview Content -->
<div class="w-full h-full overflow-y-auto bg-white custom-scrollbar">
<div class="p-2xl flex flex-col items-center text-center gap-xl bg-slate-50 border-b border-slate-200">
<div class="w-24 h-24 bg-electric-blue rounded-2xl flex items-center justify-center text-white text-4xl font-bold shadow-lg">S</div>
<h1 class="text-5xl font-black text-slate-900 leading-tight">Elevate Your Design Workflow with StudioPro</h1>
<p class="text-xl text-slate-600 max-w-2xl">The ultimate pro-grade minimalist visual editor for modern SaaS products and creative agencies.</p>
<div class="flex gap-md mt-lg">
<button class="bg-electric-blue text-white px-xl py-lg rounded-xl font-bold text-lg shadow-xl shadow-electric-blue/30">Get Started</button>
<button class="bg-white border-2 border-slate-200 text-slate-900 px-xl py-lg rounded-xl font-bold text-lg">View Demo</button>
</div>
</div>
<div class="p-2xl grid grid-cols-3 gap-xl">
<div class="aspect-square bg-slate-200 rounded-2xl overflow-hidden border border-slate-300">
<img class="w-full h-full object-cover" data-alt="A macro shot of professional creative workspace tools including a minimalist aluminum keyboard and a sleek glass tablet, set against a backdrop of clean white surfaces and warm morning light coming through a window. The style is bright, professional, and optimistic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDnbQw1h5TXiCQQZYn6YHz8vPL-hWA33KNONjvYSLhXPqhB0ZNR-yffWfpch2KL74RgG4CrgkMSWnypYqRZC9X5QjSseytnYFpiufP2IbwvcjRdR03g2KjDs8Fn3PS16L3aWcaAgj5WKG4fcIvhs8Epbcil_PfuUOPuTM-ezm6pE6N3YYeMm8i5iDBtgP2cF_yPdBH_b6H9sCB6YHj3LaZqaIrnFF8wlRe194ZDM57TOMHO_liToobov4hUQEEkMRs_mxCNazzdKyL-"/>
</div>
<div class="aspect-square bg-slate-200 rounded-2xl overflow-hidden border border-slate-300">
<img class="w-full h-full object-cover" data-alt="A minimalist architectural photograph of a modern office building interior with sharp geometric lines and large glass panels reflecting a clear blue sky. The lighting is high-key and the color palette is dominated by whites, silvers, and deep electric blue accents. The mood is corporate, professional, and clean." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAk5MUT0uiR1saPKs5zbe7CQL2yXMHXnlsAlbTCxwb3YDlYH-TGY4UympP7cipoqXpxIKh0_ViycjPvePqLbe0MCju_gyw1EFWC3veWsESdSHUjZs1SJ7ZZtj-CTTfKgD8sig1AF5fwqGr9tTDEgHorLC9fCOnfXa-vRzlzekVSDmHP3nqqc5ECNDCqKKnENkcklAQjFQHZm4A96zHBEwD2XOJK_mBGOXmxUNWENLYABe8Bn78fNpLHsZ_9plB7M99s_wqWjIugfY6U"/>
</div>
<div class="aspect-square bg-slate-200 rounded-2xl overflow-hidden border border-slate-300">
<img class="w-full h-full object-cover" data-alt="An abstract 3D render of fluid, organic shapes cascading in a pristine white environment, with soft shadows and sharp highlights. The accent color is a vibrant electric blue, creating a sense of dynamic energy within a professional and clean design framework." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAFFI8NhwvSiFsQ0cA0GR4HXA5BIVoYweNFzRvbSzFdqVN5kA-vtN_6ZdcCQCr5i3E6OTItXw3oi-pbDt0GMOALTYzAIYwEl9jGE3MamZom-iNTwPahWMzNXTffecT-41YRw0U7Rv277fMFRLK-WyKXWLpLNYG0LPQiGqJd-CE3QMnKNmlMNU05rLmzIxXvU-cp3l47mz9IzSReEhCN9t3LRjcXPZHkaUPdXNNmpBgLrYsAiHoxBuB6YB99nDW811b1hKs6u1tDwfNk"/>
</div>
</div>
</div>
<!-- Active Selection Overlay (Simulated) -->
<div class="absolute top-2xl left-[50%] -translate-x-1/2 w-[80%] h-[320px] border-2 border-electric-blue pointer-events-none">
<div class="absolute -top-6 left-0 bg-electric-blue text-white px-sm py-0.5 text-[10px] font-bold uppercase rounded-t-sm">Container</div>
<div class="absolute -top-1.5 -left-1.5 w-3 h-3 bg-white border-2 border-electric-blue"></div>
<div class="absolute -top-1.5 -right-1.5 w-3 h-3 bg-white border-2 border-electric-blue"></div>
<div class="absolute -bottom-1.5 -left-1.5 w-3 h-3 bg-white border-2 border-electric-blue"></div>
<div class="absolute -bottom-1.5 -right-1.5 w-3 h-3 bg-white border-2 border-electric-blue"></div>
<div class="absolute top-1/2 -left-1.5 -translate-y-1/2 w-3 h-3 bg-white border-2 border-electric-blue"></div>
<div class="absolute top-1/2 -right-1.5 -translate-y-1/2 w-3 h-3 bg-white border-2 border-electric-blue"></div>
</div>
</div>
<!-- Canvas Zoom/Controls (Bottom Corner) -->
<div class="absolute bottom-md right-md flex items-center bg-panel-surface border border-outline-variant rounded-md px-sm py-1 gap-md">
<span class="font-mono-label text-mono-label text-outline">1200 x 800</span>
<div class="w-px h-3 bg-outline-variant"></div>
<span class="font-mono-label text-mono-label text-on-surface">Canvas Zoom 100%</span>
</div>
</section>
<!-- Right Sidebar: Properties Panel (Shared SideNavBar) -->
<aside class="flex flex-col h-full w-panel-width fixed right-0 top-toolbar-height bg-panel-surface border-l border-outline-variant z-40">
<div class="p-md border-b border-outline-variant flex items-center justify-between">
<div>
<span class="font-label-sm text-label-sm text-on-surface">Element Properties</span>
<p class="font-mono-label text-mono-label text-electric-blue">Selected: Container</p>
</div>
<span class="material-symbols-outlined text-outline" data-icon="more_vert">more_vert</span>
</div>
<!-- Property Tabs -->
<div class="flex border-b border-outline-variant">
<div class="flex-1 flex justify-center py-sm text-on-primary-container bg-primary-container rounded-sm transition-opacity duration-100 cursor-pointer">
<span class="material-symbols-outlined" data-icon="palette">palette</span>
</div>
<div class="flex-1 flex justify-center py-sm text-on-surface-variant hover:text-on-surface transition-opacity duration-100 cursor-pointer">
<span class="material-symbols-outlined" data-icon="bolt">bolt</span>
</div>
<div class="flex-1 flex justify-center py-sm text-on-surface-variant hover:text-on-surface transition-opacity duration-100 cursor-pointer">
<span class="material-symbols-outlined" data-icon="settings_applications">settings_applications</span>
</div>
<div class="flex-1 flex justify-center py-sm text-on-surface-variant hover:text-on-surface transition-opacity duration-100 cursor-pointer">
<span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
</div>
</div>
<div class="flex-1 custom-scrollbar overflow-y-auto p-md space-y-xl">
<!-- Spacing Control -->
<section>
<div class="flex items-center justify-between mb-md">
<span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Spacing</span>
<span class="material-symbols-outlined text-outline text-xs" data-icon="keyboard_arrow_down">keyboard_arrow_down</span>
</div>
<div class="p-sm bg-surface-container-lowest border border-outline-variant rounded relative">
<!-- Margin/Padding Box Simulator -->
<div class="grid grid-cols-5 grid-rows-5 gap-1 text-[9px] font-mono-label text-center items-center">
<div class="col-span-5 border border-dashed border-outline-variant p-1 text-outline">MARGIN</div>
<div class="border border-dashed border-outline-variant py-4 text-outline"></div>
<div class="col-span-3 border border-outline-variant bg-surface-container-high/40 p-1 flex flex-col gap-1">
<div class="text-[8px] text-electric-blue">PADDING</div>
<div class="flex justify-between px-xs">
<span class="text-on-surface">32</span>
<span class="text-on-surface">32</span>
</div>
<div class="flex justify-center gap-4">
<span class="text-on-surface">48</span>
<span class="text-on-surface">48</span>
</div>
</div>
<div class="border border-dashed border-outline-variant py-4 text-outline"></div>
<div class="col-span-5 border border-dashed border-outline-variant p-1 text-outline">0 AUTO</div>
</div>
</div>
</section>
<!-- Layout Control -->
<section>
<div class="flex items-center justify-between mb-md">
<span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Layout</span>
<span class="material-symbols-outlined text-outline text-xs" data-icon="keyboard_arrow_down">keyboard_arrow_down</span>
</div>
<div class="grid grid-cols-2 gap-sm">
<div class="col-span-2">
<label class="font-label-sm text-label-sm text-outline mb-1 block">Display</label>
<div class="flex bg-surface-container-lowest rounded overflow-hidden p-0.5">
<button class="flex-1 py-1 bg-surface-container-highest text-white rounded-sm font-label-sm text-label-sm">Flex</button>
<button class="flex-1 py-1 text-on-surface-variant font-label-sm text-label-sm">Grid</button>
<button class="flex-1 py-1 text-on-surface-variant font-label-sm text-label-sm">Block</button>
<button class="flex-1 py-1 text-on-surface-variant font-label-sm text-label-sm">None</button>
</div>
</div>
<div class="flex flex-col gap-1">
<label class="font-label-sm text-label-sm text-outline">Direction</label>
<div class="flex bg-surface-container-lowest rounded p-0.5 gap-1">
<span class="material-symbols-outlined p-1 text-on-surface bg-surface-container-high rounded-sm" data-icon="south">south</span>
<span class="material-symbols-outlined p-1 text-on-surface-variant" data-icon="east">east</span>
</div>
</div>
<div class="flex flex-col gap-1">
<label class="font-label-sm text-label-sm text-outline">Align</label>
<div class="flex bg-surface-container-lowest rounded p-0.5 gap-1">
<span class="material-symbols-outlined p-1 text-on-surface-variant" data-icon="align_items_stretch">align_items_stretch</span>
<span class="material-symbols-outlined p-1 text-on-surface bg-surface-container-high rounded-sm" data-icon="align_center">align_center</span>
</div>
</div>
</div>
</section>
<!-- Typography Control -->
<section>
<div class="flex items-center justify-between mb-md">
<span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Typography</span>
<span class="material-symbols-outlined text-outline text-xs" data-icon="keyboard_arrow_down">keyboard_arrow_down</span>
</div>
<div class="space-y-md">
<div>
<label class="font-label-sm text-label-sm text-outline mb-1 block">Font Family</label>
<div class="flex items-center justify-between bg-surface-container-lowest p-sm rounded border border-outline-variant">
<span class="font-body-sm text-body-sm">Geist Sans</span>
<span class="material-symbols-outlined text-xs" data-icon="unfold_more">unfold_more</span>
</div>
</div>
<div class="grid grid-cols-2 gap-sm">
<div>
<label class="font-label-sm text-label-sm text-outline mb-1 block">Weight</label>
<div class="bg-surface-container-lowest p-sm rounded border border-outline-variant font-body-sm text-body-sm">600 (Bold)</div>
</div>
<div>
<label class="font-label-sm text-label-sm text-outline mb-1 block">Size</label>
<div class="flex items-center bg-surface-container-lowest rounded border border-outline-variant">
<input class="w-full bg-transparent border-none text-right font-mono-label text-mono-label py-sm pl-sm focus:ring-0" type="text" value="32"/>
<span class="text-outline px-sm font-mono-label text-mono-label">PX</span>
</div>
</div>
</div>
<div class="grid grid-cols-4 gap-xs bg-surface-container-lowest p-0.5 rounded">
<button class="py-1 flex justify-center text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined" data-icon="format_align_left">format_align_left</span></button>
<button class="py-1 flex justify-center text-on-surface bg-surface-container-high rounded-sm"><span class="material-symbols-outlined" data-icon="format_align_center">format_align_center</span></button>
<button class="py-1 flex justify-center text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined" data-icon="format_align_right">format_align_right</span></button>
<button class="py-1 flex justify-center text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined" data-icon="format_align_justify">format_align_justify</span></button>
</div>
</div>
</section>
<!-- Style Manager Accordion Item (Small) -->
<div class="pt-xl opacity-40">
<div class="flex items-center justify-between mb-sm cursor-default">
<span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Effects</span>
<span class="material-symbols-outlined text-outline text-xs" data-icon="add">add</span>
</div>
</div>
</div>
</aside>
</main>
<!-- Footer (Shared Component) -->
<footer class="fixed bottom-0 left-0 w-full h-8 flex justify-between items-center px-md z-40 bg-surface-container-lowest dark:bg-surface-container-lowest border-t border-outline-variant docked full-width bottom-0">
<div class="flex items-center gap-lg">
<span class="font-mono-label text-mono-label text-on-surface">Body</span>
<span class="material-symbols-outlined text-outline text-xs" data-icon="chevron_right">chevron_right</span>
<span class="font-mono-label text-mono-label text-on-surface">Section_Hero</span>
<span class="material-symbols-outlined text-outline text-xs" data-icon="chevron_right">chevron_right</span>
<span class="font-mono-label text-mono-label text-on-surface">Container</span>
</div>
<div class="flex items-center gap-xl">
<div class="flex gap-md items-center">
<span class="font-mono-label text-mono-label text-outline hover:text-primary transition-colors cursor-default">Breadcrumbs</span>
<span class="font-mono-label text-mono-label text-on-surface hover:text-primary transition-colors cursor-default">Zoom 100%</span>
<span class="font-mono-label text-mono-label text-outline hover:text-primary transition-colors cursor-default">Canvas Help</span>
</div>
<span class="font-label-sm text-label-sm text-outline">© 2024 StudioPro Engine</span>
</div>
</footer>
<script>
        // Simple Interaction logic for demo
        document.querySelectorAll('.material-symbols-outlined').forEach(icon => {
            icon.addEventListener('mousedown', () => {
                icon.style.transform = 'scale(0.9)';
            });
            icon.addEventListener('mouseup', () => {
                icon.style.transform = 'scale(1)';
            });
        });

        // Tab logic simulation
        const tabs = document.querySelectorAll('[data-tab]');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('text-on-primary-container', 'bg-primary-container'));
                tab.classList.add('text-on-primary-container', 'bg-primary-container');
            });
        });
    </script>
</body></html>

<!-- Publish & Version History -->
<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400&amp;family=Geist:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 20;
        }
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        ::-webkit-scrollbar-track {
            background: #0B0E14;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #2E62FF;
        }
        .canvas-shadow {
            box-shadow: 0 0 40px rgba(0,0,0,0.5);
        }
        .glass-panel {
            backdrop-filter: blur(8px);
            background: rgba(22, 27, 34, 0.8);
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "panel-surface": "#161B22",
                        "electric-blue": "#2E62FF",
                        "editor-bg": "#0B0E14",
                        "canvas-bg": "#1F2937",
                        "on-surface": "#dae2fd",
                        "on-surface-variant": "#c3c5d8",
                        "outline-variant": "#434656",
                        "primary-container": "#2e62ff",
                        "on-primary-container": "#f7f6ff",
                        "surface-container-highest": "#2d3449",
                        "surface-container-lowest": "#060e20",
                        "outline": "#8d90a2"
                    },
                    "spacing": {
                        "sm": "8px",
                        "md": "12px",
                        "lg": "16px",
                        "xl": "24px",
                        "toolbar-height": "48px",
                        "panel-width": "280px",
                        "base": "4px"
                    },
                    "fontFamily": {
                        "body-sm": ["Geist"],
                        "label-sm": ["Geist"],
                        "label-md": ["Geist"],
                        "headline-sm": ["Geist"],
                        "headline-md": ["Geist"],
                        "mono-label": ["JetBrains Mono"]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-editor-bg text-on-surface font-body-sm selection:bg-electric-blue/30 overflow-hidden h-screen flex flex-col">
<!-- TopNavBar -->
<nav class="flex justify-between items-center h-toolbar-height px-md w-full bg-panel-surface border-b border-outline-variant fixed top-0 z-50">
<div class="flex items-center gap-xl">
<span class="font-headline-md text-headline-md font-bold text-on-surface">StudioPro</span>
<div class="flex items-center gap-lg ml-md">
<a class="text-on-surface-variant font-medium hover:bg-surface-container-highest transition-colors cursor-pointer active:scale-95 px-2 py-1 rounded" href="#">Pages</a>
<a class="text-on-surface-variant font-medium hover:bg-surface-container-highest transition-colors cursor-pointer active:scale-95 px-2 py-1 rounded" href="#">Assets</a>
<a class="text-electric-blue font-bold border-b-2 border-electric-blue pb-1 cursor-pointer" href="#">Settings</a>
</div>
</div>
<div class="flex items-center gap-md">
<div class="flex items-center bg-editor-bg rounded-lg p-1 mr-4 border border-outline-variant">
<button class="px-3 py-1 text-label-sm font-label-sm bg-surface-container-highest text-on-surface rounded-sm transition-all duration-150">Editor</button>
<button class="px-3 py-1 text-label-sm font-label-sm text-on-surface-variant hover:text-on-surface transition-all duration-150">Comparison</button>
</div>
<div class="flex items-center gap-sm mr-4">
<span class="material-symbols-outlined text-on-surface-variant cursor-pointer p-1.5 hover:bg-surface-container-highest rounded transition-colors" data-icon="desktop_windows">desktop_windows</span>
<span class="material-symbols-outlined text-on-surface-variant cursor-pointer p-1.5 hover:bg-surface-container-highest rounded transition-colors" data-icon="tablet_mac">tablet_mac</span>
<span class="material-symbols-outlined text-on-surface-variant cursor-pointer p-1.5 hover:bg-surface-container-highest rounded transition-colors" data-icon="smartphone">smartphone</span>
</div>
<button class="bg-electric-blue text-white px-xl py-2 rounded-lg font-label-md text-label-md font-bold transition-all hover:brightness-110 active:scale-95">Publish</button>
</div>
</nav>
<!-- Main Content Area -->
<main class="flex flex-1 pt-toolbar-height overflow-hidden">
<!-- Left SideNavBar (Navigator/Layers) -->
<aside class="flex flex-col h-full w-panel-width fixed left-0 top-toolbar-height bg-panel-surface border-r border-outline-variant z-40">
<div class="p-md flex items-center justify-between border-b border-outline-variant/30">
<div>
<p class="font-label-md text-label-md uppercase tracking-widest text-on-surface-variant">Project Alpha</p>
<p class="font-body-sm text-body-sm text-outline">V1.0.4</p>
</div>
<div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center border border-outline-variant">
<span class="material-symbols-outlined text-body-sm" data-icon="person">person</span>
</div>
</div>
<nav class="flex-1 overflow-y-auto py-sm">
<div class="px-sm mb-sm text-outline font-label-sm uppercase tracking-tighter opacity-50">Navigation</div>
<div class="flex flex-col gap-1">
<button class="flex items-center gap-md px-md py-sm hover:bg-surface-container-highest transition-all duration-150 text-on-surface-variant font-label-sm">
<span class="material-symbols-outlined text-lg" data-icon="layers">layers</span>
<span>Layers</span>
</button>
<button class="flex items-center gap-md px-md py-sm hover:bg-surface-container-highest transition-all duration-150 text-on-surface-variant font-label-sm">
<span class="material-symbols-outlined text-lg" data-icon="add_box">add_box</span>
<span>Blocks</span>
</button>
<button class="flex items-center gap-md px-md py-sm hover:bg-surface-container-highest transition-all duration-150 text-on-surface-variant font-label-sm">
<span class="material-symbols-outlined text-lg" data-icon="widgets">widgets</span>
<span>Symbols</span>
</button>
<button class="flex items-center gap-md px-md py-sm hover:bg-surface-container-highest transition-all duration-150 text-on-surface-variant font-label-sm">
<span class="material-symbols-outlined text-lg" data-icon="image">image</span>
<span>Assets</span>
</button>
<!-- Active State for Version History Settings -->
<button class="flex items-center gap-md px-md py-sm bg-primary-container/15 text-electric-blue border-l-2 border-electric-blue transition-all duration-150 font-label-sm">
<span class="material-symbols-outlined text-lg" data-icon="history">history</span>
<span>Version History</span>
</button>
</div>
</nav>
<div class="p-md border-t border-outline-variant/30">
<button class="w-full py-2 bg-surface-container-highest text-on-surface rounded font-label-sm hover:bg-outline-variant transition-colors mb-sm">Upgrade Plan</button>
<div class="flex items-center justify-around text-outline">
<span class="material-symbols-outlined cursor-pointer hover:text-on-surface" data-icon="help">help</span>
<span class="material-symbols-outlined cursor-pointer hover:text-on-surface" data-icon="chat_bubble">chat_bubble</span>
</div>
</div>
</aside>
<!-- Central Canvas Area (Version History List) -->
<section class="flex-1 ml-panel-width mr-panel-width bg-editor-bg p-xl overflow-y-auto">
<header class="mb-xl flex items-end justify-between border-b border-outline-variant/30 pb-lg">
<div>
<h1 class="font-headline-lg text-headline-lg mb-base">Version History</h1>
<p class="text-on-surface-variant font-body-md max-w-lg">Track all changes, manage project milestones, and restore previous states from any point in your creative process.</p>
</div>
<div class="flex items-center gap-md">
<div class="flex items-center gap-sm px-md py-2 bg-panel-surface border border-outline-variant rounded-lg">
<span class="material-symbols-outlined text-lg text-on-surface-variant" data-icon="search">search</span>
<input class="bg-transparent border-none text-label-md font-label-md focus:ring-0 p-0 text-on-surface placeholder:text-outline w-40" placeholder="Search versions..." type="text"/>
</div>
</div>
</header>
<!-- Version List Grid -->
<div class="space-y-4">
<!-- Current Live Version -->
<div class="group bg-panel-surface border border-electric-blue/40 rounded-xl p-lg relative overflow-hidden transition-all hover:border-electric-blue">
<div class="absolute top-0 right-0 p-lg">
<span class="bg-success-green/20 text-success-green px-3 py-1 rounded-full text-label-sm font-label-sm border border-success-green/30 flex items-center gap-1">
<span class="w-1.5 h-1.5 bg-success-green rounded-full animate-pulse"></span>
                            Live Now
                        </span>
</div>
<div class="flex items-start gap-xl">
<div class="w-48 h-32 rounded-lg bg-editor-bg border border-outline-variant/50 flex-shrink-0 relative group-hover:scale-[1.02] transition-transform duration-300">
<div class="w-full h-full bg-cover bg-center rounded-lg opacity-70" data-alt="A professional high-fidelity UI layout for a SaaS landing page, featuring sleek dark mode aesthetics, clean typography, and vibrant electric blue accents. The design is displayed as a sharp thumbnail with subtle grid lines in the background, conveying a sense of order and precision in digital production." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDE_AvyfwgFoJObzB7jV3Cp8lOcEWwHXVM_wk6lXL6LWJUM1gqrtRCkOM2h4n-8-83AKIdZxdEkmqcGKjNel7ZmITCZgZrEf125ZLUe42A4yIEQhy9hhKE06hHSJZ3HF58YXxMbRhbkRPmxcEmwAkmwhDcvij5CyDe3hce_l9hcAALdPcHf028ta0WnUXetGGJ3euit0-xTdZFT405H8vEFdy7X3IX4TqKS5DcOWXoWX6UxQ2WbGe40_mzOTnXkp9T5IuYw0O-u7WaJ')"></div>
<div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-editor-bg/60 rounded-lg">
<span class="material-symbols-outlined text-on-surface" data-icon="visibility">visibility</span>
</div>
</div>
<div class="flex-1 flex flex-col justify-between h-32 py-1">
<div>
<div class="flex items-center gap-md mb-base">
<h3 class="font-headline-sm text-headline-sm">Marketing v2.0 - Final Release</h3>
<span class="font-mono-label text-mono-label bg-outline-variant/30 px-2 py-0.5 rounded text-outline uppercase">PROD-A42</span>
</div>
<div class="flex items-center gap-xl text-on-surface-variant">
<div class="flex items-center gap-base">
<span class="material-symbols-outlined text-sm" data-icon="schedule">schedule</span>
<span class="font-body-sm">Published 2 hours ago</span>
</div>
<div class="flex items-center gap-base">
<span class="material-symbols-outlined text-sm" data-icon="account_circle">account_circle</span>
<span class="font-body-sm">Alex Rivera</span>
</div>
<div class="flex items-center gap-base">
<span class="material-symbols-outlined text-sm" data-icon="edit">edit</span>
<span class="font-body-sm">48 changes recorded</span>
</div>
</div>
</div>
<div class="flex gap-md">
<button class="bg-surface-container-highest text-on-surface px-lg py-2 rounded-lg text-label-md font-label-md hover:bg-outline-variant transition-colors">Compare with Draft</button>
<button class="text-on-surface-variant hover:text-on-surface px-lg py-2 rounded-lg text-label-md font-label-md flex items-center gap-2">
<span class="material-symbols-outlined text-lg" data-icon="more_horiz">more_horiz</span>
</button>
</div>
</div>
</div>
</div>
<!-- Draft Version -->
<div class="group bg-panel-surface/50 border border-outline-variant rounded-xl p-lg relative overflow-hidden transition-all hover:bg-panel-surface">
<div class="flex items-start gap-xl">
<div class="w-48 h-32 rounded-lg bg-editor-bg border border-outline-variant/50 flex-shrink-0 relative overflow-hidden group-hover:scale-[1.02] transition-transform duration-300">
<div class="w-full h-full bg-cover bg-center rounded-lg opacity-40 grayscale" data-alt="A detailed design prototype of a complex web application interface shown in a dark theme. The layout is structured with asymmetric panels and a focus on geometric patterns. The lighting is low-key, emphasizing the deep grays and sharp white text labels. Soft glows from UI buttons provide a sense of depth and interaction." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBkaUNrH5tIHJ_Yzomegx4D0ZvEJGl2DjQib2TvfDmo9l8ESDDugLIPfI8gzlYl0qKFPVacwYhab6f2rB90SlvwYOy7HsrRdt2bpMHsLg35Hw4ELvU0BKc3UJZljP9fjIZ_ljXPwT2vaoy91wSZddhRDJnCr3xNK7kLDL-8huJOQviSL4hZU2czfqqEGnxVM7X2EOZkqZU_OlWMREO-N-cvaag0k41Mom7ij0TlgxboNt-mm6dkz1REdHW9QUSc6VChfIeQ-D25Q6m6')"></div>
<div class="absolute inset-0 bg-editor-bg/40"></div>
</div>
<div class="flex-1 flex flex-col justify-between h-32 py-1">
<div>
<div class="flex items-center gap-md mb-base">
<h3 class="font-headline-sm text-headline-sm">Hero Section Refinement</h3>
<span class="font-mono-label text-mono-label bg-outline-variant/30 px-2 py-0.5 rounded text-outline uppercase">DEV-F12</span>
</div>
<div class="flex items-center gap-xl text-on-surface-variant">
<div class="flex items-center gap-base">
<span class="material-symbols-outlined text-sm" data-icon="history">history</span>
<span class="font-body-sm">Auto-saved 15 mins ago</span>
</div>
<div class="flex items-center gap-base">
<span class="material-symbols-outlined text-sm" data-icon="face">face</span>
<span class="font-body-sm">You (Current Draft)</span>
</div>
</div>
</div>
<div class="flex gap-md">
<button class="bg-electric-blue/10 text-electric-blue border border-electric-blue/30 px-lg py-2 rounded-lg text-label-md font-label-md hover:bg-electric-blue hover:text-white transition-all">Restore Version</button>
<button class="bg-surface-container-highest text-on-surface px-lg py-2 rounded-lg text-label-md font-label-md hover:bg-outline-variant transition-colors">Preview Changes</button>
</div>
</div>
</div>
</div>
<!-- Historical Version 1 -->
<div class="group bg-panel-surface/30 border border-outline-variant/50 rounded-xl p-lg relative overflow-hidden transition-all hover:bg-panel-surface/60">
<div class="flex items-start gap-xl opacity-80 group-hover:opacity-100 transition-opacity">
<div class="w-48 h-32 rounded-lg bg-editor-bg border border-outline-variant/30 flex-shrink-0 relative overflow-hidden group-hover:scale-[1.02] transition-transform duration-300">
<div class="w-full h-full bg-cover bg-center rounded-lg opacity-30 blur-[1px]" data-alt="A minimalist architectural blueprint visualization rendered in a futuristic design tool aesthetic. The scene uses thin, precise lines in shades of slate and charcoal against a pitch-black background. Glowing cyan highlights emphasize specific structural elements. The atmosphere is technical, sophisticated, and deeply focused on engineering details." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDnZp_awGhli7VkvvvY7yM15UPt7EakHzwAiGUwanqYy_F6TjS5NcBLexf4C17QXjKTqzZE_h7polo5JVbEB-WJA_RnSFBJjAoyAdRAC44LuJMhHosZ75nIJ0nILwKOhHj_1Fu15m0VHKlOg0YfUESLeE5pFqa6qw9Za8OZIXcJWQgvtPnsaF9vrJRpNtdtR2yq6thtbiCLwBm1NQktYhSuxR2OaQ0JoEFG6um55E8q3qDuvDy3WVUTaqr6o9QnYztLRFzw7yOtQdQW')"></div>
</div>
<div class="flex-1 flex flex-col justify-between h-32 py-1">
<div>
<div class="flex items-center gap-md mb-base">
<h3 class="font-headline-sm text-headline-sm">Initial Structure Overhaul</h3>
<span class="font-mono-label text-mono-label bg-outline-variant/10 px-2 py-0.5 rounded text-outline uppercase">ARCH-009</span>
</div>
<div class="flex items-center gap-xl text-on-surface-variant">
<div class="flex items-center gap-base">
<span class="material-symbols-outlined text-sm" data-icon="calendar_today">calendar_today</span>
<span class="font-body-sm">Oct 24, 2024 • 10:30 AM</span>
</div>
<div class="flex items-center gap-base">
<span class="material-symbols-outlined text-sm" data-icon="account_circle">account_circle</span>
<span class="font-body-sm">Sarah Chen</span>
</div>
</div>
</div>
<div class="flex gap-md">
<button class="bg-surface-container-highest/50 text-outline px-lg py-2 rounded-lg text-label-md font-label-md hover:bg-electric-blue/20 hover:text-electric-blue border border-transparent hover:border-electric-blue/30 transition-all">Restore Version</button>
</div>
</div>
</div>
</div>
<!-- Historical Version 2 -->
<div class="group bg-panel-surface/30 border border-outline-variant/50 rounded-xl p-lg relative overflow-hidden transition-all hover:bg-panel-surface/60">
<div class="flex items-start gap-xl opacity-80 group-hover:opacity-100 transition-opacity">
<div class="w-48 h-32 rounded-lg bg-editor-bg border border-outline-variant/30 flex-shrink-0 relative overflow-hidden group-hover:scale-[1.02] transition-transform duration-300">
<div class="w-full h-full bg-cover bg-center rounded-lg opacity-30 blur-[1px]" data-alt="A workspace setup for a creative designer, focusing on a high-resolution display showing a colorful geometric branding project. The room is dimly lit with ambient purple and blue LEDs reflecting off professional studio hardware. The mood is creative and nocturnal, emphasizing a pro-grade creative workflow environment with meticulous attention to color and shape." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDLPwn5Rcw_nzlkhS4r6RyRRuJ5bbTeas8yKPZdU-wQJBA_69u12FqKPsb4OdPjfjkCk8zuOMLVYE_B1gTYCKZZi-JR8zbOe5psxfrHatfGCaQg2tum7-vt9aiG8B6-epRXsF_QdCVQU1NLCx-YRoxlmX7Xt9QdaVHiC2_EoZ1qpN-0koh9-duAG9kuLV5ue-Kr7RZ3dcsbtETbZQkQdmk-mOAJTtJCzLNbq4GoIW0hH-SQI3rv-INkL80r1xMcOvBVx-nd9pdh-s2z')"></div>
</div>
<div class="flex-1 flex flex-col justify-between h-32 py-1">
<div>
<div class="flex items-center gap-md mb-base">
<h3 class="font-headline-sm text-headline-sm">Branding Concept v1</h3>
<span class="font-mono-label text-mono-label bg-outline-variant/10 px-2 py-0.5 rounded text-outline uppercase">BRAND-X</span>
</div>
<div class="flex items-center gap-xl text-on-surface-variant">
<div class="flex items-center gap-base">
<span class="material-symbols-outlined text-sm" data-icon="calendar_today">calendar_today</span>
<span class="font-body-sm">Oct 22, 2024 • 04:15 PM</span>
</div>
<div class="flex items-center gap-base">
<span class="material-symbols-outlined text-sm" data-icon="account_circle">account_circle</span>
<span class="font-body-sm">Alex Rivera</span>
</div>
</div>
</div>
<div class="flex gap-md">
<button class="bg-surface-container-highest/50 text-outline px-lg py-2 rounded-lg text-label-md font-label-md hover:bg-electric-blue/20 hover:text-electric-blue border border-transparent hover:border-electric-blue/30 transition-all">Restore Version</button>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Right SideNavBar (Properties/Audit) -->
<aside class="flex flex-col h-full w-panel-width fixed right-0 top-toolbar-height bg-panel-surface border-l border-outline-variant z-40">
<div class="p-md flex items-center justify-between border-b border-outline-variant/30">
<span class="font-label-sm text-label-sm text-on-surface">Publishing Audit</span>
<span class="material-symbols-outlined text-on-surface-variant" data-icon="info">info</span>
</div>
<nav class="flex border-b border-outline-variant/30">
<button class="flex-1 py-3 text-label-sm font-label-sm text-on-primary-container bg-primary-container rounded-sm">Audit</button>
<button class="flex-1 py-3 text-label-sm font-label-sm text-on-surface-variant hover:text-on-surface transition-all duration-100">Interactions</button>
<button class="flex-1 py-3 text-label-sm font-label-sm text-on-surface-variant hover:text-on-surface transition-all duration-100">Config</button>
</nav>
<div class="flex-1 overflow-y-auto p-md space-y-lg">
<div class="space-y-sm">
<p class="font-label-sm text-label-sm uppercase tracking-wider text-outline opacity-70">Readiness Check</p>
<div class="space-y-2">
<div class="flex items-center justify-between p-sm bg-editor-bg rounded border border-outline-variant/30">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-success-green text-lg" data-icon="check_circle">check_circle</span>
<span class="text-body-sm">All Assets Optimized</span>
</div>
<span class="font-mono-label text-mono-label text-outline">100%</span>
</div>
<div class="flex items-center justify-between p-sm bg-editor-bg rounded border border-outline-variant/30">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-warning-amber text-lg" data-icon="warning">warning</span>
<span class="text-body-sm">SEO Meta Missing</span>
</div>
<span class="font-mono-label text-mono-label text-warning-amber">High</span>
</div>
<div class="flex items-center justify-between p-sm bg-editor-bg rounded border border-outline-variant/30">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-success-green text-lg" data-icon="check_circle">check_circle</span>
<span class="text-body-sm">Forms Connected</span>
</div>
<span class="material-symbols-outlined text-sm text-outline" data-icon="link">link</span>
</div>
</div>
</div>
<div class="space-y-sm">
<p class="font-label-sm text-label-sm uppercase tracking-wider text-outline opacity-70">Domain Status</p>
<div class="p-md bg-editor-bg rounded border border-outline-variant/30 space-y-md">
<div class="flex justify-between items-center">
<p class="font-body-sm text-on-surface">studiopro.design</p>
<span class="bg-success-green/10 text-success-green text-[10px] px-2 py-0.5 rounded font-bold uppercase">Active</span>
</div>
<p class="text-mono-label font-mono-label text-outline leading-tight">IP: 192.158.1.38<br/>SSL: Valid (Auto-renew)</p>
</div>
</div>
<div class="space-y-sm">
<p class="font-label-sm text-label-sm uppercase tracking-wider text-outline opacity-70">Project Stats</p>
<div class="grid grid-cols-2 gap-2">
<div class="p-sm bg-editor-bg rounded border border-outline-variant/30 text-center">
<p class="text-outline text-[10px] uppercase">Versions</p>
<p class="font-headline-sm text-headline-sm">128</p>
</div>
<div class="p-sm bg-editor-bg rounded border border-outline-variant/30 text-center">
<p class="text-outline text-[10px] uppercase">Contributors</p>
<p class="font-headline-sm text-headline-sm">04</p>
</div>
</div>
</div>
</div>
<div class="p-md bg-surface-container-highest/20 border-t border-outline-variant/30">
<p class="font-label-sm text-label-sm text-on-surface mb-sm">Unpublished Changes</p>
<div class="flex items-center gap-2 mb-md">
<div class="h-1 flex-1 bg-outline-variant/30 rounded-full overflow-hidden">
<div class="h-full bg-electric-blue w-2/3"></div>
</div>
<span class="font-mono-label text-mono-label text-outline">12 New</span>
</div>
<button class="w-full py-2 bg-electric-blue text-white rounded font-label-md hover:brightness-110 active:scale-95 transition-all">Publish Now</button>
</div>
</aside>
</main>
<!-- Footer -->
<footer class="fixed bottom-0 left-0 w-full h-8 flex justify-between items-center px-md z-50 bg-surface-container-lowest border-t border-outline-variant text-on-surface-variant font-mono-label text-mono-label">
<div class="flex items-center gap-xl">
<span class="text-outline">© 2024 StudioPro Engine</span>
<div class="flex items-center gap-md">
<span class="material-symbols-outlined text-sm text-success-green" data-icon="cloud_done">cloud_done</span>
<span>System Online</span>
</div>
</div>
<div class="flex items-center gap-lg">
<button class="hover:text-electric-blue transition-colors cursor-default">Breadcrumbs</button>
<span class="text-outline-variant">/</span>
<button class="hover:text-electric-blue transition-colors cursor-default">Settings</button>
<span class="text-outline-variant">/</span>
<button class="text-on-surface">Version History</button>
</div>
<div class="flex items-center gap-xl">
<button class="hover:text-electric-blue transition-colors cursor-default">Zoom 100%</button>
<button class="hover:text-electric-blue transition-colors cursor-default">Canvas Help</button>
</div>
</footer>
<script>
        // Simple Interaction logic
        document.querySelectorAll('.material-symbols-outlined').forEach(icon => {
            icon.addEventListener('click', function() {
                this.classList.add('scale-125');
                setTimeout(() => this.classList.remove('scale-125'), 150);
            });
        });
    </script>
</body></html>

<!-- Project Dashboard -->
<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>StudioPro | Project Dashboard</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400&amp;family=Geist:wght@100..900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 20;
        }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #0B0E14;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #2E62FF;
        }
        .canvas-grid {
            background-image: radial-gradient(#334155 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .project-card:hover .project-overlay {
            opacity: 1;
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container": "#171f33",
                        "error": "#ffb4ab",
                        "on-error": "#690005",
                        "surface-container-lowest": "#060e20",
                        "success-green": "#10B981",
                        "royal-purple": "#8B5CF6",
                        "electric-blue": "#2E62FF",
                        "outline-variant": "#334155",
                        "primary-container": "#2e62ff",
                        "on-primary-container": "#f7f6ff",
                        "on-surface": "#dae2fd",
                        "on-surface-variant": "#c3c5d8",
                        "panel-surface": "#161B22",
                        "editor-bg": "#0B0E14",
                        "canvas-bg": "#1F2937",
                        "outline": "#8d90a2"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "toolbar-height": "48px",
                        "panel-width": "280px",
                        "md": "12px",
                        "lg": "16px",
                        "sm": "8px",
                        "xs": "4px"
                    },
                    "fontFamily": {
                        "headline-md": ["Geist"],
                        "label-md": ["Geist"],
                        "headline-sm": ["Geist"],
                        "mono-label": ["JetBrains Mono"],
                        "body-sm": ["Geist"],
                        "label-sm": ["Geist"]
                    },
                    "fontSize": {
                        "headline-md": ["24px", {"lineHeight": "1.3", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "label-md": ["12px", {"lineHeight": "1", "letterSpacing": "0.02em", "fontWeight": "500"}],
                        "headline-sm": ["18px", {"lineHeight": "1.4", "fontWeight": "500"}],
                        "mono-label": ["11px", {"lineHeight": "1", "fontWeight": "400"}],
                        "body-sm": ["13px", {"lineHeight": "1.5", "fontWeight": "400"}],
                        "label-sm": ["11px", {"lineHeight": "1", "letterSpacing": "0.05em", "fontWeight": "600"}]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-editor-bg text-on-surface font-body-sm overflow-hidden h-screen flex flex-col">
<!-- TopNavBar -->
<header class="bg-panel-surface border-b border-outline-variant flex justify-between items-center h-toolbar-height px-md w-full fixed top-0 z-50">
<div class="flex items-center gap-lg">
<span class="font-headline-md text-headline-md font-bold text-on-surface">StudioPro</span>
<nav class="hidden md:flex items-center gap-md ml-lg">
<a class="text-electric-blue font-bold border-b-2 border-electric-blue pb-1 font-label-sm text-label-sm transition-colors cursor-pointer" href="#">Pages</a>
<a class="text-on-surface-variant font-medium hover:bg-surface-container-highest transition-colors font-label-sm text-label-sm px-xs py-1 cursor-pointer" href="#">Assets</a>
<a class="text-on-surface-variant font-medium hover:bg-surface-container-highest transition-colors font-label-sm text-label-sm px-xs py-1 cursor-pointer" href="#">Settings</a>
</nav>
</div>
<div class="flex items-center gap-md">
<div class="flex items-center gap-xs mr-md border-r border-outline-variant pr-md">
<button class="material-symbols-outlined p-1 text-on-surface-variant hover:bg-surface-container-highest transition-colors rounded">desktop_windows</button>
<button class="material-symbols-outlined p-1 text-on-surface-variant hover:bg-surface-container-highest transition-colors rounded">tablet_mac</button>
<button class="material-symbols-outlined p-1 text-on-surface-variant hover:bg-surface-container-highest transition-colors rounded">smartphone</button>
</div>
<button class="bg-electric-blue text-on-primary-container px-lg py-sm font-label-sm text-label-sm rounded hover:brightness-110 active:scale-95 transition-all">Publish</button>
</div>
</header>
<div class="flex flex-1 pt-toolbar-height pb-8 overflow-hidden">
<!-- SideNavBar (Left) -->
<aside class="flex flex-col h-full w-panel-width fixed left-0 top-toolbar-height bg-panel-surface border-r border-outline-variant z-40">
<div class="p-lg border-b border-outline-variant">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full bg-electric-blue flex items-center justify-center">
<span class="material-symbols-outlined text-white text-[20px]">user_attributes</span>
</div>
<div>
<div class="font-label-md text-label-md uppercase tracking-widest text-on-surface-variant">Project Alpha</div>
<div class="font-body-sm text-body-sm text-outline opacity-70">V1.0.4</div>
</div>
</div>
</div>
<nav class="flex-1 py-md overflow-y-auto">
<div class="px-md mb-xs">
<button class="w-full flex items-center gap-md px-md py-sm bg-primary-container/15 text-electric-blue border-l-2 border-electric-blue font-label-sm text-label-sm transition-all duration-150">
<span class="material-symbols-outlined">layers</span>
<span>Layers</span>
</button>
</div>
<div class="px-md mb-xs">
<button class="w-full flex items-center gap-md px-md py-sm text-on-surface-variant hover:bg-surface-container-highest font-label-sm text-label-sm transition-all duration-150">
<span class="material-symbols-outlined">add_box</span>
<span>Blocks</span>
</button>
</div>
<div class="px-md mb-xs">
<button class="w-full flex items-center gap-md px-md py-sm text-on-surface-variant hover:bg-surface-container-highest font-label-sm text-label-sm transition-all duration-150">
<span class="material-symbols-outlined">widgets</span>
<span>Symbols</span>
</button>
</div>
<div class="px-md mb-xs">
<button class="w-full flex items-center gap-md px-md py-sm text-on-surface-variant hover:bg-surface-container-highest font-label-sm text-label-sm transition-all duration-150">
<span class="material-symbols-outlined">image</span>
<span>Assets</span>
</button>
</div>
<div class="px-md mb-xs">
<button class="w-full flex items-center gap-md px-md py-sm text-on-surface-variant hover:bg-surface-container-highest font-label-sm text-label-sm transition-all duration-150">
<span class="material-symbols-outlined">settings</span>
<span>Config</span>
</button>
</div>
<div class="mt-lg px-md pt-lg border-t border-outline-variant">
<button class="w-full bg-surface-container-highest text-on-surface border border-outline-variant/50 py-sm px-md rounded flex items-center justify-center gap-sm font-label-sm text-label-sm hover:bg-surface-bright transition-colors">
<span class="material-symbols-outlined text-[18px]">upgrade</span>
                        Upgrade Plan
                    </button>
</div>
</nav>
<div class="p-md flex flex-col gap-xs border-t border-outline-variant">
<button class="flex items-center gap-md px-md py-sm text-on-surface-variant hover:bg-surface-container-highest transition-colors font-label-sm text-label-sm">
<span class="material-symbols-outlined">help</span>
                    Help
                </button>
<button class="flex items-center gap-md px-md py-sm text-on-surface-variant hover:bg-surface-container-highest transition-colors font-label-sm text-label-sm">
<span class="material-symbols-outlined">chat_bubble</span>
                    Feedback
                </button>
</div>
</aside>
<!-- Main Content (Canvas Area) -->
<main class="ml-[280px] mr-[280px] flex-1 bg-editor-bg canvas-grid overflow-y-auto p-2xl">
<div class="max-w-6xl mx-auto">
<header class="flex justify-between items-end mb-3xl">
<div>
<h1 class="font-headline-lg text-headline-lg mb-xs">Project Dashboard</h1>
<p class="text-on-surface-variant font-body-md">Manage your ongoing designs and team collaborations.</p>
</div>
<button class="bg-electric-blue text-white px-xl py-md rounded-lg flex items-center gap-md font-label-md text-label-md hover:shadow-[0_0_20px_rgba(46,98,255,0.4)] transition-all active:scale-95">
<span class="material-symbols-outlined">add_circle</span>
                        Create New Project
                    </button>
</header>
<!-- Project Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-xl">
<!-- Project Card 1 -->
<div class="project-card group bg-panel-surface border border-outline-variant rounded-xl overflow-hidden cursor-pointer hover:border-electric-blue transition-colors flex flex-col">
<div class="relative h-48 bg-canvas-bg overflow-hidden">
<div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-105" data-alt="A clean and modern website landing page design for a high-tech startup. The layout features dark blue gradients, sharp geometric shapes, and vibrant electric blue highlights. Professional white typography stands out against a charcoal grey background, showcasing a pro-grade minimalist design aesthetic suitable for a design tool dashboard preview." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCS02110WFWO7GWHTfQNkQiRPSCSQ-iqew_WeTGryaJpLMkO9RcWgYZW7mxDUOtA-RqaDmWtIiuX2-88Q3-_UWS0SZB30UfdRP0Rf1ilaTS5V1bCJ-uGUQ1Y7WRhiWTXe20Ye6Br0Pw3bT-162WXNy_kFxChAHTx7dNBghvX9ZvDmPkBjOlKlZNwbbmV3kVXflkv-wBV_PMIDEuLMnK_m3F4cXmnKRWLo3AhGEQXiDIzXd2v6_VLLf0pD4vap1Ed6XTpSntpRAsY1FC')">
</div>
<div class="project-overlay absolute inset-0 bg-editor-bg/40 opacity-0 transition-opacity flex items-center justify-center backdrop-blur-sm">
<button class="bg-white text-black px-lg py-sm rounded-full font-label-sm text-label-sm flex items-center gap-xs">
<span class="material-symbols-outlined text-[16px]">edit</span>
                                    Open Editor
                                </button>
</div>
<div class="absolute top-md left-md">
<span class="bg-success-green/20 text-success-green px-sm py-xs rounded font-mono-label text-mono-label uppercase tracking-wider backdrop-blur-md border border-success-green/30">Published</span>
</div>
</div>
<div class="p-lg">
<h3 class="font-headline-sm text-headline-sm mb-base text-on-surface">Modern SaaS Landing</h3>
<div class="flex justify-between items-center text-outline font-mono-label text-mono-label">
<span>Last edited: 2h ago</span>
<div class="flex items-center -space-x-2">
<div class="w-6 h-6 rounded-full border-2 border-panel-surface bg-surface-container flex items-center justify-center text-[10px] font-bold">JD</div>
<div class="w-6 h-6 rounded-full border-2 border-panel-surface bg-electric-blue flex items-center justify-center text-[10px] font-bold">SP</div>
</div>
</div>
</div>
</div>
<!-- Project Card 2 -->
<div class="project-card group bg-panel-surface border border-outline-variant rounded-xl overflow-hidden cursor-pointer hover:border-electric-blue transition-colors flex flex-col">
<div class="relative h-48 bg-canvas-bg overflow-hidden">
<div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-105" data-alt="A sophisticated mobile app dashboard interface design showing data analytics and colorful charts. The interface uses a dark mode palette with neon purple and deep cyan accents. The cards are arranged in an asymmetrical bento grid style, emphasizing a technical and expert workflow vibe within the StudioPro design ecosystem." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDOXdcQPgZJCS5En0lAJzLV9Zsa2LqnRvPR9HG77GzWTthh3YdzeFCDM6GiGkGnyjEqzThlmWxn4hA5_q0Ozm0P5AWrXjNPJjvOxXuRaAmDWPZwm5o5Zau7cqMXZSrl0-L4IHJwoZZMhH9ao-gUzn1km4TIdJ5lXGnHfiCEyNn9VUnPEq1FDEHKMPVdtnBwi24fwhfQ_lnOqvmWPk-1pvOYICOJelxOKnfFL2a1-UCSqG-WFKJ7DfAT1rzj_oLbV3sKBOCkPpXmnUt-')">
</div>
<div class="project-overlay absolute inset-0 bg-editor-bg/40 opacity-0 transition-opacity flex items-center justify-center backdrop-blur-sm">
<button class="bg-white text-black px-lg py-sm rounded-full font-label-sm text-label-sm flex items-center gap-xs">
<span class="material-symbols-outlined text-[16px]">edit</span>
                                    Open Editor
                                </button>
</div>
<div class="absolute top-md left-md">
<span class="bg-outline-variant/50 text-outline px-sm py-xs rounded font-mono-label text-mono-label uppercase tracking-wider backdrop-blur-md border border-outline-variant/30">Draft</span>
</div>
</div>
<div class="p-lg">
<h3 class="font-headline-sm text-headline-sm mb-base text-on-surface">Analytics Dashboard</h3>
<div class="flex justify-between items-center text-outline font-mono-label text-mono-label">
<span>Last edited: 1d ago</span>
<div class="flex items-center -space-x-2">
<div class="w-6 h-6 rounded-full border-2 border-panel-surface bg-royal-purple flex items-center justify-center text-[10px] font-bold">ML</div>
</div>
</div>
</div>
</div>
<!-- Project Card 3 -->
<div class="project-card group bg-panel-surface border border-outline-variant rounded-xl overflow-hidden cursor-pointer hover:border-electric-blue transition-colors flex flex-col">
<div class="relative h-48 bg-canvas-bg overflow-hidden">
<div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-105" data-alt="A minimalist portfolio website design featuring high-contrast black and white photography. The layout is clean and spacious, with delicate lines and bold sans-serif typography. The mood is quiet and authoritative, focusing on the visual content of the work-in-progress project." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuD3ZDCNBwrCRhI-UBWYwOpMONeISDZFTEdaPUBZZjVfJZk-qRCxKed4nXf6N3eZEZUFwN9OfIVU4extUfUf-nLjN7VyPQawPVONjMoPIdwhCLELV-YuAgbx9X-I8hUr2ktUp_uEYr5Z87h5LjCijIAGiAwy0RxoVvarIi4yAGBuye_WJhlx2JR2QDIqfAAZRCs36XMtwxrm62ghKNQnmtFIcq25kG3jC6nj7-2DTZJYXGUi_6eQkCjR7TNviciByLDYtoQdpWjww4g7')">
</div>
<div class="project-overlay absolute inset-0 bg-editor-bg/40 opacity-0 transition-opacity flex items-center justify-center backdrop-blur-sm">
<button class="bg-white text-black px-lg py-sm rounded-full font-label-sm text-label-sm flex items-center gap-xs">
<span class="material-symbols-outlined text-[16px]">edit</span>
                                    Open Editor
                                </button>
</div>
<div class="absolute top-md left-md">
<span class="bg-success-green/20 text-success-green px-sm py-xs rounded font-mono-label text-mono-label uppercase tracking-wider backdrop-blur-md border border-success-green/30">Published</span>
</div>
</div>
<div class="p-lg">
<h3 class="font-headline-sm text-headline-sm mb-base text-on-surface">Creative Portfolio</h3>
<div class="flex justify-between items-center text-outline font-mono-label text-mono-label">
<span>Last edited: 3d ago</span>
<div class="flex items-center -space-x-2">
<div class="w-6 h-6 rounded-full border-2 border-panel-surface bg-surface-container flex items-center justify-center text-[10px] font-bold">AL</div>
<div class="w-6 h-6 rounded-full border-2 border-panel-surface bg-surface-container flex items-center justify-center text-[10px] font-bold">TK</div>
</div>
</div>
</div>
</div>
<!-- Empty State Add Card -->
<div class="group border-2 border-dashed border-outline-variant rounded-xl flex flex-col items-center justify-center p-2xl hover:border-electric-blue hover:bg-surface-container-low transition-all cursor-pointer">
<div class="w-12 h-12 rounded-full border border-outline-variant flex items-center justify-center mb-md group-hover:bg-electric-blue/10 group-hover:border-electric-blue transition-colors">
<span class="material-symbols-outlined text-outline group-hover:text-electric-blue">add</span>
</div>
<span class="font-label-md text-label-md text-on-surface-variant group-hover:text-on-surface">New Template</span>
</div>
</div>
<!-- Recent Activity Bento -->
<div class="mt-4xl grid grid-cols-1 lg:grid-cols-3 gap-xl">
<div class="lg:col-span-2 bg-panel-surface border border-outline-variant rounded-xl p-xl">
<h4 class="font-headline-sm text-headline-sm mb-lg flex items-center gap-sm">
<span class="material-symbols-outlined text-electric-blue">timeline</span>
                            Recent Activity
                        </h4>
<div class="space-y-md">
<div class="flex items-center gap-md p-md hover:bg-surface-container-lowest transition-colors rounded-lg border border-transparent hover:border-outline-variant">
<div class="w-2 h-2 rounded-full bg-electric-blue"></div>
<div class="flex-1">
<p class="font-body-sm text-on-surface">Sarah P. <span class="text-on-surface-variant">published</span> Modern SaaS Landing</p>
</div>
<span class="font-mono-label text-mono-label text-outline">14:02 PM</span>
</div>
<div class="flex items-center gap-md p-md hover:bg-surface-container-lowest transition-colors rounded-lg border border-transparent hover:border-outline-variant">
<div class="w-2 h-2 rounded-full bg-royal-purple"></div>
<div class="flex-1">
<p class="font-body-sm text-on-surface">Marcus L. <span class="text-on-surface-variant">added 4 assets to</span> Elements Library</p>
</div>
<span class="font-mono-label text-mono-label text-outline">11:45 AM</span>
</div>
<div class="flex items-center gap-md p-md hover:bg-surface-container-lowest transition-colors rounded-lg border border-transparent hover:border-outline-variant">
<div class="w-2 h-2 rounded-full bg-warning-amber"></div>
<div class="flex-1">
<p class="font-body-sm text-on-surface">System <span class="text-on-surface-variant">back-up completed for</span> Project Alpha</p>
</div>
<span class="font-mono-label text-mono-label text-outline">09:00 AM</span>
</div>
</div>
</div>
<div class="bg-electric-blue rounded-xl p-xl flex flex-col justify-between text-white relative overflow-hidden group">

<div class="relative z-10">
<span class="bg-white/20 text-white font-mono-label text-mono-label px-sm py-xs rounded-full uppercase">Update v2.1</span>
<h4 class="font-headline-sm text-headline-sm mt-md">New AI Layout Generator</h4>
<p class="text-white/80 text-body-sm mt-sm">Generate complete landing pages from text prompts in seconds.</p>
</div>
<button class="relative z-10 w-full bg-white text-electric-blue font-label-md text-label-md py-md rounded-lg hover:bg-on-surface transition-colors mt-lg">Try it Now</button>
</div>
</div>
</div>
</main>
<!-- SideNavBar (Right) - Element Properties / Settings -->
<aside class="flex flex-col h-full w-panel-width fixed right-0 top-toolbar-height bg-panel-surface border-l border-outline-variant z-40">
<div class="p-lg border-b border-outline-variant">
<div class="font-label-sm text-label-sm text-on-surface uppercase mb-base">Element Properties</div>
<div class="font-body-sm text-body-sm text-outline opacity-70">Selected: Section</div>
</div>
<nav class="flex px-md pt-md gap-xs">
<button class="flex-1 py-xs rounded-sm text-on-primary-container bg-primary-container font-label-sm text-label-sm flex flex-col items-center gap-1 transition-opacity duration-100">
<span class="material-symbols-outlined text-[18px]">palette</span>
                    Style
                </button>
<button class="flex-1 py-xs rounded-sm text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm flex flex-col items-center gap-1 transition-opacity duration-100">
<span class="material-symbols-outlined text-[18px]">bolt</span>
                    Interactions
                </button>
<button class="flex-1 py-xs rounded-sm text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm flex flex-col items-center gap-1 transition-opacity duration-100">
<span class="material-symbols-outlined text-[18px]">settings_applications</span>
                    Settings
                </button>
<button class="flex-1 py-xs rounded-sm text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm flex flex-col items-center gap-1 transition-opacity duration-100">
<span class="material-symbols-outlined text-[18px]">fact_check</span>
                    Audit
                </button>
</nav>
<div class="p-md flex-1 overflow-y-auto space-y-lg">
<!-- Property Group -->
<div>
<div class="flex justify-between items-center mb-md">
<span class="font-label-sm text-label-sm text-outline uppercase">Layout</span>
<span class="material-symbols-outlined text-[16px] cursor-pointer">expand_more</span>
</div>
<div class="space-y-sm">
<div class="flex items-center justify-between">
<span class="text-on-surface-variant text-body-sm">Display</span>
<select class="bg-editor-bg border-outline-variant rounded px-sm py-1 text-on-surface font-mono-label text-mono-label w-24">
<option>Flex</option>
<option>Grid</option>
<option>Block</option>
</select>
</div>
<div class="flex items-center justify-between">
<span class="text-on-surface-variant text-body-sm">Direction</span>
<div class="flex bg-editor-bg border border-outline-variant rounded p-xs">
<button class="p-xs bg-surface-container-highest rounded"><span class="material-symbols-outlined text-[16px]">arrow_downward</span></button>
<button class="p-xs hover:bg-surface-container-highest rounded"><span class="material-symbols-outlined text-[16px]">arrow_forward</span></button>
</div>
</div>
</div>
</div>
<!-- Property Group -->
<div>
<div class="flex justify-between items-center mb-md">
<span class="font-label-sm text-label-sm text-outline uppercase">Spacing</span>
<span class="material-symbols-outlined text-[16px] cursor-pointer">expand_more</span>
</div>
<div class="grid grid-cols-2 gap-sm">
<div class="space-y-xs">
<span class="font-mono-label text-mono-label text-outline block">Padding</span>
<input class="w-full bg-editor-bg border border-outline-variant rounded h-[28px] px-sm font-mono-label text-mono-label text-on-surface focus:border-electric-blue outline-none transition-all" type="text" value="32px"/>
</div>
<div class="space-y-xs">
<span class="font-mono-label text-mono-label text-outline block">Margin</span>
<input class="w-full bg-editor-bg border border-outline-variant rounded h-[28px] px-sm font-mono-label text-mono-label text-on-surface focus:border-electric-blue outline-none transition-all" type="text" value="0px"/>
</div>
</div>
</div>
</div>
</aside>
</div>
<!-- Footer -->
<footer class="fixed bottom-0 left-0 w-full h-8 flex justify-between items-center px-md z-50 bg-surface-container-lowest border-t border-outline-variant text-on-surface-variant font-mono-label text-mono-label">
<div class="flex items-center gap-lg">
<div class="flex items-center gap-sm">
<span class="w-2 h-2 rounded-full bg-success-green animate-pulse"></span>
<span>System Online</span>
</div>
<div class="flex items-center gap-xs text-outline">
<a class="hover:text-primary transition-colors cursor-default" href="#">Dashboard</a>
<span>/</span>
<a class="hover:text-primary transition-colors cursor-default" href="#">Projects</a>
</div>
</div>
<div class="flex items-center gap-lg">
<div class="flex items-center gap-md">
<button class="hover:text-primary transition-colors cursor-default">Zoom 100%</button>
<button class="hover:text-primary transition-colors cursor-default flex items-center gap-xs">
<span class="material-symbols-outlined text-[14px]">help</span>
                    Canvas Help
                </button>
</div>
<div class="font-label-sm text-label-sm text-outline">© 2024 StudioPro Engine</div>
</div>
</footer>
<script>
        // Micro-interactions and simple layout fixes
        document.querySelectorAll('.project-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-4px)';
                card.style.transition = 'transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });

        // Toggle simple property accordions
        document.querySelectorAll('.material-symbols-outlined').forEach(icon => {
            if(icon.innerText === 'expand_more') {
                icon.addEventListener('click', function() {
                    const content = this.parentElement.nextElementSibling;
                    if (content) {
                        content.classList.toggle('hidden');
                        this.style.transform = content.classList.contains('hidden') ? 'rotate(-90deg)' : 'rotate(0)';
                    }
                });
            }
        });
    </script>
</body></html>

<!-- SEO & Page Settings -->
<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>StudioPro | Page Settings &amp; SEO</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&amp;family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 20;
        }
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        ::-webkit-scrollbar-track {
            background: #0B0E14;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #2E62FF;
        }
        .code-editor-line::before {
            content: attr(data-line);
            color: #434656;
            margin-right: 12px;
            display: inline-block;
            width: 20px;
            text-align: right;
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary-fixed-dim": "#4cd7f6",
                        "on-tertiary": "#003640",
                        "royal-purple": "#8B5CF6",
                        "on-primary": "#002682",
                        "on-secondary-container": "#c4abff",
                        "surface-container": "#171f33",
                        "primary-fixed-dim": "#b7c4ff",
                        "on-background": "#dae2fd",
                        "secondary-container": "#571bc1",
                        "surface-container-high": "#222a3d",
                        "error": "#ffb4ab",
                        "on-surface-variant": "#c3c5d8",
                        "error-container": "#93000a",
                        "canvas-bg": "#1F2937",
                        "on-tertiary-fixed": "#001f26",
                        "on-secondary": "#3c0091",
                        "on-error": "#690005",
                        "panel-surface": "#161B22",
                        "electric-blue": "#2E62FF",
                        "secondary-fixed": "#e9ddff",
                        "tertiary-fixed": "#acedff",
                        "success-green": "#10B981",
                        "on-tertiary-container": "#e9f9ff",
                        "surface-container-lowest": "#060e20",
                        "on-primary-fixed": "#001552",
                        "editor-bg": "#0B0E14",
                        "on-primary-container": "#f7f6ff",
                        "inverse-on-surface": "#283044",
                        "outline-variant": "#434656",
                        "tertiary": "#4cd7f6",
                        "surface-bright": "#31394d",
                        "primary-container": "#2e62ff",
                        "inverse-surface": "#dae2fd",
                        "on-secondary-fixed": "#23005c",
                        "on-secondary-fixed-variant": "#5516be",
                        "surface-variant": "#2d3449",
                        "surface-dim": "#0b1326",
                        "on-surface": "#dae2fd",
                        "tertiary-container": "#007c91",
                        "inverse-primary": "#024cec",
                        "error-red": "#EF4444",
                        "secondary": "#d0bcff",
                        "primary-fixed": "#dce1ff",
                        "background": "#0b1326",
                        "surface-container-highest": "#2d3449",
                        "on-primary-fixed-variant": "#0039b5",
                        "outline": "#8d90a2",
                        "surface-tint": "#b7c4ff",
                        "secondary-fixed-dim": "#d0bcff",
                        "on-error-container": "#ffdad6",
                        "surface": "#0b1326",
                        "surface-container-low": "#131b2e",
                        "on-tertiary-fixed-variant": "#004e5c",
                        "primary": "#b7c4ff",
                        "warning-amber": "#F59E0B"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "sm": "8px",
                        "4xl": "64px",
                        "md": "12px",
                        "lg": "16px",
                        "toolbar-height": "48px",
                        "2xl": "32px",
                        "3xl": "48px",
                        "xl": "24px",
                        "base": "4px",
                        "xs": "4px",
                        "panel-width": "280px"
                    },
                    "fontFamily": {
                        "body-lg": ["Geist"],
                        "mono-label": ["JetBrains Mono"],
                        "headline-md": ["Geist"],
                        "body-md": ["Geist"],
                        "headline-lg": ["Geist"],
                        "label-md": ["Geist"],
                        "headline-sm": ["Geist"],
                        "body-sm": ["Geist"],
                        "label-sm": ["Geist"]
                    },
                    "fontSize": {
                        "body-lg": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                        "mono-label": ["11px", {"lineHeight": "1", "fontWeight": "400"}],
                        "headline-md": ["24px", {"lineHeight": "1.3", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "body-md": ["14px", {"lineHeight": "1.5", "fontWeight": "400"}],
                        "headline-lg": ["32px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "600"}],
                        "label-md": ["12px", {"lineHeight": "1", "letterSpacing": "0.02em", "fontWeight": "500"}],
                        "headline-sm": ["18px", {"lineHeight": "1.4", "fontWeight": "500"}],
                        "body-sm": ["13px", {"lineHeight": "1.5", "fontWeight": "400"}],
                        "label-sm": ["11px", {"lineHeight": "1", "letterSpacing": "0.05em", "fontWeight": "600"}]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-editor-bg text-on-surface font-body-md overflow-hidden">
<!-- TopNavBar -->
<header class="bg-panel-surface flex justify-between items-center h-toolbar-height px-md w-full border-b border-outline-variant fixed top-0 z-50">
<div class="flex items-center gap-xl">
<span class="font-headline-md text-headline-md font-bold text-on-surface">StudioPro</span>
<nav class="hidden md:flex gap-lg items-center">
<a class="font-label-sm text-label-sm text-on-surface-variant font-medium hover:bg-surface-container-highest transition-colors px-sm py-1 cursor-pointer" href="#">Pages</a>
<a class="font-label-sm text-label-sm text-on-surface-variant font-medium hover:bg-surface-container-highest transition-colors px-sm py-1 cursor-pointer" href="#">Assets</a>
<a class="font-label-sm text-label-sm text-electric-blue font-bold border-b-2 border-electric-blue pb-1 cursor-pointer" href="#">Settings</a>
</nav>
</div>
<div class="flex items-center gap-md">
<div class="flex bg-surface-container rounded-sm p-0.5 mr-md">
<button class="p-1 text-on-surface-variant hover:bg-surface-container-highest transition-colors active:scale-95"><span class="material-symbols-outlined text-[18px]">desktop_windows</span></button>
<button class="p-1 text-on-surface-variant hover:bg-surface-container-highest transition-colors active:scale-95"><span class="material-symbols-outlined text-[18px]">tablet_mac</span></button>
<button class="p-1 text-on-surface-variant hover:bg-surface-container-highest transition-colors active:scale-95"><span class="material-symbols-outlined text-[18px]">smartphone</span></button>
</div>
<button class="bg-primary-container text-on-primary-container px-lg py-1.5 font-label-sm text-label-sm font-bold transition-all hover:bg-electric-blue active:scale-95">Publish</button>
</div>
</header>
<div class="flex h-screen pt-toolbar-height pb-8">
<!-- Left SideNavBar (Navigator/Layers Context) -->
<aside class="flex flex-col h-full w-panel-width fixed left-0 top-toolbar-height bg-panel-surface border-r border-outline-variant z-40">
<div class="p-md flex items-center gap-sm">
<div class="w-8 h-8 rounded-sm bg-primary-container/10 flex items-center justify-center">
<span class="material-symbols-outlined text-electric-blue" style="font-variation-settings: 'FILL' 1;">article</span>
</div>
<div>
<p class="font-label-md text-label-md text-on-surface">Home Page</p>
<p class="font-body-sm text-body-sm text-on-surface-variant">index.html</p>
</div>
</div>
<nav class="flex-1 overflow-y-auto mt-base">
<p class="px-md py-xs font-label-sm text-label-sm uppercase tracking-widest text-outline">Page Settings</p>
<a class="flex items-center gap-sm px-md py-sm text-on-surface-variant hover:bg-surface-container-highest transition-all duration-150 cursor-pointer group" href="#general">
<span class="material-symbols-outlined text-[20px] group-hover:text-on-surface">settings</span>
<span class="font-label-sm text-label-sm">General</span>
</a>
<a class="flex items-center gap-sm px-md py-sm bg-primary-container/15 text-electric-blue border-l-2 border-electric-blue transition-all duration-150 cursor-pointer" href="#seo">
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">search</span>
<span class="font-label-sm text-label-sm">SEO</span>
</a>
<a class="flex items-center gap-sm px-md py-sm text-on-surface-variant hover:bg-surface-container-highest transition-all duration-150 cursor-pointer group" href="#social">
<span class="material-symbols-outlined text-[20px] group-hover:text-on-surface">share</span>
<span class="font-label-sm text-label-sm">Social Share</span>
</a>
<a class="flex items-center gap-sm px-md py-sm text-on-surface-variant hover:bg-surface-container-highest transition-all duration-150 cursor-pointer group" href="#code">
<span class="material-symbols-outlined text-[20px] group-hover:text-on-surface">code</span>
<span class="font-label-sm text-label-sm">Custom Code</span>
</a>
</nav>
<div class="p-md border-t border-outline-variant">
<div class="bg-surface-container-highest p-sm rounded-lg mb-sm">
<p class="font-label-sm text-label-sm text-on-surface mb-xs">Storage Usage</p>
<div class="w-full bg-outline-variant h-1 rounded-full mb-xs">
<div class="bg-electric-blue h-full w-[65%] rounded-full"></div>
</div>
<p class="font-mono-label text-mono-label text-outline">1.2GB / 2GB</p>
</div>
<button class="w-full py-2 font-label-sm text-label-sm bg-secondary-container/20 text-on-secondary-container border border-secondary-container/30 hover:bg-secondary-container/30 transition-colors">Upgrade Plan</button>
</div>
</aside>
<!-- Main Workspace (Canvas replacement) -->
<main class="ml-panel-width mr-panel-width flex-1 bg-editor-bg overflow-y-auto p-3xl">
<div class="max-w-3xl mx-auto space-y-3xl">
<!-- SEO Section -->
<section class="space-y-xl" id="seo-config">
<div class="flex items-center justify-between">
<h2 class="font-headline-sm text-headline-sm text-on-surface">SEO Settings</h2>
<span class="bg-success-green/10 text-success-green px-sm py-1 font-label-sm text-label-sm rounded-full">Indexed</span>
</div>
<div class="grid grid-cols-1 gap-lg">
<div class="space-y-xs">
<label class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Page Title (Tag)</label>
<input class="w-full bg-surface-container-low border border-outline-variant text-on-surface px-md py-sm focus:border-electric-blue focus:ring-1 focus:ring-electric-blue outline-none transition-all" type="text" value="StudioPro | Professional Design Engine for Creators"/>
<div class="flex justify-between items-center px-1">
<p class="text-mono-label font-mono-label text-outline">Characters: 52 / 60</p>
<p class="text-mono-label font-mono-label text-success-green">Optimal</p>
</div>
</div>
<div class="space-y-xs">
<label class="font-label-sm text-label-sm text-outline uppercase tracking-wider">URL Slug</label>
<div class="flex">
<span class="bg-surface-container border border-r-0 border-outline-variant px-sm py-sm text-outline font-label-sm">studiopro.io/</span>
<input class="flex-1 bg-surface-container-low border border-outline-variant text-on-surface px-md py-sm focus:border-electric-blue focus:ring-1 focus:ring-electric-blue outline-none transition-all" type="text" value="home"/>
</div>
</div>
<div class="space-y-xs">
<label class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Meta Description</label>
<textarea class="w-full bg-surface-container-low border border-outline-variant text-on-surface px-md py-sm focus:border-electric-blue focus:ring-1 focus:ring-electric-blue outline-none transition-all resize-none" rows="3">StudioPro is the world's most advanced web design platform. Create pixel-perfect responsive layouts with ease using our pro-grade minimalist toolkit.</textarea>
<div class="flex justify-between items-center px-1">
<p class="text-mono-label font-mono-label text-outline">Characters: 154 / 160</p>
<p class="text-mono-label font-mono-label text-warning-amber">Nearing Limit</p>
</div>
</div>
</div>
<div class="pt-xl border-t border-outline-variant">
<p class="font-label-sm text-label-sm text-outline uppercase tracking-wider mb-md">Google Search Preview</p>
<div class="bg-white p-lg rounded-lg shadow-xl max-w-2xl">
<p class="text-[14px] text-[#202124] mb-1">studiopro.io › home</p>
<h3 class="text-[20px] text-[#1a0dab] font-normal hover:underline cursor-pointer mb-1">StudioPro | Professional Design Engine for Creators</h3>
<p class="text-[14px] text-[#4d5156] leading-[1.58]">StudioPro is the world's most advanced web design platform. Create pixel-perfect responsive layouts with ease using our pro-grade minimalist toolkit.</p>
</div>
</div>
</section>
<!-- Social Share Section -->
<section class="pt-3xl border-t border-outline-variant space-y-xl" id="social-share">
<h2 class="font-headline-sm text-headline-sm text-on-surface">Social Share (Open Graph)</h2>
<div class="grid md:grid-cols-2 gap-2xl">
<div class="space-y-lg">
<div class="space-y-xs">
<label class="font-label-sm text-label-sm text-outline uppercase tracking-wider">OG Image</label>
<div class="relative group cursor-pointer border border-dashed border-outline rounded-lg h-40 bg-surface-container-low flex flex-col items-center justify-center gap-sm overflow-hidden">
<div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110" data-alt="A cinematic, high-fidelity hero image for a creative tech brand, featuring abstract floating glass elements in electric blue and deep slate gray. The lighting is moody and professional, with a soft glow reflecting off obsidian surfaces, conveying a premium and innovative software aesthetic. The composition is wide and modern, suitable for a social media sharing preview." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuC-WPKySRYTJOQKhsVA7xIDaZWOh0w2mXyDu1kcTfpMBkkn4Wzqp6Ljbn-IkEm2-uEnVu9JAM13EI1QEplqR1KEn2xF7AoNq1GSn43wHrQjmuqsbPDq8gVLa789LNFZJoMVCZ33U-KvMu660kVZEGB-doCSBAGHPmuWPuOGheIRIjUzk1zKyy9gCtZNOHVkqTskBhRkgBn3GPYAPqzR-zA7WJCak0pPVuRxYLENtFk6z_iHuqcowAMFd3R87IMqu0_1NdVK-Tg6aupf')"></div>
<div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
<span class="material-symbols-outlined text-white mb-xs">add_photo_alternate</span>
<span class="font-label-sm text-label-sm text-white">Change Image</span>
</div>
</div>
<p class="text-mono-label font-mono-label text-outline">Recommended: 1200 x 630px</p>
</div>
<div class="space-y-xs">
<label class="font-label-sm text-label-sm text-outline uppercase tracking-wider">OG Title</label>
<input class="w-full bg-surface-container-low border border-outline-variant text-on-surface px-md py-sm focus:border-electric-blue outline-none" placeholder="Same as SEO title" type="text"/>
</div>
</div>
<div class="space-y-md">
<p class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Twitter Card Preview</p>
<div class="bg-[#15202B] rounded-xl border border-outline-variant overflow-hidden max-w-sm shadow-2xl">
<div class="h-32 bg-cover bg-center" data-alt="A minimalist tech landscape with smooth gradients of deep blue and violet, showcasing a sleek interface design layout floating in a void. The visual style is crisp and professional, using high-contrast lighting to emphasize clean lines and modern UI components. The mood is sophisticated and future-forward, perfect for a high-end SaaS marketing preview." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBfh-IbzqlxPHN9eYGFzQnXkjK_EKIPkC8vydHAqBEw0rMA65jri_Gqo-FAsZKl4M3s_k7Nm27DdsjOcbPjkFl7C2QpoqkVMoJYd0IRwAAuSBOZeLi07W5UYk-0aylvWYEY9dKnRLK4JILtY-cAqG2RtMEtDXqTL3SzUOPKvADnW13D2yGaqOZMcbbFd77BDkRSMy05lgyPKSmFCJBSCkCNxxOcSqH7Hl6YPBMaxPtmcpjJfT6o_6-w2MVYmClhxwTEHCvUdCap5Xoa')"></div>
<div class="p-md">
<p class="text-[#8899A6] text-[13px] mb-0.5">studiopro.io</p>
<p class="text-white font-bold text-[15px] mb-1">StudioPro | Design the Future</p>
<p class="text-[#8899A6] text-[14px] line-clamp-2">Elevate your creative workflow with StudioPro's high-performance engine...</p>
</div>
</div>
</div>
</div>
</section>
<!-- Code Editor Section -->
<section class="pt-3xl border-t border-outline-variant space-y-xl pb-4xl" id="custom-code">
<h2 class="font-headline-sm text-headline-sm text-on-surface">Custom Code Scripts</h2>
<div class="space-y-lg">
<div class="space-y-xs">
<label class="font-label-sm text-label-sm text-outline uppercase tracking-wider flex items-center gap-xs">
<span class="material-symbols-outlined text-[14px]">terminal</span>
                                Head Code (&lt;head&gt;)
                            </label>
<div class="bg-editor-bg border border-outline-variant rounded-sm font-mono-label text-mono-label overflow-hidden">
<div class="flex items-center gap-sm bg-panel-surface border-b border-outline-variant px-md py-xs">
<span class="w-3 h-3 rounded-full bg-error-red/50"></span>
<span class="w-3 h-3 rounded-full bg-warning-amber/50"></span>
<span class="w-3 h-3 rounded-full bg-success-green/50"></span>
<span class="ml-auto text-outline uppercase text-[9px]">Javascript / CSS</span>
</div>
<div class="p-md bg-[#010409] text-[#e6edf3] leading-relaxed min-h-[120px]">
<div class="code-editor-line" data-line="1"><span class="text-[#79c0ff]">&lt;script&gt;</span></div>
<div class="code-editor-line" data-line="2"><span class="ml-4 text-[#ff7b72]">window</span>.dataLayer = <span class="text-[#ff7b72]">window</span>.dataLayer || [];</div>
<div class="code-editor-line" data-line="3"><span class="ml-4 text-[#ff7b72]">function</span> <span class="text-[#d2a8ff]">gtag</span>(){dataLayer.<span class="text-[#d2a8ff]">push</span>(arguments);}</div>
<div class="code-editor-line" data-line="4"><span class="ml-4 text-[#d2a8ff]">gtag</span>(<span class="text-[#a5d6ff]">'js'</span>, <span class="text-[#ff7b72]">new</span> <span class="text-[#d2a8ff]">Date</span>());</div>
<div class="code-editor-line" data-line="5"><span class="text-[#79c0ff]">&lt;/script&gt;</span></div>
</div>
</div>
</div>
<div class="space-y-xs">
<label class="font-label-sm text-label-sm text-outline uppercase tracking-wider flex items-center gap-xs">
<span class="material-symbols-outlined text-[14px]">terminal</span>
                                Body Code (&lt;/body&gt;)
                            </label>
<div class="bg-editor-bg border border-outline-variant rounded-sm font-mono-label text-mono-label overflow-hidden">
<div class="flex items-center gap-sm bg-panel-surface border-b border-outline-variant px-md py-xs">
<span class="w-3 h-3 rounded-full bg-error-red/50"></span>
<span class="w-3 h-3 rounded-full bg-warning-amber/50"></span>
<span class="w-3 h-3 rounded-full bg-success-green/50"></span>
<span class="ml-auto text-outline uppercase text-[9px]">Javascript</span>
</div>
<div class="p-md bg-[#010409] text-[#e6edf3] leading-relaxed min-h-[120px]">
<div class="code-editor-line" data-line="1"><span class="text-[#8b949e]">// Add scripts before the closing body tag</span></div>
<div class="code-editor-line" data-line="2"><span class="text-[#ff7b72]">console</span>.<span class="text-[#d2a8ff]">log</span>(<span class="text-[#a5d6ff]">'StudioPro Page Loaded'</span>);</div>
</div>
</div>
</div>
</div>
</section>
</div>
</main>
<!-- Right SideNavBar (Properties Context) -->
<aside class="flex flex-col h-full w-panel-width fixed right-0 top-toolbar-height bg-panel-surface border-l border-outline-variant z-40">
<div class="p-md border-b border-outline-variant">
<p class="font-label-sm text-label-sm text-on-surface">Page Information</p>
<p class="font-mono-label text-mono-label text-outline mt-xs">Created: Oct 24, 2024</p>
</div>
<nav class="flex border-b border-outline-variant">
<button class="flex-1 py-sm font-label-sm text-label-sm text-on-primary-container bg-primary-container transition-opacity duration-100">Style</button>
<button class="flex-1 py-sm font-label-sm text-label-sm text-on-surface-variant hover:text-on-surface transition-opacity duration-100">Config</button>
</nav>
<div class="flex-1 overflow-y-auto">
<div class="p-md space-y-lg">
<div class="space-y-sm">
<div class="flex items-center justify-between">
<span class="font-label-sm text-label-sm text-on-surface">Visibility</span>
<span class="material-symbols-outlined text-[16px] text-outline cursor-pointer">expand_more</span>
</div>
<div class="flex items-center gap-sm">
<div class="w-8 h-4 bg-electric-blue rounded-full relative cursor-pointer">
<div class="absolute right-0.5 top-0.5 w-3 h-3 bg-white rounded-full"></div>
</div>
<span class="font-body-sm text-body-sm">Publicly Visible</span>
</div>
</div>
<div class="space-y-sm">
<div class="flex items-center justify-between">
<span class="font-label-sm text-label-sm text-on-surface">Password Protect</span>
<span class="material-symbols-outlined text-[16px] text-outline cursor-pointer">expand_more</span>
</div>
<input class="w-full bg-editor-bg border border-outline-variant text-on-surface px-sm py-1 font-body-sm focus:border-electric-blue outline-none rounded-sm" placeholder="Set password..." type="password"/>
</div>
<div class="space-y-sm">
<div class="flex items-center justify-between">
<span class="font-label-sm text-label-sm text-on-surface">Custom Headers</span>
<span class="material-symbols-outlined text-[16px] text-outline cursor-pointer">add_box</span>
</div>
<div class="bg-surface-container-highest rounded-sm p-sm border border-outline-variant">
<p class="font-mono-label text-mono-label text-on-surface-variant">X-Frame-Options: SAMEORIGIN</p>
</div>
</div>
</div>
</div>
<div class="p-md space-y-sm border-t border-outline-variant">
<button class="w-full py-2 font-label-sm text-label-sm border border-error-red text-error-red hover:bg-error-red/10 transition-colors flex items-center justify-center gap-xs">
<span class="material-symbols-outlined text-[18px]">delete</span>
                    Delete Page
                </button>
<button class="w-full py-2 font-label-sm text-label-sm bg-electric-blue text-white hover:bg-electric-blue/90 transition-colors flex items-center justify-center gap-xs">
<span class="material-symbols-outlined text-[18px]">save</span>
                    Save Settings
                </button>
</div>
</aside>
</div>
<!-- Footer -->
<footer class="fixed bottom-0 left-0 w-full h-8 flex justify-between items-center px-md z-50 bg-surface-container-lowest border-t border-outline-variant">
<div class="flex items-center gap-lg">
<span class="font-label-sm text-label-sm text-outline">© 2024 StudioPro Engine</span>
<div class="flex gap-md">
<span class="font-mono-label text-mono-label text-outline hover:text-primary transition-colors cursor-default">Breadcrumbs</span>
<span class="font-mono-label text-mono-label text-on-surface cursor-default">Zoom 100%</span>
</div>
</div>
<div class="flex items-center gap-md">
<span class="font-mono-label text-mono-label text-outline hover:text-primary transition-colors cursor-default">Canvas Help</span>
<div class="flex items-center gap-xs">
<div class="w-2 h-2 rounded-full bg-success-green"></div>
<span class="font-mono-label text-mono-label text-outline">System Online</span>
</div>
</div>
</footer>
<script>
        // Simple Micro-interactions
        document.querySelectorAll('input, textarea').forEach(el => {
            el.addEventListener('focus', () => {
                el.parentElement.classList.add('scale-[1.01]');
                el.parentElement.style.transition = 'transform 0.2s ease';
            });
            el.addEventListener('blur', () => {
                el.parentElement.classList.remove('scale-[1.01]');
            });
        });

        // Toggle Switch Emulation
        const toggles = document.querySelectorAll('.bg-electric-blue.rounded-full');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                const knob = toggle.querySelector('div');
                if (knob.classList.contains('right-0.5')) {
                    knob.classList.remove('right-0.5');
                    knob.classList.add('left-0.5');
                    toggle.classList.replace('bg-electric-blue', 'bg-outline-variant');
                } else {
                    knob.classList.remove('left-0.5');
                    knob.classList.add('right-0.5');
                    toggle.classList.replace('bg-outline-variant', 'bg-electric-blue');
                }
            });
        });
    </script>
</body></html>

<!-- Assets Manager -->
<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>StudioPro Asset Manager</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&family=JetBrains+Mono:wght@100..800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet"/>
<style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 20;
    }
    ::-webkit-scrollbar {
      width: 4px;
      height: 4px;
    }
    ::-webkit-scrollbar-track {
      background: #0B0E14;
    }
    ::-webkit-scrollbar-thumb {
      background: #334155;
      border-radius: 2px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #2E62FF;
    }
    .asset-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 12px;
    }
    .glass-panel {
      backdrop-filter: blur(8px);
      background: rgba(22, 27, 34, 0.8);
    }
  </style>
<script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          "colors": {
            "panel-surface": "#161B22",
            "electric-blue": "#2E62FF",
            "editor-bg": "#0B0E14",
            "surface-container-highest": "#2d3449",
            "outline-variant": "#434656",
            "on-surface": "#dae2fd",
            "on-surface-variant": "#c3c5d8",
            "primary-container": "#2e62ff",
            "on-primary-container": "#f7f6ff",
            "surface-container-lowest": "#060e20",
            "outline": "#8d90a2"
          },
          "spacing": {
            "xs": "4px",
            "sm": "8px",
            "md": "12px",
            "lg": "16px",
            "xl": "24px",
            "toolbar-height": "48px",
            "panel-width": "280px"
          },
          "fontFamily": {
            "body-sm": ["Geist"],
            "label-sm": ["Geist"],
            "label-md": ["Geist"],
            "headline-sm": ["Geist"],
            "headline-md": ["Geist"],
            "mono-label": ["JetBrains Mono"]
          },
          "fontSize": {
            "label-sm": ["11px", {"lineHeight": "1", "letterSpacing": "0.05em", "fontWeight": "600"}],
            "label-md": ["12px", {"lineHeight": "1", "letterSpacing": "0.02em", "fontWeight": "500"}],
            "body-sm": ["13px", {"lineHeight": "1.5", "fontWeight": "400"}],
            "headline-sm": ["18px", {"lineHeight": "1.4", "fontWeight": "500"}],
            "headline-md": ["24px", {"lineHeight": "1.3", "fontWeight": "600"}],
            "mono-label": ["11px", {"lineHeight": "1", "fontWeight": "400"}]
          }
        }
      }
    }
  </script>
</head>
<body class="bg-editor-bg text-on-surface font-body-sm overflow-hidden h-screen flex flex-col">
<!-- TopNavBar -->
<header class="bg-panel-surface border-b border-outline-variant flex justify-between items-center h-toolbar-height px-md w-full fixed top-0 z-50">
<div class="flex items-center gap-xl">
<span class="font-headline-md text-headline-md font-bold text-on-surface">StudioPro</span>
<nav class="hidden md:flex items-center gap-lg">
<a class="text-on-surface-variant font-medium hover:bg-surface-container-highest transition-colors px-sm py-1 rounded" href="#">Pages</a>
<a class="text-electric-blue font-bold border-b-2 border-electric-blue pb-1" href="#">Assets</a>
<a class="text-on-surface-variant font-medium hover:bg-surface-container-highest transition-colors px-sm py-1 rounded" href="#">Settings</a>
</nav>
</div>
<div class="flex-1 max-w-xl px-xl">
<div class="relative group">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
<input class="w-full bg-editor-bg border border-outline-variant rounded-sm py-1 pl-10 pr-4 text-body-sm focus:border-electric-blue focus:ring-0 transition-all outline-none" placeholder="Search assets..." type="text"/>
</div>
</div>
<div class="flex items-center gap-md">
<div class="flex items-center gap-sm mr-md border-r border-outline-variant pr-md">
<button class="p-1 hover:bg-surface-container-highest rounded cursor-pointer active:scale-95 transition-all">
<span class="material-symbols-outlined text-[20px]">desktop_windows</span>
</button>
<button class="p-1 hover:bg-surface-container-highest rounded cursor-pointer active:scale-95 transition-all">
<span class="material-symbols-outlined text-[20px]">tablet_mac</span>
</button>
<button class="p-1 hover:bg-surface-container-highest rounded cursor-pointer active:scale-95 transition-all">
<span class="material-symbols-outlined text-[20px]">smartphone</span>
</button>
</div>
<button class="bg-electric-blue text-white px-lg py-1.5 rounded-sm font-label-sm uppercase tracking-wider hover:brightness-110 active:scale-95 transition-all">
        Upload
      </button>
</div>
</header>
<div class="flex flex-1 pt-toolbar-height h-full">
<!-- Left Sidebar: Categories -->
<aside class="w-panel-width bg-panel-surface border-r border-outline-variant flex flex-col fixed left-0 h-[calc(100vh-48px)]">
<div class="p-md">
<div class="font-label-md text-label-md uppercase tracking-widest text-on-surface-variant mb-xl">Project Alpha</div>
<div class="space-y-1">
<button class="w-full flex items-center gap-md px-md py-2 bg-primary-container/15 text-electric-blue border-l-2 border-electric-blue group transition-all duration-150">
<span class="material-symbols-outlined text-[20px]">grid_view</span>
<span class="font-label-sm">All Assets</span>
</button>
<button class="w-full flex items-center gap-md px-md py-2 text-on-surface-variant hover:bg-surface-container-highest group transition-all duration-150">
<span class="material-symbols-outlined text-[20px]">image</span>
<span class="font-label-sm">Images</span>
</button>
<button class="w-full flex items-center gap-md px-md py-2 text-on-surface-variant hover:bg-surface-container-highest group transition-all duration-150">
<span class="material-symbols-outlined text-[20px]">movie</span>
<span class="font-label-sm">Videos</span>
</button>
<button class="w-full flex items-center gap-md px-md py-2 text-on-surface-variant hover:bg-surface-container-highest group transition-all duration-150">
<span class="material-symbols-outlined text-[20px]">description</span>
<span class="font-label-sm">Documents</span>
</button>
<button class="w-full flex items-center gap-md px-md py-2 text-on-surface-variant hover:bg-surface-container-highest group transition-all duration-150">
<span class="material-symbols-outlined text-[20px]">delete</span>
<span class="font-label-sm">Trash</span>
</button>
</div>
</div>
<div class="mt-auto p-md border-t border-outline-variant">
<div class="bg-surface-container-highest/30 rounded p-md mb-md">
<div class="text-label-sm text-on-surface-variant mb-xs">Storage Used</div>
<div class="h-1 bg-outline-variant rounded-full overflow-hidden mb-xs">
<div class="h-full bg-electric-blue w-[65%]"></div>
</div>
<div class="flex justify-between text-mono-label text-outline">
<span>6.5 GB</span>
<span>10 GB</span>
</div>
</div>
<button class="w-full py-2 border border-electric-blue text-electric-blue rounded-sm font-label-sm hover:bg-electric-blue/10 transition-colors">
          Upgrade Plan
        </button>
</div>
</aside>
<!-- Main Canvas Area -->
<main class="flex-1 ml-panel-width mr-panel-width overflow-y-auto p-xl bg-editor-bg">
<div class="mb-lg flex justify-between items-end">
<div>
<h1 class="font-headline-sm text-headline-sm mb-1">All Assets</h1>
<p class="text-on-surface-variant font-body-sm">Manage and organize your project files.</p>
</div>
<div class="flex items-center gap-sm">
<button class="p-2 hover:bg-surface-container-highest rounded"><span class="material-symbols-outlined text-[20px]">sort</span></button>
<button class="p-2 hover:bg-surface-container-highest rounded"><span class="material-symbols-outlined text-[20px]">view_module</span></button>
</div>
</div>
<div class="asset-grid">
<!-- Asset Card 1 -->
<div class="group bg-panel-surface border border-outline-variant rounded-sm overflow-hidden hover:border-electric-blue transition-all cursor-pointer active:scale-[0.98]" onclick="selectAsset('Hero Image', 'hero_render_v2.png', '2.4 MB', '1920 x 1080', 'PNG', 'A hyper-realistic 3D render of a futuristic workspace with electric blue lighting accents, clean glass surfaces, and high-performance workstation hardware. The scene is captured with cinematic depth of field, highlighting the precision and pro-grade utility of the StudioPro environment.')">
<div class="aspect-square bg-editor-bg relative overflow-hidden">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="A hyper-realistic 3D render of a futuristic workspace with electric blue lighting accents, clean glass surfaces, and high-performance workstation hardware. The scene is captured with cinematic depth of field, highlighting the precision and pro-grade utility of the StudioPro environment." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCoSSBIhbf93mGdyNUhRLLKL4_onc-IZffTq2FVam5f714XITVXiMa56Wya-xz_1IBTBkrbhAOpQy30CHAKV5KYpPhcK3FnVgCoBjLSaEw8jmO5XdgoSIIrWN_wjawXNd6S0ITpfrw3vFWZiolGubMTdz31__mkBogdK5fTIK-yEa_qWV1A0FdeskqZek9inxsLiyQZTVl2uMHvFgFI_riLpBoUbEdMNtJb7sxv_rGFGwb7ZUkey_AmEIQTPzSeTf4hmi-VL9r0cJql"/>
<div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
<span class="material-symbols-outlined text-white">visibility</span>
</div>
</div>
<div class="p-md">
<div class="font-label-md truncate text-on-surface">hero_render_v2.png</div>
<div class="text-mono-label text-outline mt-1">2.4 MB</div>
</div>
</div>
<!-- Asset Card 2 -->
<div class="group bg-panel-surface border border-outline-variant rounded-sm overflow-hidden hover:border-electric-blue transition-all cursor-pointer active:scale-[0.98]" onclick="selectAsset('Abstract BG', 'texture_mesh_01.jpg', '1.1 MB', '3840 x 2160', 'JPG', 'An abstract digital mesh texture featuring intricate geometric patterns and subtle color gradients ranging from deep navy to vibrant cyan. The visual style is sleek and technical, emphasizing the data-driven and precise nature of a high-end SaaS application layout.')">
<div class="aspect-square bg-editor-bg relative overflow-hidden">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="An abstract digital mesh texture featuring intricate geometric patterns and subtle color gradients ranging from deep navy to vibrant cyan. The visual style is sleek and technical, emphasizing the data-driven and precise nature of a high-end SaaS application layout." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAuBLYKzT-eqBlV7xhCp4x7v2zH2EG6zw2zRr8ZLYj3nNnvwzrcZ7AOZPcINRR85drSKWIdGrOAPg92s6yySS76dTTkNJjXDFmviEZkzAnF7bQWE3P51sXTBp2zwN1d3ZNd1JQ28YRzwm6D_zLlpbZUm2Icdr8zunVb_Eqvd9pOMkQ68ELnUmVBKjYZgDc0FmhUZx1ZbS3wmhBpmNTwDIdoyflX0MN2oGj7G4pgEGUBsYUUtI6ZPkVSTUTfnGbd2Hl6hj8axkmfYhGg"/>
<div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
<span class="material-symbols-outlined text-white">visibility</span>
</div>
</div>
<div class="p-md">
<div class="font-label-md truncate text-on-surface">texture_mesh_01.jpg</div>
<div class="text-mono-label text-outline mt-1">1.1 MB</div>
</div>
</div>
<!-- Asset Card 3 (Video Placeholder) -->
<div class="group bg-panel-surface border border-outline-variant rounded-sm overflow-hidden hover:border-electric-blue transition-all cursor-pointer active:scale-[0.98]">
<div class="aspect-square bg-editor-bg relative flex items-center justify-center">
<span class="material-symbols-outlined text-outline text-[48px] group-hover:text-electric-blue transition-colors">movie</span>
<div class="absolute bottom-2 right-2 bg-black/60 px-1 rounded text-mono-label">0:45</div>
</div>
<div class="p-md">
<div class="font-label-md truncate text-on-surface">intro_animation.mp4</div>
<div class="text-mono-label text-outline mt-1">14.8 MB</div>
</div>
</div>
<!-- Asset Card 4 -->
<div class="group bg-panel-surface border border-outline-variant rounded-sm overflow-hidden hover:border-electric-blue transition-all cursor-pointer active:scale-[0.98]">
<div class="aspect-square bg-editor-bg relative overflow-hidden">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="A macro photograph of high-quality brushed aluminum surfaces with sharp, precise edges and subtle reflections. The lighting is cold and professional, utilizing the deep neutral tones and electric blue accents characteristic of a modern, expert-level design tool aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCyFFDPP96amLIHzi2QUQ7g6Kv9pQ7wZr82EIxNLys_VLyG0uIIps7oV5h580VLxnw0S4xyF6dg81d3eakFrenPAU-IrICrXp7qYzfVrqJI9JoGu4GF9jHxlsPW6_lWWp0JjFJc8OdhAHvAaUvusuMmUeph6lYOg3urQ8AV-in1PTWkykouaFzrsRcRG8Ah_17coB3tlrELUnFM03x9k09ql0tHPZIXP5m8AyFNsvWnFv8mcYDSIViOQMTqsH_KrFhckA1wdhnqQcis"/>
</div>
<div class="p-md">
<div class="font-label-md truncate text-on-surface">material_sample.png</div>
<div class="text-mono-label text-outline mt-1">840 KB</div>
</div>
</div>
<!-- Asset Card 5 -->
<div class="group bg-panel-surface border border-outline-variant rounded-sm overflow-hidden hover:border-electric-blue transition-all cursor-pointer active:scale-[0.98]">
<div class="aspect-square bg-editor-bg relative flex items-center justify-center">
<span class="material-symbols-outlined text-outline text-[48px] group-hover:text-electric-blue transition-colors">description</span>
</div>
<div class="p-md">
<div class="font-label-md truncate text-on-surface">project_specs.pdf</div>
<div class="text-mono-label text-outline mt-1">2.1 MB</div>
</div>
</div>
<!-- Asset Card 6 -->
<div class="group bg-panel-surface border border-outline-variant rounded-sm overflow-hidden hover:border-electric-blue transition-all cursor-pointer active:scale-[0.98]">
<div class="aspect-square bg-editor-bg relative overflow-hidden">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="A clean, minimalist UI component set displayed on a dark canvas. The components include buttons, sliders, and toggles in the electric blue and dark slate palette, showcasing pixel-perfect alignment and professional typography in a technical, tool-like layout." src="https://lh3.googleusercontent.com/aida-public/AB6AXuC3f_3R_MO0KxyG2yqbzOnYQ7TwfJNDEjOweG0LXiHWbMwFPxBIlKFZiH3nO_0n7itLkp8YK5R6ANydGE0T8br-ETqpOkTLFne03U0Oogtp8V6U8yPubMsEfGDRRgcoUV4M7-Muggr-g62qYMS4azXMQBCBeX80KdoA6-SIM6PFAGeJHPLgdxbQu4oy7G4Qehm9QKgsWuzy8i0EAA2f8ohEn0NvzFMtgRPou21qWO534wDi_drAEUpucvKibHlT1XXgqJHIM7B3r9zj"/>
</div>
<div class="p-md">
<div class="font-label-md truncate text-on-surface">ui_kit_v4.png</div>
<div class="text-mono-label text-outline mt-1">5.2 MB</div>
</div>
</div>
</div>
</main>
<!-- Right Sidebar: Asset Properties -->
<aside class="w-panel-width bg-panel-surface border-l border-outline-variant flex flex-col fixed right-0 h-[calc(100vh-48px)] transition-transform duration-300" id="detail-panel">
<div class="p-md border-b border-outline-variant flex justify-between items-center h-toolbar-height">
<span class="font-label-sm text-label-sm text-on-surface uppercase tracking-wider">Element Properties</span>
<div class="flex items-center gap-xs">
<button class="p-1 hover:text-on-surface text-on-surface-variant transition-opacity duration-100">
<span class="material-symbols-outlined text-[18px]">palette</span>
</button>
<button class="p-1 text-on-primary-container bg-primary-container rounded-sm transition-opacity duration-100">
<span class="material-symbols-outlined text-[18px]">fact_check</span>
</button>
</div>
</div>
<div class="p-md space-y-lg overflow-y-auto">
<div class="aspect-video bg-editor-bg rounded-sm border border-outline-variant flex items-center justify-center overflow-hidden" id="preview-container">
<img class="w-full h-full object-cover" data-alt="A placeholder image depicting a technical blueprint for a software interface, with grid lines and measurement markers in a minimalist dark-mode aesthetic, utilizing electric blue highlights to signify active selection." id="detail-img" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA2DBLoVM00Y7zbDj4prM6RQzVc41ANYJEXaDmL0ejKHwN_MoJ6J1gajPwIip0AQh_kjJ_gDceuhLtsUjZKSHc6WC8lFrgFWpTf-88Pv1MC3jfyjHvOVC6629AuF9Rvel9ohe09EP4KeqQZh-wXJLG08OhvJtGMSbRDqIvpwApMPuBR7cqG9qN6vx-80xqLnvDf--tcb_OHDTgyAUlM5cvJ3XHkuefbKavVCNK0y0HzevMh1sAyciIQBMWUoFPZIAQ3hZcF-tiaMZKC"/>
</div>
<div class="space-y-md">
<div class="space-y-1">
<label class="font-label-sm text-label-sm text-outline uppercase tracking-tighter">File Name</label>
<div class="font-mono-label text-mono-label text-on-surface bg-editor-bg p-2 border border-outline-variant rounded-sm truncate" id="detail-name">hero_render_v2.png</div>
</div>
<div class="grid grid-cols-2 gap-md">
<div class="space-y-1">
<label class="font-label-sm text-label-sm text-outline uppercase tracking-tighter">Size</label>
<div class="font-mono-label text-mono-label text-on-surface bg-editor-bg p-2 border border-outline-variant rounded-sm" id="detail-size">2.4 MB</div>
</div>
<div class="space-y-1">
<label class="font-label-sm text-label-sm text-outline uppercase tracking-tighter">Type</label>
<div class="font-mono-label text-mono-label text-on-surface bg-editor-bg p-2 border border-outline-variant rounded-sm" id="detail-type">PNG</div>
</div>
</div>
<div class="space-y-1">
<label class="font-label-sm text-label-sm text-outline uppercase tracking-tighter">Dimensions</label>
<div class="font-mono-label text-mono-label text-on-surface bg-editor-bg p-2 border border-outline-variant rounded-sm" id="detail-dims">1920 x 1080</div>
</div>
<div class="space-y-1">
<label class="font-label-sm text-label-sm text-outline uppercase tracking-tighter">Alt Text</label>
<textarea class="w-full font-body-sm text-body-sm text-on-surface bg-editor-bg p-2 border border-outline-variant rounded-sm h-24 focus:border-electric-blue focus:ring-0 outline-none resize-none" id="detail-alt">Hyper-realistic 3D render of a futuristic workspace with electric blue accents...</textarea>
</div>
</div>
<div class="pt-md border-t border-outline-variant flex flex-col gap-sm">
<button class="w-full py-2 bg-electric-blue text-white rounded-sm font-label-sm flex items-center justify-center gap-sm hover:brightness-110 transition-all">
<span class="material-symbols-outlined text-[18px]">download</span> Download
          </button>
<button class="w-full py-2 border border-outline-variant text-error-red rounded-sm font-label-sm flex items-center justify-center gap-sm hover:bg-error-red/10 transition-all">
<span class="material-symbols-outlined text-[18px]">delete</span> Delete
          </button>
</div>
</div>
</aside>
</div>
<!-- Footer Stats Bar -->
<footer class="fixed bottom-0 left-0 w-full h-8 flex justify-between items-center px-md z-40 bg-surface-container-lowest border-t border-outline-variant">
<div class="flex items-center gap-lg">
<span class="font-label-sm text-label-sm text-outline">© 2024 StudioPro Engine</span>
<div class="flex items-center gap-md font-mono-label text-mono-label text-outline">
<span class="hover:text-primary transition-colors cursor-default">Project Alpha</span>
<span class="text-outline-variant">/</span>
<span class="hover:text-primary transition-colors cursor-default">Assets</span>
<span class="text-outline-variant">/</span>
<span class="text-on-surface">All</span>
</div>
</div>
<div class="flex items-center gap-lg">
<div class="flex items-center gap-md">
<button class="font-mono-label text-mono-label text-outline hover:text-primary transition-colors">Zoom 100%</button>
<button class="font-mono-label text-mono-label text-outline hover:text-primary transition-colors">Canvas Help</button>
</div>
<div class="w-2 h-2 rounded-full bg-success-green shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
</div>
</footer>
<script>
    function selectAsset(title, filename, size, dims, type, alt) {
      document.getElementById('detail-name').innerText = filename;
      document.getElementById('detail-size').innerText = size;
      document.getElementById('detail-dims').innerText = dims;
      document.getElementById('detail-type').innerText = type;
      document.getElementById('detail-alt').value = alt;
      
      // Update preview with slight visual feedback
      const preview = document.getElementById('preview-container');
      preview.classList.add('opacity-50');
      setTimeout(() => preview.classList.remove('opacity-50'), 150);
    }

    // Micro-interaction for asset cards
    document.querySelectorAll('.asset-grid > div').forEach(card => {
      card.addEventListener('click', () => {
        document.querySelectorAll('.asset-grid > div').forEach(c => c.classList.remove('border-electric-blue', 'bg-primary-container/5'));
        card.classList.add('border-electric-blue', 'bg-primary-container/5');
      });
    });
  </script>
</body></html>