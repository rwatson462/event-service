<?php

use RWA\Event\Dispatcher, RWA\Event\Event, RWA\Event\SynchronousListenerProvider;
use RWA\Todo\Events\TodoCreatedEvent;

require dirname(__DIR__).'/vendor/autoloader.php';

$listener_provider = new SynchronousListenerProvider;
$dispatcher = new Dispatcher($listener_provider);

/**
 * In this example, this function would be in a different file loaded away from the user
 */
function todo_created_listener(object $event) {
   echo get_class($event) . " triggered!\n";
}

$listener_provider->addListenerForEvent(TodoCreatedEvent::class, 'todo_created_listener');
// $listener_provider->removeListenerForEvent(TodoCreatedEvent::class, 'todo_created_listener');

/**
 * During normal execution we might create a todo
 */
class Todo {
   public function __construct(
      public string $creater_id,
      public string $title
   ) {}
}
$todo = new Todo(12345, 'Buy new car');

/**
 * Then we can dispatch the event to any interested parties
 */
$dispatcher->dispatch(new TodoCreatedEvent($todo));
