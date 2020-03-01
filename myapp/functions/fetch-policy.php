<?php
require_once '../init.php';
use App\UserPolicy;

if(isset($_POST)) {
    $policies = UserPolicy::where('user_id', $_POST['user_id'])->with('policy')->get();

    echo "<select>";
    foreach($policies as $key => $value) {
        echo "<option>Select Policy</option>";
        echo "<option value=".$value->policy_id.">".$value->policy->name."</option>";
    }
    echo "</select>";
}

?>