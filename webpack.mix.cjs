const config = await import('/home/mirit/twitter-podr/webpack.mix.js');
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');