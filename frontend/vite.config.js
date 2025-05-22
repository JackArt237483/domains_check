import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
// frontend/tailwind.config.js
/** @type {import('tailwindcss').Config} */


export default {
  content: [
      "./index.html",
      "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
      extend: {},
  },
  plugins: [vue()],
}