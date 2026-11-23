// Apply the saved scheme before Vue renders, including on sign-in and reload.
(() => {
  try {
    const scheme = localStorage.getItem('studiopro.color-scheme')
    if (scheme === 'dark' || scheme === 'light') {
      document.querySelector('meta[name="color-scheme"]').content = scheme
      document.documentElement.dataset.studioTheme = scheme
    }
  } catch {
    // System appearance is available when browser storage is blocked.
  }
})()
