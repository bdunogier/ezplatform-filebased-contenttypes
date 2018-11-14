# eZ Platform File Based Content Types

Allows Content Types to be defined in PHP classes.

Can be used to define packages that automatically define content types they need.
By defining a "blog_post" Content Type, a template and controllers for displaying
blob posts on the frontend can be published and re-used.

## Usage

In `app/config/services.yml`:

```yml
services:
    # ...

    AppBundle\Platform\ContentTypes\:
        resource: '../../src/AppBundle/Platform/ContentTypes/*'
        tags: [ezplatform.content_type_provider]
```

Create `src/AppBundle/Platform/ContentTypes/MyContentType`, and use [`tests/Fixtures/BlogPostContentType.php`](https://github.com/bdunogier/ezplatform-filebased-contenttypes/blob/prototype1/tests/Fixtures/BlogPostContentType.php) as a basis.
