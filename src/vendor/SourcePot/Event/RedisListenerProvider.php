<?php

namespace SourcePot\Event;

use Redis;

/**
 * Redis-backed listener provider that stores the list of listeners in Redis lists
 * 1 list for each event type
 */
class RedisListenerProvider extends ListenerProvider {
    public static string $event_name_prefix = 'e_';

    public function getListenersForEvent(object $event_name): iterable
    {
        return [];
    }

    public function addListenerForEvent(string $event_name, callable $listener): void
    {
        $redis = new Redis;
        $redis->connect('localhost');
        $redis->rpush(self::$event_name_prefix . $event_name, serialize($listener));
    }

    public function removeListenerForEvent(string $event_name, callable $listener): void
    {
        // todo find listener in redis list
    }
}
