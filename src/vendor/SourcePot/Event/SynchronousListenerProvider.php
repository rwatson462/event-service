<?php

namespace SourcePot\Event;

/**
 * This provider just maintains a list built on the fly during execution.
 * It never remembers listeners between code executions.
 */
class SynchronousListenerProvider extends ListenerProvider {

   /**
    * @param string $event_name should be a fully qualified class name like 'SourcePot\Event\Event'
    */
   public function addListenerForEvent(string $event_name, Callable $listener): void {
      if(!isset($this->registered_listeners[$event_name])) $this->registered_listeners[$event_name] = [];
      $this->registered_listeners[$event_name][] = $listener;
   }

   public function removeListenerForEvent(string $event_name, Callable $listener): void {
      foreach($this->registered_listeners[$event_name] ?? [] as $index => $registered_listener) {
         if($registered_listener === $listener) {
            array_splice($this->registered_listeners, $index, 1);
            return;
         }
      }
   }

   public function getListenersForEvent(object $event): iterable {
      return $this->registered_listeners[get_class($event)] ?? [];
   }
}
