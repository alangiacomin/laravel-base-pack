const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setResourceRoot(process.env.APP_BASENAME);
mix.options({
  clearConsole: false,
});

mix.webpackConfig({
  output: {
    publicPath: process.env.APP_BASENAME,
    chunkFilename: 'js/app/[id].js?id=[chunkhash]',
  },
  resolve: {
    alias: {
      react: path.resolve('./node_modules/react'),
      '@areas': path.resolve(__dirname, 'resources/js/areas/'),
      '@components': path.resolve(__dirname, 'resources/js/components/'),
      '@utils': path.resolve(__dirname, 'resources/js/utils/'),
    },
  },
  module: {
    rules: [
      {
        test: /\.jsx?$/,
        exclude: [/node_modules/],
        use: {
          loader: 'babel-loader',
          options: {
            presets: [
              ['@babel/preset-env', { modules: false }],
              ['@babel/preset-react', { runtime: 'automatic' }],
            ],
          },
        },
      },
    ],

  },
});

// mix.copy('resources/assets/favicon.ico', 'public');
// mix.copy('resources/assets/icons', 'public/assets/icons');
// mix.copyDirectory('resources/images', 'public/images');
// mix.copyDirectory('resources/js/locales', 'public/locales');

mix.js('resources/js/index.jsx', 'public/js/app.js').react();
mix.sass('resources/sass/app.scss', 'public/css', {
  sassOptions: {
    quietDeps: mix.inProduction(),
  },
});

mix.extract();

mix.disableNotifications();
// mix.disableSuccessNotifications();

// https://github.com/laravel-mix/laravel-mix/issues/2764#issuecomment-804247445
mix.after((stats) => {
  const assets = { ...stats.compilation.assets };
  stats.compilation.assets = {};

  if (!mix.inProduction()) {
    for (const [path, asset] of Object.entries(assets)) {
      if (!path.includes('_test_jsx.js') && !path.includes('_test_js.js')) {
        stats.compilation.assets[path] = asset;
      }
    }
  }
});

if (mix.inProduction()) {
  mix.version();
}
mix.sourceMaps();

// mix.dump();
