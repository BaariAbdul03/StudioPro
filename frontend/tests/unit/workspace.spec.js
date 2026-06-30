import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useWorkspaceStore } from '../../src/stores/workspace'

describe('Workspace Pinia Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    global.fetch = vi.fn()
  })

  it('initializes state correctly', () => {
    const store = useWorkspaceStore()
    expect(store.zoomLevel).toBe(100)
    expect(store.isUnsaved).toBe(false)
    expect(store.activeLeftTab).toBe('layers')
  })

  it('marks state dirty', () => {
    const store = useWorkspaceStore()
    store.markDirty(true)
    expect(store.isUnsaved).toBe(true)
  })

  it('sets page metadata', () => {
    const store = useWorkspaceStore()
    store.setPageMeta({ seoTitle: 'New Title' })
    expect(store.pageMeta.seoTitle).toBe('New Title')
    expect(store.isUnsaved).toBe(true)
  })

  it('toggles cart drawer', () => {
    const store = useWorkspaceStore()
    store.toggleCartDrawer(true)
    expect(store.isCartDrawerOpen).toBe(true)
  })
})
