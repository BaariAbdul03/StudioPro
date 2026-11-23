import { expect, test } from '@playwright/test'

const originalHeading = 'Built for the quiet hours.'
const editedHeading = 'Hillside House at dusk.'

test('portfolio opens a finished sample before loading the editor', async ({ page }) => {
  const requests = []
  page.on('request', request => requests.push(new URL(request.url()).pathname))

  await page.goto('/')
  await expect(page.getByRole('heading', { name: 'Build a page. Make it yours.' })).toBeVisible()
  await expect(page).toHaveTitle('StudioPro — Visual Page Builder')
  await page.getByRole('link', { name: 'View sample', exact: true }).click()

  const sampleFrame = page.locator('iframe[title="Fieldwork sample page"]')
  await expect(sampleFrame).toHaveAttribute('sandbox', '')
  await expect(sampleFrame.contentFrame().getByRole('heading', { name: originalHeading })).toBeVisible()
  await expect(page).toHaveTitle('Fieldwork — StudioPro Sample')
  expect(requests.some(path => /\/api\/|grapesjs|\/assets\/Editor-|\/src\/views\/Editor\.vue/.test(path))).toBe(false)
})

test('visitor edits survive a reload, can be previewed on mobile, and reset in the tab', async ({ page }) => {
  const backendRequests = []
  await page.route(/\/(api|sanctum)\//, async route => {
    backendRequests.push(route.request().url())
    await route.abort()
  })

  await page.goto('/demo')
  const frame = page.locator('#gjs-container iframe').first()
  const canvas = frame.contentFrame()
  const original = canvas.getByRole('heading', { name: originalHeading })
  await expect(original).toBeVisible()
  await expect(page.getByRole('button', { name: /publish|upload|sign in/i })).toHaveCount(0)

  const textEditor = canvas.locator('h1')
  await textEditor.click()
  await page.getByRole('button', { name: 'Isolate selection' }).click()
  const secondHeading = canvas.getByRole('heading', { name: 'A home that follows the land.', includeHidden: true })
  await expect(secondHeading).toBeHidden()
  await textEditor.dblclick()
  await expect(textEditor).toBeEditable()
  await textEditor.fill(editedHeading)
  await page.getByRole('button', { name: 'Exit isolation' }).click()
  await expect(secondHeading).toBeVisible()
  await canvas.getByRole('heading', { name: 'A home that follows the land.' }).click()
  await expect(canvas.getByRole('heading', { name: editedHeading })).toBeVisible()

  await expect.poll(() => page.evaluate(() => sessionStorage.getItem('studiopro.demo.v1'))).toContain(editedHeading)
  expect(await page.evaluate(() => sessionStorage.getItem('studiopro.demo.v1'))).not.toContain('data-studio-isolation-hidden')
  await page.reload()
  await expect(canvas.getByRole('heading', { name: editedHeading })).toBeVisible()

  await page.getByRole('button', { name: /^mobile$/i }).click()
  await expect.poll(() => frame.evaluate(iframe => iframe.clientWidth)).toBeLessThan(500)
  await page.getByRole('button', { name: 'Preview', exact: true }).click()
  await expect(canvas.getByRole('heading', { name: editedHeading })).toBeVisible()
  await page.getByRole('button', { name: 'Exit preview', exact: true }).click()

  const openReview = page.getByRole('button', { name: 'Responsive review' })
  await openReview.click()
  const review = page.getByRole('dialog', { name: 'Responsive review' })
  await expect(review.getByText('One main heading')).toBeVisible()
  for (const [device, width] of [['Desktop', 1280], ['Tablet', 768], ['Mobile', 390]]) {
    const preview = review.getByTitle(device + ' draft preview', { exact: true })
    await expect(preview).toHaveAttribute('sandbox', '')
    expect(await preview.evaluate(iframe => iframe.clientWidth)).toBe(width)
    await expect(preview.contentFrame().getByRole('heading', { name: editedHeading })).toBeVisible()
  }
  await page.keyboard.press('Escape')
  await expect(review).toBeHidden()
  await expect(openReview).toBeFocused()

  await page.getByRole('button', { name: 'Reset', exact: true }).click()
  await page.getByRole('button', { name: 'Reset sample', exact: true }).click()
  await expect(original).toBeVisible()
  expect(backendRequests).toEqual([])
})


test('public information and FAQ fit a small screen and have reachable error recovery', async ({ page }) => {
  await page.setViewportSize({ width: 320, height: 720 })
  await page.goto('/')
  const question = page.locator('summary').filter({ hasText: 'Do I need an account' })
  await question.focus()
  await question.press('Enter')
  await expect(page.getByText('No. Open the sample editor', { exact: false })).toBeVisible()
  await expect(page.locator('link[rel="icon"]')).toHaveAttribute('href', '/favicon.svg')
  expect(await page.evaluate(() => document.documentElement.scrollWidth)).toBe(320)

  for (const title of ['About us', 'Privacy policy', 'Terms & conditions']) {
    await page.getByRole('navigation', { name: 'Site information' }).getByRole('link', { name: title }).click()
    await expect(page.getByRole('heading', { name: title })).toBeVisible()
    expect(await page.evaluate(() => document.documentElement.scrollWidth)).toBe(320)
  }

  await page.goto('/missing-page')
  await expect(page.getByRole('heading', { name: 'Page not found' })).toBeVisible()
  await page.getByRole('link', { name: 'Go home' }).click()
  await expect(page.getByRole('heading', { name: /Build a page/ })).toBeVisible()
})
