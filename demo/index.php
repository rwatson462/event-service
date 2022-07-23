<?php

include 'HttpClientInterface.php';
include 'CurlClient.php';
include 'SocketClient.php';

/**
 * Runs a function repeatedly and records the time taken on each run to calculate a grand total,
 * mean and median averages.
 * @param HttpClientInterface $client the client to use
 * @param callable $function the function to call over and over
 * @param int $count the number of times to call the function
 * @return array [total, average, median]
 */
function run(HttpClientInterface $client, callable $function, int $count): array
{
    // Set up some counting state
    $times = [];
    $i = $count;

    // Repeat the function, recording the time of each tun
    while($i-- > 0) {
        $start = microtime(true);
        $function($client);
        $times[] = microtime(true)-$start;
    }
    
    // Calculate the total and averages
    $total = array_sum($times);
    $average = $total / $count;

    sort($times);
    // Median of an even sized list is the mean of the middle 2 values
    // (because there is no "middle" of an even list)
    if ($count % 2 === 0) {
        $median = ($times[$count/2] + $times[$count/2 +1]) / 2;
    } else {
        $median = $times[floor($count/2)];
    }

    // Done
    return [
        'total' => $total,
        'average' => $average,
        'median' => $median
    ];
}

// Get this out of the way to stop PHP from complaining later
header('Content-type: text/plain');

// Create our clients
$s = new SocketClient;
$c = new CurlClient;

// Set some state
$url = 'http://server:9501/ping';
$totalTimes = 10000;

// This is the repeatable call that will be made
$clientFunction = function(HttpClientInterface $client) use ($url): void {
    $client->post($url);
};

// We pass the repeatable call into this run function to actually run it
$socketTimes = run($s, $clientFunction, $totalTimes);
$curlTimes = run($c, $clientFunction, $totalTimes);

// Output the results
print_r([
    'socket' => array_map(fn($item) => number_format($item, 4), $socketTimes),
    'curl' => array_map(fn($item) => number_format($item, 4), $curlTimes)
]);

// Some silliness to show the faster method with a percentage speed difference
[$faster, $slower, $fasterName] = $socketTimes['total'] > $curlTimes['total'] 
    ? [$curlTimes, $socketTimes, 'Curl'] 
    : [$socketTimes, $curlTimes, 'Socket'];

$speedDiff = ($slower['average'] - $faster['average']) / $slower['average'] * 100;

echo "$fasterName is ".number_format($speedDiff, 4)."% faster\n";
