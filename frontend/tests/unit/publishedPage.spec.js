import { afterEach, describe, expect, it, vi } from 'vitest'
import { onRequest } from '../../functions/p/[publicId].js'

const id = '764416a0-d90e-4ab9-9f55-4f62147a7894'
const bucket = 'https://br-example.storage.c-1.eu-central-1.aws.neon.tech/published'

function context({ publicId = id, method = 'GET', bucketUrl = bucket } = {}) {
  return {
    request: new Request(`https://studiopro.pages.dev/p/${publicId}`, { method }),
    params: { publicId },
    env: { PUBLISHED_BUCKET_URL: bucketUrl },
  }
}

afterEach(() => vi.unstubAllGlobals())

describe('published page delivery', () => {
  it('streams a release with script-free headers and disables stale caches', async () => {
    const fetch = vi.fn().mockResolvedValue(new Response('<h1>Fieldwork</h1>'))
    vi.stubGlobal('fetch', fetch)

    const response = await onRequest(context({ publicId: id.toUpperCase() }))

    expect(fetch).toHaveBeenCalledWith(`${bucket}/pages/${id}.html`, {
      method: 'GET', redirect: 'manual', cache: 'no-store',
    })
    expect(response.status).toBe(200)
    expect(response.headers.get('Content-Type')).toBe('text/html; charset=utf-8')
    expect(response.headers.get('Content-Security-Policy')).toContain("script-src 'none'")
    expect(response.headers.get('Content-Security-Policy')).toContain(`img-src 'self' ${bucket}/`)
    expect(response.headers.get('Cache-Control')).toBe('no-store')
    expect(await response.text()).toBe('<h1>Fieldwork</h1>')
  })

  it('rejects invalid identifiers, methods, and bucket URLs before fetching', async () => {
    const fetch = vi.fn()
    vi.stubGlobal('fetch', fetch)

    expect((await onRequest(context({ publicId: 'invalid' }))).status).toBe(404)
    expect((await onRequest(context({ method: 'POST' }))).status).toBe(405)
    expect((await onRequest(context({ bucketUrl: 'https://example.com/published' }))).status).toBe(503)
    expect(fetch).not.toHaveBeenCalled()
  })

  it('treats removed releases as missing and hides upstream failures', async () => {
    const fetch = vi.fn()
      .mockResolvedValueOnce(new Response('private upstream error', { status: 404 }))
      .mockResolvedValueOnce(new Response(null, { status: 302, headers: { Location: 'https://example.com/' } }))
      .mockRejectedValueOnce(new Error('internal upstream failure'))
    vi.stubGlobal('fetch', fetch)

    const removed = await onRequest(context())
    expect(removed.status).toBe(404)
    expect(await removed.text()).toBe('Page not found.')

    const redirect = await onRequest(context())
    expect(redirect.status).toBe(503)
    expect(fetch).toHaveBeenCalledTimes(2)

    const unavailable = await onRequest(context({ method: 'HEAD' }))
    expect(unavailable.status).toBe(503)
    expect(await unavailable.text()).toBe('')
  })
})
