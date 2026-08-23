/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './index.html',
    './src/**/*.{js,ts,jsx,tsx}',
  ],
  theme: {
    extend: {
      colors: {
        unerg: {
          blue: '#003366',
          dark: '#002244',
          gold: '#c59b27',
          light: '#f0f4f8',
        }
      }
    },
  },
  plugins: [],
};
