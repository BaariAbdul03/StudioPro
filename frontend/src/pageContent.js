import DOMPurify from 'dompurify'
import bundledPhotos from './data/bundledPhotos.json'

const photoIds = new Set(bundledPhotos)
export const canvasStyles = ['/template-utilities.css', '/canvas.css'].map(path => path + '?v=' + __STUDIO_CANVAS_VERSION__)

export function localImageSource(source) {
  const match = /^https:\/\/images\.unsplash\.com\/photo-(\d+-[a-f0-9]+)(?:\?[^#]*)?$/i.exec(source)
  const id = match?.[1]?.toLowerCase()
  return photoIds.has(id) ? '/images/template-' + id + '.webp' : source
}

export function sanitizeHtml(html) {
  const markup = (html || '').replace(/(\bsrc=["'])(https:\/\/images\.unsplash\.com\/photo-[^"']+)(?=["'])/gi,
    (_, attribute, source) => attribute + localImageSource(source))
  return DOMPurify.sanitize(markup, {
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
      if (key === 'src' && typeof item === 'string') item = localImageSource(item)
      if (['src', 'href', 'poster', 'xlink:href'].includes(key)
        && typeof item === 'string' && !/^(?:https:\/\/|\/(?!\/)|#|data:image\/(?:png|jpeg|webp|gif);base64,)/i.test(item)) {
        return [key, '']
      }
      return [key, sanitizeProjectData(item)]
    }))
}

export function previewDocument(html, css) {
  const safeCss = String(css || '').replace(/</g, '\\3C ')
  const styles = canvasStyles.map(url => '<link rel="stylesheet" href="' + url + '">').join('')
  return '<!doctype html><html><head><meta name="viewport" content="width=device-width, initial-scale=1">' +
    styles + '<style>' + safeCss + '</style></head><body>' +
    sanitizeHtml(html) + '</body></html>'
}
