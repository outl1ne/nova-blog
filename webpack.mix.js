let mix = require('laravel-mix');

mix
  .setPublicPath('dist')
  .js('resources/nova-blog-dist.js', 'js')
