<?php
$active = 'policies';
require_once 'init.php';
use App\Policy;
$policies = Policy::all();
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if (isset($_POST['policy_id'])) {
        Policy::updatePolicy($_POST);
    } elseif(isset($_POST['delete-policy'])) {
        Policy::deletePolicy($_POST['delete-policy']);
    } else {
        Policy::add($_POST);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Plans - Personal Production and Client Monitoring System for Financial Advisors</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
        <link rel="stylesheet" href="font/iconsmind-s/css/iconsminds.css">
        <link rel="stylesheet" href="font/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="css/vendor/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="css/vendor/datatables.responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap-datepicker3.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.min.css">
        <link rel="stylesheet" href="css/vendor/glide.core.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.rtl.only.min.css">
        <link rel="stylesheet" href="css/vendor/component-custom-switch.min.css">
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
                <div class="row">
                    <div class="col-12">
                        <?php include 'partials/message.php' ?>
                        <h1>Products</h1>
                        <div class="top-right-button-container">
                            <button type="button" class="btn btn-outline-primary btn-lg top-right-button mr-1" data-toggle="modal" data-target="#rightModal">ADD NEW</button>
                            <div class="modal fade modal-right" id="rightModal" tabindex="-1" role="dialog" aria-labelledby="rightModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rightModalLabel">Add a Plan</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Submitting below form will add the data to Data Table and rows will be updateda.</p>
                                            <form class="tooltip-right-top" id="addToDatatableForm" method="POST" novalidate>
                                                <div class="form-group position-relative info">
                                                    <label>Plan Name</label>
                                                    <input type="text" class="form-control" name="policy" placeholder="Enter Plan" required>
                                                </div>
                                                <div class="form-group position-relative info">
                                                    <label>Commission Rate</label>
                                                    <input type="text" class="form-control" name="commission" placeholder="Enter Commission Rate" required>
                                                </div>
                                                <div class="form-group position-relative info">
                                                    <label>Excess Premium Rate</label>
                                                    <input type="text" class="form-control" name="excess_premium" placeholder="Enter Excess Premium Rate" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Category</label> 
                                                    <select class="form-control select2-single" name="type">
                                                        <option value="Traditional">Traditional</option>
                                                        <option value="VUL">VUL</option>
                                                    </select>
                                                </div>
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
                </div>
                <div class="row mb-4">
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <table class="data-table data-table-feature payment-table">
                                    <thead>
                                        <tr>
                                            <th>Policy</th>
                                            <th>Commission Rate</th>
                                            <th>Excess Premium Rate</th>
                                            <th>Category</th>
                                            <th>Actions</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($policies as $key => $value): ?>
                                            <tr>
                                                <td><?= $value->name ?></td>
                                                <td><?= $value->commission.'%' ?></td>
                                                <td><?= $value->excess_premium.'%' ?></td>
                                                <td><?= $value->type ?></td>
                                                <td>
                                                    <button onClick="view(<?=$value->id ?>)" class="edit btn btn-primary" id="<?= 'edit-'.$value->id ?>"><i class="iconsminds-file-edit">Edit</i></button>
                                                    <button onClick="deleteRecord(<?=$value->id ?>)" class="delete btn btn-danger" id="<?= 'delete-'.$value->id ?>"><i class="iconsminds-trash-with-men">Delete</i></button>
                                                </td>
                                                <td></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal modal-action" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Policy Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="policy_modal">
                            <div class="form-group">
                                <label for="inputPolicy">Plan Name</label>
                                <input type="text" class="form-control" id="inputPolicy" name="policy" placeholder="Enter Plan">
                            </div>
                            <div class="form-group position-relative info">
                                <label>Commission Rate</label>
                                <input type="text" class="form-control" id="inputCommission" name="commission" placeholder="Enter Commission Rate" required>
                            </div>
                            <div class="form-group position-relative info">
                                <label>Excess Premium Rate</label>
                                <input type="text" class="form-control" id="inputExcessPremium" name="excess_premium" placeholder="Enter Excess Premium Rate" required>
                            </div>
                            <div class="form-group">
                                <label>Category</label> 
                                <select class="form-control select2-single" id="product-type" name="type">
                                    <option value="Traditional">Traditional</option>
                                    <option value="VUL">VUL</option>
                                </select>
                            </div>
                            <input type="hidden" name="policy_id" id="policy_id">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" form="policy_modal">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>
            <div id="delete-modal" class="modal fade">
                <div class="modal-dialog modal-confirm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Are you sure?</h4>	
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Do you really want to delete this record? This process cannot be undone.</p>
                            <form method="POST" id="delete-policy-form">
                                <input type="hidden" id="delete-policy" name="delete-policy">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger delete-policy">Delete</button>
                        </div>
                    </div>
                </div>
            </div>    
        </main>
        <script src="js/vendor/jquery-3.3.1.min.js"></script>
        <script src="js/vendor/bootstrap.bundle.min.js"></script>
        <script src="js/vendor/perfect-scrollbar.min.js"></script>
        <script src="js/vendor/jquery.validate/jquery.validate.min.js"></script>
        <script src="js/vendor/jquery.validate/additional-methods.min.js"></script>
        <script src="js/vendor/mousetrap.min.js"></script>
        <script src="js/vendor/datatables.min.js"></script>
        <script src="js/vendor/select2.full.js"></script>
        <script src="js/dore.script.js"></script>
        <script src="js/scripts.js"></script>
        <script>
            $('#addToDatatable').click(function () {
                $('#addToDatatableForm').submit();
            })
            $('.delete-policy').click(function () {
                $('#delete-policy-form').submit();
            })
            $('.delete').click(function() {
                $('#delete-modal').modal('toggle');
            })
            $('.alert-success').fadeIn('fast').fadeOut(8000);

            function view(id) {
                $.ajax({
                    url:"functions/fetch-policy-details.php",
                    type:"POST",
                    data:{id},
                    success:function(data) {
                        var parsed = JSON.parse(data)
                        $('#inputPolicy').val(parsed.name);
                        $('#inputCommission').val(parsed.commission);
                        $('#inputExcessPremium').val(parsed.excess_premium);
                        $("#product-type").val(parsed.type).change();
                        $('#policy_id').val(parsed.id); 
                    }
                })
                $('.modal-action').modal('toggle');
            }

            function deleteRecord(id) {
                $('#delete-policy').val(id);
                $('#delete-modal').modal('toggle');
            }
        </script>
    </body>
</html>