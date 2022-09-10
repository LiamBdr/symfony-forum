/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './assets/**/*.{vue,js,jsx,ts,tsx}',
        './templates/**/*.{html,twig}',
        './node_modules/flowbite/**/*.js'
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/line-clamp'),
        require('flowbite/plugin'),
        require('flowbite-typography'),
    ],
}
