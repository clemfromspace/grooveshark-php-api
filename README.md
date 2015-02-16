# grooveshark-php-api
Grooveshark php api

Usage:
```php
$session = new GroovesharkAPI\Session('YOUR APPLICATION KEY', 'YOUR APPLICATION SECRET');
$session->startSession();

$api = new GroovesharkAPI\GroovesharkAPI($session);
$api->getSongSearchResults('Beat it')
```