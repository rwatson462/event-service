<?php

namespace Psr\EventDispatcher;

interface ListenerProviderInterface {
   public function getListenersForEvent(object $event) : iterable;
}
