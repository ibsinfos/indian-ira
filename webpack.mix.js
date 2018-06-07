let mix = require('laravel-mix');

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

// mix.js('resources/assets/js/app.js', 'public/js')
//    .sass('resources/assets/sass/app.scss', 'public/css');

mix.sass('resources/assets/sass/app.scss', 'public/css');

mix.js([
    'resources/assets/js/app.js',
    'resources/assets/js/character-maxlength.js',
    'resources/assets/libs/iGrowl/javascripts/igrowl.js'
], 'public/js/app.js');

mix.styles([
    'public/css/app.css',
    'resources/assets/libs/iGrowl/stylesheets/animate.css',
    'resources/assets/libs/iGrowl/stylesheets/igrowl.css',
    'resources/assets/libs/iGrowl/stylesheets/icomoon/feather.css',
], 'public/css/app.css');

mix.copyDirectory('resources/assets/libs/iGrowl/fonts/feather', 'public/fonts/feather');
