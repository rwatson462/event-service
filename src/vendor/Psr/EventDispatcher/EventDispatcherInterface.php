<?php

namespace Psr\EventDispatcher;

interface EventDispatcherInterface {
   public function dispatch(object $event);
}
