<?php

namespace Espo\Modules\SurveyEntitySync\Core\Synchronization;

use Espo\Core\Utils\Metadata;

class SurveyQuestionsFieldsParser
{
    /**
     * @var
     */
    protected $questionsFields = [];

    /**
     * @var string[]
     */
    protected $questionsParseProcessors = [
        'single_choice' =>  'parseSingleChoice',
        'matrix' => 'parseMatrix',
        'multiple_choice' => 'parseMultipleChoice'
    ];

    /**
     * @var
     */
    protected $surveyData;

    /**
     * @var string
     */
    protected $reassessmentType;

    /**
     * @var Metadata
     */
    protected $metadata;

    /**
     * @param Metadata $metadata
     */
    public function __construct(Metadata $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @param $surveyData
     * @return $this
     */
    public function setSurveyData($surveyData = [])
    {
        $this->surveyData = $surveyData;
        return $this;
    }

    /**
     * @param $reassessmentType
     * @return $this
     */
    public function setReassessmentType($reassessmentType)
    {
        $this->reassessmentType = $reassessmentType;
        return $this;
    }

    /**
     * @return SurveyQuestionsFieldsParser
     */
    public function parseSurvey()
    {
        $survey = $this->surveyData;

        foreach($survey['pages'] as $page) {
            foreach($page['questions'] as $question) {
                if (!empty($this->questionsParseProcessors[$question['family']])) {
                    $this->{$this->questionsParseProcessors[$question['family']]}($question);
                }
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuestionsFields()
    {
        return $this->questionsFields;
    }

    /**
     * @param $question
     * @return void
     */
    protected function parseMultipleChoice($question)
    {
        $options = $this->removeEmptyOrNAFromOptions($this->getFieldOptions($question['field']));

        $options = array_values(array_filter($options,function ($item) {
            return strtolower($item) != 'other';
        }));
        $options[] = 'Other';

        $this->questionsFields[$question['id']] = [
            'id' => $question['id'],
            'field' => $question['field'],
            'family' => $question['family'],
            'subtype' => $question['subtype']
        ];

        foreach($question['answers']['choices'] as $index => $choice) {
            if (!empty($options[$index])) {
                $this->questionsFields[$question['id']]['choices'][] = [
                    'id' => $choice['id'],
                    'text' => $options[$index]
                ];
            }
        }

        if (!empty($question['answers']['other'])) {
            $this->questionsFields[$question['id']]['choices'][] = [
                'other_id' => $question['answers']['other']['id'],
                'text' => $question['answers']['other']['text'],
                'field' => $question['answers']['other']['field']
            ];
        }
    }

    /**
     * @param $question
     * @return void
     */
    protected function parseMatrix($question)
    {
        if (empty($question['answers']['choices']) || empty($question['answers']['rows'])) {
            return;
        }

        $choicesClosure = function ($choices,$field) {
            $options = $this->removeEmptyOrNAFromOptions($this->getFieldOptions($field));

            $choicesData = [];
            foreach($choices as $index => $choice) {
                if (!empty($options[$index])) {
                    $choicesData[] = [
                        'id' => $choice['id'],
                        'text' => $options[$index]
                    ];
                }
            }

            return $choicesData;
        };

        foreach($question['answers']['rows'] as $row) {
            $this->questionsFields[$row['id']] = [
                'id' => $row['id'],
                'field' => $row['field'],
                'family' => $question['family'],
                'subtype' => $question['subtype'],
                'choices' => $choicesClosure($question['answers']['choices'], $row['field'])
            ];
        }
    }

    /**
     * @param $question
     * @return void
     */
    protected function parseSingleChoice($question)
    {
        if (empty($question['answers']['choices'])) {
            return;
        }
        $this->questionsFields[$question['id']] = [
            'id' => $question['id'],
            'field' => $question['field'],
            'family' => $question['family'],
            'subtype' => $question['subtype']
        ];
        $options = $this->removeEmptyOrNAFromOptions($this->getFieldOptions($question['field']));
        foreach($question['answers']['choices'] as $index => $choice) {
            if (!empty($options[$index])) {
                $this->questionsFields[$question['id']]['choices'][] = [
                    'id' => $choice['id'],
                    'text' => $options[$index]
                ];
            }
        }
    }

    /**
     * @param $options
     * @return array
     */
    private function removeEmptyOrNAFromOptions($options)
    {
        $filterOptions = [];
        foreach($options as $key => $option) {
            if (empty($option) || strtolower($option) == 'n/a') {
                continue;
            }
            $filterOptions[] = $option;
        }

        return $filterOptions;
    }

    /**
     * @param $field
     * @return array
     */
    private function getFieldOptions($field)
    {
        return $this->metadata->get(['entityDefs',$this->reassessmentType,'fields', $field,'options']);
    }

}