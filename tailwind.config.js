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
            colors: {
                maroon: {
                    DEFAULT: '#8B1A1A',
                    dark:    '#6B1414',
                    light:   '#A52020',
                    50:      '#fdf5f5',
                    100:     '#fdf0f0',
                    200:     '#f0e8e8',
                    900:     '#3a0808',
                },
            },
            fontFamily: {
                sans:     ['Nunito', ...defaultTheme.fontFamily.sans],
                playfair: ['Playfair Display', 'serif'],
            },
            keyframes: {
                'marquee-scroll': {
                    '0%':   { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(-50%)' },
                },
                shakeX: {
                    '0%, 100%': { transform: 'translateX(0)' },
                    '20%':      { transform: 'translateX(-8px)' },
                    '40%':      { transform: 'translateX(8px)' },
                    '60%':      { transform: 'translateX(-5px)' },
                    '80%':      { transform: 'translateX(5px)' },
                },
            },
            animation: {
                'marquee': 'marquee-scroll 35s linear infinite',
                'shake':   'shakeX 0.4s ease',
            },
        },
    },

    plugins: [forms],
};
