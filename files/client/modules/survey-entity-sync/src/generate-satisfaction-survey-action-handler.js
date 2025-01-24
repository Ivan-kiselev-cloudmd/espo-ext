/*********************************************************************************
 * The contents of this file are subject to the EspoCRM Outlook Integration
 * Agreement ("License") which can be viewed at
 * https://www.espocrm.com/google-integration-agreement.
 * By installing or using this file, You have unconditionally agreed to the
 * terms and conditions of the License, and You may not use this file except in
 * compliance with the License.  Under the terms of the license, You shall not,
 * sublicense, resell, rent, lease, distribute, or otherwise  transfer rights
 * or usage to the software.
 * 
 * Copyright (C) 2015-2021 Letrium Ltd.
 * 
 * License ID: f76253995c1b40f19b3d02aad52c1995
 ***********************************************************************************/

define('survey-entity-sync:generate-satisfaction-survey-action-handler', ['action-handler'], function (Dep) {
    return Dep.extend({

        initGenerateSatisfactionSurveyMonkey: function () {
            // this.view.on('after:render',  () => {
            //     this.view.hideActionItem('generateSatisfactionSurveyMonkey');
            // }, this);
        },

        actionGenerateSatisfactionSurveyMonkey: function (data, e) {
            this.confirm(this.view.translate('confirmation', 'messages'), () => {

                Espo.Ui.notify('...');
                Espo.Ajax.postRequest('SurveyMonkeyGenerate/action/satisfactionGenerate', {
                    id: this.view.model.id,
                    entityType: this.view.model.entityType,
                }).then(function (response) {
                    if (response.success) {
                        Espo.Ui.success(this.view.translate('Done'));
                    } else {
                        Espo.Ui.error(this.view.translate('Error'));
                    }
                }.bind(this));
            })
        },
    });
});
