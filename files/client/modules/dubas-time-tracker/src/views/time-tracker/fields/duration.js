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

Espo.define('dubas-time-tracker:views/time-tracker/fields/duration', 'views/fields/int', function (Dep) {

    return Dep.extend({

        getValueForDisplay: function () {
            var seconds = this.model.get(this.name);

            if (seconds < 60) {
                return seconds + '' + this.getLanguage().translate('s', 'durationUnits');
            }

            var d = seconds;
            var hours = Math.floor(d / (3600));
            d = d % (3600);
            var minutes = Math.floor(d / (60));

            var parts = [];

            if (hours) {
                parts.push(hours + '' + this.getLanguage().translate('h', 'durationUnits'));
            }
            if (minutes) {
                parts.push(minutes + '' + this.getLanguage().translate('m', 'durationUnits'));
            }

            return parts.join(' ');
        },

    });

});
