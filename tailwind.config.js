/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{html,js,php}"],
  theme: {
    extend: {},
  },
  plugins: [],
};

module.exports = {
  plugins: [require("flowbite/plugin")],
};

module.exports = {
  content: ["./node_modules/flowbite/**/*.js"],
};
