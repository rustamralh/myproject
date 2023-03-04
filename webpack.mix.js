// webpack.mix.js

const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'publick/js')
    .vue()
    .postCss('resources/css/app.css', 'public.css', [

    ])
    .version();