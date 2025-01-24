<?php

/*
 * This file is part of the Dubas Time Tracker - EspoCRM extension.
 *
 * DUBAS S.C. - contact@dubas.pro
 * Copyright (C) 2021 Arkadiy Asuratov, Emil Dubielecki
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Espo\Modules\DubasTimeTracker\Controllers;

use Espo\Core\Exceptions\BadRequest;
use Espo\Core\Exceptions\Error;
use Espo\Core\Exceptions\Forbidden;
use stdClass;

class TimeEntry extends \Espo\Core\Controllers\Record
{
    public function postActionStart($params, $data, $request): stdClass
    {
        if (empty($data->targetType)) {
            throw new BadRequest();
        }
        if (empty($data->targetId)) {
            throw new BadRequest();
        }
        if (!$this->getAcl()->checkScope('TimeEntry')) {
            throw new Forbidden();
        }
        if (!$this->getAcl()->checkScope($data->targetType)) {
            throw new Forbidden();
        }

        $userId = $data->userId ?? $this->getUser()->id;

        $session = $this->getRecordService()->start($userId, $data->targetType, $data->targetId);
        if (!$session) {
            throw new Error();
        }

        return $session->getValueMap();
    }

    public function postActionStop($params, $data, $request)
    {
        if (!$this->getAcl()->checkScope('TimeEntry')) {
            throw new Forbidden();
        }

        $userId = $data->userId ?? $this->getUser()->id;

        return $this->getRecordService()->stop($userId);
    }

    public function getActionGetCurrentSession($params, $data, $request): stdClass
    {
        if (!$this->getAcl()->checkScope('TimeEntry')) {
            throw new Forbidden();
        }

        $userId = $data->userId ?? $this->getUser()->id;

        return $this->getRecordService()->getCurrentSession($userId);
    }
}
