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

Espo.define('dubas-time-tracker:views/time-entry/fields/date-end', 'views/fields/datetime', function (Dep) {

    return Dep.extend({

        data: function () {
            var data = Dep.prototype.data.call(this);

            if (!this.model.get(this.name)) {
                if (!this.model.isNew()) {
                    if (this.mode === 'edit') {
                        var value = this.getDateTime().toDisplay(this.getDateTime().getNow());
                        data.date = value.substr(0, value.indexOf(' '));
                        data.time = value.substr(value.indexOf(' ') + 1);
                    }
                }
            }

            return data;
        },

    });

});
