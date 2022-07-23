<?php

namespace Psr\EventDispatcher;

interface StoppableEventInterface {
   public function isPropagationStopped() : bool;
}
