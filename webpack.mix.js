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

mix.styles([
    'public/theme/gaukmotors/assets/css/bootstrap.css',
    'public/theme/gaukmotors/assets/css/bootstrap-theme.css',
    'public/theme/gaukmotors/assets/css/iconmoon.css',
    'public/theme/4/assets/css/icons/font-awesome/css/font-awesome.min.css',
    'public/assets/css/icons/fontawesome5/css/fontawesome-all.min.css',
    'public/theme/gaukmotors/assets/css/chosen.css',
    'public/theme/gaukmotors/assets/css/style.css',
    'public/theme/gaukmotors/assets/css/cs-automobile-plugin.css',
    'public/theme/gaukmotors/assets/css/color.css',
    'public/theme/gaukmotors/assets/css/widget.css',
    'public/theme/gaukmotors/assets/css/responsive.css',
    'public/assets/css/gsi-step-indicator.css',
    'public/assets/css/core.css',
    'public/assets/css/ouibounce.min.css',
    'public/assets/css/public.css',
    'public/theme/gaukmotors/assets/css/register.css',
    'public/assets/css/vehicles.css'
], 'public/assets/css/gauk.css').version();

mix.styles([
    'public/assets/css/members.css'
], 'public/assets/css/gaukMembers.css').version();


mix.scripts([
    'public/theme/gaukmotors/assets/scripts/jquery.js',
    'public/theme/gaukmotors/assets/scripts/modernizr.js',
    'public/theme/gaukmotors/assets/scripts/bootstrap.min.js',
    'public/theme/gaukmotors/assets/scripts/responsive.menu.js',
    'public/theme/gaukmotors/assets/scripts/chosen.select.js',
    'public/theme/gaukmotors/assets/scripts/slick.js',
    'public/theme/gaukmotors/assets/scripts/echo.js',
    'public/theme/gaukmotors/assets/scripts/functions.js',
    'public/assets/js/NavTabs.js'
], 'public/assets/js/gauk.js').version();
