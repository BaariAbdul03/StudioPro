<template>
  <div class="studio-light site-information">
    <header class="studio-bar">
      <StudioBrand />
      <RouterLink class="studio-button" to="/demo">Try the editor</RouterLink>
    </header>

    <main class="studio-dashboard studio-guide" :key="kind">
      <div class="studio-heading"><h1>{{ document.title }}</h1></div>
      <section v-for="section in document.sections" :key="section.title">
        <h2>{{ section.title }}</h2>
        <p>{{ section.text }}</p>
      </section>
    </main>

  </div>
</template>

<script setup>
import { computed } from 'vue'
import StudioBrand from '../components/StudioBrand.vue'

const props = defineProps({ kind: { type: String, required: true } })
const documents = {
  about: {
    title: 'About us',
    sections: [
      { title: 'How StudioPro started', text: 'StudioPro began as a software assignment and grew into a portfolio project. It gives each account a workspace to assemble, edit, and publish pages in a visual canvas.' },
      { title: 'Explore the project', text: 'Fieldwork is the sample site. Open it in the demo editor to try the same canvas controls the private workspace uses.' }
    ]
  },
  privacy: {
    title: 'Privacy policy',
    sections: [
      { title: 'Visiting and trying the demo', text: 'The public demo requires no account. Edits are saved in browser session storage for the current tab. Use Reset to clear those edits. The hosting provider processes requests to serve the site, and the interface loads fonts from Google Fonts.' },
      { title: 'Using the private workspace', text: 'StudioPro stores your account name, email address, password hash, projects, page versions, and uploads. It uses a session cookie to keep you signed in. Published pages and their released images can be opened by anyone with their URLs.' },
      { title: 'Optional AI providers', text: 'When you choose Gemini, StudioPro sends the relevant prompt, copy, or page content to Google. When you choose Stitch, StudioPro sends your design prompt to Google and creates the screen in the configured Stitch project. Built-in suggestions are processed within StudioPro.' },
      { title: 'Questions about your data', text: 'For workspace data questions, contact the workspace operator. If you found the public demo through a portfolio, use the contact details on that portfolio.' }
    ]
  },
  terms: {
    title: 'Terms & conditions',
    sections: [
      { title: 'Using StudioPro', text: 'The public demo is provided for evaluation. Create an account to use your private workspace and keep your sign-in details secure.' },
      { title: 'Content and publishing', text: 'Upload and publish only content you have permission to use. Review the page and its media before publishing. Anyone with the released URL can view the published page.' },
      { title: 'Availability and exports', text: 'The demo and workspace are provided as available. Keep an exported copy of work you need to retain. Review any generated draft or suggested copy before you use it.' }
    ]
  }
}
const document = computed(() => documents[props.kind])
</script>
