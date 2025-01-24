<?php

namespace Espo\Modules\SnapclaritySync\Core\Events\Contracts;

interface EventHandlerable
{
    /**
     * @param $event
     * @return mixed
     */
    public function handle($event);
}