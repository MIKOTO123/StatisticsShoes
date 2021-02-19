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

mix.js('resources/client/src/app.js', 'public/client/js')
    .extract(['vue', 'vue-router', 'axios', 'view-design'])
    .browserSync({
        proxy: 'http://www.cyr.com',
        host: 'www.cyr.com'
    })
    .sourceMaps(true, 'source-map')
    // .sass('resources/sass/app.scss', 'public/css')
    .webpackConfig({
        resolve: {
            extensions: ['.js', '.vue', '.json'],
            alias: {
                '@': path.resolve(__dirname, 'resources/client/src'),
                '@common@': path.resolve(__dirname, 'resources/common'),
                '_c': path.resolve(__dirname, 'resources/client/src/components'),
                'icon_cus': path.resolve(__dirname, 'resources/client/src/assets/icon_custom'),
            }
        },

        output: {
            //publicPath : '/',
            chunkFilename: '[name].js?id=[chunkhash:20]'
        }
    }).version();


