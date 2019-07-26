const mix = require('laravel-mix');
const WebpackShellPlugin = require('webpack-shell-plugin');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.copy('node_modules/@fortawesome/fontawesome-free', 'public/plugins/fontawesome');

mix.webpackConfig({
    plugins: [
        new WebpackShellPlugin({ onBuildStart: ['php artisan laroute:generate'], onBuildEnd: [] }),
    ],
});
