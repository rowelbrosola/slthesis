<?php
require_once 'init.php';
use App\User;
use App\Unit;
use App\UserProfile;
use App\Session;
use App\Payment;
use App\Status;
use App\Production;
User::isLogged();
$status = Status::all();
$my_unit = UserProfile::where('user_id', Session::get('user_id'))->with('unit')->get();
$active = 'units';
if (isset($_GET['unit_id']) && $_GET['unit_id'] == $my_unit[0]->unit->id) {
    $active = 'my_unit';
}
$units = Unit::with('creator')->get();
$current_unit = Unit::find($_GET['unit_id']);
Session::put('owner_id', $current_unit->owner_id);
$unit_members = UserProfile::where('unit_id', $_GET['unit_id'])
    ->with('status', 'unit', 'advisor', 'production')
    // ->whereHas('unit', function($q) {
    //     $q->where('owner_id', '!=', Session::get('owner_id'));
    // })
    ->get();
$payments = Payment::where('unit_id', $_GET['unit_id'])->get();
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    User::add($_POST);
}

$current_production = Production::currentProduction();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= $current_unit->name ?> - Personal Production and Client Monitoring System for Financial Advisors</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
        <link rel="stylesheet" href="font/iconsmind-s/css/iconsminds.css">
        <link rel="stylesheet" href="font/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="css/vendor/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="css/vendor/datatables.responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.rtl.only.min.css">
        <link rel="stylesheet" href="css/vendor/perfect-scrollbar.css">
        <link rel="stylesheet" href="css/vendor/bootstrap-datepicker3.min.css">
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
                            <?php include 'partials/error-message.php' ?>
                            <h1><?= $current_unit->name ?></h1>
                            <div class="top-right-button-container">
                                <button type="button" class="btn btn-outline-primary btn-lg top-right-button mr-1" data-toggle="modal" data-target="#rightModal">ADD NEW</button>
                                <div class="modal fade modal-right" id="rightModal" tabindex="-1" role="dialog" aria-labelledby="rightModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="rightModalLabel">Add financial advisor</h5>
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
                                                        <label>Middle Name</label>
                                                        <input type="text" class="form-control" name="middlename" placeholder="Middle Name" required>
                                                    </div>
                                                    <div class="form-group position-relative info">
                                                        <label>Last Name</label>
                                                        <input type="text" class="form-control" name="lastname" placeholder="Last Name" required>
                                                    </div>
                                                    <div class="form-group position-relative info">
                                                        <label>Email</label>
                                                        <input type="text" class="form-control" name="email" placeholder="Email" required>
                                                    </div>
                                                    <div class="form-group position-relative info">
                                                        <label>Advisor Code</label>
                                                        <input type="text" class="form-control" maxlength="6" name="advisor_code" placeholder="Advisor Code" required>
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
                                                    <div class="form-group position-relative">
                                                        <label>Gender</label>
                                                        <select class="form-control select2-single role" name="gender" data-width="100%" required>
                                                            <option value="">Select Gender</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                        </select>
                                                    </div>
                                                    <div class="input-group date form-group position-relative info">
                                                        <label>Birthdate</label>
                                                        <input type="text" class="form-control" name="dob" style="width: 100%;" placeholder="Birth Date" required>
                                                        <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                    <div class="input-group date form-group position-relative info">
                                                        <label>Coding Date</label>
                                                        <input type="text" class="form-control" name="coding_date" style="width: 100%;" placeholder="Coding Date" required>
                                                        <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                    <input type="hidden" value="<?= $_GET['unit_id'] ?>" name="unit">
                                                    <input type="hidden" value="true" name="addtounit">
                                                    <input type="hidden" value="2" name="role">
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
                                    <li class="breadcrumb-item"><a href="#">Dagupan Sales Team</a></li>
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
                                                    <th>Year-to-date Production</th>
                                                    <th>Campaign</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($unit_members as $key => $value): ?>
                                                <?php
                                                    $sum = 0;
                                                    foreach($value->production as $k => $v)
                                                    {
                                                        $sum+= $v->amount;
                                                    }
                                                ?>
                                                <tr>
                                                    <!-- <td class="user-name" id="<?= $value->user_id ?>"><a href="#"><?= $value->firstname.' '.$value->lastname ?></a></td> -->
                                                    <td><?= $value->firstname.' '.$value->lastname ?></td>
                                                    <td><?= $value->advisor_code ?></td>
                                                    <td><?= isset($value->status) ? $value->status->name : null ?></td>
                                                    <td><?= isset($value->advisor) ? $value->advisor->firstname.' '.$value->advisor->lastname : null ?></td>
                                                    <td><?= isset($value->production) ? $sum : '&#8369;0' ?></td>
                                                    <td><?= $current_production ?></td>
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
                        <p class="text-muted text-small">Team Production</p>
                        <ul class="list-unstyled mb-5">
                            <!-- <li><i class="simple-icon-check"></i> Completed <span class="float-right">Paid</span></a></li> -->
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
                        </ul> -->
                    </div>
                </div>
                <a class="app-menu-button d-inline-block d-xl-none" href="#"><i class="simple-icon-options"></i></a>
            </div>
            <div class="modal modal-action" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Profile Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="policy_modal">
                            <div class="form-group">
                                <label for="modalName">Name</label>
                                <input type="text" class="form-control" id="modalName" name="name" disabled>
                            </div>
                            <div class="form-group">
                                <label for="modalAdvisorCode">Adivsor Code</label>
                                <input type="text" class="form-control" id="modalAdvisorCode" name="advisor_code" disabled>
                            </div>
                            <div class="form-group">
                                <label for="modalDob">Date of Birth</label>
                                <input type="text" class="form-control" id="modalDob" name="dob" disabled>
                            </div>
                            <div class="form-group">
                                <label for="modalCodingDate">Coding Date</label>
                                <input type="text" class="form-control" id="modalCodingDate" name="coding_date" disabled>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <a href="#" type="button" class="btn btn-secondary gotoProduction">Go to Production</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        <script src="js/vendor/bootstrap-datepicker.js"></script>
        <script src="js/vendor/select2.full.js"></script>
        <script src="js/dore.script.js"></script>
        <script src="js/scripts.js"></script>
        <script>
            $('#addToDatatable').click(function () {
                $('#addToDatatableForm').submit();
            })
            $('.user-name').click(function() {
                let id = $(this).attr('id')
                $.ajax({
                    url:"functions/fetch-profile.php",
                    type:"POST",
                    data:{id},
                    success:function(data) {
                        var parsed = JSON.parse(data);
                        const name = `${parsed.profile.firstname} ${parsed.profile.lastname}`
                        const url = `sales-production.php?id=${parsed.id}`
                        $('#modalName').val(name);
                        $('#modalAdvisorCode').val(parsed.profile.advisor_code);
                        $('#modalDob').val(parsed.profile.dob);
                        $('#modalCodingDate').val(parsed.profile.coding_date);
                        $('.gotoProduction').attr("href", url);
                    }
                })
                $('.modal-action').modal('toggle');
            })
            $('.alert-success').fadeIn('fast').fadeOut(8000);
            $('.alert-danger').fadeIn('fast').fadeOut(10000);
        </script>
    </body>
</html>