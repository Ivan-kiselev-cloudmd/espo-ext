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

namespace Espo\Modules\DubasTimeTracker\Hooks\TimeEntry;

use Espo\Core\ORM\EntityManager;
use Espo\ORM\Entity;

class HandleTimeTrackerSession
{
    public static $order = 9;

    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function afterSave(Entity $entity, array $options = []): void
    {
        if (!empty($options['silent'])) {
            return;
        }

        $userId = $entity->get('assignedUserId');
        if (empty($userId)) {
            return;
        }

        $repository = $this->entityManager->getRepository('TimeTrackerSession');
        if ($entity->isNew() || $entity->get('dateEnd')) {
            $repository->deleteByUserId($userId);
        }

        if (!$entity->get('dateEnd') && !$repository->getByUserId($userId)) {
            $session = $this->entityManager->getEntity('TimeTrackerSession');
            $session->set([
                'timeEntryId' => $entity->id,
                'assignedUserId' => $userId,
            ]);
            $this->entityManager->saveEntity($session);
        }
    }

    public function afterRemove(Entity $entity, array $options = []): void
    {
        if (!empty($options['silent'])) {
            return;
        }

        $userId = $entity->get('assignedUserId');
        if (empty($userId)) {
            return;
        }

        $repository = $this->entityManager->getRepository('TimeTrackerSession');
        if ($repository->getByUserId($userId)) {
            $repository->deleteByUserId($userId);
        }
    }
}
