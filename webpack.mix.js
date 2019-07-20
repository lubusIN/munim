const mix = require('laravel-mix');

mix.postCss('src/css/style.css', 'assets/css', [
    require('tailwindcss'),
]);
