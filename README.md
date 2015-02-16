# Grooveshark Web Api PHP

This is a PHP implementation of the [Grooveshark Web API](http://developers.grooveshark.com/docs/public_api/v3/).

## Requirements

PHP 5.3 or greater.

## Installation

Using composer:
```json
"require": {
    "clemfromspace/grooveshark-php-api": "dev-master"
}
```

## Examples

First, request a session ID using your [application credentials](http://developers.grooveshark.com/api).
```php
$session = new GroovesharkAPI\Session('YOUR APPLICATION KEY', 'YOUR APPLICATION SECRET');
$session->startSession();

$api = new GroovesharkAPI\GroovesharkAPI($session);
```

Using this session object, initialize the client :

```php
$api = new GroovesharkAPI\GroovesharkAPI($session);
```

You can now send requests to Grooveshark's API :

```php
$response = $api->getSongSearchResults('Beat it');
```


[Grooveshark Api documentation](http://developers.grooveshark.com/docs/public_api/v3/)

