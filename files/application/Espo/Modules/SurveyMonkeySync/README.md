# Survey Monkey sync extension

## Current version - 1.0.0 Version

### Usage guide

* #### Install this extension in Espo CRM
* #### Go to [Administration](https://devmvp.medicalconfidence.com/#Admin)  => [Integrations](https://devmvp.medicalconfidence.com/#Admin/integrations) =>  Select from left menu [SurveyMonkey](https://devmvp.medicalconfidence.com/#Admin/integrations/name=SurveyMonkey)
* #### Click "Enabled" Checkbox
* #### Fill this fields and save: 
    * isProduction - Checked if this environment is production
    * surveyApiKey - Survey monkey api key
    * surveyAccessToken - Survey monkey access token
    * syncLastMinutes - Set some minutes
    * version_1_en, version_1_fr, version_2_en, version_2_fr - I will send you this ids
* #### Go to [Schedule Jobs](https://devmvp.medicalconfidence.com/#ScheduledJob) and [Create Job](https://devmvp.medicalconfidence.com/#ScheduledJob/create)
    * Select job "SurveyMonkeyJob"
    * Set name
    * Set active status
    * Set cron time
    


