<?php
namespace BD\EzPlatformFileBasedContentType;

interface ContentTypeProvider
{
    /**
     * @return \eZ\Publish\SPI\Persistence\Content\Type
     */
    public function getType();
}