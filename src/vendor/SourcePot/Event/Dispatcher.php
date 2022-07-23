<?php

namespace SourcePot\Event;

use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class Dispatcher implements EventDispatcherInterface {
   public function __construct(
      private ListenerProviderInterface  $listenerProvider
   ) {}

   public function dispatch(object $event): void {
      foreach($this->listenerProvider->getListenersForEvent($event) as $listener) {
         $listener($event);
      }
   }
}
