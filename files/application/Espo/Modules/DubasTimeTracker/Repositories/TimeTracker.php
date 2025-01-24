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

use Espo\Core\Di;
use Espo\ORM\Entity;

class TimeTracker extends \Espo\Core\Repositories\Database implements Di\DefaultLanguageAware
{
    use Di\DefaultLanguageSetter;

    public function getEntityByTarget(string $targetType, string $targetId): ?Entity
    {
        $target = $this->getEntityManager()->getEntity($targetType, $targetId);
        if ($target !== null) {
            return $this->where([
                'targetId' => $targetId,
                'targetType' => $targetType,
            ])->findOne();
        }

        return null;
    }

    public function fillNameField(Entity $entity): void
    {
        $targetName = null;
        $targetId = $entity->get('targetId');
        $targetType = $entity->get('targetType');
        if ($targetId && $targetType) {
            $target = $this->getEntityManager()->getEntity($targetType, $targetId);
            if ($target !== null && $target->has('name')) {
                $targetName = $target->get('name') ?? null;
            }
        }

        if ($targetName !== null) {
            $scopeName = $this->getLanguage()->translate($targetType, 'scopeNames') ?? '';
            $entity->set('name', $scopeName . ' | ' . $targetName);
        }
    }

    protected function getLanguage()
    {
        return $this->defaultLanguage;
    }

    protected function beforeSave(Entity $entity, array $options = []): void
    {
        if ($entity->isNew() && !$entity->get('name')) {
            $this->fillNameField($entity);
        }

        parent::beforeSave($entity, $options);
    }
}
