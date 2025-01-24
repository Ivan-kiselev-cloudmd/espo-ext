<?php

namespace Espo\Modules\SurveyEntitySync\Core\Synchronization\SaveProcessors;

use Espo\Modules\SurveyEntitySync\Core\Synchronization\SurveyQuestionAnswerParser;
use Espo\Modules\SurveyEntitySync\Core\Synchronization\SurveyQuestionsFieldsParser;
use Espo\ORM\EntityManager;

abstract class BaseSaveProcessor
{
    /**
     * @var SurveyQuestionsFieldsParser
     */
    protected $surveyQuestionsFieldsParser;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var SurveyQuestionAnswerParser
     */
    protected $surveyQuestionAnswerParser;

    /**
     * @param SurveyQuestionsFieldsParser $surveyQuestionsFieldsParser
     * @param EntityManager $entityManager
     * @param SurveyQuestionAnswerParser $surveyQuestionAnswerParser
     */
    public function __construct(
        SurveyQuestionsFieldsParser $surveyQuestionsFieldsParser,
        EntityManager $entityManager,
        SurveyQuestionAnswerParser $surveyQuestionAnswerParser
    )
    {
        $this->surveyQuestionsFieldsParser = $surveyQuestionsFieldsParser;
        $this->entityManager = $entityManager;
        $this->surveyQuestionAnswerParser = $surveyQuestionAnswerParser;
    }

    /**
     * @param $responseData
     * @param $surveyData
     * @param $locale
     * @return mixed
     */
    public abstract function save($responseData, $surveyData, $locale);

}