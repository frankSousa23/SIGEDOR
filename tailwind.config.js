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
          'blue-dark': '#002244',
          'blue-light': '#0a4b8f',
          gold: '#D4AF37',
          'gold-light': '#F3E5AB',
          'gold-dark': '#AA820A',
        }
      }
    },
  },
  plugins: [],
};
