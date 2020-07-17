<?php
require_once 'init.php';
use App\User;
use App\Policy;
use App\Benefit;
User::isLogged();
$active = 'clients';
$policies = Policy::all();
$benefits = Benefit::all();
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    User::addClient($_POST);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Add Client - Personal Production and Client Monitoring System for Financial Advisors</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
        <link rel="stylesheet" href="font/iconsmind-s/css/iconsminds.css">
        <link rel="stylesheet" href="font/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.rtl.only.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap-datepicker3.min.css">
        <link rel="stylesheet" href="css/vendor/perfect-scrollbar.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/vendor/select2.min.css">
        <link rel="stylesheet" href="css/vendor/select2-bootstrap.min.css">
        <link rel="stylesheet" href="css/add-fa.css">
        <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    </head>
    <body id="app-container" class="menu-default show-spinner">
        <?php include 'partials/header.php' ?>
        <?php include 'partials/sidebar.php' ?>
        <main>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h1>Add a client</h1>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                            <ol class="breadcrumb pt-0">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                            </ol>
                        </nav>
                        <div class="separator mb-5"></div>
                    </div>
                </div>
                <form id="regForm" method="POST">
                    <h1 id="title">Basic Information:</h1>
                    <!-- One "tab" for each step in the form: -->
                    <div class="tab" id="basic-form">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="firstName">First Name</label>
                                    <input type="text" class="form-control" name="firstname" oninput="this.className = 'form-control'" id="firstName" placeholder="First Name">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="firstName">Middle Name</label>
                                    <input type="text" class="form-control" name="middlename" oninput="this.className = 'form-control'" id="middlename" placeholder="Middle Name">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="lastName">Last Name</label>
                                    <input type="text" class="form-control" name="lastname" oninput="this.className = 'form-control'" id="lastName" placeholder="Last Name">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Gender</label> 
                                    <select class="form-control select2" data-width="100%" name="gender" oninput="this.className = 'form-control'">
                                        <option value="">Choose...</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" oninput="this.className = 'form-control'" id="email" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group input-group date">
                                    <label>Date of Birth</label>
                                    <input type="text" class="form-control" name="dob" id="dob" style="width: 100%;" placeholder="Date of Birth">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <h2>Beneficiaries</h2>
                        <br />
                        <div class="beneficiaries">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="fullname">Full Name</label>
                                        <input type="text" class="form-control" name="fullname[]" oninput="this.className = 'form-control'" placeholder="Full Name">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="fullname">Relationship</label>
                                        <input type="text" class="form-control" name="beneficiary_relationship[]" oninput="this.className = 'form-control'" placeholder="Relationship">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Designation</label> 
                                        <select class="form-control select2" data-width="100%" name="designation[]" oninput="this.className = 'form-control'">
                                            <option value="">Choose...</option>
                                            <option value="Revocable">Revocable</option>
                                            <option value="Irrevocable">Irrevocable</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group input-group">
                                        <label>Date of Birth</label>
                                        <input type="date" class="form-control" name="beneficiaries_dob[]" style="width: 100%;" placeholder="Date of Birth" oninput="this.className = 'form-control'">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                    <a href="#" onclick="return false;" style="position: absolute; top:0; right:0;" class="btn btn-primary addRow">+</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab">
                        <div class="form-group">
                            <label>Plan</label> 
                            <select class="form-control select2" data-width="100%" name="product">
                                <option label="&nbsp;">&nbsp;</option>
                                <?php foreach($policies as $key => $value): ?>
                                <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Benefits</label> 
                            <select class="form-control select2-multiple" multiple="multiple" data-width="100%" name="benefits[]">
                                <?php foreach($benefits as $key => $value): ?>
                                <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="annualPremium">Face Amount</label>
                                    <input type="text" class="form-control" name="face_amount" oninput="this.className = ''" id="faceAmount" placeholder="Face Amount">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="annualPremium">Annual Premium</label>
                                    <input type="text" class="form-control" name="annual_premium" oninput="this.className = ''" id="annualPremium" placeholder="Annual Premium">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="excessPremium">Excess Premium</label>
                                    <input type="text" class="form-control" name="excess_premium" oninput="this.className = ''" id="excessPremium" placeholder="Excess Premium">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="policyNumber">Policy Number</label>
                                    <input type="text" class="form-control" name="policy_number" oninput="this.className = ''" id="policyNumber" placeholder="Policy Number">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Mode of Payment</label> 
                                    <select class="form-control select2" data-width="100%" name="mode_of_payment">
                                        <option value="Annual">Annual</option>
                                        <option value="Semi-Annual">Semi-Annual</option>
                                        <option value="Quarterly">Quarterly</option>
                                        <option value="Monthly">Monthly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group input-group date">
                                    <label>Issue Date</label>
                                    <input type="text" class="form-control" oninput="this.className = ''"  name="issue_date" style="width: 100%;" placeholder="Issue Date">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="overflow:auto;">
                        <div style="float:right; margin-top: 2rem;">
                            <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                            <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                        </div>
                    </div>
                    <!-- Circles which indicates the steps of the form: -->
                    <div style="text-align:center;margin-top:40px;">
                        <span class="step"></span>
                        <span class="step"></span>
                    </div>
                </form>
            </div>
        </main>
        <script src="js/vendor/jquery-3.3.1.min.js"></script>
        <script src="js/vendor/bootstrap.bundle.min.js"></script>
        <script src="js/vendor/perfect-scrollbar.min.js"></script>
        <script src="js/vendor/mousetrap.min.js"></script>
        <script src="js/vendor/bootstrap-datepicker.js"></script>
        <script src="js/vendor/select2.full.js"></script>
        <script src="js/dore.script.js"></script>
        <script src="js/scripts.js"></script>
        <script src="js/add-fa.js"></script>
        <script>
            $( "#dob" ).focus(function() {
                $(this).removeClass("invalid");
            });
            $('.addRow').on('click', function() {
                addRow();
            });

            function addRow() {
                var div = '<div class="row">'+
                            '<div class="col-3">'+
                                '<div class="form-group">'+
                                    '<label for="fullname">Full Name</label>'+
                                    '<input type="text" class="form-control" name="fullname[]" placeholder="Full Name">'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-3">'+
                                '<div class="form-group">'+
                                    '<label for="fullname">Relationship</label>'+
                                    '<input type="text" class="form-control" name="beneficiary_relationship[]" placeholder="Relationship">'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-3">'+
                                    '<div class="form-group">'+
                                        '<label>Designation</label> '+
                                        '<select class="form-control select2" data-width="100%" name="designation[]">'+
                                            '<option value="">Choose...</option>'+
                                            '<option value="Revocable">Revocable</option>'+
                                            '<option value="Irrevocable">Irrevocable</option>'+
                                        '</select>'+
                                    '</div>'+
                                '</div>'+
                            '<div class="col-3">'+
                                '<div class="form-group input-group date">'+
                                    '<label>Date of Birth</label>'+
                                    '<input type="date" class="form-control" name="beneficiaries_dob[]" style="width: 100%;" placeholder="Date of Birth">'+
                                    '<span class="input-group-addon">'+
                                        '<span class="glyphicon glyphicon-calendar"></span>'+
                                    '</span>'+
                                '</div>'+
                                '<a href="#" onclick="return false;" style="position: absolute; top:0; right:0;" class="btn btn-danger remove">-</a>'+
                            '</div>'+
                        '</div>';
                $('.beneficiaries').append(div);
            };

            $('.beneficiaries').on('click', '.remove', function() {
                $(this).parent().parent().remove();
            });

        </script>
    </body>
</html>
