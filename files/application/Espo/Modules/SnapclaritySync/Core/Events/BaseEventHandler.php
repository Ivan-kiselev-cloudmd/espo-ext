<?php

namespace Espo\Modules\SnapclaritySync\Core\Events;

abstract class BaseEventHandler
{
    protected $eventHandlers = [];

    public function __construct()
    {
        $this->initHandlers();
        $this->executeHandlers();
    }

    /**
     * @return void
     */
    public function executeHandlers()
    {
        foreach($this->eventHandlers as $eventHandler) {
            $eventHandlerProcessor = new $eventHandler();
            $eventHandlerProcessor->handle($this);
        }
    }

    protected abstract function initHandlers();

}