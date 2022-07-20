# event-service
A simple http listener that accepts events as JSON packets, stores then dispatches them

# Tests
To run the tests, run `docker compose run test` from a terminal.  This will build the test container
and run PHPUnit which will use the tests directory then exit.
