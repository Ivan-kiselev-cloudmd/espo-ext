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

use Espo\Core\Exceptions\Error;
use Espo\Core\ORM\EntityManager;
use Espo\ORM\Entity;

class HandleParentDurationField
{
    public static $order = 9;

    protected $operatorMap = [
        '+' => 'add',
        '-' => 'sub',
    ];

    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function beforeSave(Entity $entity, array $options = []): void
    {
        $parentIdChanged = $entity->has('parentId') && $entity->get('parentId') !== $entity->getFetched('parentId');
        if ($parentIdChanged || $entity->isAttributeChanged('duration')) {
            if ($entity->getFetched('parentId') && $entity->getFetched('duration')) {
                $this->calculateDurationTime($entity->getFetched('parentId'), '-', $entity->getFetched('duration'));
            }

            if ($entity->get('parentId') && $entity->get('duration')) {
                $this->calculateDurationTime($entity->get('parentId'), '+', $entity->get('duration'));
            }
        }
    }

    public function afterRemove(Entity $entity, array $options = []): void
    {
        if ($entity->get('parentId') && $entity->get('duration')) {
            $this->calculateDurationTime($entity->get('parentId'), '-', $entity->get('duration'));
        }
    }

    protected function calculateDurationTime(string $parentId, string $operator, int $seconds): void
    {
        if (in_array($operator, $this->operatorMap, true)) {
            throw new Error('The operation is not permitted.');
        }

        $parent = $this->entityManager->getEntity('TimeTracker', $parentId);
        if ($parent !== null) {
            $methodName = 'parentDurationTime' . ucfirst($this->operatorMap[$operator]);
            if (method_exists($this, $methodName)) {
                $duration = $this->$methodName($parent, $seconds);

                if ($duration <= 0) {
                    $duration = null;
                }

                $parent->set('duration', $duration);
                $this->entityManager->saveEntity($parent);
            }
        }
    }

    protected function parentDurationTimeAdd(Entity $parent, int $seconds): int
    {
        return ($parent->get('duration') ?? 0) + $seconds;
    }

    protected function parentDurationTimeSub(Entity $parent, int $seconds): int
    {
        return ($parent->get('duration') ?? 0) - $seconds;
    }
}
