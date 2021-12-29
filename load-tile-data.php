

<?php
require 'vendor/autoload.php';

use JsonMachine\Items;
use JsonMachine\JsonMachine;

$client = new \Predis\Client();
$client->set('city', 'set city tempe object {"type":"Polygon","coordinates":[[[0,0],[10,10],[10,0],[0,0]]]}');

$value = $client->get('city');





$fname = 'ratings_20211228_224101.csv';


$json = csvToJson($fname);

dump($json);

die;
$fruits = JsonMachine::fromFile('ratings_20211228_224101.json');

foreach ($fruits as $key => $value) {
    dump($value);

    // 1st and final iteration:
    // $key === 'lastModified'
    // $value === '2012-12-12'
    exec("curl localhost:9851/set+pup+point_{$value['reference']}+point+{$value['latitude']}+{$value['longitude']}");
}


dump($value);






function csvToJson($fname)
{
    // open csv file
    if (!($fp = fopen($fname, 'r'))) {
        die("Can't open file...");
    }

    //read csv headers
    $key = fgetcsv($fp, "1024", ",");
    $json = [];

    $file = new SplFileObject($fname, 'r');
    $file->seek(PHP_INT_MAX);
    $total_rows = $file->key() + 1;


    //write each line of csv file into json array

    file_put_contents($fname . '.json', "[");
    $rowcount = 1;
    echo "total " .  $total_rows;

    while ($row = fgetcsv($fp, "1024", ",")) {
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
    return json_encode($json);
}
