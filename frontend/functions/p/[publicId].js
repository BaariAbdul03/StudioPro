const publicIdPattern = /^[a-f0-9]{8}(?:-[a-f0-9]{4}){3}-[a-f0-9]{12}$/i
const neonStorageHost = /^[a-z0-9-]+\.storage\.c-\d+\.[a-z0-9-]+\.aws\.neon\.tech$/
const bucketPath = /^\/[a-z0-9][a-z0-9.-]{1,61}[a-z0-9]\/?$/

function publishedBucket(value) {
  let url
  try {
    url = new URL(value)
  } catch {
    return null
  }

  if (url.protocol !== 'https:' || !neonStorageHost.test(url.hostname) ||
      url.port || url.username || url.password || url.search || url.hash ||
      !bucketPath.test(url.pathname)) {
    return null
  }

  return url.href.replace(/\/$/, '')
}

function reply(request, status, body, bucket) {
  const imageSources = bucket ? `'self' ${bucket}/` : "'none'"
  const headers = {
    'Cache-Control': 'no-store',
    'Content-Type': status === 200 ? 'text/html; charset=utf-8' : 'text/plain; charset=utf-8',
    'Content-Security-Policy': `default-src 'none'; script-src 'none'; style-src 'unsafe-inline'; img-src ${imageSources}; base-uri 'none'; form-action 'none'; frame-ancestors 'none'`,
    'Referrer-Policy': 'strict-origin-when-cross-origin',
    'Strict-Transport-Security': 'max-age=31536000; includeSubDomains',
    'X-Content-Type-Options': 'nosniff',
    'X-Frame-Options': 'DENY',
  }

  if (status === 405) headers.Allow = 'GET, HEAD'
  return new Response(request.method === 'HEAD' ? null : body, { status, headers })
}

export async function onRequest({ request, params, env }) {
  if (request.method !== 'GET' && request.method !== 'HEAD') {
    return reply(request, 405, 'Method not allowed.')
  }

  if (!publicIdPattern.test(params.publicId)) {
    return reply(request, 404, 'Page not found.')
  }

  const bucket = publishedBucket(env.PUBLISHED_BUCKET_URL)
  if (!bucket) {
    console.warn('Published page storage binding is missing or invalid.')
    return reply(request, 503, 'Published pages are temporarily unavailable.')
  }

  let upstream
  try {
    upstream = await fetch(`${bucket}/pages/${params.publicId.toLowerCase()}.html`, {
      method: request.method,
      redirect: 'manual',
      cache: 'no-store',
    })
  } catch (error) {
    console.warn('Published page origin request failed.', error instanceof Error ? error.message : 'Unknown error')
    return reply(request, 503, 'Published pages are temporarily unavailable.')
  }

  if (upstream.status === 403 || upstream.status === 404) {
    return reply(request, 404, 'Page not found.')
  }

  if (upstream.status !== 200) {
    console.warn('Published page origin returned HTTP', upstream.status)
    return reply(request, 503, 'Published pages are temporarily unavailable.')
  }

  return reply(request, 200, upstream.body, bucket)
}
