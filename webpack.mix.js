const mix = require('laravel-mix');

const plugins = [require("tailwindcss")];

mix
    .postCss("src/css/tailwind.css", "assets/css", plugins)
    .copy('src/js/dashboard.js', 'assets/js/dashboard.js').minify('assets/js/dashboard.js')
    .copy('src/js/script.js', 'assets/js/script.js').minify('assets/js/script.js')
    .copy('src/css/munim.css', 'assets/css')
    .copy('node_modules/clipboard/dist/clipboard.min.js', 'assets/js');
