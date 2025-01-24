<?php

namespace Espo\Modules\SurveyEntitySync\Core\Synchronization\SaveProcessors;

class SatisfactionSaveProcessor extends BaseSaveProcessor
{

    /**
     * @param $responseData
     * @param $surveyData
     * @param $locale
     * @return mixed|void
     */
    public function save($responseData, $surveyData, $locale)
    {
        if (!count($responseData['pages'])) {
            return;
        }

        $questionsFields = $this->surveyQuestionsFieldsParser->setReassessmentType($surveyData['entity'])->setSurveyData($surveyData['survey'][$locale])->parseSurvey()->getQuestionsFields();

        $fieldsWithValues = [];
        foreach($responseData['pages'] as $page) {
            foreach($page['questions'] as $question) {
                $fieldsWithValues = array_merge($this->surveyQuestionAnswerParser->setQuestion($question)->setQuestionFields($questionsFields)->parseQuestionsAnswers()->getFieldsWithValue(),$fieldsWithValues);
            }
        }

        $surveyMonkeyCollector = $this->entityManager->getRepository('SurveyMonkeyCollector')
            ->where([
                'collectorId' => $responseData['collector_id']
            ])->findOne();

        if (empty($surveyMonkeyCollector)) {
            return;
        }

        $satisfaction = $this->entityManager->getRepository($surveyMonkeyCollector->get('entityType'))->where([
            'id' => $surveyMonkeyCollector->get('entityId')
        ])->findOne();
        if (empty($satisfaction)) {
            return;
        }
        if ($satisfaction->get('surveyResponseId')) {
            return;
        }

        $saveProvidedData = $fieldsWithValues;
        $saveProvidedData['contactId'] = $surveyMonkeyCollector->get('contactId');
        $saveProvidedData['date'] = date('Y-m-d H:i:s');
        $saveProvidedData['surveyResponseId'] = $responseData['id'];
        $saveProvidedData['surveyCollectorId'] = $surveyMonkeyCollector->get('id');
        $satisfaction->set($saveProvidedData);

        $responseSaveEntity = $this->entityManager->saveEntity($satisfaction);

        $surveyMonkeyCollector->set([
            'entityId' => $satisfaction->get('id')
        ]);
        $this->entityManager->saveEntity(is_string($responseSaveEntity) ? $responseSaveEntity : $responseSaveEntity->get('id'));
    }

}