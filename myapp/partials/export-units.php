<?php
require_once 'init.php';
use App\User;
use App\Unit;
use App\Production;
$units = Unit::with('creator', 'owner', 'members', 'production')->get();
$production = Production::eachUnitProduction();
$current_production = Production::currentProduction();
$unit_manager = User::with('profile')->find(Session::get('owner_id'));
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Untitled Document</title>
    </head>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
    <img src="img/sunlife-logo.png" />
    <body>
        <table>
            <tr>
                <th>Unit Name</th>
                <th>Advisor Code</th>
                <th>Unit Manager</th>
                <th>Man Power</th>
                <th>YTD Production</th>
                <th>Campaign</th>
            </tr>
            <?php foreach($units as $key => $value): ?>
             <?php   $sum = 0;
                foreach($value->production as $k => $v)
                {
                    $sum+= $v->amount;
                }
            ?>
            <tr>
                <td><?= $value->name ?></td>
                <td><?= isset($value->owner) ? $value->owner->advisor_code : null ?></td>
                <td><?= isset($value->owner) ? $value->owner->firstname.' '.$value->owner->lastname : null ?></td>
                <td><?= $value->members->count() ?></td>
                <td><?= isset($value->production) ? $sum : null ?></td>
                <td><?= isset($production[$value->name]) ? $production[$value->name] : null ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </body>
</html>