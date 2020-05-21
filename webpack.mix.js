const mix = require('laravel-mix');

const plugins = [require("tailwindcss")];

const js_assets = [
    'assets/js/dashboard.js',
    'assets/js/script.js',
    'assets/js/clipboard.js'
];

mix
    .postCss("src/css/tailwind.css", "assets/css", plugins)
    .copy('src/css/munim.css', 'assets/css')
    .copy('node_modules/clipboard/dist/clipboard.js', 'src/js')
    .copyDirectory('src/js', 'assets/js/')
    .minify(js_assets);