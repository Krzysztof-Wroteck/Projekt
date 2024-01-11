const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/delete.js', 'public/js')
   .js('resources/js/like.js', 'public/js')
   .js('resources/js/deleteCom.js', 'public/js')
   .js('resources/js/shere.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');
