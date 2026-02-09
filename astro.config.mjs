// @ts-check
import { defineConfig } from 'astro/config';
// @ts-ignore
import tailwindcss from '@tailwindcss/vite';
import sitemap from '@astrojs/sitemap';

// https://astro.build/config
export default defineConfig({
  site: 'https://fti-weeb.netlify.app', 

  output: 'server',
  devToolbar: {
      enabled: false,
  },

  vite: {
      plugins: [tailwindcss()],
  },

  integrations: [sitemap()],
});