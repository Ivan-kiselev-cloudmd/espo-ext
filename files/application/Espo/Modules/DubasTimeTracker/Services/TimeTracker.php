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

namespace Espo\Modules\DubasTimeTracker\Services;

use Espo\ORM\Entity;
use stdClass;

class TimeTracker extends \Espo\Services\Record
{
    protected $checkForDuplicatesInUpdate = true;

    public function loadAdditionalFields(Entity $entity): void
    {
        parent::loadAdditionalFields($entity);
        $this->loadClockedHoursField($entity);
    }

    public function stringifyDuration(int $seconds = 0)
    {
        if ($seconds < 60) {
            return $seconds . $this->getLanguage()->translate('s', 'durationUnits');
        }
        $hours = \intval(\floor($seconds / 3600));
        $seconds = $seconds - $hours * 3600;
        $minutes = \intval(\floor($seconds / 60));

        $value = '';
        if ($hours) {
            $value .= $hours . $this->getLanguage()->translate('h', 'durationUnits');
            if ($minutes) {
                $value .= ' ';
            }
        }
        if ($minutes) {
            $value .= $minutes . $this->getLanguage()->translate('m', 'durationUnits');
        }

        return $value;
    }

    public function getTimerIdByTarget(string $targetType, string $targetId): stdClass
    {
        $timeTracker = $this->getEntityManager()->getRepository('TimeTracker')->where([
            'targetId' => $targetId,
            'targetType' => $targetType,
        ])->findOne();

        $result = (object) [];

        if ($timeTracker) {
            $result->id = $timeTracker->id;
        }

        return $result;
    }

    protected function init(): void
    {
        parent::init();
        $this->addDependency('language');
    }

    protected function getLanguage()
    {
        return $this->injections['language'];
    }

    protected function loadClockedHoursField(Entity $entity): void
    {
        $clockedHours = null;
        if ($entity->get('duration')) {
            $clockedHours = $this->stringifyDuration($entity->get('duration'));
        }
        $entity->set('clockedHours', $clockedHours);
    }

    protected function getDuplicateWhereClause(Entity $entity, $data)
    {
        $whereClause = [
            'OR' => [],
        ];

        $toCheck = false;

        if ($entity->get('targetType') && $entity->get('targetId')) {
            $part = [];
            $part['targetType'] = $entity->get('targetType');
            $part['targetId'] = $entity->get('targetId');
            $whereClause['OR'][] = $part;
            $toCheck = true;
        }

        if ($entity->get('name')) {
            $toCheck = true;
            $whereClause['OR']['name'] = $entity->get('name');
        }

        if (!$toCheck) {
            return null;
        }

        return $whereClause;
    }
}
