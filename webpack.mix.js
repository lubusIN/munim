const mix = require('laravel-mix');

const plugins = [require("tailwindcss")];

mix
    .postCss("src/css/tailwind.css", "assets/css", plugins)
    .copy('src/js/', 'assets/js/')
    .copy('src/css/munim.css', 'assets/css');