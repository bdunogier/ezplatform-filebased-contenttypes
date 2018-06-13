<?php
namespace BD\Tests\EzPlatformFileBasedContentType\Fixtures;

use BD\EzPlatformFileBasedContentType\ContentTypeProvider;
use eZ\Publish\SPI;
use eZ\Publish\SPI\Persistence\Content\FieldValue;

class BlogPostContentType implements ContentTypeProvider
{
    public function getType()
    {
        $type = new SPI\Persistence\Content\Type();

        $type->id = 18;
        $type->status = SPI\Persistence\Content\Type::STATUS_DEFINED;
        $type->name = ['eng-GB' => 'Blog post'];
        $type->description = ['eng-GB' => 'A Blog post written by an author'];
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
        $type->fieldDefinitions = [
            $this->getTitleFieldDefinition(),
        ];
        $type->defaultAlwaysAvailable = false;

        return $type;
    }

    private function getTitleFieldDefinition(): SPI\Persistence\Content\Type\FieldDefinition
    {
        $fieldDefinition = new SPI\Persistence\Content\Type\FieldDefinition();
        $fieldDefinition->identifier = 'title';
        $fieldDefinition->id = 185;
        $fieldDefinition->name = ['eng-GB' => "Title"];
        $fieldDefinition->position = 10;
        $fieldDefinition->defaultValue = new FieldValue(['data' => 'default value']);
        $fieldDefinition->fieldType = 'ezstring';
        $fieldDefinition->isRequired = true;
        $fieldDefinition->isTranslatable = 1;
        $fieldDefinition->isSearchable = true;
        $fieldDefinition->fieldGroup = 'content';
        $fieldDefinition->fieldTypeConstraints = new SPI\Persistence\Content\FieldTypeConstraints([
            'validators' => ['StringLengthValidator' => ['minStringLength' => 10, 'maxStringLength' => null]],
        ]);

        return $fieldDefinition;
    }
}

