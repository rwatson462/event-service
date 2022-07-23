<?php
namespace SourcePot\EventSource;

/**
 * Stream class.
 * An object of this type represents a history of events with a given id (e.g.
 * a customer account).
 * @version 0.1
 * @author Rob Watson
 * @since 2022-01-02
 */

use Redis;

class Stream {

   protected string $stream_name = '';

   public function __construct(string $stream_name) {
      $this->stream_name = $stream_name;
   }

   public function add_event(Event $event): bool {
      // first, check that the event shares the same stream name
      if($event->stream_name !== $this->stream_name) {
         // todo emit warning propertly
         echo __FILE__ . '@' . __LINE__ . ': Stream name of event does not match this Stream name' . "\n";
         echo "> {$this->stream_name} - {$event->stream_name}\n";
         return false;
      }

      // todo lock stream whilst processing
      // todo add checks for accepting event

      // todo use a connection pool
      $redis = new Redis;
      $redis->connect('redis');
      $redis->lpush('stream_'.$this->stream_name, $event->serialize());
   }

   // returns latest n records from stream
   public function peek(int $n): array {
      $redis = new Redis;
      $redis->connect('redis');
      return $redis->lrange('stream_'.$this->stream_name, -$n, -1);
   }

   public function debug_stream() {
      $redis = new Redis;
      $redis->connect('redis');
      print_r($redis->lrange('stream_'.$this->stream_name, 0, -1));
   }
}
