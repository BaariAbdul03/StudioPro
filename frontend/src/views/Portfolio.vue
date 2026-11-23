<template>
  <div class="portfolio">
    <header class="portfolio-nav">
      <StudioBrand />
      <nav aria-label="Main navigation">
        <a href="#faq">FAQ</a>
        <RouterLink to="/showcase">Sample page</RouterLink>
        <RouterLink class="portfolio-nav-action" to="/demo">Open editor <span aria-hidden="true">↗</span></RouterLink>
      </nav>
    </header>

    <main class="portfolio-main">
      <section class="portfolio-hero" aria-labelledby="portfolio-heading">
        <div class="portfolio-copy">
          <h1 id="portfolio-heading">Build a page.<br><span>Make it yours.</span></h1>
          <p>StudioPro is a visual page builder. Start with a finished design, edit any component, and review desktop, tablet, and mobile layouts together.</p>
          <div class="portfolio-actions">
            <RouterLink class="portfolio-primary" to="/demo">Try the editor <span aria-hidden="true">→</span></RouterLink>
            <RouterLink class="portfolio-secondary" to="/showcase">View sample</RouterLink>
          </div>
          <small>No sign-in. Your edits stay in this tab.</small>
        </div>

        <RouterLink class="portfolio-preview" to="/showcase" aria-label="View the Fieldwork sample page">
          <div class="portfolio-preview-bar">
            <span>Page preview</span>
            <span>fieldwork / home</span>
            <span aria-hidden="true">↗</span>
          </div>
          <div class="portfolio-preview-page" aria-hidden="true">
            <div class="portfolio-preview-name">Fieldwork</div>
            <div class="portfolio-preview-content">
              <div class="portfolio-preview-copy">
                <span>Residential architecture</span>
                <strong>Built for the quiet hours.</strong>
              </div>
              <img src="/images/arch_hero_01.png" alt="" width="1024" height="1024" fetchpriority="high">
            </div>
          </div>
        </RouterLink>
      </section>
      <section id="faq" class="portfolio-faq" aria-labelledby="faq-heading">
        <h2 id="faq-heading">Frequently asked questions</h2>
        <div>
          <details>
            <summary>Do I need an account to try the editor?</summary>
            <p>No. Open the sample editor, change the text or move a block, and try a different preview width.</p>
          </details>
          <details>
            <summary>Where are my demo edits saved?</summary>
            <p>They stay in this browser tab and survive a reload. Select Reset to start again from the original sample.</p>
          </details>
          <details>
            <summary>Can I publish from the demo?</summary>
            <p>Publishing and ZIP export are available in the private workspace. The public demo lets you edit and preview a copy of the sample.</p>
          </details>
          <details>
            <summary>What can the AI tools do?</summary>
            <p>When AI is enabled, workspace owners can generate a draft with Gemini or Google Stitch, rewrite copy, and suggest SEO metadata.</p>
          </details>
        </div>
      </section>
    </main>
    <SiteFooter />
  </div>
</template>

<script setup>
import SiteFooter from '../components/SiteFooter.vue'
import StudioBrand from '../components/StudioBrand.vue'
import { onMounted, onUnmounted } from 'vue'

const title = 'StudioPro — Visual Page Builder'
onMounted(() => { document.title = title })
onUnmounted(() => {
  if (document.title === title) document.title = 'StudioPro'
})
</script>

<style scoped>
.portfolio {
  min-height: 100svh;
  background: #f3f5fa;
  color: #171b2c;
  font-family: Geist, Arial, sans-serif;
}
.portfolio-nav,
.portfolio-main {
  width: min(100%, 1360px);
  margin-inline: auto;
  padding-inline: clamp(20px, 5.4vw, 78px);
}
.portfolio-nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-height: 72px;
  gap: 16px;
  border-bottom: 1px solid #d6dbe8;
}
.portfolio-nav nav,
.portfolio-actions {
  display: flex;
  gap: clamp(14px, 3vw, 30px);
  align-items: center;
  flex-wrap: wrap;
}
.portfolio-nav nav { font-size: 14px; font-weight: 600; }
.portfolio-nav-action,
.portfolio-secondary { color: #304dce; }
.portfolio-hero {
  display: grid;
  grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr);
  align-items: center;
  gap: clamp(28px, 4vw, 64px);
  padding-block: clamp(54px, 9vw, 120px);
}
.portfolio-copy h1 {
  margin: 0;
  font-size: clamp(46px, 6.1vw, 92px);
  line-height: .99;
  font-weight: 730;
  letter-spacing: -.087em;
}
.portfolio-copy h1 span { color: #304dce; }
.portfolio-copy p {
  margin: 26px 0;
  max-width: 390px;
  color: #4d556a;
  font-size: clamp(15px, 1.5vw, 19px);
  line-height: 1.65;
}
.portfolio-actions { gap: 20px; font-weight: 650; font-size: 14px; }
.portfolio-primary {
  display: inline-flex;
  align-items: center;
  gap: 16px;
  padding: 13px 17px;
  border-radius: 8px;
  background: #304dce;
  color: white;
}
.portfolio-primary:hover { background: #223ba9; }
.portfolio-secondary:hover,
.portfolio-nav a:hover { text-decoration: underline; text-underline-offset: 4px; }
.portfolio-copy small { display: block; margin-top: 17px; color: #555e71; font-size: 13px; }
.portfolio-preview {
  display: block;
  padding: 8px;
  border: 1px solid #d6dbe8;
  border-radius: 14px;
  background: #fff;
  box-shadow: 0 20px 48px #1b284715;
  overflow: hidden;
}
.portfolio-preview:hover { border-color: #304dce; }
.portfolio-preview-bar {
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  align-items: center;
  gap: 8px;
  min-height: 39px;
  padding-inline: 8px;
  color: #4d556a;
  font-size: 12px;
  font-weight: 600;
}
.portfolio-preview-bar span:nth-child(2) { font-family: monospace; font-weight: 400; }
.portfolio-preview-bar span:last-child { text-align: right; }
.portfolio-preview-page {
  background: #f3f0e7;
  color: #252820;
  border-radius: 7px;
  overflow: hidden;
}
.portfolio-preview-name {
  padding: 14px 6%;
  border-bottom: 1px solid #c9c7b9;
  font: 19px Georgia, serif;
}
.portfolio-preview-content {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1.15fr);
  align-items: center;
  gap: clamp(12px, 3vw, 34px);
  padding: 8% 6%;
}
.portfolio-preview-content img { display: block; width: 100%; height: auto; }
.portfolio-preview-copy span {
  display: block;
  margin-bottom: 9px;
  font-size: clamp(8px, .8vw, 11px);
  font-weight: 700;
  letter-spacing: .09em;
  text-transform: uppercase;
}
.portfolio-preview-copy strong {
  display: block;
  font: 400 clamp(18px, 3.5vw, 46px)/.99 Georgia, serif;
  letter-spacing: -.065em;
}
.portfolio a:focus-visible { outline: 2px solid #304dce; outline-offset: 4px; }
@media (max-width: 800px) {
  .portfolio-hero { grid-template-columns: minmax(0, 1fr); }
  .portfolio-preview { max-width: 600px; }
  .portfolio-preview-copy strong { font-size: clamp(22px, 6vw, 46px); }
}
@media (max-width: 520px) {
  .portfolio-nav { flex-wrap: wrap; padding-block: 12px; }
  .portfolio-nav nav { gap: 12px; }
  .portfolio-nav nav a { padding-block: 5px; }
}
</style>
