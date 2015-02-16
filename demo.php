<?php

require 'vendor/autoload.php';

$session = new GroovesharkAPI\Session('APP_KEY', 'APP_SECRET');
$session->startSession();
$api = new GroovesharkAPI\GroovesharkAPI($session);
print_r($api->getSongSearchResults('Beat it'));