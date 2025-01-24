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

Espo.define('dubas-time-tracker:views/time-entry/fields/name', 'views/fields/varchar', function (Dep) {

    return Dep.extend({

        getValueForDisplay: function () {
            var name = this.model.get('name');

            if (name === '') {
                name = this.model.id;
            }

            if (this.model.get('dateStart')) {
                name = this.getDateTime().toDisplay(this.model.get('dateStart'));

                if (this.model.get('dateEnd')) {
                    var dateStartDate = name.substr(0, name.indexOf(' '));
                    var dateEnd = this.getDateTime().toDisplay(this.model.get('dateEnd'));
                    var dateEndDate = dateEnd.substr(0, dateEnd.indexOf(' '));
                    var dateEndTime = dateEnd.substr(dateEnd.indexOf(' ') + 1);

                    var dateEndValue = dateEnd;
                    if (dateStartDate === dateEndDate) {
                        dateEndValue = dateEndTime;
                    }
                    name += ' - ' + dateEndValue;
                }
            }

            return name;
        },

    });

});
