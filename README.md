# Scrutinzer API


## Run It!

Start the WS server listening on port 8080

```
php -f index.php
```

## Interact With It!

The API expects a request with a message like this:

```json
{
    "request": "/<ENDPOINT>",
    "body": {
        "URL": "https://example.org"
    }
}
```

A response will look like the following:

```json
{
    "testName": "testThingAlpha",
    "pass": true
}
```