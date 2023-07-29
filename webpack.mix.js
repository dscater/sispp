const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.setPublicPath('./')
    .js('resources/js/app.js', './js')
    .vue()
    .sass('resources/sass/app.scss', './css')
    .styles([
        'resources/assets/css/all.min.css',
        'resources/assets/css/icheck-bootstrap.css',
        'resources/assets/css/adminlte.css',
        'resources/assets/css/config.css'
    ], './css/plantilla.css')
    .scripts([
        'resources/assets/js/adminlte.min.js',
        'resources/assets/js/demo.js',
    ], './js/plantilla.js')
    .copy('resources/assets/imgs', './imgs')
    .copy('resources/assets/webfonts', './webfonts');