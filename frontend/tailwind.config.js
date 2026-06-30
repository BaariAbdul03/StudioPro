/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#2e62ff',
        'surface': '#0b1326',
        'surface-container': '#121b2e',
        'surface-bright': '#1a243d',
        'outline': '#2d3a54',
        'on-surface': '#ffffff',
        'on-surface-variant': '#94a3b8',
        // Keeping editor-bg for the literal GrapeJS workspace if needed, otherwise it falls back to surface
        'editor-bg': '#0B0E14',
      },
      fontFamily: {
        geist: ['Geist', 'sans-serif'],
        mono: ['JetBrains Mono', 'monospace'],
      },
      fontSize: {
        'display-xl': ['48px', { lineHeight: '1.1', fontWeight: '700' }],
        'headline-lg': ['24px', { lineHeight: '1.2', fontWeight: '600' }],
        'title-md': ['18px', { lineHeight: '1.4', fontWeight: '500' }],
        'label-sm': ['13px', { lineHeight: '1.5', fontWeight: '500' }],
        'mono-xs': ['12px', { lineHeight: '1.5', fontWeight: '400' }],
      }
    },
  },
  plugins: [],
}
