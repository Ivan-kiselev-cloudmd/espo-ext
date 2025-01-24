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

Espo.define('dubas-time-tracker:views/time-tracker/fields/target', 'views/fields/link-parent', function (Dep) {

    return Dep.extend({

        setup: function () {
            Dep.prototype.setup.call(this);

            if (!this.model.isNew()) {
                if (!this.getUser().isAdmin()) {
                    this.setReadOnly(true);
                }
            }

            var entityListAllowed = this.getMetadata().get(['app', 'timeTrackerOptions', 'entityListAllowed']) || [];
            var entityListToIgnore = this.getMetadata().get(['app', 'timeTrackerOptions', 'entityListToIgnore']) || [];

            this.foreignScopeList = this.getMetadata().getScopeObjectList().filter(function (item) {
                if (!~entityListAllowed.indexOf(item)) {
                    if (~entityListToIgnore.indexOf(item)) return;
                }

                if (!this.getUser().isAdmin()) {
                    if (!this.getAcl().checkScopeHasAcl(item)) return;
                }

                if (!this.getAcl().checkScope(item)) return;

                return true;
            }, this);

            this.getLanguage().sortEntityList(this.foreignScopeList);
            this.foreignScope = this.model.get(this.typeName) || this.foreignScopeList[0];
        }

    });

});
