<?php
require_once '../init.php';
use App\Event;

$data = [];
$results = Event::loadEvents();

if ($results) {
    foreach($results as $key => $value) {
        $data[] = [
            'id'   => $value['id'],
            'title'   => $value['title'],
            'start'   => $value['start_date'],
            'end'   => $value['end_date']
        ];
    }
}
echo json_encode($data);

?>

