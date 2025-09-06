/** @type {import('tailwindcss').Config} */
export default {
    content: [
      "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
      "./storage/framework/views/*.php",
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
      "./resources/css/editor/editor-support.css",
    ],

  // 👇 Add this safelist so gradient color utilities survive tree-shaking
  safelist: [
    // from-*/via-*/to-* with 500 and opacity 10 or 15
    { pattern: /(from|via|to)-(pink|rose|amber|yellow|emerald|green|sky|blue|indigo|violet)-500\/(10|15)/ },
  ],

  theme: {
    extend: {
      colors: {
        brand: { DEFAULT: "#025d6bff" }, // text-brand / bg-brand
      },
      boxShadow: {
        card: "0 4px 20px rgba(0,0,0,.15)", // shadow-card
      },
    },
  },
  plugins: [
    require("@tailwindcss/typography"),
    require("@tailwindcss/forms"),
  ],
}
