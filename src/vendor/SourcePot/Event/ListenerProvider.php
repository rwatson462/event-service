<?php

namespace SourcePot\Event;

abstract class ListenerProvider implements \Psr\EventDispatcher\ListenerProviderInterface {
   protected array $registered_listeners = [];

   abstract public function addListenerForEvent(string $event_name, Callable $listener): void;
   abstract public function removeListenerForEvent(string $event_name, Callable $listener): void;
   abstract public function getListenersForEvent(object $event_name): iterable;
}
