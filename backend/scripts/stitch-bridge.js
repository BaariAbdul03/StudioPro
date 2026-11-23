import { Stitch, StitchToolClient } from '@google/stitch-sdk'

const client = new StitchToolClient({
  apiKey: process.env.STITCH_API_KEY,
  baseUrl: 'https://stitch.googleapis.com/mcp',
  timeout: 115_000
})

try {
  const project = new Stitch(client).project(process.env.STITCH_PROJECT_ID)
  const screen = await project.generate(process.env.STITCH_PROMPT, 'AGNOSTIC')
  const downloadUrl = await screen.getHtml().catch(() => '')
  console.log(JSON.stringify({ screenId: screen.id, downloadUrl }))
} catch {
  process.exitCode = 1
} finally {
  await client.close()
}
