<?php

namespace Espo\Modules\SurveyEntitySync\TemplateHelpers;

class RiskFactorSurveyLinkTemplateHelper
{
    /**
     * @return LightnCandy\SafeString
     */
    public static function selectAreaRiskOfRisk1SurveyLink()
    {
        $htmlData = '';
        $args = func_get_args();
        $context = $args[count($args) - 1];
        $hash = $context['hash'];
        $data = $context['data']['root'];

        $language = $hash['language'] ?? 'en';

        $entityManager = $data['__entityManager'];

        $contact = $entityManager->getRepository('Contact')->where([
            'emailAddress' => $_GET['emailAddress']
        ])->findOne();

        if (empty($contact)) {
            return new LightnCandy\SafeString($htmlData);
        }

        $selectedAreaOfRisk1 = $contact->get('selectedAreaOfRisk1');
        if (empty($selectedAreaOfRisk1)) {
            return new LightnCandy\SafeString($htmlData);
        }

        $riskFactorsContactRelations = [
            'Alcohol Use Disorder' => 'reassessmentAlcoholUseDisorders',
            'Attention Deficit Hyperactivity Disorder' => 'reassessmentADHDs',
            'Bipolar Disorder' => 'reassessmentBipolars',
            'Depression Disorder' => 'reassessmentDepressions',
            'Eating Disorder' => 'reassessmentEatingDisorders',
            'Generalized Anxiety Disorder' => 'reassessmentGADs',
            'Intermittent Explosive Disorder' => 'reassessmentAngerManagements',
            'Obsessive-Compulsive Disorder' => 'reassessmentOCDs',
            'Panic Disorder' => 'reassessmentPanicDisorders',
            'Post-Traumatic Stress-Disorder' => 'reassessmentPTSDs',
            'Sleep Disorder' => 'reassessmentSleepDisorders',
            'Social Anxiety Disorder' => 'reassessmentSADs',
            'Substance Use Disorder' => 'reassessmentSUDs'
        ];
        $riskFactorEntityType = $riskFactorsContactRelations[$selectedAreaOfRisk1];
        if (empty($riskFactorEntityType)) {
            return new LightnCandy\SafeString($htmlData);
        }

        $latestAssessment = null;
        foreach($contact->get($riskFactorEntityType) as $assessmentItem) {
            if ($assessmentItem->get('latest') == 1) {
                $latestAssessment = $assessmentItem;
                break;
            }
        }

        if (empty($latestAssessment)) {
            return new LightnCandy\SafeString($htmlData);
        }

        $surveyMonkeyCollector = $entityManager->getRepository('SurveyMonkeyCollector')
            ->where([
                'entityType' => $latestAssessment->getEntityType(),
                'entityId' => $latestAssessment->get('id'),
                'contactId' => $contact->get('id'),
                'language' => $language
            ])->findOne();

        if (empty($surveyMonkeyCollector)) {
            return new LightnCandy\SafeString($htmlData);
        }

        $htmlData = $surveyMonkeyCollector->get('collectorUrl');

        return new LightnCandy\SafeString($htmlData);
    }

    /**
     * @return LightnCandy\SafeString
     */
    public static function selectAreaRiskOfRisk2SurveyLink()
    {
        $htmlData = '';
        $args = func_get_args();
        $context = $args[count($args) - 1];
        $hash = $context['hash'];
        $data = $context['data']['root'];

        $language = $hash['language'] ?? 'en';

        $entityManager = $data['__entityManager'];

        $contact = $entityManager->getRepository('Contact')->where([
            'emailAddress' => $_GET['emailAddress']
        ])->findOne();

        if (empty($contact)) {
            return new LightnCandy\SafeString($htmlData);
        }

        $selectedAreaOfRisk2 = $contact->get('selectedAreaOfRisk2');
        if (empty($selectedAreaOfRisk2)) {
            return new LightnCandy\SafeString($htmlData);
        }

        $riskFactorsContactRelations = [
            'Alcohol Use Disorder' => 'reassessmentAlcoholUseDisorders',
            'Attention Deficit Hyperactivity Disorder' => 'reassessmentADHDs',
            'Bipolar Disorder' => 'reassessmentBipolars',
            'Depression Disorder' => 'reassessmentDepressions',
            'Eating Disorder' => 'reassessmentEatingDisorders',
            'Generalized Anxiety Disorder' => 'reassessmentGADs',
            'Intermittent Explosive Disorder' => 'reassessmentAngerManagements',
            'Obsessive-Compulsive Disorder' => 'reassessmentOCDs',
            'Panic Disorder' => 'reassessmentPanicDisorders',
            'Post-Traumatic Stress-Disorder' => 'reassessmentPTSDs',
            'Sleep Disorder' => 'reassessmentSleepDisorders',
            'Social Anxiety Disorder' => 'reassessmentSADs',
            'Substance Use Disorder' => 'reassessmentSUDs'
        ];
        $riskFactorEntityType = $riskFactorsContactRelations[$selectedAreaOfRisk2];
        if (empty($riskFactorEntityType)) {
            return new LightnCandy\SafeString($htmlData);
        }

        $latestAssessment = null;
        foreach($contact->get($riskFactorEntityType) as $assessmentItem) {
            if ($assessmentItem->get('latest') == 1) {
                $latestAssessment = $assessmentItem;
                break;
            }
        }

        if (empty($latestAssessment)) {
            return new LightnCandy\SafeString($htmlData);
        }

        $surveyMonkeyCollector = $entityManager->getRepository('SurveyMonkeyCollector')
            ->where([
                'entityType' => $latestAssessment->getEntityType(),
                'entityId' => $latestAssessment->get('id'),
                'contactId' => $contact->get('id'),
                'language' => $language
            ])->findOne();

        if (empty($surveyMonkeyCollector)) {
            return new LightnCandy\SafeString($htmlData);
        }

        $htmlData = $surveyMonkeyCollector->get('collectorUrl');

        return new LightnCandy\SafeString($htmlData);
    }
}