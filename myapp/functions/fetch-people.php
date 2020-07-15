<?php
require_once '../init.php';
use App\People;

if(isset($_POST)) {
    $user = People::find($_POST['id']);

    echo json_encode($user);
}

?>