// @ts-check
import { defineConfig } from 'astro/config';
// @ts-ignore
import tailwindcss from '@tailwindcss/vite';
import sitemap from '@astrojs/sitemap';

// https://astro.build/config
export default defineConfig({
  // ESTA ES LA LÍNEA QUE FALTA
  site: 'https://terraandinahotel.com', 

  devToolbar: {
      enabled: false,
  },

  vite: {
      plugins: [tailwindcss()],
  },

  integrations: [sitemap()],
});