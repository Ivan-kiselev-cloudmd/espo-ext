<?php


namespace Espo\Modules\SurveyMonkeySync\Core\Services;


use Espo\Core\Container;
use Espo\ORM\EntityManager;

/**
 * @property Container container
 * @property EntityManager entityManager
 */
class StoreSurveyResponse
{
    /**
     * @var Container
     */
    public $container;

    /**
     * @var array
     */
    public $questionsData = [];

    /**
     * @var EntityManager
     */
    public $entityManager;

    /**
     * StoreSurveyResponse constructor.
     * @param Container $container
     * @param EntityManager $entityManager
     */
    public function __construct(
        Container $container,
        EntityManager $entityManager
    )
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    /**
     * @return \Espo\ORM\Entity|null
     */
    public function execute()
    {
        $saveProvidedData = [];
        foreach($this->questionsData['answers'] as $answer) {
            if (is_string($answer['value'])) {
                $saveProvidedData[$answer['field']] = $answer['value'];
            } else if ($answer['type'] !== 'multiple_choice' && is_array($answer['value'])) {
                foreach($answer['value'] as $value) {
                    $saveProvidedData[$value['field']] = $value['choice_text'];
                }
            } else if ($answer['type'] === 'multiple_choice' && is_array($answer['value'])) {
                foreach($answer['value'] as $choiceItem) {
                    if (!empty($choiceItem['other_id'])) {
                        $saveProvidedData[$answer['field']][] = 'Other';
                        $saveProvidedData[$choiceItem['field']] = $choiceItem['choice_text'];
                    } else {
                        $saveProvidedData[$choiceItem['field']][] = $choiceItem['choice_text'];
                    }
                }
            }
        }

        $entityItem = $this->entityManager->getRepository($this->questionsData['entity'])->where([
            'surveyAnswerId' => $this->questionsData['answer_id'],
            'deleted' => 0
        ])->findOne();

        if (!empty($entityItem)) {
            return $entityItem;
        }

        $saveProvidedData['language'] = $this->questionsData['locale'];
        $saveProvidedData['name'] = $this->questionsData['name'];
        $saveProvidedData['surveyId'] = $this->questionsData['survey_id'];
        $saveProvidedData['surveyAnswerId'] = $this->questionsData['answer_id'];

        $entityItem = $this->entityManager->getRepository($this->questionsData['entity'])->get();
        $entityItem->set($saveProvidedData);
        $this->entityManager->saveEntity($entityItem);
        if (!empty($saveProvidedData['email'])) {
            $this->syncContact($entityItem,$saveProvidedData['email']);
        }

        return $entityItem;
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        if (property_exists($this,$key)) {
            $this->{$key} = $value;
        }
    }

    /**
     * @param $entityItem
     * @param $email
     */
    protected function syncContact($entityItem,$email)
    {
        $emails = $this->entityManager->getRepository('EmailAddress')->where([
            'name' => $email,
            'lower' => strtolower($email)
        ])->find();

        foreach($emails as $currentEmail) {
            $emailEntity = $this->entityManager->getRepository('EntityEmailAddress')->where([
                'emailAddressId' => $currentEmail->get('id')
            ])->findOne();
            if (!empty($emailEntity)) {
                $entityContact = $this->entityManager->getRepository('Contact')
                    ->where([
                        'id' => $emailEntity->get('entityId'),
                        'deleted' => 0
                    ])->findOne();
                if (!empty($entityContact)) {
                    $this->entityManager
                        ->getRepository('Contact')
                        ->getRelation($entityContact, $this->questionsData['contactRelation'])
                        ->relate($entityItem);
                }
            }
        }
    }
}