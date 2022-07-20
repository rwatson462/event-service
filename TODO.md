# The Todo List

- Add routing engine to use fancy routes like POST /events/{event-name}
- Create routes to register clients
    - initial request sends API key with some data
    - store that data against a user and create a token
    - client must send the token with future requests
    - token is validated on server to confirm identify
    - token contains list of events allowed to publish
