const mix = require('laravel-mix');

const plugins = [require("tailwindcss")];

mix.postCss("src/css/tailwind.css", "assets/css", plugins);