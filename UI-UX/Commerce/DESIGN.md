---
name: Pro-Grade Minimalist
colors:
  surface: '#0b1326'
  surface-dim: '#0b1326'
  surface-bright: '#31394d'
  surface-container-lowest: '#060e20'
  surface-container-low: '#131b2e'
  surface-container: '#171f33'
  surface-container-high: '#222a3d'
  surface-container-highest: '#2d3449'
  on-surface: '#dae2fd'
  on-surface-variant: '#c3c5d8'
  inverse-surface: '#dae2fd'
  inverse-on-surface: '#283044'
  outline: '#8d90a2'
  outline-variant: '#434656'
  surface-tint: '#b7c4ff'
  primary: '#b7c4ff'
  on-primary: '#002682'
  primary-container: '#2e62ff'
  on-primary-container: '#f7f6ff'
  inverse-primary: '#024cec'
  secondary: '#d0bcff'
  on-secondary: '#3c0091'
  secondary-container: '#571bc1'
  on-secondary-container: '#c4abff'
  tertiary: '#4cd7f6'
  on-tertiary: '#003640'
  tertiary-container: '#007c91'
  on-tertiary-container: '#e9f9ff'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#dce1ff'
  primary-fixed-dim: '#b7c4ff'
  on-primary-fixed: '#001552'
  on-primary-fixed-variant: '#0039b5'
  secondary-fixed: '#e9ddff'
  secondary-fixed-dim: '#d0bcff'
  on-secondary-fixed: '#23005c'
  on-secondary-fixed-variant: '#5516be'
  tertiary-fixed: '#acedff'
  tertiary-fixed-dim: '#4cd7f6'
  on-tertiary-fixed: '#001f26'
  on-tertiary-fixed-variant: '#004e5c'
  background: '#0b1326'
  on-background: '#dae2fd'
  surface-variant: '#2d3449'
  editor-bg: '#0B0E14'
  panel-surface: '#161B22'
  canvas-bg: '#1F2937'
  electric-blue: '#2E62FF'
  royal-purple: '#8B5CF6'
  success-green: '#10B981'
  error-red: '#EF4444'
  warning-amber: '#F59E0B'
typography:
  headline-lg:
    fontFamily: Geist
    fontSize: 32px
    fontWeight: '600'
    lineHeight: '1.2'
    letterSpacing: -0.02em
  headline-md:
    fontFamily: Geist
    fontSize: 24px
    fontWeight: '600'
    lineHeight: '1.3'
    letterSpacing: -0.01em
  headline-sm:
    fontFamily: Geist
    fontSize: 18px
    fontWeight: '500'
    lineHeight: '1.4'
  body-lg:
    fontFamily: Geist
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontFamily: Geist
    fontSize: 14px
    fontWeight: '400'
    lineHeight: '1.5'
  body-sm:
    fontFamily: Geist
    fontSize: 13px
    fontWeight: '400'
    lineHeight: '1.5'
  label-md:
    fontFamily: Geist
    fontSize: 12px
    fontWeight: '500'
    lineHeight: '1'
    letterSpacing: 0.02em
  label-sm:
    fontFamily: Geist
    fontSize: 11px
    fontWeight: '600'
    lineHeight: '1'
    letterSpacing: 0.05em
  mono-label:
    fontFamily: JetBrains Mono
    fontSize: 11px
    fontWeight: '400'
    lineHeight: '1'
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  base: 4px
  xs: 4px
  sm: 8px
  md: 12px
  lg: 16px
  xl: 24px
  2xl: 32px
  3xl: 48px
  4xl: 64px
  panel-width: 280px
  toolbar-height: 48px
---

## Brand & Style

The design system is engineered for high-performance creative workflows, embodying a **Pro-grade Minimalist** aesthetic. It prioritizes technical precision, clarity, and tool-like utility. The application is a "quiet" environment—it recedes into the background to allow the user’s work-in-progress to remain the focal point.

The visual language draws from **Modern SaaS** and **Systematic Minimalism**, utilizing deep neutral tones to create a sense of focused immersion. The personality is authoritative and expert, designed for power users who value density and pixel-perfect control. Visual hierarchy is established through meticulous spacing and subtle tonal shifts rather than decorative elements.

## Colors

The palette is optimized for long-duration focus. The primary **Editor UI** uses a dark mode configuration to reduce eye strain and provide high contrast against the "Electric Blue" accent color used for interactive handles and selections.

- **Primary (Electric Blue):** Reserved for primary actions, active selection states, and focus indicators.
- **Secondary (Royal Purple):** Used for advanced features, hover states on complex components, and secondary highlights.
- **Neutral:** A deep navy-slate scale. The background (`#0B0E14`) provides the foundation, while surfaces (`#161B22`) and borders (`#334155`) create structure.
- **State Colors:** Success, Error, and Warning colors are used sparingly for validation and system status messages.
- **Dashboard Note:** While the editor is dark, the administrative dashboard should transition to a "Light/Clean" mode using the same accent colors but substituting the dark neutrals for white (`#FFFFFF`) and light grays (`#F8FAFC`).

## Typography

This design system utilizes **Geist** for its technical precision and high legibility at small sizes. Typography is used to communicate hierarchy through weight and casing rather than massive scale shifts.

- **Labels:** Use `label-sm` for sidebar panel headers and property names to maximize density.
- **Inputs:** Use `mono-label` for numeric values (width, height, padding) to ensure character alignment.
- **Headlines:** Reserved for dashboard views and top-level settings modals. 
- **Canvas Default:** While the UI uses Geist, the default font for the user's canvas is Inter, providing a neutral starting point for web content.

## Layout & Spacing

The system follows a strict **4px-based spacing rhythm**. This granularity allows for the high density required in design tools. 

- **Layout Model:** A three-panel fixed architecture. Left Sidebar (Navigator/Layers), Right Sidebar (Style/Settings), and Top Toolbar. The central Canvas is fluid.
- **Breakpoints:** The editor itself is fixed for desktop efficiency. The canvas within the editor uses responsive triggers: Desktop (1440px), Tablet (768px), and Mobile (375px).
- **Density:** Use `sm` (8px) for internal component padding and `md` (12px) for gaps between functional groups. Panels use a fixed `panel-width` to ensure predictable canvas real estate.

## Elevation & Depth

Hierarchy is defined through **Tonal Layers** and **Low-Contrast Outlines**. Deep shadows are avoided in favor of subtle border separations to maintain a flat, tool-like feel.

- **Level 0 (Background):** The base editor shell (`#0B0E14`).
- **Level 1 (Panels):** Sidebars and toolbars use a slightly lighter surface (`#161B22`) with a 1px solid border (`#334155`).
- **Level 2 (Popovers/Modals):** Floating menus and dropdowns use the most elevated surface (`#1F2937`) with a soft, 12px ambient shadow (Black, 40% opacity) and a high-contrast border.
- **Glassmorphism:** Use backdrop-blur (8px) sparingly on top-level navigation bars to provide a sense of depth without distracting from the canvas content.

## Shapes

The shape language is **Soft** (4px radius). This maintains a professional, geometric look while feeling modern. 

- **Small Components:** Buttons, inputs, and checkboxes use `rounded` (4px).
- **Containers:** Side panels and toolbars are sharp (0px) where they meet the screen edge, but use `rounded-lg` (8px) for internal cards or floating UI elements.
- **Selection Handles:** Canvas handles for resizing elements are 6x6px squares with a 1px radius to emphasize precision.

## Components

- **Buttons:** 
  - *Primary:* Solid Electric Blue, white text.
  - *Secondary:* Ghost style with 1px border.
  - *Icon-only:* Used extensively in toolbars; 32x32px hit area with 20px icons.
- **Input Fields:**
  - Compact height (28px).
  - Background: `#0B0E14`; Border: `#334155`.
  - Focus State: 1px solid Electric Blue with a subtle outer glow.
- **Layers List:**
  - High-density rows (24px height).
  - Hover: Background `#1F2937`.
  - Active: Background Electric Blue (15% opacity) with a left-edge 2px blue accent line.
- **Style Manager Accordions:**
  - Flat headers with `label-sm` typography. 
  - Use 12px chevron icons for expansion.
- **Selection Box:**
  - 1px Electric Blue outline around active canvas elements.
  - Small square handles at corners and mid-points.
- **Chips:**
  - Small, pill-shaped tags for CSS classes. 
  - Background: `#334155`; Text: Geist Mono 10px.