<?php
namespace BD\EzPlatformFileBasedContentType\Platform\SPI\Persistence;

use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;
use eZ\Publish\SPI\Persistence\Content\Type as SPI;
use eZ\Publish\SPI\Persistence\Content\Type as SpiType;

/**
 * Glossary
 * - CT: Content Type
 * - FBCT: File Based Content Type
 */
class ContentTypeHandler implements SPI\Handler
{
    /**
     * @var SPI\Handler
     */
    private $innerHandler;

    /**
     * File based content type classes, indexed by {@see self::$innerHandler} compatible id.
     * @var SpiType[]
     */
    private $typesById = [];

    /**
     * Map of {@see self::$typesBy} by identifier.
     * @var string[]
     */
    private $typesIdsByIdentifier = [];

    /**
     * Map of {@see self::$typesBy} by remoteId.
     * @var string[]
     */
    private $typesIdsByRemoteId = [];

    public function __construct(SPI\Handler $innerHandler, array $typesProviders = [])
    {
        $this->innerHandler = $innerHandler;

        foreach ($typesProviders as $typeProvider) {
            $type = $typeProvider->getType();
            $id = $type->id;

            $this->typesById[$id] = $type;
            $this->typesIdsByIdentifier[$type->identifier] = $id;
            $this->typesIdsByRemoteId[$type->remoteId] = $id;
        }
    }

    /**
     * @param mixed $groupId
     * @param int $status One of Type::STATUS_DEFINED|Type::STATUS_DRAFT|Type::STATUS_MODIFIED
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Type[]
     */
    public function loadContentTypes($groupId, $status = SpiType::STATUS_DEFINED)
    {
        $persistenceContentTypes = $this->innerHandler->loadContentTypes($groupId, $status);

        foreach ($this->typesById as $type) {
            if (in_array($groupId, $type->groupIds)) {
                $persistenceContentTypes[] = $type;
            }
        }

        return $persistenceContentTypes;
    }

    public function load($contentTypeId, $status = SpiType::STATUS_DEFINED)
    {
        try {
            return $this->innerHandler->load($contentTypeId, $status);
        }
        catch (NotFoundException $notFoundException) {
            $parameters = ['id' => $contentTypeId];
            if ($this->hasType($parameters)) {
                return $this->newType($parameters);
            }

            throw $notFoundException;
        }
    }

    /**
     * @param string $identifier
     * @return SpiType
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    public function loadByIdentifier($identifier)
    {
        try {
            return $this->innerHandler->loadByIdentifier($identifier);
        }
        catch (NotFoundException $notFoundException) {
            $parameters = ['identifier' => $identifier];
            if ($this->hasType($parameters)) {
                return $this->newType($parameters);
            }

            throw $notFoundException;
        }
    }

    /**
     * @param mixed $remoteId
     * @return SpiType
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    public function loadByRemoteId($remoteId)
    {
        try {
            return $this->innerHandler->loadByRemoteId($remoteId);
        }
        catch (NotFoundException $notFoundException) {
            $parameters = ['remoteId' => $remoteId];
            if ($this->hasType($parameters)) {
                return $this->newType($parameters);
            }

            throw $notFoundException;
        }
    }

    public function create(SPI\CreateStruct $contentType)
    {
        return $this->innerHandler->create($contentType);
    }

    public function update($contentTypeId, $status, SPI\UpdateStruct $contentType)
    {
        return $this->innerHandler->update($contentTypeId, $status, $contentType);
    }

    public function delete($contentTypeId, $status)
    {
        $this->innerHandler->delete($contentTypeId, $status);
    }

    public function createDraft($modifierId, $contentTypeId)
    {
        return $this->innerHandler->createDraft($modifierId, $contentTypeId);
    }

    public function copy($userId, $contentTypeId, $status)
    {
        /**
         * @todo loading the copied CT is done inside the implementation.
         *       Figure how to allow copy from file to repository.
        return $this->innerHandler->copy($userId, $contentTypeId, $status);
         */
    }

    public function unlink($groupId, $contentTypeId, $status)
    {
        $this->innerHandler->link($groupId, $contentTypeId, $status);
    }

    public function link($groupId, $contentTypeId, $status)
    {
        $this->innerHandler->link($groupId, $contentTypeId, $status);
    }

    public function getFieldDefinition($id, $status)
    {
        return $this->innerHandler->getFieldDefinition($id, $status);
    }


    public function getContentCount($contentTypeId)
    {
        return $this->innerHandler->getContentCount($contentTypeId);
    }


    public function addFieldDefinition($contentTypeId, $status, SPI\FieldDefinition $fieldDefinition)
    {
        return $this->innerHandler->addFieldDefinition($contentTypeId, $status, $fieldDefinition);
    }

    public function removeFieldDefinition($contentTypeId, $status, $fieldDefinitionId)
    {
        return $this->innerHandler->removeFieldDefinition($contentTypeId, $status, $fieldDefinitionId);
    }

    public function updateFieldDefinition($contentTypeId, $status, SPI\FieldDefinition $fieldDefinition)
    {
        return $this->innerHandler->updateFieldDefinition($contentTypeId, $status, $fieldDefinition);
    }

    public function publish($contentTypeId)
    {
        return $this->innerHandler->publish($contentTypeId);
    }

    public function getSearchableFieldMap()
    {
        return $this->innerHandler->getSearchableFieldMap();
    }

    /**
     * @param array $parameters
     * @return SpiType
     * @throws InvalidArgumentException
     */
    private function newType(array $parameters)
    {
        return $this->typesById[$this->extractId($parameters)];
    }

    /**
     * @param array $parameters
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    private function hasType(array $parameters)
    {
        $id = $this->extractId($parameters);

        return (isset($this->typesById[$id]));
    }

    /**
     * @param array $parameters
     *
     * @return int|string
     *
     * @throws InvalidArgumentException
     */
    private function extractId(array $parameters)
    {
        extract($parameters);

        if (isset($identifier) && is_string($identifier)) {
            if (!isset($this->typesIdsByIdentifier[$identifier])) {
                throw new InvalidArgumentException(
                    'identifier',
                    'No File based content type with this identifier'
                );
            }
            $id = $this->typesIdsByIdentifier[$identifier];
        } else if (isset($remoteId)) {
            if (!isset($this->typesByRemoteId[$remoteId])) {
                throw new InvalidArgumentException(
                    'remoteId',
                    'No File based content type with this remoteId'
                );
            }
            $id = $this->typesByRemoteId[$remoteId];
        } else if (!isset($id)) {
            throw new InvalidArgumentException(
                'parameters',
                "Must contain one of 'id', 'identifiers' or 'remoteId'"
            );
        }

        return $id;
    }

    public function createGroup(SPI\Group\CreateStruct $group)
    {
        return $this->innerHandler->createGroup($group);
    }

    public function updateGroup(SPI\Group\UpdateStruct $group)
    {
        $this->innerHandler->updateGroup($group);
    }

    public function deleteGroup($groupId)
    {
        $this->innerHandler->deleteGroup($groupId);
    }

    public function loadGroup($groupId)
    {
        return $this->innerHandler->loadGroup($groupId);
    }

    public function loadGroups(array $groupIds)
    {
        return $this->innerHandler->loadGroups($groupIds);
    }

    public function loadGroupByIdentifier($identifier)
    {
        return $this->innerHandler->loadGroupByIdentifier($identifier);
    }

    public function loadAllGroups()
    {
        return $this->innerHandler->loadAllGroups();
    }
}
