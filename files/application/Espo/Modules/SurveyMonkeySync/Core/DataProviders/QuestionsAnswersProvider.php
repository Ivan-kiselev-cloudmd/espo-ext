<?php

namespace Espo\Modules\SurveyMonkeySync\Core\DataProviders;

use Espo\Core\Container;

/**
 * @property Container container
 */
class QuestionsAnswersProvider
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
            [
                'locale' => strtoupper('en'),
                'id' => '190233788',
                'entity' => 'SatisfactionSurvey',
                'contactRelation' => 'satisfactionSurvey',
                'name' => 'Mental Health Coach Satisfaction Survey',
                'questions' => [
                    [
                        'id' => '63244844',
                        'type' => 'text',
                        'field' => 'email'
                    ],
                    [
                        'id' => '63244845',
                        'type' => 'matrix',
                        'answers' => [
                            'rows' => [
                                [
                                    'id' => '520153957',
                                    'field' => 'resourcesProvidedWereRelevant',
                                    'text' => 'I found the resources provided were relevant to my assessment results.'
                                ],
                                [
                                    'id' => '520153975',
                                    'field' => 'satisfiedWithTheService',
                                    'text' => 'I am satisfied with the service provided.'
                                ],
                                [
                                    'id' => '520153958',
                                    'text' => 'I have a better understanding of my benefits.',
                                    'field' => 'benefitsBetterUnderstanding'
                                ]
                            ],
                            'choices' => [
                                [
                                    'id' => '520153959',
                                    'text' => 'Strongly Agree'
                                ],
                                [
                                    'id' => '520153960',
                                    'text' => 'Agree'
                                ],
                                [
                                    'id' => '520153961',
                                    'text' => 'Neutral'
                                ],
                                [
                                    'id' => '520153962',
                                    'text' => 'Disagree'
                                ],
                                [
                                    'id' => '520153963',
                                    'text' => 'Strongly Disagree'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => '63256295',
                        'type' => 'matrix',
                        'answers' => [
                            'rows' => [
                                [
                                    'id' => '520230706',
                                    'text' => '',
                                    'field' => 'recommendServiceToOthers'
                                ]
                            ],
                            'choices' => [
                                [
                                    'id' => '520230707',
                                    'text' => 'Very Likely'
                                ],
                                [
                                    'id' => '520230708',
                                    'text' => 'Somewhat Likely'
                                ],
                                [
                                    'id' => '520230709',
                                    'text' => 'Not Sure'
                                ],
                                [
                                    'id' => '520230710',
                                    'text' => 'Not Likely'
                                ],
                                [
                                    'id' => '520230711',
                                    'text' => 'Not Likely At All'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => '63244846',
                        'type' => 'multiple_choice',
                        'field' => 'whatMadeYouEngageInTheService',
                        'answers' => [
                            'other' => [
                                'text' => 'Other (please specify)',
                                'id' => '520153969',
                                'field' => 'whatMadeYouEngageInTheServiceOther'
                            ],
                            'choices' => [
                                [
                                    'id' => '520153965',
                                    'field' => 'whatMadeYouEngageInTheService',
                                    'text' => 'I was curious and wanted to see what it was about.'
                                ],
                                [
                                    'id' => '520153966',
                                    'field' => 'whatMadeYouEngageInTheService',
                                    'text' => 'I thought this would be a therapy session.'
                                ],
                                [
                                    'id' => '520153967',
                                    'field' => 'whatMadeYouEngageInTheService',
                                    'text' => 'I feel like I could use some help/support with my mental health.'
                                ],
                                [
                                    'id' => '520153968',
                                    'field' => 'whatMadeYouEngageInTheService',
                                    'text' => 'I was advised to try it.'
                                ]
                            ]
                        ]
                    ],
                ]
            ],
            [
                'locale' => strtoupper('fr'),
                'id' => '190233792',
                'entity' => 'SatisfactionSurvey',
                'contactRelation' => 'satisfactionSurvey',
                'name' => 'Coach en Santé Mentale Enquête de Satisfaction',
                'questions' => [
                    [
                        'id' => '63244865',
                        'type' => 'text',
                        'field' => 'email'
                    ],
                    [
                        'id' => '63244866',
                        'type' => 'matrix',
                        'answers' => [
                            'rows' => [
                                [
                                    'id' => '520154077',
                                    'field' => 'resourcesProvidedWereRelevant',
                                    'text' => 'I found the resources provided were relevant to my assessment results.'
                                ],
                                [
                                    'id' => '520154095',
                                    'field' => 'satisfiedWithTheService',
                                    'text' => 'I am satisfied with the service provided.'
                                ],
                                [
                                    'id' => '520154078',
                                    'text' => 'I have a better understanding of my benefits.',
                                    'field' => 'benefitsBetterUnderstanding'
                                ]
                            ],
                            'choices' => [
                                [
                                    'id' => '520154079',
                                    'text' => 'Strongly Agree'
                                ],
                                [
                                    'id' => '520154080',
                                    'text' => 'Agree'
                                ],
                                [
                                    'id' => '520154081',
                                    'text' => 'Neutral'
                                ],
                                [
                                    'id' => '520154082',
                                    'text' => 'Disagree'
                                ],
                                [
                                    'id' => '520154083',
                                    'text' => 'Strongly Disagree'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => '63244864',
                        'type' => 'matrix',
                        'answers' => [
                            'rows' => [
                                [
                                    'id' => '520154084',
                                    'text' => '',
                                    'field' => 'recommendServiceToOthers'
                                ]
                            ],
                            'choices' => [
                                [
                                    'id' => '520154070',
                                    'text' => 'Very Likely'
                                ],
                                [
                                    'id' => '520154071',
                                    'text' => 'Somewhat Likely'
                                ],
                                [
                                    'id' => '520154072',
                                    'text' => 'Not Sure'
                                ],
                                [
                                    'id' => '520154073',
                                    'text' => 'Not Likely'
                                ],
                                [
                                    'id' => '520154074',
                                    'text' => 'Not Likely At All'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => '63244867',
                        'type' => 'multiple_choice',
                        'field' => 'whatMadeYouEngageInTheService',
                        'answers' => [
                            'other' => [
                                'text' => 'Other (please specify)',
                                'id' => '520154089',
                                'field' => 'whatMadeYouEngageInTheServiceOther'
                            ],
                            'choices' => [
                                [
                                    'id' => '520154085',
                                    'field' => 'whatMadeYouEngageInTheService',
                                    'text' => 'I was curious and wanted to see what it was about.'
                                ],
                                [
                                    'id' => '520154086',
                                    'field' => 'whatMadeYouEngageInTheService',
                                    'text' => 'I thought this would be a therapy session.'
                                ],
                                [
                                    'id' => '520154087',
                                    'field' => 'whatMadeYouEngageInTheService',
                                    'text' => 'I feel like I could use some help/support with my mental health.'
                                ],
                                [
                                    'id' => '520154088',
                                    'field' => 'whatMadeYouEngageInTheService',
                                    'text' => 'I was advised to try it.'
                                ]
                            ]
                        ]
                    ],
                ]
            ],
            [
                'locale' => strtoupper('en'),
                'id' => '190233789',
                'entity' => 'SatisfactionSurveyYAP',
                'contactRelation' => 'satisfactionSurveyYAP',
                'name' => 'Mental Health Coach Satisfaction Survey',
                'questions' => [
                    [
                        'id' => '63244852',
                        'type' => 'text',
                        'field' => 'email'
                    ],
                    [
                        'id' => '63244853',
                        'type' => 'matrix',
                        'answers' => [
                            'rows' => [
                                [
                                    'id' => '520153989',
                                    'field' => 'iHaveNoticedAnImprovement',
                                    'text' => 'I have noticed an improvement in the mental health symptoms identified and discussed with my Coach at the start of this service.'
                                ],
                                [
                                    'id' => '520153990',
                                    'field' => 'iHadAPositiveExperienceWithMyCoach',
                                    'text' => 'I had a positive experience with my coach.'
                                ],
                                [
                                    'id' => '520153999',
                                    'text' => 'The materials my coach presented me with were useful.',
                                    'field' => 'materialsUseful'
                                ],
                                [
                                    'id' => '520154000',
                                    'text' => 'I have a better understanding of my benefits.',
                                    'field' => 'benefitsBetterUnderstanding'
                                ]
                            ],
                            'choices' => [
                                [
                                    'id' => '520153991',
                                    'text' => 'Strongly Agree'
                                ],
                                [
                                    'id' => '520153992',
                                    'text' => 'Agree'
                                ],
                                [
                                    'id' => '520153993',
                                    'text' => 'Neutral'
                                ],
                                [
                                    'id' => '520153994',
                                    'text' => 'Disagree'
                                ],
                                [
                                    'id' => '520153995',
                                    'text' => 'Strongly Disagree'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => '63244851',
                        'type' => 'matrix',
                        'answers' => [
                            'rows' => [
                                [
                                    'id' => '520153996',
                                    'text' => '',
                                    'field' => 'recommendServiceToOthers'
                                ]
                            ],
                            'choices' => [
                                [
                                    'id' => '520153982',
                                    'text' => 'Very Likely'
                                ],
                                [
                                    'id' => '520153983',
                                    'text' => 'Somewhat Likely'
                                ],
                                [
                                    'id' => '520153984',
                                    'text' => 'Not Sure'
                                ],
                                [
                                    'id' => '520153985',
                                    'text' => 'Not Likely'
                                ],
                                [
                                    'id' => '520153986',
                                    'text' => 'Not Likely At All'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => '63244854',
                        'type' => 'text',
                        'field' => 'thoughtsaboutyourexperience'
                    ],
                ]
            ],
            [
                'locale' => strtoupper('fr'),
                'id' => '190233796',
                'entity' => 'SatisfactionSurveyYAP',
                'contactRelation' => 'satisfactionSurveyYAP',
                'name' => 'Coach en Santé Mentale Enquête de Satisfaction',
                'questions' => [
                    [
                        'id' => '63244887',
                        'type' => 'text',
                        'field' => 'email'
                    ],
                    [
                        'id' => '63244888',
                        'type' => 'matrix',
                        'answers' => [
                            'rows' => [
                                [
                                    'id' => '520154237',
                                    'field' => 'iHaveNoticedAnImprovement',
                                    'text' => 'I have noticed an improvement in the mental health symptoms identified and discussed with my Coach at the start of this service.'
                                ],
                                [
                                    'id' => '520154246',
                                    'field' => 'iHadAPositiveExperienceWithMyCoach',
                                    'text' => 'I had a positive experience with my coach.'
                                ],
                                [
                                    'id' => '520154247',
                                    'text' => 'The materials my coach presented me with were useful.',
                                    'field' => 'materialsUseful'
                                ],
                                [
                                    'id' => '520154248',
                                    'text' => 'I have a better understanding of my benefits.',
                                    'field' => 'benefitsBetterUnderstanding'
                                ]
                            ],
                            'choices' => [
                                [
                                    'id' => '520154238',
                                    'text' => 'Strongly Agree'
                                ],
                                [
                                    'id' => '520154239',
                                    'text' => 'Agree'
                                ],
                                [
                                    'id' => '520154240',
                                    'text' => 'Neutral'
                                ],
                                [
                                    'id' => '520154241',
                                    'text' => 'Disagree'
                                ],
                                [
                                    'id' => '520154242',
                                    'text' => 'Strongly Disagree'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => '63244886',
                        'type' => 'matrix',
                        'answers' => [
                            'rows' => [
                                [
                                    'id' => '520154243',
                                    'text' => '',
                                    'field' => 'recommendServiceToOthers'
                                ]
                            ],
                            'choices' => [
                                [
                                    'id' => '520154230',
                                    'text' => 'Very Likely'
                                ],
                                [
                                    'id' => '520154231',
                                    'text' => 'Somewhat Likely'
                                ],
                                [
                                    'id' => '520154232',
                                    'text' => 'Not Sure'
                                ],
                                [
                                    'id' => '520154233',
                                    'text' => 'Not Likely'
                                ],
                                [
                                    'id' => '520154234',
                                    'text' => 'Not Likely At All'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => '63244889',
                        'type' => 'text',
                        'field' => 'thoughtsaboutyourexperience'
                    ],
                ]
            ],
        ] : [
            [
                'locale' => strtoupper('en'),
                'id' => '190200153',
                'entity' => 'SatisfactionSurvey',
                'contactRelation' => 'satisfactionSurvey',
                'name' => 'Mental Health Coach Satisfaction Survey',
                'questions' => [
                    [
                        'id' => '62982651',
                        'type' => 'text',
                        'field' => 'email'
                    ],
                    [
                        'id' => '62982652',
                        'type' => 'matrix',
                        'answers' => [
                            'rows' => [
                                [
                                    'id' => '518432868',
                                    'field' => 'resourcesProvidedWereRelevant',
                                    'text' => 'I found the resources provided were relevant to my assessment results.'
                                ],
                                [
                                    'id' => '518542354',
                                    'field' => 'satisfiedWithTheService',
                                    'text' => 'I am satisfied with the service provided.'
                                ],
                                [
                                    'id' => '518432869',
                                    'text' => 'I have a better understanding of my benefits.',
                                    'field' => 'benefitsBetterUnderstanding'
                                ]
                            ],
                            'choices' => [
                                [
                                    'id' => '518432873',
                                    'text' => 'Strongly Agree'
                                ],
                                [
                                    'id' => '518432874',
                                    'text' => 'Agree'
                                ],
                                [
                                    'id' => '518432875',
                                    'text' => 'Neutral'
                                ],
                                [
                                    'id' => '518432876',
                                    'text' => 'Disagree'
                                ],
                                [
                                    'id' => '518432877',
                                    'text' => 'Strongly Disagree'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => '62982650',
                        'type' => 'matrix',
                        'answers' => [
                            'rows' => [
                                [
                                    'id' => '518432879',
                                    'text' => '',
                                    'field' => 'recommendServiceToOthers'
                                ]
                            ],
                            'choices' => [
                                [
                                    'id' => '518432861',
                                    'text' => 'Very Likely'
                                ],
                                [
                                    'id' => '518432862',
                                    'text' => 'Somewhat Likely'
                                ],
                                [
                                    'id' => '518432863',
                                    'text' => 'Not Sure'
                                ],
                                [
                                    'id' => '518432864',
                                    'text' => 'Not Likely'
                                ],
                                [
                                    'id' => '518432865',
                                    'text' => 'Not Likely At All'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => '62982653',
                        'type' => 'multiple_choice',
                        'field' => 'whatMadeYouEngageInTheService',
                        'answers' => [
                            'other' => [
                                'text' => 'Other (please specify)',
                                'id' => '518432884',
                                'field' => 'whatMadeYouEngageInTheServiceOther'
                            ],
                            'choices' => [
                                [
                                    'id' => '518432880',
                                    'field' => 'whatMadeYouEngageInTheService',
                                    'text' => 'I was curious and wanted to see what it was about.'
                                ],
                                [
                                    'id' => '518432881',
                                    'field' => 'whatMadeYouEngageInTheService',
                                    'text' => 'I thought this would be a therapy session.'
                                ],
                                [
                                    'id' => '518432882',
                                    'field' => 'whatMadeYouEngageInTheService',
                                    'text' => 'I feel like I could use some help/support with my mental health.'
                                ],
                                [
                                    'id' => '518432883',
                                    'field' => 'whatMadeYouEngageInTheService',
                                    'text' => 'I was advised to try it.'
                                ]
                            ]
                        ]
                    ],
                ]
            ],
            [
                'locale' => strtoupper('fr'),
                'id' => '190201596',
                'entity' => 'SatisfactionSurvey',
                'contactRelation' => 'satisfactionSurvey',
                'name' => 'Coach en Santé Mentale Enquête de Satisfaction',
                'questions' => [
                    [
                        'id' => '63000629',
                        'type' => 'text',
                        'field' => 'email'
                    ],
                    [
                        'id' => '63000630',
                        'type' => 'matrix',
                        'answers' => [
                            'rows' => [
                                [
                                    'id' => '518544942',
                                    'field' => 'resourcesProvidedWereRelevant',
                                    'text' => 'I found the resources provided were relevant to my assessment results.'
                                ],
                                [
                                    'id' => '518544960',
                                    'field' => 'satisfiedWithTheService',
                                    'text' => 'I am satisfied with the service provided.'
                                ],
                                [
                                    'id' => '518544943',
                                    'text' => 'I have a better understanding of my benefits.',
                                    'field' => 'benefitsBetterUnderstanding'
                                ]
                            ],
                            'choices' => [
                                [
                                    'id' => '518544944',
                                    'text' => 'Strongly Agree'
                                ],
                                [
                                    'id' => '518544945',
                                    'text' => 'Agree'
                                ],
                                [
                                    'id' => '518544945',
                                    'text' => 'Neutral'
                                ],
                                [
                                    'id' => '518544947',
                                    'text' => 'Disagree'
                                ],
                                [
                                    'id' => '518544948',
                                    'text' => 'Strongly Disagree'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => '63000628',
                        'type' => 'matrix',
                        'answers' => [
                            'rows' => [
                                [
                                    'id' => '518544949',
                                    'text' => '',
                                    'field' => 'recommendServiceToOthers'
                                ]
                            ],
                            'choices' => [
                                [
                                    'id' => '518544935',
                                    'text' => 'Very Likely'
                                ],
                                [
                                    'id' => '518544936',
                                    'text' => 'Somewhat Likely'
                                ],
                                [
                                    'id' => '518544937',
                                    'text' => 'Not Sure'
                                ],
                                [
                                    'id' => '518544938',
                                    'text' => 'Not Likely'
                                ],
                                [
                                    'id' => '518544939',
                                    'text' => 'Not Likely At All'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => '63000631',
                        'type' => 'multiple_choice',
                        'field' => 'whatMadeYouEngageInTheService',
                        'answers' => [
                            'other' => [
                                'text' => 'Other (please specify)',
                                'id' => '518544954',
                                'field' => 'whatMadeYouEngageInTheServiceOther'
                            ],
                            'choices' => [
                                [
                                    'id' => '518544950',
                                    'field' => 'whatMadeYouEngageInTheService',
                                    'text' => 'I was curious and wanted to see what it was about.'
                                ],
                                [
                                    'id' => '518544951',
                                    'field' => 'whatMadeYouEngageInTheService',
                                    'text' => 'I thought this would be a therapy session.'
                                ],
                                [
                                    'id' => '518544952',
                                    'field' => 'whatMadeYouEngageInTheService',
                                    'text' => 'I feel like I could use some help/support with my mental health.'
                                ],
                                [
                                    'id' => '518544953',
                                    'field' => 'whatMadeYouEngageInTheService',
                                    'text' => 'I was advised to try it.'
                                ]
                            ]
                        ]
                    ],
                ]
            ],
            [
                'locale' => strtoupper('en'),
                'id' => '190200158',
                'entity' => 'SatisfactionSurveyYAP',
                'contactRelation' => 'satisfactionSurveyYAP',
                'name' => 'Mental Health Coach Satisfaction Survey',
                'questions' => [
                    [
                        'id' => '62982683',
                        'type' => 'text',
                        'field' => 'email'
                    ],
                    [
                        'id' => '62982684',
                        'type' => 'matrix',
                        'answers' => [
                            'rows' => [
                                [
                                    'id' => '518433007',
                                    'field' => 'iHaveNoticedAnImprovement',
                                    'text' => 'I have noticed an improvement in the mental health symptoms identified and discussed with my Coach at the start of this service.'
                                ],
                                [
                                    'id' => '518433008',
                                    'field' => 'iHadAPositiveExperienceWithMyCoach',
                                    'text' => 'I had a positive experience with my coach.'
                                ],
                                [
                                    'id' => '518542375',
                                    'text' => 'The materials my coach presented me with were useful.',
                                    'field' => 'materialsUseful'
                                ],
                                [
                                    'id' => '518613944',
                                    'text' => 'I have a better understanding of my benefits.',
                                    'field' => 'benefitsBetterUnderstanding'
                                ]
                            ],
                            'choices' => [
                                [
                                    'id' => '518433009',
                                    'text' => 'Strongly Agree'
                                ],
                                [
                                    'id' => '518433010',
                                    'text' => 'Agree'
                                ],
                                [
                                    'id' => '518433011',
                                    'text' => 'Neutral'
                                ],
                                [
                                    'id' => '518433012',
                                    'text' => 'Disagree'
                                ],
                                [
                                    'id' => '518433013',
                                    'text' => 'Strongly Disagree'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => '62982682',
                        'type' => 'matrix',
                        'answers' => [
                            'rows' => [
                                [
                                    'id' => '518433014',
                                    'text' => '',
                                    'field' => 'recommendServiceToOthers'
                                ]
                            ],
                            'choices' => [
                                [
                                    'id' => '518433000',
                                    'text' => 'Very Likely'
                                ],
                                [
                                    'id' => '518433001',
                                    'text' => 'Somewhat Likely'
                                ],
                                [
                                    'id' => '518433002',
                                    'text' => 'Not Sure'
                                ],
                                [
                                    'id' => '518433003',
                                    'text' => 'Not Likely'
                                ],
                                [
                                    'id' => '518433004',
                                    'text' => 'Not Likely At All'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => '63011799',
                        'type' => 'text',
                        'field' => 'thoughtsaboutyourexperience'
                    ],
                ]
            ],
            [
                'locale' => strtoupper('fr'),
                'id' => '190201598',
                'entity' => 'SatisfactionSurveyYAP',
                'contactRelation' => 'satisfactionSurveyYAP',
                'name' => 'Coach en Santé Mentale Enquête de Satisfaction',
                'questions' => [
                    [
                        'id' => '63000642',
                        'type' => 'text',
                        'field' => 'email'
                    ],
                    [
                        'id' => '63000643',
                        'type' => 'matrix',
                        'answers' => [
                            'rows' => [
                                [
                                    'id' => '518545052',
                                    'field' => 'iHaveNoticedAnImprovement',
                                    'text' => 'I have noticed an improvement in the mental health symptoms identified and discussed with my Coach at the start of this service.'
                                ],
                                [
                                    'id' => '518545070',
                                    'field' => 'iHadAPositiveExperienceWithMyCoach',
                                    'text' => 'I had a positive experience with my coach.'
                                ],
                                [
                                    'id' => '518545121',
                                    'text' => 'The materials my coach presented me with were useful.',
                                    'field' => 'materialsUseful'
                                ],
                                [
                                    'id' => '518614187',
                                    'text' => 'I have a better understanding of my benefits.',
                                    'field' => 'benefitsBetterUnderstanding'
                                ]
                            ],
                            'choices' => [
                                [
                                    'id' => '518545054',
                                    'text' => 'Strongly Agree'
                                ],
                                [
                                    'id' => '518545055',
                                    'text' => 'Agree'
                                ],
                                [
                                    'id' => '518545056',
                                    'text' => 'Neutral'
                                ],
                                [
                                    'id' => '518545057',
                                    'text' => 'Disagree'
                                ],
                                [
                                    'id' => '518545058',
                                    'text' => 'Strongly Disagree'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => '63000641',
                        'type' => 'matrix',
                        'answers' => [
                            'rows' => [
                                [
                                    'id' => '518545059',
                                    'text' => '',
                                    'field' => 'recommendServiceToOthers'
                                ]
                            ],
                            'choices' => [
                                [
                                    'id' => '518545045',
                                    'text' => 'Very Likely'
                                ],
                                [
                                    'id' => '518545046',
                                    'text' => 'Somewhat Likely'
                                ],
                                [
                                    'id' => '518545047',
                                    'text' => 'Not Sure'
                                ],
                                [
                                    'id' => '518545048',
                                    'text' => 'Not Likely'
                                ],
                                [
                                    'id' => '518545049',
                                    'text' => 'Not Likely At All'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => '63011914',
                        'type' => 'text',
                        'field' => 'thoughtsaboutyourexperience'
                    ],
                ]
            ],
        ];
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