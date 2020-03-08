<?php
require_once '../init.php';
use App\Policy;

if(isset($_POST)) {
    $id = explode('-', $_POST['id']);
    $id = $id[1];
    $policy = Policy::find($id);

    echo json_encode($policy);
}

?>