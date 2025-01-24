<?php


namespace Espo\Modules\SnapclaritySync\Core\Services;


use Espo\Core\Container;
use Espo\Core\Utils\Metadata;
use Espo\ORM\Entity;
use Espo\ORM\EntityManager;

/**
 * @property array baseData
 * @property \Espo\ORM\Entity contactEntity
 */
abstract class BaseImportService
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Metadata
     */
    protected $metadata;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var
     */
    protected $baseData;

    /**
     * @var
     */
    protected $contactEntity;

    /**
     * ContactSync constructor.
     * @param Container $container
     * @param EntityManager $entityManager
     * @param Metadata $metadata
     */
    public function __construct(
        Container $container,
        EntityManager $entityManager,
        Metadata $metadata
    )
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->metadata = $metadata;
    }

    /**
     * @return mixed|string
     */
    protected abstract function getEntityName();

    /**
     * @param array $baseData
     * @return $this
     */
    public function setBaseData(array $baseData = [])
    {
        $this->baseData = $baseData;
        return $this;
    }

    /**
     * @param \Espo\ORM\Entity $contactEntity
     */
    public function setContact($contactEntity)
    {
        $this->contactEntity = $contactEntity;
        return $this;
    }

    /**
     * @return \Espo\ORM\Entity
     */
    public function getContact()
    {
        return $this->contactEntity;
    }

    /**
     * @param $entity
     * @return mixed
     */
    protected function saveEntity($entity)
    {
        $this->entityManager->saveEntity($entity);
        return $entity;
    }

    /**
     * @param Entity $entity
     * @param array $entityData
     * @param false $isCreate
     * @return array
     */
    protected function saveEntityUpdatedData(Entity $entity,array $entityData = [], $isCreate = false)
    {
        if ($isCreate === true) {
            $entityData['createdAt'] = gmdate('Y-m-d H:i:s', time());
        }

        $mustInsert = false;
        $entityDefsFields = $this->getEntityDefsFields();

        foreach($entityData as $attributeKey => $attributeValue) {
            $defaultValue = !empty($entityDefsFields[$attributeKey]['default']) ? $this->getEntityDefsFieldDefaultValue($entityDefsFields[$attributeKey]) : null;
            if ($mustInsert === false) {
                $mustInsert = ($isCreate === true || (!empty($attributeValue) && $attributeValue != $entity->get($attributeKey)));
            }
            $entityData[$attributeKey] = $this->getDefaultOrOriginalValue($attributeValue,$defaultValue);
        }

        if ($mustInsert) {
            if ($entity->get('deleted') == 1) {
                $entityData['deleted'] = 0;
            }

            $entity->set($entityData);

            return [
                'entity' => $this->saveEntity($entity),
                'mustInsert' => $mustInsert
            ];
        }

        return [
            'entity' => $entity,
            'mustInsert' => $mustInsert
        ];
    }

    /**
     * @return array
     */
    protected function getEntityDefsFields()
    {
        return $this->metadata->get(['entityDefs', $this->getEntityName(), 'fields']);
    }

    /**
     * @param $field
     * @return mixed|null
     */
    protected function getEntityDefsFieldDefaultValue($field)
    {
        return !empty($field['default']) ? $field['default'] : null;
    }

    /**
     * @param $field
     * @return mixed|null
     */
    protected function mustStoreAsArray($field)
    {
        return !empty($field['storeArrayValues']) ? $field['storeArrayValues'] : false;
    }

    /**
     * @param null $attributeValue
     * @param null $defaultValue
     * @return mixed|null
     */
    private function getDefaultOrOriginalValue($attributeValue = null,$defaultValue = null)
    {
        return !empty($attributeValue) ? $attributeValue : $defaultValue;
    }

}