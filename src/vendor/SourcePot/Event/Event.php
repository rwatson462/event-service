<?php

namespace SourcePot\Event;

use Psr\EventDispatcher\StoppableEventInterface;

class Event implements StoppableEventInterface {
   public function __construct(
      protected ?object $payload = null
   ) {}

   public function isPropagationStopped() : bool {
      // for now we don't support this
      return false;
   }

   public function getPayload(): object {
      return $this->payload;
   }
}
