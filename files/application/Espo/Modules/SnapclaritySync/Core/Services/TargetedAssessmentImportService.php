<?php


namespace Espo\Modules\SnapclaritySync\Core\Services;


use Espo\Core\Container;
use Espo\Core\Utils\Metadata;
use Espo\Modules\SnapclaritySync\Core\Services\Contracts\EntityDataSetable;
use Espo\ORM\Entity;
use Espo\ORM\EntityManager;

/**
 * @property mixed defaultSource
 */
class TargetedAssessmentImportService extends BaseImportService implements EntityDataSetable
{
    /**
     * @var array
     */
    private $targetedAssessmentData;

    /**
     * @var mixed
     */
    protected $defaultSource;

    public function __construct(Container $container, EntityManager $entityManager, Metadata $metadata)
    {
        parent::__construct($container, $entityManager, $metadata);
        $this->defaultSource = $this->metadata->get(['entityDefs', $this->getEntityName(), 'fields'])['source']['options'][0];
    }

    /**
     * @param array $entityData
     * @return $this|mixed
     */
    public function setEntityData(array $entityData)
    {
        $this->targetedAssessmentData = $entityData;
        return $this;
    }

    /**
     * @return $this
     */
    public function import()
    {
        /** @var $contact Entity */
        $contact = $this->contactEntity;
        $targetedAssessmentData = $this->targetedAssessmentData;
        if (empty($targetedAssessmentData['questions'])) {
            return $this;
        }
        $questions = $targetedAssessmentData['questions'];

        /** @var $entityItem Entity */
        $entityItem = $this->entityManager->getRepository($this->getEntityName())->where([
            'contactId' => $contact->get('id'),
            'source' => $this->defaultSource,
            'deleted' => 0
        ])->findOne();

        $isCreate = false;

        if (empty($entityItem)) {
            $isCreate = true;
            /** @var $entityItem Entity */
            $entityItem = $this->entityManager->getRepository($this->getEntityName())->get();
        }

        $entityFields = $this->getEntityDefsFields();

        foreach($questions as $field => $question) {
            $field = strtolower($field);
            if (array_key_exists($field, $entityFields) === false) {
                continue;
            }
            $answer = !empty($question['answers'][0]) ? $question['answers'][0] : [];
            if (empty($answer)) {
                continue;
            }
            $mustStoreAsArray = $this->mustStoreAsArray($entityFields[$field]);

            if (!empty($question['answers'][0]['options'])) {
                $optionValues = $this->getValuesByOptionsId($field, $question['answers'][0]['options']);
            } else if(!empty($question['answers'][0]['nvalue'])) {
                $optionValues = $this->getValuesByOptionsId($field, [$question['answers'][0]['nvalue']]);
            } else {
                $optionValues = $this->getEntityDefsFieldDefaultValue($entityFields[$field]);
            }

            if ($mustStoreAsArray) {
                $value = is_array($optionValues) ? $optionValues : [$optionValues];
            } else {
                $value = !empty($optionValues['0']) ? $optionValues['0'] : null;
            }

            $saveData[$field] = $value;
        }

        $saveData['dateAssessmentCompleted'] = !empty($this->targetedAssessmentData['checkpoints']['assessmentCompleted'])
            ? gmdate('Y-m-d', strtotime($this->targetedAssessmentData['checkpoints']['assessmentCompleted'])) : null;
        $saveData['source'] = $this->defaultSource;
        $saveData['name'] = $this->getEntityName() . ' Api';
        $saveData['contactId'] = $contact->get('id');

        $this->saveEntityUpdatedData($entityItem,$saveData,$isCreate);

        return $this;
    }

    /**
     * @param string $field
     * @param array $optionsId
     * @return array
     */
    protected function getValuesByOptionsId($field, $optionsId = [])
    {
        $options = $this->getFieldOptions($field);
        $options = array_values(array_filter($options,function($optionId) use($optionsId){
            return in_array($optionId,$optionsId);
        },ARRAY_FILTER_USE_KEY));

        return $options;
    }

    /**
     * @param $field
     * @return mixed|null
     */
    protected function getFieldOptions($field)
    {
        return !empty($this->getEntityDefsFields()[$field]['options']) ? $this->getEntityDefsFields()[$field]['options'] : null;
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return 'TargetedAssessment';
    }
}