

<?php
require 'vendor/autoload.php';
use JsonMachine\Items;
use JsonMachine\JsonMachine;

$client = new \Predis\Client();
$client->set('city', 'set city tempe object {"type":"Polygon","coordinates":[[[0,0],[10,10],[10,0],[0,0]]]}');

$value = $client->get('city');



$fruits = JsonMachine::fromFile('pickup_points_20211226_200542.json');
foreach ($fruits as $key => $value) {
    dump($value);

    // 1st and final iteration:
    // $key === 'lastModified'
    // $value === '2012-12-12'
        exec("curl localhost:9851/set+pup+point_{$value['reference']}+point+{$value['latitude']}+{$value['longitude']}");

}


dump($value);