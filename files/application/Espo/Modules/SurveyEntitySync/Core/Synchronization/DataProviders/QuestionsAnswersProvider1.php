<?php

namespace Espo\Modules\SurveyEntitySync\Core\Synchronization\DataProviders;

use Espo\Core\Container;
use Espo\Custom\Entities\ReassessmentADHD;
use Espo\Custom\Entities\ReassessmentAlcoholUseDisorder;
use Espo\Custom\Entities\ReassessmentAngerManagement;
use Espo\Custom\Entities\ReassessmentBipolar;
use Espo\Custom\Entities\ReassessmentDepression;
use Espo\Custom\Entities\ReassessmentEatingDisorder;
use Espo\Custom\Entities\ReassessmentGAD;
use Espo\Custom\Entities\ReassessmentOCD;
use Espo\Custom\Entities\ReassessmentPanicDisorder;
use Espo\Custom\Entities\ReassessmentPTSD;
use Espo\Custom\Entities\ReassessmentSAD;
use Espo\Custom\Entities\ReassessmentSleepDisorder;
use Espo\Custom\Entities\ReassessmentSUD;

/**
 * @property Container container
 */
class QuestionsAnswersProvider1
{
    /**
     * @var Container
     */
    protected $container;


    /**
     * QuestionsAnswersProvider constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return array[]
     * @throws \Espo\Core\Exceptions\Error
     */
    public function getQuestionsData()
    {
        return $this->isProduction() === false ? [
            ReassessmentGAD::class => [
                'en'    => [
                    'locale' => strtoupper('en'),
                    'id' => '190287502',
                    'entity' => 'ReassessmentGAD',
                    'contactRelation' => 'reassessmentGADs',
                    'name' => 'Generalized Anxiety Disorder Reassessment',
                    'questions' => [
                        [
                            'id' => '63750419',
                            'type' => 'text',
                            'field' => 'email',
                        ],
                        [
                            'id' => '63750420',
                            'type' => 'matrix',
                            'answers' => [
                                'rows' => [
                                    [
                                        'id' => '523509446',
                                        'field' => 'q1',
                                        'text' => 'Feeling nervous, anxious or on edge.'
                                    ],
                                    [
                                        'id' => '523509447',
                                        'field' => 'q2',
                                        'text' => 'Not being able to stop or control worrying.'
                                    ],
                                    [
                                        'id' => '523509456',
                                        'text' => 'Worrying too much about different things.',
                                        'field' => 'q3'
                                    ],
                                    [
                                        'id' => '523509457',
                                        'text' => 'Trouble relaxing.',
                                        'field' => 'q4'
                                    ],
                                    [
                                        'id' => '523509469',
                                        'text' => 'Being so restless that it is hard to sit still.',
                                        'field' => 'q5'
                                    ],
                                    [
                                        'id' => '523509470',
                                        'text' => 'Becoming easily annoyed or irritable.',
                                        'field' => 'q6'
                                    ],
                                    [
                                        'id' => '523509471',
                                        'text' => 'Feeling afraid as if something awful might happen.',
                                        'field' => 'q7'
                                    ]
                                ],
                                'choices' => [
                                    [
                                        'id' => '523509448',
                                        'text' => 'Not at all'
                                    ],
                                    [
                                        'id' => '523509449',
                                        'text' => 'Several days'
                                    ],
                                    [
                                        'id' => '523509450',
                                        'text' => 'More than half the days'
                                    ],
                                    [
                                        'id' => '523509451',
                                        'text' => 'Nearly every day'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            ReassessmentADHD::class => [
                'en'    => [
                    'locale' => strtoupper('en'),
                    'id' => '190291270',
                    'entity' => 'ReassessmentADHD',
                    'contactRelation' => 'reassessmentADHDs',
                    'name' => 'Reassessment - ADHD',
                    'questions' => [
                        [
                            'id' => '63808522',
                            'type' => 'text',
                            'field' => 'email',
                        ],
                        [
                            'id' => '63808523',
                            'type' => 'matrix',
                            'answers' => [
                                'rows' => [
                                    [
                                        'field' => 'q1',
                                        "text" => "How often do you have difficulty concentrating on what people are saying to you even when they are speaking to you directly?",
                                        "id" => "523860673"
                                    ],
                                    [
                                        'field' => 'q2',
                                        "text" => "How often do you leave your seat in meetings or other situations in which you are expected to remain seated?",
                                        "id" => "523860674"
                                    ],
                                    [
                                        'field' => 'q3',
                                        "text" => "How often do you have difficulty unwinding and relaxing when you have time to yourself?",
                                        "id" => "523860681"
                                    ],
                                    [
                                        'field' => 'q4',
                                        "text" => "When you’re in a conversation, how often do you find yourself finishing the sentences of the people you are talking to before they can finish them themselves?",
                                        "id" => "523860682"
                                    ],
                                    [
                                        'field' => 'q5',
                                        "text" => "How often do you put things off until the last minute?",
                                        "id" => "523860683"
                                    ],
                                    [
                                        'field' => 'q6',
                                        "text" => "How often do you depend on others to keep your life in order and attend to details?",
                                        "id" => "523860684"
                                    ]
                                ],
                                'choices' => [
                                    [
                                        "text" => "Never",
                                        "id" => "523860675"
                                    ],
                                    [
                                        "text" => "Rarely",
                                        "id" => "523860676"
                                    ],
                                    [
                                        "text" => "Sometimes",
                                        "id" => "523860677",
                                    ],
                                    [
                                        "text" => "Often",
                                        "id" => "523860678",
                                    ],
                                    [
                                        "text" => "Very Often",
                                        "id" => "523860688",
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            ReassessmentAlcoholUseDisorder::class => [
                'en'    => [
                    'locale' => strtoupper('en'),
                    'id' => '190291271',
                    'entity' => 'ReassessmentAlcoholUseDisorder',
                    'contactRelation' => 'reassessmentAlcoholUseDisorders',
                    'name' => 'Reassessment - Alcohol Use Disorder',
                    'questions' => [
                        [
                            'id' => '63808524',
                            'type' => 'text',
                            'field' => 'email',
                        ],
                        [
                            'id' => '63808525',
                            'type' => 'matrix',
                            'answers' => [
                                'rows' => [
                                    [
                                        'field' => 'q1',
                                        "text" => "How often do you have a drink containing alcohol?",
                                        "id" => "523860691"
                                    ],
                                    [
                                        'field' => 'q2',
                                        "text" => "How many drinks containing alcohol do you have on a typical day when you are drinking?",
                                        "id" => "523860692"
                                    ],
                                    [
                                        'field' => 'q3',
                                        "text" => "How often do you have five or more drinks on one occasion?",
                                        "id" => "523860699"
                                    ],
                                    [
                                        'field' => 'q4',
                                        "text" => "found that you were not able to stop drinking once you had started?",
                                        "id" => "523860700"
                                    ],
                                    [
                                        'field' => 'q5',
                                        "text" => "failed to do what was normally expected of you because of drinking?",
                                        "id" => "523860701"
                                    ],
                                    [
                                        'field' => 'q6',
                                        "text" => "needed a first drink in the morning to get yourself going after a heavy drinking session?",
                                        "id" => "523860702"
                                    ],
                                    [
                                        'field' => 'q7',
                                        "text" => "had a feeling of guilt or remorse after drinking?",
                                        "id" => "523860706"
                                    ],
                                    [
                                        'field' => 'q8',
                                        "text" => "been unable to remember what happened the night before because of your drinking?",
                                        "id" => "523860707"
                                    ],
                                    [
                                        'field' => 'q9',
                                        "text" => "Have you or someone else been injured because of your drinking?",
                                        "id" => "523860708"
                                    ],
                                    [
                                        'field' => 'q10',
                                        "text" => "Has a relative, friend, doctor, or other health care worker been concerned about your drinking or suggested you cut down?",
                                        "id" => "523860709"
                                    ]
                                ],
                                'choices' => [
                                    [
                                        "text" => "No",
                                        "id" => "523860693"
                                    ],
                                    [
                                        "text" => "Yes, but not in the last year",
                                        "id" => "523860694"
                                    ],
                                    [
                                        "text" => "Yes, during the last year",
                                        "id" => "523860695"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            ReassessmentAngerManagement::class => [
                'en'    => [
                    'locale' => strtoupper('en'),
                    'id' => '190291272',
                    'entity' => 'ReassessmentAngerManagement',
                    'contactRelation' => 'reassessmentAngerManagements',
                    'name' => 'Reassessment - Anger Management',
                    'questions' => [
                        [
                            'id' => '63811669',
                            'type' => 'single_choice',
                            'field' => 'q1',
                            'answers' => [
                                'question' => [
                                    'text' => 'Please read and identify the statement that best reflects how you feel:',
                                ],
                                'choices' => [
                                    [
                                        'id' => '523883997',
                                        'text' => 'I do not feel angry.',
                                    ],
                                    [
                                        'id' => '523883998',
                                        'text' => 'I feel angry.',
                                    ],
                                    [
                                        'id' => '523883999',
                                        'text' => 'I am angry most of the time now.',
                                    ],
                                    [
                                        'id' => '523884000',
                                        'text' => 'I am so angry and hostile all the time that I can"t stand it.',
                                    ]
                                ]
                            ]
                        ],
                        [
                            'id' => '63811670',
                            'type' => 'single_choice',
                            'field' => 'q2',
                            'answers' => [
                                'question' => [
                                    'text' => 'Please read and identify the statement that best reflects how you feel:',
                                ],
                                'choices' => [
                                    [
                                        "text" => "I am not particularly angry about my future.",
                                        "id" => "523884006"
                                    ],
                                    [
                                        "text" => "When I think about my future, I feel angry.",
                                        "id" => "523884007"
                                    ],
                                    [
                                        "text" => "I feel angry about what I have to look forward to.",
                                        "id" => "523884008"
                                    ],
                                    [
                                        "text" => "I feel intensely angry about my future, since it cannot be improved.",
                                        "id" => "523884009"
                                    ]
                                ]
                            ]
                        ],
                        [
                            'id' => '63811690',
                            'type' => 'single_choice',
                            'field' => 'q3',
                            'answers' => [
                                'question' => [
                                    'text' => 'Please read and identify the statement that best reflects how you feel:',
                                ],
                                'choices' => [
                                    [
                                        "text" => "It makes me angry that I feel like such a failure.",
                                        "id" => "523884067"
                                    ],
                                    [
                                        "text" => "It makes me angry that I have failed more than the average person.",
                                        "id" => "523884068"
                                    ],
                                    [
                                        "text" => "As I look back on my life, I feel angry about my failures.",
                                        "id" => "523884069"
                                    ],
                                    [
                                        "text" => "It makes me angry to feel like a complete failure as a person.",
                                        "id" => "523884070"
                                    ]
                                ]
                            ]
                        ],
                        [
                            'id' => '63811689',
                            'type' => 'single_choice',
                            'field' => 'q4',
                            'answers' => [
                                'question' => [
                                    'text' => 'Please read and identify the statement that best reflects how you feel:',
                                ],
                                'choices' => [
                                    [
                                        "text" => "I am not all that angry about things.",
                                        "id" => "523884062"
                                    ],
                                    [
                                        "text" => "I am becoming more hostile about things than I used to be.",
                                        "id" => "523884063"
                                    ],
                                    [
                                        "text" => "I am pretty angry about things these days.",
                                        "id" => "523884064"
                                    ],
                                    [
                                        "text" => "I am angry and hostile about everything.",
                                        "id" => "523884065"
                                    ]
                                ]
                            ]
                        ],
                        [
                            'id' => '63811688',
                            'type' => 'single_choice',
                            'field' => 'q5',
                            'answers' => [
                                'question' => [
                                    'text' => 'Please read and identify the statement that best reflects how you feel:',
                                ],
                                'choices' => [
                                    [
                                        "text" => "I don't feel particularly hostile at others.",
                                        "id" => "523884057"
                                    ],
                                    [
                                        "text" => "I feel hostile a good deal of the time.",
                                        "id" => "523884058"
                                    ],
                                    [
                                        "text" => "I feel quite hostile most of the time.",
                                        "id" => "523884059"
                                    ],
                                    [
                                        "text" => "I feel hostile all of the time.",
                                        "id" => "523884060"
                                    ]
                                ]
                            ]
                        ],
                        [
                            'id' => '63811687',
                            'type' => 'single_choice',
                            'field' => 'q6',
                            'answers' => [
                                'question' => [
                                    'text' => 'Please read and identify the statement that best reflects how you feel:',
                                ],
                                'choices' => [
                                    [
                                        "text" => "I don't feel that others are trying to annoy me.",
                                        "id" => "523884052"
                                    ],
                                    [
                                        "text" => "At times I think people are trying to annoy me.",
                                        "id" => "523884053"
                                    ],
                                    [
                                        "text" => "More people than usual are beginning to make me feel angry.",
                                        "id" => "523884054"
                                    ],
                                    [
                                        "text" => "I feel that others are constantly and intentionally making me angry.",
                                        "id" => "523884055"
                                    ]
                                ]
                            ]
                        ],
                        [
                            'id' => '63811686',
                            'type' => 'single_choice',
                            'field' => 'q7',
                            'answers' => [
                                'question' => [
                                    'text' => 'Please read and identify the statement that best reflects how you feel:',
                                ],
                                'choices' => [
                                    [
                                        "text" => "I don't feel angry when I think about myself.",
                                        "id" => "523884047"
                                    ],
                                    [
                                        "text" => "I feel more angry about myself these days than I used to.",
                                        "id" => "523884048"
                                    ],
                                    [
                                        "text" => "I feel angry about myself a good deal of the time.",
                                        "id" => "523884049"
                                    ],
                                    [
                                        "text" => "When I think about myself, I feel intense anger.",
                                        "id" => "523884050"
                                    ]
                                ]
                            ]
                        ],
                        [
                            'id' => '63811685',
                            'type' => 'single_choice',
                            'field' => 'q8',
                            'answers' => [
                                'question' => [
                                    'text' => 'Please read and identify the statement that best reflects how you feel:',
                                ],
                                'choices' => [
                                    [
                                        "text" => "I don't have angry feelings about others having screwed up my life.",
                                        "id" => "523884042"
                                    ],
                                    [
                                        "text" => "It's beginning to make me angry that others are screwing up my life.",
                                        "id" => "523884043"
                                    ],
                                    [
                                        "text" => "I feel angry that others prevent me from having a good life.",
                                        "id" => "523884044"
                                    ],
                                    [
                                        "text" => "I am constantly angry because others have made my life totally miserable.",
                                        "id" => "523884045"
                                    ]
                                ]
                            ]
                        ],
                        [
                            'id' => '63811684',
                            'type' => 'single_choice',
                            'field' => 'q9',
                            'answers' => [
                                'question' => [
                                    'text' => 'Please read and identify the statement that best reflects how you feel:',
                                ],
                                'choices' => [
                                    [
                                        "text" => "I don't feel angry enough to hurt someone.",
                                        "id" => "523884037"
                                    ],
                                    [
                                        "text" => "Sometimes I am so angry that I feel like hurting others, but I would not really do it.",
                                        "id" => "523884038"
                                    ],
                                    [
                                        "text" => "My anger is so intense that I sometimes feel like hurting others.",
                                        "id" => "523884039"
                                    ],
                                    [
                                        "text" => "I'm so angry that I would like to hurt someone.",
                                        "id" => "523884040"
                                    ]
                                ]
                            ]
                        ],
                        [
                            'id' => '63811683',
                            'type' => 'single_choice',
                            'field' => 'q10',
                            'answers' => [
                                'question' => [
                                    'text' => 'Please read and identify the statement that best reflects how you feel:',
                                ],
                                'choices' => [
                                    [
                                        "text" => "I don't shout at people any more than usual.",
                                        "id" => "523884032"
                                    ],
                                    [
                                        "text" => "I shout at others more now than I used to.",
                                        "id" => "523884092"
                                    ],
                                    [
                                        "text" => "I shout at people all the time now.",
                                        "id" => "523884093"
                                    ],
                                    [
                                        "text" => "I shout at others so often that sometimes I just can't stop.",
                                        "id" => "523884094"
                                    ]
                                ]
                            ]
                        ],
                        [
                            'id' => '63811682',
                            'type' => 'single_choice',
                            'field' => 'q11',
                            'answers' => [
                                'question' => [
                                    'text' => 'Please read and identify the statement that best reflects how you feel:',
                                ],
                                'choices' => [
                                    [
                                        "text" => "Things are not more irritating to me now than usual.",
                                        "id" => "523884027"
                                    ],
                                    [
                                        "text" => "I feel slightly more irritated now than usual.",
                                        "id" => "523884096"
                                    ],
                                    [
                                        "text" => "I feel irritated a good deal of the time.",
                                        "id" => "523884097"
                                    ],
                                    [
                                        "text" => "I'm irritated all the time now.",
                                        "id" => "523884098"
                                    ]
                                ]
                            ]
                        ],
                    ]
                ]
            ],
            ReassessmentBipolar::class => [
                'en'    => [
                    'locale' => strtoupper('en'),
                    'id' => '190291273',
                    'entity' => 'ReassessmentBipolar',
                    'contactRelation' => 'reassessmentBipolars',
                    'name' => 'Reassessment - Bipolar Disorder',
                    'questions' => [
                        [
                            'id' => '63808528',
                            'type' => 'text',
                            'field' => 'email',
                        ],
                        [
                            'id' => '63808529',
                            'type' => 'matrix',
                            'answers' => [
                                'rows' => [
                                    [
                                        "field" => "q1",
                                        "text" => "you felt so good or so hyper that other people thought you were not your normal self or you were so hyper that you got into trouble?",
                                        "id" => "523860743"
                                    ],
                                    [
                                        "field" => "q2",
                                        "text" => "you were so irritable that you shouted at people or started fights or arguments?",
                                        "id" => "523860744"
                                    ],
                                    [
                                        "field" => "q3",
                                        "text" => "you felt much more self-confident than usual?",
                                        "id" => "523860750"
                                    ],
                                    [
                                        "field" => "q4",
                                        "text" => "you got much less sleep than usual and found you didn’t really miss it?",
                                        "id" => "523860751"
                                    ],
                                    [
                                        "field" => "q5",
                                        "text" => "you were much more talkative or spoke faster than usual?",
                                        "id" => "523860752"
                                    ],
                                    [
                                        "field" => "q6",
                                        "text" => "thoughts raced through your head or you couldn’t slow your mind down?",
                                        "id" => "523860753"
                                    ],
                                    [
                                        "field" => "q7",
                                        "text" => "you were so easily distracted by things around you that you had trouble concentrating or staying on track?",
                                        "id" => "523860756"
                                    ],
                                    [
                                        "field" => "q8",
                                        "text" => "you had much more energy than usual?",
                                        "id" => "523860757"
                                    ],
                                    [
                                        "field" => "q9",
                                        "text" => "you were much more active or did many more things than usual?",
                                        "id" => "523860758"
                                    ],
                                    [
                                        "field" => "q10",
                                        "text" => "you were much more social or outgoing than usual, for example, you telephoned friends in the middle of the night?",
                                        "id" => "523860759"
                                    ],
                                    [
                                        "field" => "q11",
                                        "text" => "you were much more interested in sex than usual?",
                                        "id" => "523860760"
                                    ],
                                    [
                                        "field" => "q12",
                                        "text" => "you did things that were unusual for you or that other people might have thought were excessive, foolish, or risky?",
                                        "id" => "523860761"
                                    ],
                                    [
                                        "field" => "q13",
                                        "text" => "spending money got you or your family in trouble?",
                                        "id" => "523860762"
                                    ],
                                    [
                                        "field" => "q14",
                                        "text" => "If you checked YES to more than one of the above, have several of these ever happened during the same period of time?",
                                        "id" => "523860763"
                                    ],
                                    [
                                        "field" => "q15",
                                        "text" => "How much of a problem did any of these cause you — like being able to work; having family, money, or legal troubles; getting into arguments or fights?",
                                        "id" => "523860764"
                                    ],
                                    [
                                        "field" => "q16",
                                        "text" => "Have any of your blood relatives (ie, children, siblings, parents, grandparents, aunts, uncles) had manic-depressive illness or bipolar disorder?",
                                        "id" => "523860765"
                                    ],
                                    [
                                        "field" => "q17",
                                        "text" => "Has a health professional ever told you that you have manic-depressive illness or bipolar disorder?",
                                        "id" => "523860766"
                                    ]
                                ],
                                'choices' => [
                                    [
                                        'id' => '523860745',
                                        'text' => 'Yes'
                                    ],
                                    [
                                        'id' => '523860746',
                                        'text' => 'No'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            ReassessmentDepression::class => [
                'en'    => [
                    'locale' => strtoupper('en'),
                    'id' => '190291274',
                    'entity' => 'ReassessmentDepression',
                    'contactRelation' => 'reassessmentDepressions',
                    'name' => 'Reassessment - Depression Disorder',
                    'questions' => [
                        [
                            'id' => '63808530',
                            'type' => 'text',
                            'field' => 'email',
                        ],
                        [
                            'id' => '63808531',
                            'type' => 'matrix',
                            'answers' => [
                                'rows' => [
                                    [
                                        "field" => "q1",
                                        "text" => "Little interest or pleasure in doing things",
                                        "id" => "523860774"
                                    ],
                                    [
                                        "field" => "q2",
                                        "text" => "Feeling down, depressed, or hopeless",
                                        "id" => "523860775"
                                    ],
                                    [
                                        "field" => "q3",
                                        "text" => "Trouble falling or staying asleep, or sleeping too much",
                                        "id" => "523860780"
                                    ],
                                    [
                                        "field" => "q4",
                                        "text" => "Feeling tired or having little energy",
                                        "id" => "523860781"
                                    ],
                                    [
                                        "field" => "q5",
                                        "text" => "Poor appetite or overeating",
                                        "id" => "523860782"
                                    ],
                                    [
                                        "field" => "q6",
                                        "text" => "Feeling bad about yourself, or that you are a failure, or have let yourself or your family down",
                                        "id" => "523860783"
                                    ],
                                    [
                                        "field" => "q7",
                                        "text" => "Trouble concentrating on things, such as reading or watching television",
                                        "id" => "523860786"
                                    ],
                                    [
                                        "field" => "q8",
                                        "text" => "Moving or speaking so slowly that other people could have noticed? OR the opposite-being so fidgety or restless that you have been moving around a lot more than usual?",
                                        "id" => "523860787"
                                    ],
                                    [
                                        "field" => "q9",
                                        "text" => "Thoughts that you would be better off dead or of hurting yourself in some way",
                                        "id" => "523860788"
                                    ]
                                ],
                                'choices' => [
                                    [
                                        "text" => "Not at all",
                                        "id" => "523860776",
                                    ],
                                    [
                                        "text" => "Several days",
                                        "id" => "523860777",
                                    ],
                                    [
                                        "text" => "More than half the days",
                                        "id" => "523860797",
                                    ],
                                    [
                                        "text" => "Nearly every day",
                                        "id" => "523860798",
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            ReassessmentEatingDisorder::class => [
                'en'    => [
                    'locale' => strtoupper('en'),
                    'id' => '190291275',
                    'entity' => 'ReassessmentEatingDisorder',
                    'contactRelation' => 'reassessmentEatingDisorders',
                    'name' => 'Reassessment - Eating Disorder',
                    'questions' => [
                        [
                            'id' => '63808532',
                            'type' => 'text',
                            'field' => 'email',
                        ],
                        [
                            'id' => '63808533',
                            'type' => 'matrix',
                            'answers' => [
                                'rows' => [
                                    [
                                        "field" => "q1",
                                        "text" => "Am terrified about being overweight.",
                                        "id" => "523860801"
                                    ],
                                    [
                                        "field" => "q2",
                                        "text" => "Avoid eating when I am hungry.",
                                        "id" => "523860802"
                                    ],
                                    [
                                        "field" => "q3",
                                        "text" => "Find myself preoccupied with food.",
                                        "id" => "523860807"
                                    ],
                                    [
                                        "field" => "q4",
                                        "text" => "Have gone on eating binges where I feel that I may not be able to stop.",
                                        "id" => "523860808"
                                    ],
                                    [
                                        "field" => "q5",
                                        "text" => "Cut my food into small pieces.",
                                        "id" => "523860809"
                                    ],
                                    [
                                        "field" => "q6",
                                        "text" => "Aware of the calorie content of foods that I eat.",
                                        "id" => "523860810"
                                    ],
                                    [
                                        "field" => "q7",
                                        "text" => "Particularly avoid food with a high carbohydrate content (i.e. bread, rice, potatoes, etc.)",
                                        "id" => "523860813"
                                    ],
                                    [
                                        "field" => "q8",
                                        "text" => "Feel that others would prefer if I ate more.",
                                        "id" => "523860814"
                                    ],
                                    [
                                        "field" => "q9",
                                        "text" => "Vomit after I have eaten.",
                                        "id" => "523860815"
                                    ],
                                    [
                                        "field" => "q10",
                                        "text" => "Feel extremely guilty after eating.",
                                        "id" => "523860818"
                                    ],
                                    [
                                        "field" => "q11",
                                        "text" => "Am preoccupied with a desire to be thinner.",
                                        "id" => "523860819"
                                    ],
                                    [
                                        "field" => "q12",
                                        "text" => "Think about burning up calories when I exercise.",
                                        "id" => "523860820"
                                    ],
                                    [
                                        "field" => "q13",
                                        "text" => "Other people think that I am too thin.",
                                        "id" => "523860821"
                                    ],
                                    [
                                        "field" => "q14",
                                        "text" => "Am preoccupied with the thought of having fat on my body",
                                        "id" => "523860822"
                                    ],
                                    [
                                        "field" => "q15",
                                        "text" => "Take longer than others to eat my meals.",
                                        "id" => "523860823"
                                    ],
                                    [
                                        "field" => "q16",
                                        "text" => "Avoid foods with sugar in them.",
                                        "id" => "523860824"
                                    ],
                                    [
                                        "field" => "q17",
                                        "text" => "Eat diet foods.",
                                        "id" => "523860825"
                                    ],
                                    [
                                        "field" => "q18",
                                        "text" => "Feel that food controls my life.",
                                        "id" => "523860826"
                                    ],
                                    [
                                        "field" => "q19",
                                        "text" => "Display self-control around food.",
                                        "id" => "523860827"
                                    ]
                                ],
                                'choices' => [
                                    [
                                        "text" => "Always",
                                        "id" => "523860803",
                                    ],
                                    [
                                        "text" => "Usually",
                                        "id" => "523860804",
                                    ],
                                    [
                                        "text" => "Often",
                                        "id" => "523860816",
                                    ],
                                    [
                                        "text" => "Sometimes",
                                        "id" => "523860817",
                                    ],
                                    [
                                        "text" => "Rarely",
                                        "id" => "523860828",
                                    ],
                                    [
                                        "text" => "Never",
                                        "id" => "523860829",
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            ReassessmentOCD::class => [
                'en'    => [
                    'locale' => strtoupper('en'),
                    'id' => '190291276',
                    'entity' => 'ReassessmentOCD',
                    'contactRelation' => 'reassessmentOCDs',
                    'name' => 'Reassessment - OCD',
                    'questions' => [
                        [
                            'id' => '63808534',
                            'type' => 'text',
                            'field' => 'email',
                        ],
                        [
                            'id' => '63808535',
                            'type' => 'matrix',
                            'answers' => [
                                'rows' => [
                                    [
                                        "field" => "q1",
                                        "text" => "How much of your time is occupied by obsessive thoughts?",
                                        "id" => "523860832"
                                    ],
                                    [
                                        "field" => "q2",
                                        "text" => "How much do your obsessive thoughts interfere with your work, school, social, or other important role functioning? Is there anything that you don’t do because of them?",
                                        "id" => "523860833"
                                    ],
                                    [
                                        "field" => "q3",
                                        "text" => "How much distress do your obsessive thoughts cause you?",
                                        "id" => "523860838"
                                    ],
                                    [
                                        "field" => "q4",
                                        "text" => "How much of an effort do you make to resist the obsessive thoughts? How often do you try to disregard or turn your attention away from these thoughts as they enter your mind?",
                                        "id" => "523860861"
                                    ],
                                    [
                                        "field" => "q5",
                                        "text" => "How much control do you have over your obsessive thoughts? How successful are you in stopping or diverting your obsessive thinking? Can you dismiss them?",
                                        "id" => "523860862"
                                    ],
                                    [
                                        "field" => "q6",
                                        "text" => "How much time do you spend performing compulsive behaviors? How much longer than most people does it take to complete routine activities because of your rituals? How frequently do you do rituals?",
                                        "id" => "523860863"
                                    ],
                                    [
                                        "field" => "q7",
                                        "text" => "How much do your compulsive behaviors interfere with your work, school, social, or other important role functioning? Is there anything that you don’t do because of the compulsions?",
                                        "id" => "523860864"
                                    ],
                                    [
                                        "field" => "q8",
                                        "text" => "How would you feel if prevented from performing your compulsion(s)? How anxious would you become?",
                                        "id" => "523860865"
                                    ],
                                    [
                                        "field" => "q9",
                                        "text" => "How much of an effort do you make to resist the compulsions?",
                                        "id" => "523860866"
                                    ],
                                    [
                                        "field" => "q10",
                                        "text" => "How strong is the drive to perform the compulsive behavior? How much control do you have over the compulsions?",
                                        "id" => "523860867"
                                    ]
                                ],
                                'choices' => [
                                    [
                                        "text" => "Complete control",
                                        "id" => "523860834",
                                    ],
                                    [
                                        "text" => "Pressure to perform the behavior but usually able to exercise voluntary control over it",
                                        "id" => "523860835",
                                    ],
                                    [
                                        "text" => "Strong pressure to perform behavior, can control it only with difficulty",
                                        "id" => "523860847",
                                    ],
                                    [
                                        "text" => "Very strong drive to perform behavior, must be carried to completion, can only delay with difficulty",
                                        "id" => "523860848",
                                    ],
                                    [
                                        "text" => "Drive to perform behavior experienced as completely involuntary and overpowering, rarely able",
                                        "id" => "523860859",
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            ReassessmentPanicDisorder::class => [
                'en'    => [
                    'locale' => strtoupper('en'),
                    'id' => '190291277',
                    'entity' => 'ReassessmentPanicDisorder',
                    'contactRelation' => 'reassessmentPanicDisorders',
                    'name' => 'Reassessment - Panic Disorder',
                    'questions' => [
                        [
                            'id' => '63808536',
                            'type' => 'text',
                            'field' => 'email',
                        ],
                        [
                            'id' => '63808537',
                            'type' => 'matrix',
                            'answers' => [
                                'rows' => [
                                    [
                                        'field' => 'q1',
                                        "text" => "Felt moments of sudden terror, fear or fright, sometimes out of the blue (i.e., a panic attack)",
                                        "id" => "523860870"
                                    ],
                                    [
                                        'field' => 'q2',
                                        "text" => "Felt anxious, worried, or nervous about having more panic attacks",
                                        "id" => "523860871"
                                    ],
                                    [
                                        'field' => 'q3',
                                        "text" => "Had thoughts of losing control, dying, going crazy, or other bad things happening because of panic attacks",
                                        "id" => "523860876"
                                    ],
                                    [
                                        'field' => 'q4',
                                        "text" => "Felt a racing heart, sweaty, trouble breathing, faint, or shaky",
                                        "id" => "523860882"
                                    ],
                                    [
                                        'field' => 'q5',
                                        "text" => "Felt tense muscles, felt on edge or restless, or had trouble relaxing or trouble sleeping",
                                        "id" => "523860883"
                                    ],
                                    [
                                        'field' => 'q6',
                                        "text" => "Avoided, or did not approach or enter, situations in which panic attacks might occur",
                                        "id" => "523860884"
                                    ],
                                    [
                                        'field' => 'q7',
                                        "text" => "Left situations early, or participated only minimally, because of panic attacks",
                                        "id" => "523860885"
                                    ],
                                    [
                                        'field' => 'q8',
                                        "text" => "spent a lot of time preparing for, or procrastinating about (putting off), situations in which panic attacks might occur",
                                        "id" => "523860886"
                                    ],
                                    [
                                        'field' => 'q9',
                                        "text" => "Distracted myself to avoid thinking about panic attacks",
                                        "id" => "523860887"
                                    ],
                                    [
                                        'field' => 'q10',
                                        "text" => "Needed help to cope with panic attacks (e.g., alcohol or medication, superstitious objects, other people)",
                                        "id" => "523860888"
                                    ]
                                ],
                                'choices' => [
                                    [
                                        "text" => "Never",
                                        "id" => "523860872",
                                    ],
                                    [
                                        "text" => "Occasionally",
                                        "id" => "523860873",
                                    ],
                                    [
                                        "text" => "Half of the time",
                                        "id" => "523860879",
                                    ],
                                    [
                                        "text" => "Most of the time",
                                        "id" => "523860880",
                                    ],
                                    [
                                        "text" => "All of the time",
                                        "id" => "523860881",
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            ReassessmentPTSD::class => [
                'en'    => [
                    'locale' => strtoupper('en'),
                    'id' => '190291278',
                    'entity' => 'ReassessmentPTSD',
                    'contactRelation' => 'reassessmentPTSDs',
                    'name' => 'Reassessment - PTSD',
                    'questions' => [
                        [
                            'id' => '63808538',
                            'type' => 'text',
                            'field' => 'email',
                        ],
                        [
                            'id' => '63808539',
                            'type' => 'matrix',
                            'answers' => [
                                'rows' => [
                                    [
                                        'field' => 'q1',
                                        "text" => "had nightmares about the event(s) or thought about the event(s) when you did not want to?",
                                        "id" => "523860891"
                                    ],
                                    [
                                        'field' => 'q2',
                                        "text" => "tried hard not to think about the event(s) or went out of your way to avoid situations that reminded you of the event(s)?",
                                        "id" => "523860892"
                                    ],
                                    [
                                        'field' => 'q3',
                                        "text" => "been constantly on guard, watchful, or easily startled?",
                                        "id" => "523860897"
                                    ],
                                    [
                                        'field' => 'q4',
                                        "text" => "felt numb or detached from people, activities, or your surroundings?",
                                        "id" => "523860903"
                                    ],
                                    [
                                        'field' => 'q5',
                                        "text" => "felt guilty or unable to stop blaming yourself or others for the event(s) or any problems the event(s) may have caused?",
                                        "id" => "523860904"
                                    ]
                                ],
                                'choices' => [
                                    [
                                        'id' => '523860893',
                                        'text' => 'Yes'
                                    ],
                                    [
                                        'id' => '523860894',
                                        'text' => 'No'
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            ReassessmentSAD::class => [
                'en'    => [
                    'locale' => strtoupper('en'),
                    'id' => '190291279',
                    'entity' => 'ReassessmentSAD',
                    'contactRelation' => 'reassessmentSADs',
                    'name' => 'Reassessment - SAD',
                    'questions' => [
                        [
                            'id' => '63808540',
                            'type' => 'text',
                            'field' => 'email',
                        ],
                        [
                            'id' => '63808541',
                            'type' => 'matrix',
                            'answers' => [
                                'rows' => [
                                    [
                                        'field' => 'q1',
                                        "text" => "felt moments of sudden terror, fear, or fright in social situations",
                                        "id" => "523860912"
                                    ],
                                    [
                                        'field' => 'q2',
                                        "text" => "felt anxious, worried, or nervous about social situations",
                                        "id" => "523860913"
                                    ],
                                    [
                                        'field' => 'q3',
                                        "text" => "had thoughts of being rejected, humiliated, embarrassed, ridiculed, or offending others",
                                        "id" => "523860918"
                                    ],
                                    [
                                        'field' => 'q4',
                                        "text" => "felt a racing heart, sweaty, trouble breathing, faint, or shaky in social situations",
                                        "id" => "523860921"
                                    ],
                                    [
                                        'field' => 'q5',
                                        "text" => "felt tense muscles, felt on edge or restless, or had trouble relaxing in social situations",
                                        "id" => "523860922"
                                    ],
                                    [
                                        'field' => 'q6',
                                        "text" => "avoided, or did not approach or enter, social situations",
                                        "id" => "523860923"
                                    ],
                                    [
                                        'field' => 'q7',
                                        "text" => "left social situations early or participated only minimally (e.g., said little, avoided eye contact)",
                                        "id" => "523860924"
                                    ],
                                    [
                                        'field' => 'q8',
                                        "text" => "spent a lot of time preparing what to say or how to act in social situations",
                                        "id" => "523860925"
                                    ],
                                    [
                                        'field' => 'q9',
                                        "text" => "distracted myself to avoid thinking about social situations",
                                        "id" => "523860926"
                                    ],
                                    [
                                        'field' => 'q10',
                                        "text" => "needed help to cope with social situations (e.g., alcohol or medications, superstitious objects)",
                                        "id" => "523860927"
                                    ]
                                ],
                                'choices' => [
                                    [
                                        "text" => "Never",
                                        "id" => "523860914",
                                    ],
                                    [
                                        "text" => "Occasionally",
                                        "id" => "523860915",
                                    ],
                                    [
                                        "text" => "Half of the time",
                                        "id" => "523860928",
                                    ],
                                    [
                                        "text" => "Most of the time",
                                        "id" => "523860929",
                                    ],
                                    [
                                        "text" => "All of the time",
                                        "id" => "523860930",
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            ReassessmentSleepDisorder::class => [
                'en'    => [
                    'locale' => strtoupper('en'),
                    'id' => '190291280',
                    'entity' => 'ReassessmentSleepDisorder',
                    'contactRelation' => 'reassessmentSleepDisorders',
                    'name' => 'Reassessment - Sleep Disorder',
                    'questions' => [
                        [
                            'id' => '63808542',
                            'type' => 'text',
                            'field' => 'email',
                        ],
                        [
                            'id' => '63750420',
                            'type' => 'matrix',
                            'answers' => [
                                'rows' => [
                                    [
                                        'field' => 'q1',
                                        "text" => "Difficulty falling asleep",
                                        "id" => "523860933"
                                    ],
                                    [
                                        'field' => 'q2',
                                        "text" => "Difficulty staying asleep",
                                        "id" => "523860934"
                                    ],
                                    [
                                        'field' => 'q3',
                                        "text" => "Problem waking up too early",
                                        "id" => "523860939"
                                    ],
                                    [
                                        'field' => 'q4',
                                        "text" => "How satisfied/dissatisfied are you with your current sleep patterns?",
                                        "id" => "523860942"
                                    ],
                                    [
                                        'field' => 'q5',
                                        "text" => "How noticeable to others do you think your current sleep problem is in terms of impairing the quality of your life?",
                                        "id" => "523860943"
                                    ],
                                    [
                                        'field' => 'q6',
                                        "text" => "How worried/distressed are you about your current sleep problem?",
                                        "id" => "523860944"
                                    ],
                                    [
                                        'field' => 'q7',
                                        "text" => "To what extent do you consider your sleep problem to interfere with your daily functioning (e.g., daytime fatigue, mood, ability to function at work, or concentration)",
                                        "id" => "523860945"
                                    ]
                                ],
                                'choices' => [
                                    [
                                        "text" => "Not at all noticeable",
                                        "id" => "523860935",
                                    ],
                                    [
                                        "text" => "A little",
                                        "id" => "523860936",
                                    ],
                                    [
                                        "text" => "Somewhat",
                                        "id" => "523860949",
                                    ],
                                    [
                                        "text" => "Much",
                                        "id" => "523860950",
                                    ],
                                    [
                                        "text" => "Very much noticeable",
                                        "id" => "523860951",
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            ReassessmentSUD::class => [
                'en'    => [
                    'locale' => strtoupper('en'),
                    'id' => '190291281',
                    'entity' => 'ReassessmentSUD',
                    'contactRelation' => 'reassessmentSUDs',
                    'name' => 'Reassessment - SUD',
                    'questions' => [
                        [
                            'id' => '63808544',
                            'type' => 'text',
                            'field' => 'email',
                        ],
                        [
                            'id' => '63808545',
                            'type' => 'matrix',
                            'answers' => [
                                'rows' => [
                                    [
                                        'field' => 'q1',
                                        "text" => "Have you used drugs other than those required for medical reasons?",
                                        "id" => "523860954"
                                    ],
                                    [
                                        'field' => 'q2',
                                        "text" => "Do you abuse more than one drug at a time?",
                                        "id" => "523860955"
                                    ],
                                    [
                                        'field' => 'q3',
                                        "text" => "Are you unable to stop abusing drugs when you want to?",
                                        "id" => "523860960"
                                    ],
                                    [
                                        'field' => 'q4',
                                        "text" => "Have you ever had blackouts or flashbacks as a result of drug use?",
                                        "id" => "523860963"
                                    ],
                                    [
                                        'field' => 'q5',
                                        "text" => "Do you ever feel bad or guilty about your drug use?",
                                        "id" => "523860964"
                                    ],
                                    [
                                        'field' => 'q6',
                                        "text" => "Does your spouse (or parents) ever complain about your involvement with drugs?",
                                        "id" => "523860965"
                                    ],
                                    [
                                        'field' => 'q7',
                                        "text" => "Have you neglected your family because of your use of drugs?",
                                        "id" => "523860966"
                                    ],
                                    [
                                        'field' => 'q8',
                                        "text" => "Have you engaged in illegal activities in order to obtain drugs?",
                                        "id" => "523860970"
                                    ],
                                    [
                                        'field' => 'q9',
                                        "text" => "Have you ever experienced withdrawal symptoms (felt sick) when you stopped taking drugs?",
                                        "id" => "523860971"
                                    ],
                                    [
                                        'field' => 'q10',
                                        "text" => "Have you had medical problems as a result of your drug use (e.g. memory loss, hepatitis, convulsions, bleeding)?",
                                        "id" => "523860972"
                                    ]
                                ],
                                'choices' => [
                                    [
                                        'id' => '523860956',
                                        'text' => 'Yes'
                                    ],
                                    [
                                        'id' => '523860957',
                                        'text' => 'No'
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ] : [];
    }

    /**
     * @return bool
     * @throws \Espo\Core\Exceptions\Error
     */
    protected function isProduction()
    {
        $integration = $this->container->get('entityManager')->getEntity('Integration', 'SurveyMonkey');

        return !empty($integration->get('isProduction'));
    }
}