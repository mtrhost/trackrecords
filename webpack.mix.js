let mix = require('laravel-mix');
const webpack = require('webpack')
const CopyWebpackPlugin = require('copy-webpack-plugin')
const HtmlWebpackPlugin = require('html-webpack-plugin')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const OptimizeCSSPlugin = require('optimize-css-assets-webpack-plugin')
const UglifyJsPlugin = require('uglifyjs-webpack-plugin')

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
mix.js('resources/assets/frontend/src/main.js', 'static/js')
    //.styles(['resources/assets/frontend/static/css/main.css', 'resources/assets/frontend/static/css/normalize.css'], 'public/static/css/main.css')
   //.sass('resources/assets/sass/app.scss', 'assets/css')
   .webpackConfig({
        resolve: {
            extensions: ['.js', '.vue', '.json'],
            alias: {
                'vue$': 'vue/dist/vue.esm.js',
                '@': __dirname + '/resources/assets/frontend/src',
                'public': path.resolve(__dirname, '/public') // or wherever it is located relative to this file 
            },
        },
        plugins: [
            new UglifyJsPlugin({
                uglifyOptions: {
                    compress: {
                        warnings: false
                    }
                },
                sourceMap: true,
                parallel: true
            }),
            // extract css into its own file
            new ExtractTextPlugin({
                filename: 'public/static/css/main.css',
                // set the following option to `true` if you want to extract CSS from
                // codesplit chunks into this main css file as well.
                // This will result in *all* of your app's CSS being loaded upfront.
                allChunks: false,
            }),
            // Compress extracted CSS. We are using this plugin so that possible
            // duplicated CSS from different components can be deduped.
            new OptimizeCSSPlugin({
                cssProcessorOptions: true
                    ? { safe: true, map: { inline: false } }
                    : { safe: true }
            }),
            new HtmlWebpackPlugin({
                filename: path.resolve(__dirname, 'resources/views/templates/main.php'),
                template: 'resources/assets/frontend/index.html',
                inject: true,
                minify: {
                    removeComments: true,
                    collapseWhitespace: true,
                    removeAttributeQuotes: true
                    // more options:
                    // https://github.com/kangax/html-minifier#options-quick-reference
                },
                // necessary to consistently work with multiple chunks via CommonsChunkPlugin
                chunksSortMode: 'dependency'
            }),
            // copy custom static assets
            /*new CopyWebpackPlugin([
                {
                    from: path.resolve(__dirname, 'resources/assets/frontend/static'),
                    to: 'public/static',
                    ignore: ['.css']
                }
            ])*/
        ]
    });
/*mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');*/
