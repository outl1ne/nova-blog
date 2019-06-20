# Nova Blog

This [Laravel Nova](https://nova.laravel.com) package allows you to create a blog and manage blogposts. The package is geared towards headless CMS's.

## Features

- Blogpost management

## Installation

Install the package in a Laravel Nova project via Composer:

```bash
composer require optimistdigital/nova-blog
```

Publish the `nova-blog` configuration file and edit it to your preference:

```bash
php artisan vendor:publish --provider="OptimistDigital\NovaBlog\ToolServiceProvider" --tag="config"
```

Publish the database migration(s) and run migrate:

```bash
php artisan vendor:publish --provider="OptimistDigital\NovaBlog\ToolServiceProvider" --tag="migrations"
php artisan migrate
```

Register the tool with Nova in the `tools()` method of the `NovaServiceProvider`:

```php
// in app/Providers/NovaServiceProvider.php

public function tools()
{
    return [
        // ...
        new \OptimistDigital\NovaBlog\NovaBlog
    ];
}
```

## Usage

## Helper functions

### nova_get_blog_structure()

The helper function `nova_get_blog_structure()` returns the base posts structure (titles, slugs, published at dates, content) that you can build your routes upon in the front-end.

Example response:

```json
[
  {
    "id": 7,
    "created_at": "2019-06-19 11:58:56",
    "updated_at": "2019-06-19 11:59:23",
    "title": "Test post 1",
    "slug": "testpost1",
    "post_content": [
      {
        "layout": "text",
        "key": "8965c7bfc0918086",
        "attributes": {
          "text-content": "Test post content."
        }
      },
      {
        "layout": "image",
        "key": "56f5bbe608b68cd6",
        "attributes": {
          "caption": "Test post image."
        }
      }
    ],
    "published_at": "2019-06-19 09:00:00",
    "seo_title": null,
    "seo_description": null,
    "seo_image": null,
    "data": null
  },
  {
    "id": 8,
    "created_at": "2019-06-19 12:00:06",
    "updated_at": "2019-06-19 12:00:06",
    "title": "Test post 2",
    "slug": "testpost2",
    "post_content": [
      {
        "layout": "text",
        "key": "0e340b84bc5dec28",
        "attributes": {
          "text-content": "Test post content."
        }
      },
      {
        "layout": "image",
        "key": "a4625050e49cf77c",
        "attributes": {
          "caption": "Test post image."
        }
      }
    ],
    "published_at": "2019-06-19 09:00:05",
    "seo_title": null,
    "seo_description": null,
    "seo_image": null,
    "data": null
  }
]
```

### nova_get_post(\$postId)

The helper function `nova_get_post($postId)` finds and returns the post with the given ID.

Example response for querying page with ID `7` (`nova_get_post(7)`):

```json
{
  "id": 7,
  "name": "Test post 1",
  "slug": "testpost1",
  "published_at": "2019-06-19T09:00:00.000000Z",
  "data": [
    {
      "layout": "text",
      "key": "8965c7bfc0918086",
      "attributes": {
        "text-content": "Test post content."
      }
    },
    {
      "layout": "image",
      "key": "56f5bbe608b68cd6",
      "attributes": {
        "caption": "Test post image."
      }
    }
  ]
}
```

## Credits

- [Marika Must](https://github.com/marycaz)
- [Tarvo Reinpalu](https://github.com/Tarpsvo)

## License

Nova blog is open-sourced software licensed under the [MIT license](LICENSE.md).
