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

class TimeEntry extends \Espo\Core\Repositories\Database
{
    protected $parentType = 'TimeTracker';

    public function save(Entity $entity, array $options = []) : void
    {
        $isNew = $entity->isNew();

        if ($isNew && ($entity->get('dateStart') && $entity->get('dateEnd'))) {
            if ($entity->get('dateStart') === $entity->get('dateEnd')) {
                return;
            }
        }

        return parent::save($entity, $options);
    }

    protected function beforeSave(Entity $entity, array $options = []): void
    {
        if ($entity->isAttributeChanged('targetId') || $entity->isAttributeChanged('targetType')) {
            $this->fillParent($entity);
        }

        $this->handleDurationField($entity);

        parent::beforeSave($entity, $options);
    }

    protected function fillParent(Entity $entity): void
    {
        $entity->set('parentId', null);

        $targetType = $entity->get('targetType');
        $targetId = $entity->get('targetId');
        if ($targetType && $targetId) {
            $timeTracker = $this->getEntityManager()->getRepository('TimeTracker')->getEntityByTarget($targetType, $targetId);
            if ($timeTracker === null) {
                $timeTracker = $this->getEntityManager()->getEntity('TimeTracker');
                $timeTracker->set([
                    'targetId' => $targetId,
                    'targetType' => $targetType,
                ]);
                $this->getEntityManager()->saveEntity($timeTracker);
            }

            if ($timeTracker) {
                $entity->set('parentId', $timeTracker->id);
            }
        }
    }

    protected function handleDurationField(Entity $entity): void
    {
        $dateStart = $entity->get('dateStart');
        $dateEnd = $entity->get('dateEnd');

        if (!$dateStart || !$dateEnd) {
            return;
        }

        if ($dateEnd === $dateStart) {
            $delete = $this->getEntityManager()->getQueryBuilder()
                ->delete()
                ->from($entity->getEntityType())
                ->where([
                    'id' => $entity->id,
                ])
                ->build();

            $this->getEntityManager()->getQueryExecutor()->execute($delete);
            return;
        }

        $dateDiff = \strtotime($dateEnd) - \strtotime($dateStart);
        $duration = $dateDiff > 0 ? $dateDiff : null;

        $entity->set('duration', $duration);
    }
}
