import { readFile, writeFile } from 'node:fs/promises'
import { fileURLToPath } from 'node:url'
import tailwind from '@tailwindcss/postcss'
import postcss from 'postcss'

const from = fileURLToPath(new URL('./template-tailwind.css', import.meta.url))
const output = new URL('./public/template-utilities.css', import.meta.url)
const input = [
  '@import "tailwindcss/theme.css";',
  '@import "tailwindcss/utilities.css" source(none);',
  '@source "./src/data/templates.js";'
].join('\n')
const result = await postcss([tailwind({ optimize: true })]).process(input, { from })

try {
  if (await readFile(output, 'utf8') === result.css) process.exit(0)
} catch (error) {
  if (error.code !== 'ENOENT') throw error
}
await writeFile(output, result.css)
