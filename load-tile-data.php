

<?php
require 'vendor/autoload.php';

$client = new \Predis\Client();
$client->set('foo', 'bar');
$value = $client->get('foo');

dump($value);