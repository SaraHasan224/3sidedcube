const mix = require('laravel-mix');

mix.js('resources/js/admin.js', 'public/js/admin.js');
// Compile main.js and output to public/js
// mix.js('resources/scripts/main.js', 'public/js/main.js');

// Compile init.scss and output to public/css
mix.sass('resources/css/init.scss', 'public/css');

// Compile app.css and output to public/css
mix.postCss('resources/css/app.css', 'public/css/main.css');

// Enable versioning
mix.version();
