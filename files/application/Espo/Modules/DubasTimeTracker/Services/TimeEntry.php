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

use Espo\Core\Di;
use Espo\Core\Exceptions\NotFound;
use Espo\ORM\Entity;
use stdClass;

class TimeEntry extends \Espo\Services\Record implements Di\WebSocketSubmissionAware
{
    use Di\WebSocketSubmissionSetter;

    public function start(string $userId, string $targetType, string $targetId): Entity
    {
        $user = $this->getEntityManager()->getEntity('User', $userId);
        if (!$user) {
            throw new NotFound();
        }

        $target = $this->getEntityManager()->getEntity($targetType, $targetId);
        if (!$target) {
            throw new NotFound();
        }

        $session = $this->getEntityManager()->getRepository('TimeTrackerSession')->where([
            'assignedUserId' => $userId,
            'targetType' => $targetType,
            'targetId' => $targetId,
        ])->findOne();
        if ($session && $session->get('timeEntryId')) {
            $timeEntry = $this->getEntityManager()->getEntity('TimeEntry', $session->get('timeEntryId'));
            if ($timeEntry) {
                return $timeEntry;
            }
        }

        $timeEntry = $this->getEntityManager()->getEntity('TimeEntry');
        $timeEntry->set([
            'dateStart' => \date('Y-m-d H:i:s'),
            'targetType' => $targetType,
            'targetId' => $targetId,
            'assignedUserId' => $userId,
        ]);
        $this->getEntityManager()->saveEntity($timeEntry);

        if ($this->getConfig()->get('useWebSocket')) {
            $this->webSocketSubmission->submit('timerSessionStart', $userId);
        }

        return $timeEntry;
    }

    public function stop(string $userId)
    {
        $user = $this->getEntityManager()->getEntity('User', $userId);
        if (!$user) {
            throw new NotFound();
        }

        $session = $this->getEntityManager()->getRepository('TimeTrackerSession')->getByUserId($userId);
        $timeEntry = $session->get('timeEntry');

        if ($session) {
            $this->getEntityManager()->removeEntity($session);

            if ($this->getConfig()->get('useWebSocket')) {
                $this->webSocketSubmission->submit('timerSessionStop', $userId);
            }
        }

        return $timeEntry->getValueMap();
    }

    public function getCurrentSession(string $userId): stdClass
    {
        $user = $this->getEntityManager()->getEntity('User', $userId);
        if (!$user) {
            throw new NotFound();
        }

        $resultData = (object) [];

        $session = $this->getEntityManager()->getRepository('TimeTrackerSession')->getByUserId($userId);
        if ($session) {
            $resultData = $session->getValueMap();
        }

        return $resultData;
    }

    public function loadParentNameFields(Entity $entity): void
    {
        if ($entity->get('targetId') && $entity->get('targetType')) {
            $repository = $this->getEntityManager()->getRepository($entity->get('targetType'));
            if ($repository) {
                $target = $repository->where([
                    'id' => $entity->get('targetId'),
                ])->findOne([
                    'withDeleted' => true,
                ]);
                if ($target && $target->get('name')) {
                    $entity->set('targetName', $target->get('name'));
                }
            }
        }
    }
}
