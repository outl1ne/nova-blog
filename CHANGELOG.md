# Changelog

## [6.1.0] - 2020-02-04

### Fixed
- Fixed bug, where slug field didn't listen to deletion or cut/paste.

### Added
- Added `.editorconfig` to keep a common formatting rules between different branches and developers.

## [6.0.1] - 2020-02-03

### Fixed
- Slug field now again changes spaces to dashes automatically. Feature was accidentally removed when slug validation was added in [5.3.0].

## [6.0.0] - 2019-01-10

### Added
- Added [nova-drafts](https://github.com/optimistdigital/nova-drafts) package to replace previous drafts logic.
 **All previous functionality will remain the same.**

### Removed
- `NovaBlog::draftsEnabled()` and replaced it with `NovaBlog::hasNovaDrafts`.
- Removed `drafts_enabled` from config. Now it checks whether user has the package installed or not.

## [5.6.0] - 2019-01-03

### Added
- Users can now configure categories table name.
- The default name for categories table will now be `nova-blog-categories`.
- Added new migration to change categories table name to the one assigned in config file.
If you wish to keep your previous table name, please change the `nova-blog.php` config file
before migrating.

### Changed
- Changed `table` name inside config. `table -> blog_posts_table`

### Removed
- Removed `getPostsTableName()` function


## [5.5.0] - 2019-01-02

### Fixed
- Fixed [nova-locale-field](https://github.com/optimistdigital/nova-locale-field) package version.


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

[6.1.0]: https://github.com/optimistdigital/nova-blog/compare/6.0.1...6.1.0
[6.0.1]: https://github.com/optimistdigital/nova-blog/compare/6.0.0...6.0.1
[6.0.0]: https://github.com/optimistdigital/nova-blog/compare/5.6.0...6.0.0
[5.6.0]: https://github.com/optimistdigital/nova-blog/compare/5.5.0...5.6.0
[5.5.0]: https://github.com/optimistdigital/nova-blog/compare/5.4.0...5.5.0
[5.4.0]: https://github.com/optimistdigital/nova-blog/compare/5.3.0...5.4.0
[5.3.0]: https://github.com/optimistdigital/nova-blog/compare/5.2.0...5.3.0
[5.2.0]: https://github.com/optimistdigital/nova-blog/compare/5.1.0...5.2.0
[5.0.0]: https://github.com/optimistdigital/nova-blog/compare/4.1.0...5.0.0
