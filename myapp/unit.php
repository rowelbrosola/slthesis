<?php
require_once 'init.php';
use App\User;
use App\Unit;
use App\UserProfile;
use App\Session;
User::isLogged();
$my_unit = UserProfile::where('user_id', Session::get('user_id'))->with('unit')->get();
$active = 'units';
if (isset($_GET['unit_id']) && $_GET['unit_id'] == $my_unit[0]->unit->id) {
    $active = 'my_unit';
}
$units = Unit::with('creator')->get();
$unit_members = UserProfile::where('unit_id', $_GET['unit_id'])->get();
$current_unit = Unit::find($_GET['unit_id']);
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    User::add($_POST);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Appointments - Personal Production and Client Monitoring System for Financial Advisors</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
        <link rel="stylesheet" href="font/iconsmind-s/css/iconsminds.css">
        <link rel="stylesheet" href="font/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="css/vendor/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="css/vendor/datatables.responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.min.css">
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
                            <h1>Unit</h1>
                            <div class="top-right-button-container">
                                <button type="button" class="btn btn-outline-primary btn-lg top-right-button mr-1" data-toggle="modal" data-target="#rightModal">ADD NEW</button>
                                <div class="modal fade modal-right" id="rightModal" tabindex="-1" role="dialog" aria-labelledby="rightModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="rightModalLabel">Add unit member</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Submitting below form will add the data to Data Table and rows will be updateda.</p>
                                                <form class="tooltip-right-top" id="addToDatatableForm" method="POST" novalidate>
                                                    <div class="form-group position-relative info">
                                                        <label>First Name</label>
                                                        <input type="text" class="form-control" name="firstname" placeholder="First Name" required>
                                                    </div>
                                                    <div class="form-group position-relative info">
                                                        <label>Last Name</label>
                                                        <input type="text" class="form-control" name="lastname" placeholder="Last Name" required>
                                                    </div>
                                                    <div class="form-group position-relative info">
                                                        <label>Email</label>
                                                        <input type="text" class="form-control" name="email" placeholder="Email" required>
                                                    </div>
                                                    <input type="hidden" value="<?= $_GET['unit_id'] ?>" name="unit">
                                                    <input type="hidden" value="true" name="addtounit">
                                                    <input type="hidden" value="2" name="role">
                                                    <input type="hidden" value="1" name="status">
                                                    <input type="hidden" value="<?= Session::get('user_id') ?>" name="advisor">
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
                                    <li class="breadcrumb-item"><a href="#"><?= $current_unit->name ?></a></li>
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
                                        <table class="data-table data-table-feature payment-table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Status</th>
                                                    <th>Created At</th>
                                                    <th>Client Number</th>
                                                    <th>Updated At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($unit_members as $key => $value): ?>
                                                <tr>
                                                    <td><?= $value->id ?></td>
                                                    <td><?= $value->firstname.' '.$value->lastname ?></td>
                                                    <td>Active</td>
                                                    <td><?= $value->client_number ?></td>
                                                    <td><?= date('Y-m-d H:i', strtotime($value->created_at)) ?></td>
                                                    <td><?= date('Y-m-d H:i', strtotime($value->updated_at)) ?></td>
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
        <script src="js/dore.script.js"></script>
        <script src="js/scripts.js"></script>
        <script>
            $('#addToDatatable').click(function () {
                $('#addToDatatableForm').submit();
            })
            $('.alert-success').fadeIn('fast').fadeOut(8000);
        </script>
    </body>
</html>