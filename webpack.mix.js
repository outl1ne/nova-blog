let mix = require('laravel-mix');

mix
  .setPublicPath('dist')
  .js('resources/slug-field/slug-field.js', 'js')
  .js('resources/markdown-field/markdown-field.js', 'js');
