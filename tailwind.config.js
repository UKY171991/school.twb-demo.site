import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                indigo: {
                    50: '#f5f7ff',
                    100: '#ebf0fe',
                    200: '#ced9fd',
                    300: '#adc0fb',
                    400: '#6b8df8',
                    50: '#4f70f5', // This is a bit weird, let's use standard ones or custom
                },
                primary: {
                    DEFAULT: '#4f46e5',
                    dark: '#4338ca',
                },
                secondary: {
                    DEFAULT: '#6366f1',
                    dark: '#4f46e5',
                }
            },
            boxShadow: {
                'premium': '0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02)',
            }
        },
    },

    plugins: [forms],
};
