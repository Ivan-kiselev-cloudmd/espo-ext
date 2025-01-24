/************************************************************************
This file is part of the Dubas Time Tracker - EspoCRM extension.

DUBAS S.C. - contact@dubas.pro
Copyright (C) 2021 Arkadiy Asuratov, Emil Dubielecki

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
************************************************************************/

Espo.define('dubas-time-tracker:views/time-tracker/record/detail-bottom', 'views/record/detail-bottom', function (Dep) {

    return Dep.extend({

        setupPanels: function () {
            Dep.prototype.setupPanels.call(this);

            if (this.getAcl().check('TimeEntry', 'read')) {
                this.panelList.unshift({
                    name: 'timeEntries',
                    label: this.translate('timeEntries', 'links', 'TimeTracker'),
                    view: 'views/record/panels/relationship',
                    select: false,
                    create: false,
                    rowActionsView: 'views/record/row-actions/empty'
                });
            }
        },

        afterRender: function () {
            Dep.prototype.setupPanels.call(this);
        }

    });
});
