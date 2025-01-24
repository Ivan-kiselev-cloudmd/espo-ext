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

namespace Espo\Modules\DubasTimeTracker\Repositories;

use Espo\ORM\Entity;

class TimeTrackerSession extends \Espo\Core\Repositories\Database
{
    public function getByUserId(string $userId): ?Entity
    {
        return $this->getEntityManager()->getRepository('TimeTrackerSession')->where([
            'assignedUserId' => $userId,
        ])->findOne();
    }

    public function deleteByUserId(string $userId): void
    {
        $sessionList = $this->getEntityManager()->getRepository('TimeTrackerSession')->where([
            'assignedUserId' => $userId,
        ])->find();

        foreach ($sessionList as $session) {
            $this->getEntityManager()->removeEntity($session);
        }
    }

    public function fillTarget(Entity $entity): void
    {
        $timeTrackerId = null;
        $targetType = null;
        $targetId = null;

        if ($entity->get('timeEntryId')) {
            $timeEntry = $this->getEntityManager()->getEntity('TimeEntry', $entity->get('timeEntryId'));
            if ($timeEntry !== null) {
                $timeTrackerId = $timeEntry->get('parentId') ?? null;
            }
        }

        if ($timeTrackerId !== null) {
            $timeTracker = $this->getEntityManager()->getEntity('TimeTracker', $timeTrackerId);
            if ($timeTracker !== null) {
                $targetType = $timeTracker->get('targetType') ?? null;
                $targetId = $timeTracker->get('targetId') ?? null;
            }
        }

        if ($targetType && $targetId) {
            $target = $this->getEntityManager()->getEntity($targetType, $targetId);
            if ($target !== null) {
                $entity->set('targetType', $targetType);
                $entity->set('targetId', $targetId);
            }
        }
    }

    protected function beforeSave(Entity $entity, array $options = []): void
    {
        if ($entity->isNew() && $entity->get('assignedUserId')) {
            $this->deleteByUserId($entity->get('assignedUserId'));
        }

        if ($entity->isNew() && !$entity->get('targetId')) {
            $this->fillTarget($entity);
        }

        parent::beforeSave($entity, $options);
    }

    protected function afterRemove(Entity $entity, array $options = []): void
    {
        parent::afterRemove($entity, $options);

        if ($entity->get('timeEntryId')) {
            $timeEntry = $this->getEntityManager()->getEntity('TimeEntry', $entity->get('timeEntryId'));
            if ($timeEntry !== null && !$timeEntry->get('dateEnd')) {
                $timeEntry->set('dateEnd', \date('Y-m-d H:i:s'));
                $this->getEntityManager()->saveEntity($timeEntry);
            }

            $delete = $this->getEntityManager()->getQueryBuilder()
                ->delete()
                ->from('TimeTrackerSession')
                ->where([
                    'id' => $entity->id,
                    'timeEntryId' => $entity->get('timeEntryId'),
                ])
                ->build();

            $this->getEntityManager()->getQueryExecutor()->execute($delete);
        }
    }
}
