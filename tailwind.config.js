import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    DEFAULT: '#5B2C83',
                    50: '#f3edf8',
                    100: '#e4d5ef',
                    200: '#c9abdf',
                    300: '#ad82ce',
                    400: '#8f58b8',
                    500: '#5B2C83',
                    600: '#4f2571',
                    700: '#421e5e',
                    800: '#35174a',
                    900: '#281137',
                },
                gold: {
                    DEFAULT: '#D4AF37',
                    50: '#fbf7e9',
                    100: '#f5eaC4',
                    200: '#ecd68c',
                    300: '#e2c162',
                    400: '#d9b74a',
                    500: '#D4AF37',
                    600: '#ab8c2c',
                    700: '#826921',
                    800: '#594716',
                    900: '#33290d',
                },
            },
        },
    },

    plugins: [forms],
};
