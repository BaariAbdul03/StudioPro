export async function apiFetch(path, options = {}) {
  const url = new URL(path, window.location.origin)
  if (url.origin !== window.location.origin || !url.pathname.startsWith('/api/')) {
    throw new Error('Invalid API destination')
  }

  const headers = new Headers(options.headers)
  headers.set('Accept', 'application/json')
  const method = (options.method || 'GET').toUpperCase()
  const mutates = !['GET', 'HEAD', 'OPTIONS'].includes(method)
  if (mutates) {
    const token = readCsrfToken() || await refreshCsrfToken()
    headers.set('X-XSRF-TOKEN', token)
  }

  const send = () => fetch(url, { ...options, headers, credentials: 'same-origin', redirect: 'error' })
  let response = await send()
  if (mutates && response.status === 419) {
    headers.set('X-XSRF-TOKEN', await refreshCsrfToken())
    response = await send()
  }
  if (response.status === 401 && !['/api/user', '/api/login'].includes(url.pathname)) {
    window.location.assign('/login')
  }
  return response
}

async function refreshCsrfToken() {
  const response = await fetch('/sanctum/csrf-cookie', { redirect: 'error' })
  const token = readCsrfToken()
  if (!response.ok || !token) throw new Error('Could not start a session. Try again.')
  return token
}

function readCsrfToken() {
  const cookie = document.cookie.split('; ').find(value => value.startsWith('XSRF-TOKEN='))
  return cookie ? decodeURIComponent(cookie.slice('XSRF-TOKEN='.length)) : ''
}
