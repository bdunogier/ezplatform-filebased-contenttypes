# eZ Platform File Based Content Types

Allows Content Types to be defined in PHP classes.

## Usage

In `app/config/services.yml`:

```yaml
services:
    _defaults:
    AppBundle\Platform\ContentTypes\:
        resource: '../../src/AppBundle/Platform/ContentTypes/*'
        tags: [ezplatform.content_type_provider]
```

In `src/AppBundle/Platform/ContentTypes/MyContentType`:
```php
namespace AppBundle\Platform\ContentTypes;

use BD\EzPlatformFileBasedContentType\ContentTypeProvider;
use eZ\Publish\SPI;

class BlogPostContentType implements ContentTypeProvider
{
    public function getType()
    {
        $type = new SPI\Persistence\Content\Type();

        $type->id = 18;
        $type->status = SPI\Persistence\Content\Type::STATUS_DEFINED;
        $type->name = ['eng-GB' => 'Blog post'];
        $type->description = ['A Blog post written by an author'];
        $type->identifier = 'blog_post';
        $type->created = 1528882990;
        $type->modified = 1528882990;
        $type->creatorId = '14';
        $type->modifierId = '14';
        $type->remoteId = 'b9dfe86b6188e504d64f3369d29350d5';
        $type->urlAliasSchema = null;
        $type->nameSchema = '<short_title|title>';
        $type->isContainer = false;
        $type->initialLanguageId = 2;
        $type->sortField = SPI\Persistence\Content\Location::SORT_FIELD_PUBLISHED;
        $type->sortOrder = SPI\Persistence\Content\Location::SORT_ORDER_DESC;
        $type->groupIds = [1];
        $type->fieldDefinitions = [];
        $type->defaultAlwaysAvailable = false;

        return $type;
    }
}
```