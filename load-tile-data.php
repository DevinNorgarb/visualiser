

<?php
require 'vendor/autoload.php';

use JsonMachine\Items;
use JsonMachine\JsonMachine;
use Illuminate\Support\Collection;

$client = new \Predis\Client([
    'host' => '127.0.0.1',
    'port' => 9851,
]);
// $client->set('city', 'set city tempe object {"type":"Polygon","coordinates":[[[0,0],[10,10],[10,0],[0,0]]]}');

// $value = $client->get('city');




$pickup_points = 'pickup_points_20211226_200542.json';
$fname = 'ratings_20211228_224101.csv';
$fname_json = 'ratings_20211228_224101.csv.json';
$pups_with_ratings = 'pup_with_order_processes_count.csv';


$json_file = csvToJson($pups_with_ratings);
$json_pups = JsonMachine::fromFile($json_file);

$key = "pups";

foreach ($json_pups as $key => $pup) {
    $key = "pups_geojson_new";



    // for ($i=0; $i < $pup['orders']; $i++) {
        # code...


        // $json_e = json_encode($geojson);
        // dump($pup['orders']);

        if (!$pup['orders']) {
            $pup['orders'] = 0 ;
        }
        // $ref = (string)$pup['reference'].$i;
        $ref = (string) $pup['reference'].':'.$pup['orders'];
        // dump($ref);
        // dump('{"type":"Point","coordinates":['.$pup['lat'].",".$pup['lng'].'],"properties":{"id":"'. $pup['reference'] .'","orders":'. $pup['orders'] .'}}');
        $res = $client->set($key, $ref, 'OBJECT', '{"type":"Point","coordinates":['.$pup['lat'].",".$pup['lng'].'],"properties":{"id":"'. $pup['reference'].':'.$pup['orders'] .'","orders":'. $pup['orders'] .'}}');
        // dump($res);


        // SET city tempe OBJECT '{"type":"Polygon","coordinates":[[[-111.9787,33.4411],[-111.8902,33.4377],[-111.8950,33.2892],[-111.9739,33.2932],[-111.9787,33.4411]]]}'
        // $json_e = urlencode($json_e);

        // $cmd = "./tile38-cli SET $key {$ref} OBJECT ". "'". '{"type":"Point","coordinates":['.$pup['lat'].",".$pup['lng'].'],"properties":{"id":"'.$pup['reference'].$i .'","orders":"'. $pup['orders'].'"}}' . "'";
        // dump($cmd);

        // echo shell_exec($cmd);
        // echo  exec("curl localhost:9851/set+{$key}+{$pup['reference']}+OBJECT+$json_e");

// dump("SET city tempe OBJECT " . "'".  '{"type":"Point","coordinates":['.$pup['lat'].",".$pup['lng'].'],"properties":{"id":"'. $pup['reference'] .'","orders":'. $pup['orders'] .'}}' . "'");

    // echo "Executed: " . "curl localhost:9851/set+{$key}+{$pup['reference']}+OBJECT+$json_e";
    // }
}
// dump($json_file);


die;

$ratings = JsonMachine::fromFile($fname_json);
$aggregated_ratings = [];

foreach ($ratings as $key => $value) {
    if (isset($aggregated_ratings[$value['pickup_point']])) {
        $aggregated_ratings[$value['pickup_point']] = $aggregated_ratings[$value['pickup_point']] +  $value['rating'];
    } else {
        $aggregated_ratings[$value['pickup_point']] = (int) $value['rating'];
    }
}

$pick_up_points = JsonMachine::fromFile($pickup_points);
$pick_up_points = json_decode(file_get_contents($pickup_points));


$collect = new Collection($pick_up_points);



foreach ($aggregated_ratings as $key => $rating) {
    // $collect->where();
    dump($collect->where('reference', $key)->first());
}






function csvToJson($fname)
{
    // open csv file
    if (!($fp = fopen($fname, 'r'))) {
        die("Can't open file...");
    }

    //read csv headers
    $key = fgetcsv($fp, "1024", ";");
    $json = [];

    $file = new SplFileObject($fname, 'r');
    $file->seek(PHP_INT_MAX);
    $total_rows = $file->key() + 1;



    file_put_contents($fname . '.json', "[");
    $rowcount = 1;
    echo "total " .  $total_rows;

    while ($row = fgetcsv($fp, "1024", ";")) {
        $rowcount++;
        $json_line =  array_combine($key, $row);
        echo "current " . $rowcount . " of " . $total_rows . PHP_EOL;

        $rowcount == ($total_rows - 1)  ? $comma = '' : $comma = ',';
        file_put_contents($fname . '.json', json_encode($json_line) . "$comma" . PHP_EOL, FILE_APPEND);
        // echo ftell($fp) .PHP_EOL;

    }

    file_put_contents($fname . '.json', "]", FILE_APPEND);


    // release file handle
    fclose($fp);

    // encode array to json
    return $fname . '.json';
}
