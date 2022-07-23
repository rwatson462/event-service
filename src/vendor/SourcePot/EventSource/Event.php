<?php
namespace SourcePot\EventSource;

/**
 * Event class.
 * An object of this class represents an entry in an "event stream".  The
 * contents of this object are immutable (through general means).
 * @version 0.1
 * @author Rob Watson
 * @since 2022-01-02
 */


class Event {

   protected string $stream_name = '';
   protected string $event_hash = '';
   protected array $data = [];

   public function __construct(string $stream_name, array|object $input) {
      $this->stream_name = $stream_name;

      // convert incoming data blog to an associative array
      foreach($input as $key => $value) {
         $this->data[$key] = $value;
      }

      // generate a hash of the event contents (used as a unique identifier)
      $this->event_hash = sha1($this->serialize());
   }

   public static function from(string $event_name, array|object $input): self {
      return new self($event_name, $input);
   }

   public function get_data(): array {
      return $this->data;
   }

   public function serialize(): string {
      return json_encode([
         'name' => $this->name,
         'data' => $this->data
      ]);
   }
   
   public function __get(string $var): mixed {
      if($var === 'stream_name') return $this->stream_name;
   }
}
