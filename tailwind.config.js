import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                purple: {
                    50: '#F7F1FA',
                    100: '#EEE1F5',
                    200: '#D9BEEA',
                    300: '#C093DA',
                    400: '#A468C4',
                    500: '#8347A8',
                    600: '#6C3690',
                    700: '#5B2C83', // brand primary
                    800: '#47225F',
                    900: '#331942',
                    950: '#1F0F28',
                },
                gold: {
                    50: '#FDF9EC',
                    100: '#FBF1D2',
                    200: '#F6E2A3',
                    300: '#EFCE6E',
                    400: '#E4BC4F',
                    500: '#D4AF37', // brand secondary
                    600: '#B08A25',
                    700: '#8C6A1D',
                    800: '#6B5016',
                    900: '#4D3A10',
                    950: '#2E220A',
                },
            },
        },
    },

    plugins: [forms],
};
