<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $project = \App\Models\Project::create([
            'id' => 1,
            'user_id' => $user->id,
            'name' => 'Project Alpha',
            'slug' => 'project-alpha',
            'description' => 'A demo visual page builder project',
        ]);

        $project->pages()->create([
            'id' => 1,
            'title' => 'Home Page',
            'slug' => 'home',
            'html' => '<div class="w-full h-full overflow-y-auto bg-white custom-scrollbar">
  <div class="p-12 flex flex-col items-center text-center gap-6 bg-slate-50 border-b border-slate-200">
    <div class="w-24 h-24 bg-[#2E62FF] rounded-2xl flex items-center justify-center text-white text-4xl font-bold shadow-lg">S</div>
    <h1 class="text-5xl font-black text-slate-900 leading-tight">Elevate Your Design Workflow with StudioPro</h1>
    <p class="text-xl text-slate-600 max-w-2xl">The ultimate pro-grade minimalist visual editor for modern SaaS products and creative agencies.</p>
    <div class="flex gap-4 mt-6">
      <button class="bg-[#2E62FF] text-white px-6 py-3 rounded-xl font-bold text-lg shadow-xl shadow-blue-500/30">Get Started</button>
      <button class="bg-white border-2 border-slate-200 text-slate-900 px-6 py-3 rounded-xl font-bold text-lg">View Demo</button>
    </div>
  </div>
  <div class="p-12 grid grid-cols-3 gap-6">
    <div class="aspect-square bg-slate-200 rounded-2xl overflow-hidden border border-slate-300">
      <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDnbQw1h5TXiCQQZYn6YHz8vPL-hWA33KNONjvYSLhXPqhB0ZNR-yffWfpch2KL74RgG4CrgkMSWnypYqRZC9X5QjSseytnYFpiufP2IbwvcjRdR03g2KjDs8Fn3PS16L3aWcaAgj5WKG4fcIvhs8Epbcil_PfuUOPuTM-ezm6pE6N3YYeMm8i5iDBtgP2cF_yPdBH_b6H9sCB6YHj3LaZqaIrnFF8wlRe194ZDM57TOMHO_liToobov4hUQEEkMRs_mxCNazzdKyL-"/>
    </div>
    <div class="aspect-square bg-slate-200 rounded-2xl overflow-hidden border border-slate-300">
      <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAk5MUT0uiR1saPKs5zbe7CQL2yXMHXnlsAlbTCxwb3YDlYH-TGY4UympP7cipoqXpxIKh0_ViycjPvePqLbe0MCju_gyw1EFWC3veWsESdSHUjZs1SJ7ZZtj-CTTfKgD8sig1AF5fwqGr9tTDEgHorLC9fCOnfXa-vRzlzekVSDmHP3nqqc5ECNDCqKKnENkcklAQjFQHZm4A96zHBEwD2XOJK_mBGOXmxUNWENLYABe8Bn78fNpLHsZ_9plB7M99s_wqWjIugfY6U"/>
    </div>
    <div class="aspect-square bg-slate-200 rounded-2xl overflow-hidden border border-slate-300">
      <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAFFI8NhwvSiFsQ0cA0GR4HXA5BIVoYweNFzRvbSzFdqVN5kA-vtN_6ZdcCQCr5i3E6OTItXw3oi-pbDt0GMOALTYzAIYwEl9jGE3MamZom-iNTwPahWMzNXTffecT-41YRw0U7Rv277fMFRLK-WyKXWLpLNYG0LPQiGqJd-CE3QMnKNmlMNU05rLmzIxXvU-cp3l47mz9IzSReEhCN9t3LRjcXPZHkaUPdXNNmpBgLrYsAiHoxBuB6YB99nDW811b1hKs6u1tDwfNk"/>
    </div>
  </div>
</div>',
            'css' => '',
            'components' => [],
            'styles' => [],
        ]);

        $project->products()->create([
            'title' => 'Minimalist Desk Lamp',
            'slug' => 'minimalist-desk-lamp',
            'sku' => 'STL-9902-BLK',
            'price' => 129.00,
            'inventory_quantity' => 42,
            'category' => 'Furniture / Lighting',
            'images' => ['https://lh3.googleusercontent.com/aida-public/AB6AXuAAH8sHaDos2oIlXy4ofTRNc0JY6Ow0LA561iVF9cV3urTLV1ge-HwO2GkPOQqRs7t_qr3YKLdvHJYThIAYEYQ-kIMZ8mTHqBnoUtQ9_bHd0Tz0JUMItT7bgf2xLBX2CaOLY58oCjcBJ9DMFbRKXrozYez9_Pfxav92YOaLV16tXkAyrzuUj0OvSyJYYiFCRR-vAGV5PNSaRAcjzfv0ZP4GjrLCFm3xoEM6kd1AhAt9K20bCLBsoV1BLm4XNB9DD4pMmfrItI_fB6f5'],
            'description' => 'A professional studio product shot of a minimalist matte black desk lamp with clean architectural lines, warm ambient lighting against a dark charcoal backdrop, emphasizing pro-grade design aesthetics and high-end materials.',
        ]);

        $project->products()->create([
            'title' => 'Wireless Mechanical Keyboard',
            'slug' => 'wireless-mechanical-keyboard',
            'sku' => 'WMK-X1-PRO',
            'price' => 199.00,
            'inventory_quantity' => 3,
            'category' => 'Electronics / Peripherals',
            'images' => ['https://lh3.googleusercontent.com/aida-public/AB6AXuAgO1AuZmb5Wr276ST0wTJVZaYajhy3h9fv2RDWCS6RyArIzt9PF5NE6w1OUsXkLyF9fuDGW8NTsr3zwv61K_mRlxulTYNVeuIgwxlsYhcQJ_7tjgPheKm6DKvzA5TWrffACVDqpfe0wWHh1lJ5vZuw2y6HqRTwT9O_g_LnYhYleCeWhxOjJJHHpqx03Eez9j756zgtvzpRMFzi2mFxUt816lYfNGmpjQOFffWerTlfQa30OtYaWbW5ykMaP5g_1PulmeFfbni_KT4_'],
            'description' => 'A top-down macro photograph of a sleek wireless mechanical keyboard with low-profile keys and subtle RGB underglow in a custom electric blue. The surface is brushed aluminum set on a premium leather desk mat, capturing a professional creator workstation vibe.',
        ]);

        $project->products()->create([
            'title' => 'Pro Audio Studio Monitor',
            'slug' => 'pro-audio-studio-monitor',
            'sku' => 'AUD-S7-MON',
            'price' => 349.00,
            'inventory_quantity' => 0,
            'category' => 'Electronics / Audio',
            'images' => ['https://lh3.googleusercontent.com/aida-public/AB6AXuCSZmj5TTbVOyEhZELODE_a9vBaCgtOzRQwTNdvY02JU5GmtQlKIl90atXcOgI7BxMU9bmRs-P8043CoiUP_p7Xnx6LQ1NBztZtmwQPD2xwfY5pn8DorT74JXe70yiNaVRHimCYy9r02_ZBkuP9nFcQLLcdSKnfO2SadkP3Dn3NReNqmGfy_xbYTnaSPw2TnsZ31SXLMb_xQwKss6Tf7JRyt-8XyUF1KKGNtYxxIEaDE_zRwisiJo6z03OxlVTE9rdgpWe3QkaMlQ9P'],
            'description' => 'High-end noise-canceling studio headphones with leather earcups and magnesium frame. The photography uses dramatic chiaroscuro lighting to highlight the metallic textures and premium build quality against a dark studio background.',
        ]);

        $project->products()->create([
            'title' => 'Ergonomic Task Chair',
            'slug' => 'ergonomic-task-chair',
            'sku' => 'CHR-M1-GRY',
            'price' => 599.00,
            'inventory_quantity' => 18,
            'category' => 'Furniture / Office',
            'images' => ['https://lh3.googleusercontent.com/aida-public/AB6AXuBq1_BuW2Rm-lNsC8yJI95eav5DvK-o0L6sfwzyujQ-wthG_Lb65jODeWyQvkaFm-qZBzowTTFpZjrZjDCqPuUeGe8yRuNKtkmUIKMEiYkeRK0q6RybH_AtCGLj1re3ZbNYjyRwFjUPCiW08em2fvH0wAp_d4Hyjo_WV1qeCPTXJCLQXe501WscPszrI5kIyhAGAYLSjQTKQvCaWZaUAIvenPEKP6MnG2xE-kuW4MC1h7MW1grORDRUjAmsaAY_JkJLc_UvvdNC3jKq'],
            'description' => 'A minimalist ergonomic task chair with mesh back and polished chrome base. Styled in a contemporary office with soft daylight and high-contrast shadows, emphasizing form and ergonomic function.',
        ]);

        // Seed CMS collections
        $blogCol = $project->collections()->create([
            'name' => 'Blog Posts',
            'slug' => 'blog-posts',
            'fields' => [
                ['name' => 'Item Name', 'type' => 'plain-text', 'key' => 'name'],
                ['name' => 'Author', 'type' => 'plain-text', 'key' => 'author'],
                ['name' => 'Publish Date', 'type' => 'plain-text', 'key' => 'publish_date'],
                ['name' => 'Category', 'type' => 'plain-text', 'key' => 'category'],
                ['name' => 'Main Image', 'type' => 'asset', 'key' => 'image'],
            ],
        ]);

        $teamCol = $project->collections()->create([
            'name' => 'Team',
            'slug' => 'team',
            'fields' => [
                ['name' => 'Name', 'type' => 'plain-text', 'key' => 'name'],
                ['name' => 'Role', 'type' => 'plain-text', 'key' => 'role'],
                ['name' => 'Bio', 'type' => 'rich-text', 'key' => 'bio'],
                ['name' => 'Photo', 'type' => 'asset', 'key' => 'photo'],
            ],
        ]);

        // Seed CMS items
        $blogCol->items()->createMany([
            [
                'slug' => 'future-of-design-systems-in-2025',
                'status' => 'published',
                'data' => [
                    'name' => 'Future of Design Systems in 2025',
                    'author' => 'Alex Rivera',
                    'publish_date' => 'Oct 12, 2024',
                    'category' => 'Design',
                    'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAl894T_XYY6ZOvs0l3ngbcQ3ayUFkrdjoqrdB8qUV8dKL-ixMuL2uxXwXteNRkxK_TFt7bUUOhJf4L-n39xo3mjErfacUr4BjcUx_taaI7-ozXMJp_g5jjBLcD75LVrXR9b8nEB2tqWWuEdAhc5IB23WgM2kRo9b5vDrZg9ZuJ6-rOwn0O7OEqjtV1Qj4uwt7oqODGMnGLngyKJ0ZFRLkP0aq_GddoJ9OIB0UKTgz5u4W9atnpepQ-7LadoAHXM5mKhzKUxgyfr2ki',
                ]
            ],
            [
                'slug' => 'optimizing-webgl-renderers',
                'status' => 'draft',
                'data' => [
                    'name' => 'Optimizing WebGL Renderers',
                    'author' => 'Sarah Chen',
                    'publish_date' => 'Pending',
                    'category' => 'Engineering',
                    'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDvfYiw6V8AeuXyeU2NLvSzH8hQu4qtA863Zw1qmjzZ3Fg3_Z11qiCbX-oxIl8TziaEsDGNjocY-YuNKJPA3yAMe7cKVqqWrKQaba9LMqz8B73vP86vs9PEQwH6IEYYfSLcc2HjsazgLIBwFzEGv-u5H5y-CK0irAkga5t1xMipSU_FxeAqhuZJGMGYNBnXo5qV73qA7twMYm3BflnPIMtnwKLy7udM_ARi5OO8Vo_c_-YbKX5F5YTsOgn6pe2ZqOTeVK6h_iNlRGEh',
                ]
            ],
            [
                'slug' => 'designing-for-emotional-depth',
                'status' => 'published',
                'data' => [
                    'name' => 'Designing for Emotional Depth',
                    'author' => 'Alex Rivera',
                    'publish_date' => 'Oct 01, 2024',
                    'category' => 'Design',
                    'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAyXG1dqwI4SpbLvovtZBXhRlqVw2ayzmwdAQAoQQyOu9y0wv4HSBN1I6v5bXSc1S6Aj4w3EJEBgt0aU7zxiz9pjuLNSgn1_zqNJyeohPhZGo43P-KNxkFu0MpmAygdj7nGTVf0wvNfTHQ_xV6tyDfJ-ZvcqUZI0XMM5fEH-512LBGPE_y38Fd1S3dUfusQBPZ80Ipkf9s2t2qEHB_tnfBdBG0wVrcbwyD83w5Hw87xPBgyPbinDtbX8UVD9xin6ANcrUClnTIY6pAy',
                ]
            ],
            [
                'slug' => 'studiopro-v2-whats-new',
                'status' => 'scheduled',
                'data' => [
                    'name' => "StudioPro V2: What's New?",
                    'author' => 'Jordan Peak',
                    'publish_date' => 'Nov 15, 2024',
                    'category' => 'Announcements',
                    'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBWeWcvdpzMS5FDw8BePn8QnUC1hshWsvU53VQhvtrIGOOQeE0DP7k9acmq08_2bCfTtTxY_FMuNhbsxtiCkyQCIT3wHixVsviYLFZFI2sAwRyDG0_5YSAXn6VKplCDBaqNdN_Ja41v4ozEfJY_4tl2h0ZWdpIoa6XcseIW82MAXUwcyCGNKuMwQ-18t5L-MRKVPjnPT4B42iBDp9KPZ0jnY53gFLAAcfF0DUoznVwsgDgV8bn0fZOCHvN01D38k39CglWXnCmtZ1OI',
                ]
            ]
        ]);

        $teamCol->items()->createMany([
            [
                'slug' => 'alex-rivera',
                'status' => 'published',
                'data' => [
                    'name' => 'Alex Rivera',
                    'role' => 'Principal Designer',
                    'bio' => 'Creative director with 10+ years experience building visual design languages.',
                    'photo' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDnPXAr2i9QgLP0rw4YrtIPrxWSlRFATrdAJJy7p857zKFodbw27-d5Lqql9RD1e26GDA2EO4Q0rlaJ62jaeWwVJ8H4qGWGFwANYcc4056E8AtgD_E6snDUSwKNE-hVFpcnDNtZJlxabhHQ0P9mEN9vY93B72D5RgNNjDAfu1vVzZtwjQ8e40d0r115eIbVRjUuv2tgMjCaXe2AAoAPMTMZ79urXcVlhd1ysmsRiZEED48My8iQkkME5pviUWOrlG-lBzkQgj1cyymd'
                ]
            ],
            [
                'slug' => 'sarah-chen',
                'status' => 'published',
                'data' => [
                    'name' => 'Sarah Chen',
                    'role' => 'Lead Engineer',
                    'bio' => 'WebGL and graphic engine specialist, passionate about high-performance visual tools.',
                    'photo' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDnPXAr2i9QgLP0rw4YrtIPrxWSlRFATrdAJJy7p857zKFodbw27-d5Lqql9RD1e26GDA2EO4Q0rlaJ62jaeWwVJ8H4qGWGFwANYcc4056E8AtgD_E6snDUSwKNE-hVFpcnDNtZJlxabhHQ0P9mEN9vY93B72D5RgNNjDAfu1vVzZtwjQ8e40d0r115eIbVRjUuv2tgMjCaXe2AAoAPMTMZ79urXcVlhd1ysmsRiZEED48My8iQkkME5pviUWOrlG-lBzkQgj1cyymd'
                ]
            ]
        ]);
    }
}
