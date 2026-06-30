import { test, expect } from '@playwright/test'

test.describe('Visual Editor E2E Workspace Tests', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('http://127.0.0.1:5173/editor/1/1')
  })

  test('should load editor workspace with main panels', async ({ page }) => {
    await expect(page.locator('text="StudioPro"').first()).toBeVisible()
    await expect(page.locator('text="Layers"')).toBeVisible()
    await expect(page.locator('text="Blocks"').first()).toBeVisible()
  })

  test('should allow switching tabs on the left sidebar', async ({ page }) => {
    await page.click('text="Blocks"')
    await expect(page.locator('#blocks-container')).toBeVisible()

    await page.click('text="Assets"')
    await expect(page.locator('text="Media Library"')).toBeVisible()
  })

  test('should open AI modal assistant', async ({ page }) => {
    await page.click('button[title="Generate page with AI"]')
    await expect(page.locator('text="Generate with Google Stitch"')).toBeVisible()
    await page.click('button:has-text("Cancel")')
  })

  test('should support style manager controls', async ({ page }) => {
    await expect(page.locator('text="Element Properties"')).toBeVisible()
    await page.click('span.material-symbols-outlined:has-text("palette")')
    await expect(page.locator('text="Layout"').first()).toBeVisible()
    await expect(page.locator('text="Spacing"').first()).toBeVisible()
  })
})
