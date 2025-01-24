<?php

namespace Espo\Modules\SurveyMonkeySync\Core\Services;

use Espo\Modules\SurveyMonkeySync\Core\DataProviders\QuestionsAnswersProvider;

/**
 * @property QuestionsAnswersProvider questionsAnswersProvider
 */
class QuestionData
{
    /**
     * @var QuestionsAnswersProvider
     */
    public $questionsAnswersProvider;

    /**
     * @var array
     */
    public $question = [];

    /**
     * @var string
     */
    public $surveyId = '';

    /**
     * @var
     */
    public $questionItem = [];

    /**
     * @var
     */
    public $locale = 'en';

    /**
     * QuestionData constructor.
     * @param QuestionsAnswersProvider $questionsAnswersProvider
     */
    public function __construct(QuestionsAnswersProvider $questionsAnswersProvider)
    {
        $this->questionsAnswersProvider = $questionsAnswersProvider;
    }

    /**
     * @return array|null
     */
    public function getQuestionWithData()
    {
        $questionItem = $this->questionItem;

        if (empty($questionItem['answers'])) {
            return [
                'id' => $questionItem['id'],
                'type' => $questionItem['type'],
                'value' => !empty($this->question['answers'][0]['text']) ? $this->question['answers'][0]['text'] : '',
                'field' => !empty($questionItem['field']) ? $questionItem['field'] : null
            ];
        }

        $answerChoices = [];
        foreach($this->question['answers'] as $answer) {
            if (!empty($answer['choice_id'])) {
                $choices = array_values(array_filter($questionItem['answers']['choices'],function($choice) use(&$answer){
                    if ($choice['id'] == $answer['choice_id']) {
                        if(!empty($choice['field'])) {
                            $answer['field'] = $choice['field'];
                        }
                        return true;
                    }

                    return false;
                }));
                if (empty($choices[0])) {
                    continue;
                }
                $choice = $choices[0];
            }

            if (!empty($answer['row_id'])) {
                $rows = array_values(array_filter($questionItem['answers']['rows'],function($choice) use(&$answer){
                    if ($choice['id'] == $answer['row_id']) {
                        if(!empty($choice['field'])) {
                            $answer['field'] = $choice['field'];
                        }
                        return true;
                    }
                    return false;
                }));
                if (empty($rows[0])) {
                    continue;
                }
                $row = $rows[0];
            }

            if (!empty($answer['other_id'])) {
                $other = $questionItem['answers']['other'];
                $answerChoices[] = array_merge($answer,[
                    'choice_text' => $answer['text'],
                    'choice_id' => $answer['other_id'],
                    'other_id' => $answer['other_id'],
                    'field' => !emptY($other['field']) ? $other['field'] : null
                ]);
                continue;
            }

            if (!empty($row) && !empty($choice)) {
                $answerChoices[] = array_merge($answer,[
                    'choice_text' => $choice['text'],
                    'row_text' => $row['text'],
                    'field' => $answer['field']
                ]);
            } else if (!empty($choice)) {
                $answerChoices[] = array_merge($answer,[
                    'choice_text' => $choice['text'],
                    'field' => $choice['field']
                ]);
            }
        }

        return [
            'id' => $questionItem['id'],
            'type' => $questionItem['type'],
            'field' => $questionItem['field'],
            'value' => $answerChoices
        ];
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

}