<?php
require_once 'init.php';
use App\User;
use App\Unit;
use App\UserProfile;
use App\Session;
use App\Payment;
User::isLogged();
$my_unit = UserProfile::where('user_id', Session::get('user_id'))->with('unit')->get();
$active = 'units';
if (isset($_GET['unit_id']) && $_GET['unit_id'] == $my_unit[0]->unit->id) {
    $active = 'my_unit';
}
$units = Unit::with('creator')->get();
$unit_members = UserProfile::where('unit_id', $_GET['unit_id'])->with('status', 'unit', 'advisor')->get();
$current_unit = Unit::find($_GET['unit_id']);
$payments = Payment::where('unit_id', $_GET['unit_id'])->get();
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
                                                    <th>Name</th>
                                                    <th>Advisor Code</th>
                                                    <th>Status</th>
                                                    <th>Unit Manager</th>
                                                    <th>TYD Production</th>
                                                    <th>Campaign</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($unit_members as $key => $value): ?>
                                                <tr>
                                                    <td><a href="profile.php?id=<?= $value->user_id.'&tab=home'?>"><?= $value->firstname.' '.$value->lastname ?></a></td>
                                                    <td><?= $value->advisor_code ?></td>
                                                    <td><?= isset($value->status) ? $value->status->name : null ?></td>
                                                    <td><?= isset($value->advisor) ? $value->advisor->firstname.' '.$value->advisor->lastname : null ?></td>
                                                    <td><?=  '&#8369; 10,000' ?></td>
                                                    <td><?= 'Love Month' ?></td>
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
            <div class="app-menu">
                <div class="p-4 h-100">
                    <div class="scroll">
                        <p class="text-muted text-small">Production</p>
                        <ul class="list-unstyled mb-5">
                            <li><i class="simple-icon-check"></i> Completed <span class="float-right">Paid</span></a></li>
                        </ul>
                        <!-- <p class="text-muted text-small">Categories</p>
                        <ul class="list-unstyled mb-5">
                            <li>
                                <div class="custom-control custom-checkbox mb-2"><input type="checkbox" class="custom-control-input" id="category1"> <label class="custom-control-label" for="category1">Flexbox</label></div>
                            </li>
                            <li>
                                <div class="custom-control custom-checkbox mb-2"><input type="checkbox" class="custom-control-input" id="category2"> <label class="custom-control-label" for="category2">Sass</label></div>
                            </li>
                            <li>
                                <div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="category3"> <label class="custom-control-label" for="category3">React</label></div>
                            </li>
                        </ul>
                        <p class="text-muted text-small">Labels</p>
                        <div>
                            <p class="d-sm-inline-block mb-1"><a href="#"><span class="badge badge-pill badge-outline-primary mb-1">NEW FRAMEWORK</span></a></p>
                            <p class="d-sm-inline-block mb-1"><a href="#"><span class="badge badge-pill badge-outline-theme-3 mb-1">EDUCATION</span></a></p>
                            <p class="d-sm-inline-block mb-1"><a href="#"><span class="badge badge-pill badge-outline-secondary mb-1">PERSONAL</span></a></p>
                        </div> -->
                    </div>
                </div>
                <a class="app-menu-button d-inline-block d-xl-none" href="#"><i class="simple-icon-options"></i></a>
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