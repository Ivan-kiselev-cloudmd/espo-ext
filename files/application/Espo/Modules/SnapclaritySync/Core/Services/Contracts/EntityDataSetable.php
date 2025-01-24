<?php

namespace Espo\Modules\SnapclaritySync\Core\Services\Contracts;

interface EntityDataSetable
{

    /**
     * @param array $entityData
     * @return mixed
     */
    public function setEntityData(array $entityData);

}