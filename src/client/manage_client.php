<?php

/**
 * This is a simple file to allow adding and removing event permissions from a client.
 */

// Capture posted data
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $events = explode(',',$_POST['events']);
    $clientName = $_POST['client_name'];
    var_dump($events, $clientName);

    $apikey = md5($clientName);

    $redis = new Redis;
    $redis->connect('redis');
    $redis->set("k_$clientName", $apikey);
    $redis->set("c_$clientName", json_encode($events));
    exit;
}

// Display form
?><!DOCTYPE html>
<html lang="en">
    <head>
        <title>Manage client permissions</title>
    </head>
    <body>
        <form method="POST">
            <p><input type="text" name="client_name" placeholder="Client name" /></p>
            <p><input type="text" name="events" placeholder="CSV of events" /></p>
            <p><button type="submit">Save</button></p>
        </form>
    </body>
</html>