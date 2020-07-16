<?php
require_once 'init.php';
use App\User;
use App\Unit;
use App\Status;
use App\Production;
User::isLogged();
$active = 'units';
$units = Unit::with('creator', 'owner', 'members', 'production')->get();
$status = Status::all();
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if (isset($_POST['export'])) {
        Production::exportUnits();
    } else {
        User::addUnit($_POST);
    }
}
$production = Production::eachUnitProduction();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Units - Personal Production and Client Monitoring System for Financial Advisors</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
        <link rel="stylesheet" href="font/iconsmind-s/css/iconsminds.css">
        <link rel="stylesheet" href="font/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="css/vendor/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="css/vendor/datatables.responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap-datepicker3.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.rtl.only.min.css">
        <link rel="stylesheet" href="css/vendor/perfect-scrollbar.css">
        <link rel="stylesheet" href="css/vendor/select2.min.css">
        <link rel="stylesheet" href="css/vendor/select2-bootstrap.min.css">
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body id="app-container" class="menu-default show-spinner">
        <?php include 'partials/header.php' ?>
        <?php include 'partials/sidebar.php' ?>
        <main>
            <div class="container-fluid">
                <div class="row app-row">
                    <div class="col-12">
                        <div class="mb-2">
                            <?php include 'partials/message.php' ?>
                            <h1>Units</h1>
                            <div class="top-right-button-container">
                                <button type="button" class="btn btn-outline-primary btn-lg top-right-button mr-1" data-toggle="modal" data-target="#rightModal">ADD NEW</button>
                                <div class="modal fade modal-right" id="rightModal" tabindex="-1" role="dialog" aria-labelledby="rightModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="rightModalLabel">Add Unit</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Submitting below form will add the data to Data Table and rows will be updateda.</p>
                                                <form class="tooltip-right-top" id="addToDatatableForm" method="POST" novalidate>
                                                    <div class="form-group position-relative info">
                                                        <label>Unit Name</label>
                                                        <input type="text" class="form-control" name="unit" placeholder="Unit Name" required>
                                                    </div>
                                                    <div class="form-group position-relative info">
                                                        <label>Manager First Name</label>
                                                        <input type="text" class="form-control" name="firstname" placeholder="Manager First Name" required>
                                                    </div>
                                                    <div class="form-group position-relative info">
                                                        <label>Manager Middle Name</label>
                                                        <input type="text" class="form-control" name="middlename" placeholder="Manager Middle Name" required>
                                                    </div>
                                                    <div class="form-group position-relative info">
                                                        <label>Manager Last Name</label>
                                                        <input type="text" class="form-control" name="lastname" placeholder="Manager Last Name" required>
                                                    </div>
                                                    <div class="form-group position-relative info">
                                                        <label>Email</label>
                                                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                                                    </div>
                                                    <div class="input-group date form-group position-relative info">
                                                        <label>Birthdate</label>
                                                        <input type="text" class="form-control" name="dob" style="width: 100%;" placeholder="Birth Date" required>
                                                        <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                    <div class="form-group position-relative info">
                                                        <label>Advisor Code</label>
                                                        <input type="text" class="form-control" name="advisor_code" placeholder="Advisor Code" required>
                                                    </div>
                                                    <div class="input-group form-group position-relative info">
                                                        <label>Coding Date</label>
                                                        <input type="text" class="form-control datepicker" name="coding_date" style="width: 100%;" placeholder="Coding Date" required>
                                                        <div class="input-group-addon">
                                                            <span class="glyphicon glyphicon-th"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group position-relative info">
                                                        <label>Status</label> 
                                                        <select class="form-control select2-single" name="status" data-width="100%" required>
                                                            <option value="">Select Status</option>
                                                            <?php foreach($status as $key => $value): ?>
                                                            <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <input type="hidden" name="role" value="3">
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="#" class="btn btn-primary btn-multiple-state not-active" id="addToDatatable">
                                                    <div class="spinner d-inline-block">
                                                        <div class="bounce1"></div>
                                                        <div class="bounce2"></div>
                                                        <div class="bounce3"></div>
                                                    </div>
                                                    <span class="icon success" data-toggle="tooltip" data-placement="top" title="Everything went right!"><i class="simple-icon-check"></i> </span>
                                                    <span class="icon fail" data-toggle="tooltip" data-placement="top" title="Something went wrong!"><i class="simple-icon-exclamation"></i> </span>
                                                    <span class="label">Done</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                                <ol class="breadcrumb pt-0">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <!-- <li class="breadcrumb-item"><a href="#">Library</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Data</li> -->
                                </ol>
                            </nav>
                            <div class="separator mb-5"></div>
                        </div>                
                        <div class="row mb-4">
                            <div class="col-12 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <button style="float:right;margin-bottom:10px;" onclick="report()" class="btn btn-primary">Export</button>
                                        <form method="post" id="export">
                                            <input type="hidden" name="export">
                                        </form>
                                        <table class="data-table data-table-feature payment-table">
                                            <thead>
                                                <tr>
                                                    <th>Unit Name</th>
                                                    <th>Advisor Code</th>
                                                    <th>Unit Manager</th>
                                                    <th>Man Power</th>
                                                    <th>YTD Production</th>
                                                    <th>Campaign</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($units as $key => $value): ?>
                                                <?php
                                                    $sum = 0;
                                                    foreach($value->production as $k => $v)
                                                    {
                                                        $sum+= $v->amount;
                                                    }
                                                ?>
                                                <tr>
                                                    <td><a href="unit.php?unit_id=<?= $value->id ?>"><?= $value->name ?></a></td>
                                                    <td><?= isset($value->owner) ? $value->owner->advisor_code : null  ?></td>
                                                    <td><a href="profile.php?id=<?= $value->owner_id.'&tab=home' ?>">
                                                        <?= isset($value->owner) ? $value->owner->firstname.' '.$value->owner->lastname : null ?>
                                                        </a>
                                                    </td>
                                                    <td><?= $value->members->count() ?></td>
                                                    <td><?= isset($value->production) ? $sum : '&#8369;0' ?></td>
                                                    <td><?= isset($production[$value->name]) ? $production[$value->name] : null ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <script src="js/vendor/jquery-3.3.1.min.js"></script>
        <script src="js/vendor/bootstrap.bundle.min.js"></script>
        <script src="js/vendor/perfect-scrollbar.min.js"></script>
        <script src="js/vendor/mousetrap.min.js"></script>
        <script src="js/vendor/datatables.min.js"></script>
        <script src="js/vendor/select2.full.js"></script>
        <script src="js/vendor/bootstrap-datepicker.js"></script>
        <script src="js/dore.script.js"></script>
        <script src="js/scripts.js"></script>
        <script>
            $('#addToDatatable').click(function () {
                $('#addToDatatableForm').submit();
            })
            $('.alert-success').fadeIn('fast').fadeOut(8000);

            function report() {
                $('#export').submit();
            }
        </script>
    </body>
</html>