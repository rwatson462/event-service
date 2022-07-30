<?php

$redis = new Redis;
$redis->connect('redis');

$redis->set('secret', '5ebe2294ecd0e0f08eab7690d2a6ee69');