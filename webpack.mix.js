const mix = require('laravel-mix');
require('mix-tailwindcss');
require('laravel-mix-purgecss');

mix.postCss('src/css/tailwind.css', 'assets/css')
   .tailwind();

if (mix.inProduction()) {
	mix.purgeCss({
		folders: ['includes'],
	});
}
