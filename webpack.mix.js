const mix = require('laravel-mix');

const plugins = [require("tailwindcss")];

mix
    .postCss("src/css/tailwind.css", "assets/css", plugins)
    .copy('src/js/**/*', 'assets')
    .copy('src/css/munim.css', 'assets/css');