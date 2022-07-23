# event-service
A simple http listener that accepts events as JSON packets, stores then dispatches them

# Tests
To run the tests, run `docker compose run test` from a terminal.  This will build the test container
and run PHPUnit which will use the tests directory then exit.

# Side-note

An interesting thing cropped up in conversation recently - that manually opening a socket and
sending an HTTP request is quicker than using CURL.  When you stop and think about it, it makes
sense - CURL is a wrapper around _a lot_ of ways to make a request, it has many many options and 
provides a great API for making requests and getting responses.
I was, however, surprised by just how much quicker is it to make socket requests instead.

I did some messing around (see the `example_client` container in the Docker compose file) and found
the following on my local environment.  Note that this is a docker container running on my personal
computer making a POST request to a docker container also running on my personal computer.  The
container receiving the requests is the event service in its infancy just configured to return a
`pong` when it receives a `ping`.  I deliberately kept the processing time down on the server side
to keep the simulation focussed on the client sending method.

Anyway, here it is:  10,000 HTTP requests sent and received using a manual socket connect
(`fsockopen` and related functions):
```
[total] => 5.5435
[average] => 0.0006
[median] => 0.0005
```
And the same done using CURL (`curl_init` and related functions):
```
[total] => 7.8916
[average] => 0.0008
[median] => 0.0008
```

The code for this is in the `demo` directory in this repository for you to peruse.  I believe I've
made the simplest requests I can and not added any silly extra configuration in CURL to make it
look bad.

It looks like the socket is up to 30% faster than CURL.   Amazing.