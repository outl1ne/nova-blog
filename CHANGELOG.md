# Changelog

## [5.4.0] - 2019-12-16

### Changed
- Reduced scripts amount from 4 to 1

### Fixed
- Fixed down migrations
- Fixed config example typo


## [5.3.0] - 2019-12-13

### Changed
- Removed alphanumeric & lowercase_string slug validation from front-end and moved it to back-end


## [5.2.0] - 2019-11-13

### Changed
- Migrations are now loaded automatically and can be deleted from your project. This aims to reduce the number of migration files inside the end project's folder and keep them more relevant.     
     -Migration files to delete: 
     ```
      2019_06_18_000000_create_blog_posts_table.php
      2019_08_07_000000_add_post_introduction_to_posts_table.php
      2019_08_08_000000_add_pinned_to_posts_table.php
      2019_08_13_073119_change_post_content_datatype.php
      2019_08_14_121846_create_categories_table.php
      2019_08_14_130350_add_category_to_nova_blog_posts.php
      2019_09_12_161000_add_slug_to_category.php
      2019_09_19_073119_change_post_introduction_datatype.php
      2019_11_11_000000_add_locale_to_posts_table.php
      2019_11_12_000000_add_drafts_to_posts_table.php

## [5.0.0] - 2019-11-13

### Added
- Added [Nova-Lang](https://github.com/optimistdigital/nova-lang) support. Now you can assign a locale to your blog-post and sort posts by locale. 
- drafting feature. Draft feature allows you to create previews of pages before publishing them.
- links to front-end pages. Creates a new button next to your slug, that shows link to your front-end blog post. 


## [3.0.0] - 2019-08-29

- When using the `nova_get_post_by_slug` and `nova_get_post_by_id` helpers, all images will now contain the full URL to the image (including domain). Previously, only the filename was given.

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

[5.4.0]: https://github.com/optimistdigital/nova-blog/compare/5.4.0...5.3.0
[5.3.0]: https://github.com/optimistdigital/nova-blog/compare/5.3.0...5.2.0
[5.2.0]: https://github.com/optimistdigital/nova-blog/compare/5.2.0...5.1.0
[5.0.0]: https://github.com/optimistdigital/nova-blog/compare/5.0.0...4.1.0
