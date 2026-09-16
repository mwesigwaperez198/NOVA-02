/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./*.php", "./admin/**/*.php", "./inc/**/*.php", "./assets/**/*.js"],
  theme: {
    extend: {
      colors: {
        'brand-primary': '#ef4444', // Red-500
        'brand-secondary': '#3b82f6', // Blue-500
      },
    },
  },
  plugins: [],
}
