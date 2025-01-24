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

namespace Espo\Modules\DubasTimeTracker\SelectManagers;

class TimeEntry extends \Espo\Core\Select\SelectManager
{
    public function getSelectAttributeList(array $params): ?array
    {
        $attributeList = parent::getSelectAttributeList($params);
        if (is_array($attributeList) && array_key_exists('select', $params)) {
            $passedAttributeList = $params['select'];
            if (in_array('duration', $passedAttributeList)) {
                if (!in_array('dateStart', $attributeList)) {
                    $attributeList[] = 'dateStart';
                }
                if (!in_array('dateEnd', $attributeList)) {
                    $attributeList[] = 'dateEnd';
                }
            }
            if (in_array('target', $passedAttributeList) && (!in_array('targetType', $attributeList) && !in_array('targetId', $attributeList))) {
                $attributeList[] = 'targetId';
                $attributeList[] = 'targetType';
            }
        }

        return $attributeList;
    }

    protected function filterOnlyMy(&$result): void
    {
        $result['whereClause'][] = [
            'assignedUserId' => $this->getUser()->id,
        ];
    }
}
