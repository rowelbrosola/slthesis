<?php
require_once '../init.php';
use App\User;

$data = [];
$results = User::chartData();

echo json_encode($results);

?>

