<?php

namespace Espo\Modules\SurveyEntitySync\Core\Synchronization;

/**
 * @property array $question
 * @property $questionsFields
 */
class SurveyQuestionAnswerParser
{

    /**
     * @var
     */
    protected $question;

    /**
     * @var
     */
    protected $questionsFields;

    /**
     * @var string[]
     */
    private $answerParserProcessors = [
        'single_choice' => 'singleChoiceAnswer',
        'matrix' => 'matrixAnswer',
        'multiple_choice' => 'multipleChoiceAnswer'
    ];

    /**
     * @var
     */
    protected $fieldsWithValues;

    /**
     * @param $question
     * @return $this
     */
    public function setQuestion($question = [])
    {
        $this->question = $question;
        return $this;
    }

    /**
     * @param $questionsFields
     * @return $this
     */
    public function setQuestionFields($questionsFields)
    {
        $this->questionsFields = $questionsFields;
        return $this;
    }

    /**
     * @return $this
     */
    public function parseQuestionsAnswers()
    {
        $this->fieldsWithValues = [];
        $questionField = $this->questionsFields[$this->question['id']];

        if (!empty($this->answerParserProcessors[$questionField['family']])) {
            $this->{$this->answerParserProcessors[$questionField['family']]}($questionField, $this->question);
        } else {

            if (!empty($this->question['answers'])) {
                foreach($this->question['answers'] as $answer) {
                    $questionField = $this->questionsFields[$answer['row_id']];
                    if (!empty($this->{$this->answerParserProcessors[$questionField['family']]}($questionField, $answer))) {
                        $this->{$this->answerParserProcessors[$questionField['family']]}($questionField, $this->question);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFieldsWithValue()
    {
        return $this->fieldsWithValues;
    }

    /**
     * @param $questionField
     * @param $questionAnswer
     * @return void
     */
    public function multipleChoiceAnswer($questionField, $questionAnswer)
    {
        $otherField = null;
        foreach($questionField['choices'] as $choice) {
            if (!empty($choice['other_id'])) {
                $otherField = $choice;
            }
        }

        foreach($questionAnswer['answers'] as $answer) {
            if (!empty($answer['choice_id'])) {
                $this->fieldsWithValues[$questionField['field']][] = $this->getChoiceTextById($questionField['choices'], $answer['choice_id']);
            } else if (!empty($answer['other_id'])) {
                $this->fieldsWithValues[$questionField['field']][] = $otherField['text'];
                $this->fieldsWithValues[$otherField['field']] = $answer['text'];
            }
        }
    }

    /**
     * @param $questionField
     * @param $questionAnswer
     * @return void
     */
    protected function singleChoiceAnswer($questionField, $questionAnswer)
    {
        foreach($questionAnswer['answers'] as $answer) {
            $this->fieldsWithValues[$questionField['field']] = $this->getChoiceTextById($questionField['choices'], $answer['choice_id']);
        }
    }

    /**
     * @param $questionField
     * @param $questionAnswer
     * @return void
     */
    protected function matrixAnswer($questionField, $questionAnswer)
    {
        if (empty($questionAnswer['choice_id']) || empty($questionField['field'])) {
            return;
        }
        $this->fieldsWithValues[$questionField['field']] = $this->getChoiceTextById($questionField['choices'], $questionAnswer['choice_id']);
    }

    /**
     * @param $choices
     * @param $choiceId
     * @return mixed|null
     */
    protected function getChoiceTextById($choices, $choiceId)
    {
        foreach($choices as $choice) {
            if (!empty($choice['id']) && $choice['id'] == $choiceId) {
                return $choice['text'];
            } else if (!empty($choice['other_id']) && $choice['other_id'] == $choiceId) {
                return $choice['text'];
            }
        }

        return null;
    }
}