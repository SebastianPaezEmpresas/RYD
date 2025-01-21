import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import aspectRatio from '@tailwindcss/aspect-ratio';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',  // Agregado para soportar Vue si se usa en el proyecto
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#4F46E5',
                secondary: '#9333EA',
                accent: '#F59E0B',
                neutral: '#64748B', // Añadido color neutral para más flexibilidad
            },
            boxShadow: {
                custom: '0 10px 20px rgba(0, 0, 0, 0.1)',
                soft: '0 4px 6px rgba(0, 0, 0, 0.05)',  // Nueva sombra más suave
            },
            spacing: {
                '128': '32rem',
                '144': '36rem',  // Espaciados adicionales personalizados
            },
            borderRadius: {
                xl: '1.5rem',
            },
        },
    },

    plugins: [
        forms,
        typography,
        aspectRatio,
    ],
};
