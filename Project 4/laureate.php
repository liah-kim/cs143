<?php
// get the id parameter from the request
$id = intval($_GET['id']);

// set the Content-Type header to JSON, 
// so that the client knows that we are returning JSON data
header('Content-Type: application/json');

    // Read data
    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");

    $filter = [ 'id' => strval($id) ]; 
    $options = ["projection" => ['_id' => 0]];

    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $query = new MongoDB\Driver\Query($filter, $options);
    $rows = $mng->executeQuery("nobel.laureates", $query);
    foreach ($rows as $row) {
           echo json_encode($row);
    }

?>