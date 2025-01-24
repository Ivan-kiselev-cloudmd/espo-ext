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

Espo.define('dubas-time-tracker:time-tracker-helper', ['action-handler'], function (Dep) {

    return Dep.extend({

        stopTimerIconHtml: '<span class="fas fa-stop fa-sm"></span>',

        getView: function () {
            return this.view;
        },

        getDateTime: function () {
            return this.getView().getDateTime();
        },

        init: function () {
            this.initTimer();

            window.addEventListener('storage', function (e) {
                if (e.key == 'timeTrackerIntervalIds') {
                    var timerStopped = e.oldValue && e.newValue === null;
                    if (timerStopped) {
                        var intervalIdList = JSON.parse(e.oldValue) || [];
                        this.resetStopwatch(intervalIdList);
                        this.handleStartTimerButton();
                    }

                    var timerStarted = e.oldValue === null && e.newValue;
                    if (timerStarted) {
                        this.handleStartTimerButton(true);
                    }
                }
            }.bind(this), false);
        },

        initTimer: function () {
            if (!this.getAcl().checkScope('TimeEntry', 'create')) return;
            if (this.isActionAllowed() === false) return;

            this.getView().wait(true);
            this.ajaxGetRequest('TimeEntry/action/getCurrentSession').then(
                function (session) {
                    if (!('id' in session)) {
                        this.handleStartTimerButton();
                    } else {
                        this.handleStopTimerButton(session.dateStart);
                        if ('targetId' in session) {
                            if (session.targetId !== this.getView().model.id) {
                                this.getView().showHeaderActionItem('startTimerGlobal');
                            }
                        }
                    }
                    this.getView().wait(false);
                }.bind(this)
            );

            if (this.getConfig().get('useWebSocket') && this.getMetadata().get(['scopes', this.getView().model.name, 'object'])) {
                this.subscribeToWebSocket();
                this.unsubscribeFromWebSocket();
            }
        },

        handleStartTimerButton: function (compact) {
            if (typeof compact === 'undefined') {
                compact = false;
            }

            var label = '';
            if (compact === false) {
                label = this.getView().translate('Start Timer', 'labels', 'Global');
            }

            this.getView().menu.buttons.forEach(function (item) {
                if (item.action === 'startTimerGlobal') {
                    item.label = label;
                }
            }, this);

            this.getView().showHeaderActionItem('startTimerGlobal');
            if (compact) {
                this.getView().showHeaderActionItem('stopTimerGlobal');
                this.displayStopwatch();
            } else {
                this.getView().hideHeaderActionItem('stopTimerGlobal');
            }

            this.getView().$el.find('.action[data-action="startTimerGlobal"]').each(function (index, el) {
                var iconHtml = $(el).find('span')[0].outerHTML;
                $(el).html(iconHtml + ' ' + label);
            }.bind(this));
        },

        handleStopTimerButton: function (dateStart) {
            this.getView().showHeaderActionItem('stopTimerGlobal');
            this.displayStopwatch(dateStart);
        },

        displayStopwatch: function (dateStart) {
            if (typeof dateStart === 'undefined' || dateStart === 0) {
                dateStart = moment().tz('UTC').format(this.getDateTime().internalDateTimeFullFormat);
            }

            var d = this.getDateTime().toMoment(dateStart);
            var now = moment().tz(this.getDateTime().timeZone || 'UTC');
            var duration = now.unix() - d.unix();

            if (duration < 0) {
                return;
            }

            var intervalId = window.setInterval(function () {
                ++duration;
                this.getView().$el.find('.action[data-action="stopTimerGlobal"]').html(this.stopTimerIconHtml + ' ' + this.parseDurationString(duration));
            }.bind(this), 1000);

            var intervalIdList = JSON.parse(localStorage.getItem('timeTrackerIntervalIds')) || [];
            intervalIdList.push(intervalId);
            localStorage.setItem('timeTrackerIntervalIds', JSON.stringify(intervalIdList));
        },

        resetStopwatch: function (intervalIdList) {
            if (typeof intervalIdList === 'undefined') {
                intervalIdList = JSON.parse(localStorage.getItem('timeTrackerIntervalIds')) || [];
            }

            intervalIdList.forEach(function (item) {
                window.clearInterval(item);
            }, this);

            localStorage.removeItem('timeTrackerIntervalIds');
            this.getView().$el.find('.action[data-action="stopTimerGlobal"]').html(this.stopTimerIconHtml + ' 00:00:00');
        },

        parseDurationString: function (seconds) {
            if (!seconds) {
                return '0';
            }
            var d = seconds;
            var hours = Math.floor(d / (3600));
            d = d % (3600);
            var minutes = Math.floor(d / (60));
            seconds = d % (60);

            var parts = [];
            parts.push(hours.toString().padStart(2, '0'));
            parts.push(minutes.toString().padStart(2, '0'));
            parts.push(seconds.toString().padStart(2, '0'));

            return parts.join(':');
        },

        initTimesheetLog: function () {
            if (!this.getAcl().checkScope('TimeTracker', 'read')) return;
            if (this.isActionAllowed() === false) return;

            this.getView().wait(true);
            this.ajaxPostRequest('TimeTracker/action/getTimerIdByTarget', {
                targetType: this.getView().model.name,
                targetId: this.getView().model.id,
            }).then(
                function (data) {
                    if ('id' in data) {
                        this.getView().showHeaderActionItem('timesheetLog');
                    }
                    this.getView().wait(false);
                }.bind(this)
            );
        },

        isActionAllowed: function () {
            var entityListAllowed = this.getMetadata().get(['app', 'timeTrackerOptions', 'entityListAllowed']) || [];
            var entityListToIgnore = this.getMetadata().get(['app', 'timeTrackerOptions', 'entityListToIgnore']) || [];

            var scopeEntityList = this.getMetadata()
                .getScopeEntityList()
                .filter(function (item) {
                    if (~entityListToIgnore.indexOf(item)) return;

                    return !!this.getMetadata().get(['scopes', item, 'object']);
                }, this);

            var isActionAllowed = true;
            if (!~scopeEntityList.indexOf(this.getView().model.name)) {
                isActionAllowed = false;
            }
            if (~entityListAllowed.indexOf(this.getView().model.name)) {
                isActionAllowed = true;
            }

            return isActionAllowed;
        },

        actionStartTimerGlobal: function () {
            this.resetStopwatch();
            this.getView().hideHeaderActionItem('startTimerGlobal');

            Espo.Ajax.postRequest('TimeEntry/action/start', {
                targetType: this.getView().model.entityType,
                targetId: this.getView().model.id,
            })
                .then(
                    function () {
                        this.handleStopTimerButton();
                    }.bind(this)
                )
                .fail(
                    function () {
                        this.getView().showHeaderActionItem('startTimerGlobal');
                    }.bind(this)
                );
        },

        actionStopTimerGlobal: function () {
            this.resetStopwatch();
            this.getView().hideHeaderActionItem('stopTimerGlobal');
            var viewName = this.getMetadata().get(['clientDefs', 'TimeEntry', 'modalViews', 'edit']) || 'views/modals/edit';
            var view = this.getView()
            Espo.Ajax.postRequest('TimeEntry/action/stop')
                .then(
                    function (response) {
                        this.handleStartTimerButton();
                        view.notify('Loading...');
                        view.createView('quickEdit', viewName, {
                            id: response.id,
                            scope: 'TimeEntry',
                            attributes: response
                        }, function (view) {
                            view.render();
                            view.notify(false);
                        }.bind(this));
                    }.bind(this)
                )
                .fail(
                    function () {
                        this.getView().showHeaderActionItem('stopTimerGlobal');
                    }.bind(this)
                );
        },

        actionTimesheetLog: function () {
            var viewName = this.getMetadata().get(['clientDefs', 'TimeTracker', 'modalViews', 'detail']) || 'views/modals/detail';

            this.ajaxPostRequest('TimeTracker/action/getTimerIdByTarget', {
                targetType: this.getView().model.name,
                targetId: this.getView().model.id,
            }).then(
                function (data) {
                    if ('id' in data) {
                        this.getView().notify('Loading...');
                        this.getView().createView('quickView', viewName, {
                            scope: 'TimeTracker',
                            id: data.id
                        }, function (view) {
                            view.render();
                            view.notify(false);
                        }, this);
                    }
                }.bind(this)
            );
        },

        subscribeToWebSocket: function () {
            this.getHelper().webSocketManager.subscribe('timerSessionStart', function (t, data) {
                this.getView().hideHeaderActionItem('startTimerGlobal');
                this.getView().showHeaderActionItem('stopTimerGlobal');
            }.bind(this))
        },

        unsubscribeFromWebSocket: function () {
            this.getHelper().webSocketManager.subscribe('timerSessionStop', function (t, data) {
                this.getView().hideHeaderActionItem('stopTimerGlobal');
                this.getView().showHeaderActionItem('startTimerGlobal');
            }.bind(this))
        },

    });
});
