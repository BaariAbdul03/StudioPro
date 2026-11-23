import DOMPurify from 'dompurify'

export function sanitizeHtml(html) {
  return DOMPurify.sanitize(html || '', {
    USE_PROFILES: { html: true },
    FORBID_TAGS: ['form', 'iframe', 'link', 'meta', 'base', 'style'],
    FORBID_ATTR: ['srcdoc', 'action', 'formaction', 'srcset', 'target', 'data-code-html', 'data-code-js', 'data-code-css']
  })
}

export function sanitizeProjectData(value) {
  if (Array.isArray(value)) return value.map(sanitizeProjectData)
  if (!value || typeof value !== 'object') return value

  return Object.fromEntries(Object.entries(value)
    .filter(([key]) => !/^(?:on|script(?:$|-)|__proto__$|constructor$|prototype$)/i.test(key)
      && !['srcdoc', 'formaction', 'data-code-js', 'data-code-html', 'data-code-css'].includes(key))
    .map(([key, item]) => {
      if (key === 'tagName' && !/^(?!(?:script|iframe|embed|object|link|meta|base|style|svg|math)$)[a-z][a-z0-9-]*$/i.test(item)) {
        return [key, 'div']
      }
      if (key === 'type' && ['code-embed', 'iframe', 'script'].includes(item)) return [key, 'default']
      if (['content', 'components'].includes(key) && typeof item === 'string') return [key, sanitizeHtml(item)]
      if (['src', 'href', 'poster', 'xlink:href'].includes(key)
        && typeof item === 'string' && !/^(?:https:\/\/|\/(?!\/)|#|data:image\/(?:png|jpeg|webp|gif);base64,)/i.test(item)) {
        return [key, '']
      }
      return [key, sanitizeProjectData(item)]
    }))
}

export function previewDocument(html, css) {
  const safeCss = String(css || '').replace(/</g, '\\3C ')
  return '<!doctype html><html><head><meta name="viewport" content="width=device-width, initial-scale=1">' +
    '<link rel="stylesheet" href="/canvas.css"><style>' + safeCss + '</style></head><body>' +
    sanitizeHtml(html) + '</body></html>'
}
