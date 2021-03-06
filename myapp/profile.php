<?php
$active = 'users';
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : '';
require_once 'init.php';
use App\User;
use App\UserProfile;
use App\Session;
use App\Role;
use App\Status;
use App\Unit;
use App\Policy;
use App\UserPolicy;
use App\Payment;
use App\Benefit;
use App\Production;
use App\AuditTrail;
User::isLogged();
$roles = Role::all();
$status = Status::all();
$units = Unit::all();
$policies = Policy::all();
$benefits = Benefit::all();
if(isset($_GET['policy_id'])) {
    $selected_user_policy = UserPolicy::where('policy_id', $_GET['policy_id'])
    ->with('policy', 'benefits', 'benefits.benefits')
    ->orWhereHas('benefits', function($q) {
        $q->where('user_id', $_GET['id']);
    })->first();

    $benefitsData = json_encode($selected_user_policy);
    $benefitsData = json_decode($benefitsData);

    $policy = Policy::find($_GET['policy_id']);
}
$audit_trails = AuditTrail::get(Session::get('user_id'));
$selected_user = User::with('profile', 'role', 'profile.advisor', 'profile.unit', 'profile.status')->find($_GET['id']);
$advisor = UserProfile::where('user_id', $_GET['id'])->with('advisor')->first();
$advisors = User::whereIn('role_id', [1, 2, 3, 4])->with('profile')->get();
$user_policies = UserPolicy::where('user_id', $_GET['id'])->with('policy')->get();
$selected_user_clients = UserProfile::where('advisor_id', $_GET['id'])->get();
$payment_history = Payment::where('user_id', $_GET['id'])->with('policy', 'profile')->get();
if (!$selected_user->role_id) {
    $active = 'clients';
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{ 
    if (isset($_POST['uploadImage'])) {
        UserProfile::upload($_FILES, $_POST);
    } else if (isset($_POST['update_policy'])) {
        UserPolicy::updatePolicy($_POST);
    } else if(isset($_POST['add_policy'])) {
        UserPolicy::addPolicy($_POST);
    } else {
        User::updateUser($_POST);
    }
}
$logged_user = User::with('role')->find(Session::get('user_id'));
$mode_of_payment = ['Annual', 'Semi-Annual', 'Quarterly', 'Monthly'];
$total_campaign = Production::userCampaign($_GET['id']);
$total_ytd = Production::userYTD($_GET['id']);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Profile - Personal Production and Client Monitoring System for Financial Advisors</title>
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
        <style>
            ul.timeline {
                list-style-type: none;
                position: relative;
            }
            ul.timeline:before {
                content: ' ';
                background: #d4d9df;
                display: inline-block;
                position: absolute;
                left: 29px;
                width: 2px;
                height: 100%;
                z-index: 400;
            }
            ul.timeline > li {
                margin: 20px 0;
                padding-left: 20px;
            }
            ul.timeline > li:before {
                content: ' ';
                background: white;
                display: inline-block;
                position: absolute;
                border-radius: 50%;
                border: 3px solid #22c0e8;
                left: 20px;
                width: 20px;
                height: 20px;
                z-index: 400;
            }
            .select2-selection--multiple.form-control  {
                height: auto!important;
            }
            @media only screen and (max-width: 768px) {
                .profile-image {
                    display:none;
                }
                .history-pane {
                    display:none;
                }
            }
        </style>
    </head>
    <body id="app-container" class="menu-default show-spinner">
        <?php include 'partials/header.php' ?>
        <?php include 'partials/sidebar.php' ?>
        <main>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <?php include 'partials/message.php' ?>
                        <h1><?= $selected_user->role_id ? 'Users' : 'Clients' ?></h1>
                        <div class="top-right-button-container">
                            <?php if (!$selected_user->role_id): ?>
                            <button type="button" class="btn btn-outline-primary btn-lg top-right-button mr-1" data-toggle="modal" data-target="#rightModal">ADD A PLAN</button>
                            <?php endif; ?>
                            <div class="modal fade modal-right" id="rightModal" tabindex="-1" role="dialog" aria-labelledby="rightModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rightModalLabel">Add a plan</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Submitting below form will add the policy to <?= $selected_user->profile->firstname.' '.$selected_user->profile->lastname ?>'s profile</p>
                                            <form class="tooltip-right-top" id="addToDatatableForm" novalidate method="POST">
                                                <div class="form-group position-relative">
                                                    <label>Plan Name</label> 
                                                    <select class="form-control select2-single" name="policy" data-width="100%">
                                                        <option>Select Plan</option>
                                                        <?php foreach($policies as $key => $policy): ?>
                                                        <option value="<?= $policy->id ?>"><?= $policy->name ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Benefits</label> 
                                                    <select class="form-control select2-multiple" multiple="multiple"  name="benefits[]">
                                                        <?php foreach($benefits as $key => $value): ?>
                                                        <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="annualPremium">Face Amount</label>
                                                    <input type="text" class="form-control" name="face_amount" id="faceAmount" placeholder="Face Amount">
                                                </div>
                                                <div class="form-group position-relative">
                                                    <label>Annual Premium Amount</label> 
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="annual_premium_amount" placeholder="Annual Premium Amount" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group position-relative">
                                                    <label>Excess Premium Amount</label> 
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="excess_premium_amount" placeholder="Excess Premium Amount" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group position-relative">
                                                    <label>Policy Number</label> 
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="policy_number" placeholder="Policy Number" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group position-relative">
                                                    <label>Mode of Payment</label> 
                                                    <select class="form-control select2-single" name="mode_of_payment" data-width="100%">
                                                        <option>Select Mode of Payment</option>
                                                        <option value="Annual">Annual</option>
                                                        <option value="Semi-Annual">Semi-Annual</option>
                                                        <option value="Quarterly">Quarterly</option>
                                                        <option value="Monthly">Monthly</option>
                                                    </select>
                                                </div>
                                                <div class="input-group date form-group position-relative info">
                                                    <label>Issue Date</label>
                                                    <input type="text" class="form-control" name="issue_date" style="width: 100%;" placeholder="Issue Date" required>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                                <input type="hidden" name="add_policy" value="add_policy">
                                                <input type="hidden" name="profile_user_id" value="<?= $_GET['id'] ?>">
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="#" class="btn btn-primary btn-multiple-state" id="addToDatatable">
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
                                <li class="breadcrumb-item"><a href="clients.php">Profile</a></li>
                                <!-- <li class="breadcrumb-item"><a href="#">Library</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Data</li> -->
                            </ol>
                        </nav>
                        <div class="separator mb-5"></div>
                    </div>
                    <div class="col-3 profile-image">
                        <div class="card" style="width: 18rem;">
                            <img src="<?= $selected_user->profile->image_path ?: 'img/no-photo.png' ?>" class="card-img-top" alt="...">
                            <form method="post" enctype="multipart/form-data">
                                Select image to upload:
                                <input type="file" name="fileToUpload" id="fileToUpload">
                                <input type="hidden" name="user_id" value="<?= $_GET['id'] ?>">
                                <input type="submit" value="Upload Image" name="uploadImage">
                            </form>
                            <div class="card-body">
                                <h5 class="card-title"><?= $selected_user->profile->firstname.' '.$selected_user->profile->lastname  ?></h5>
                                <p class="card-text"><?= $selected_user->email ?></p>
                                <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <?php if($selected_user->role_id): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $active_tab === 'home' ? 'active' : null ?>" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Production</a>
                            </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $active_tab === 'profile' ? 'active' : null ?>" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Profile Info</a>
                            </li>
                            <?php if(!$selected_user->role_id): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $active_tab === 'policy' ? 'active' : null ?>" id="pills-policy-tab" data-toggle="pill" href="#pills-policy" role="tab" aria-controls="pills-policy" aria-selected="false">Policy Info</a>
                            </li>
                            <?php endif; ?>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade <?= $active_tab === 'clients' ? 'show active' : null ?>" id="pills-clients" role="tabpanel" aria-labelledby="pills-clients-tab">
                                <ul class="list-group list-group-flush">
                                    <?php if($selected_user_clients->count()): ?>
                                    <?php foreach($selected_user_clients as $key => $value): ?>
                                    <li class="list-group-item"><a href="profile.php?id=<?= $value->user_id.'&tab=home' ?>"><?= $value->firstname.' '.$value->lastname ?></a></li>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                        <h1>No Clients found.</h1>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="tab-pane fade <?= $active_tab === 'home' ? 'show active' : null ?>" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <p style="margin-top:3rem;">Total YTD</p>
                                <p style="font-size:2rem;">&#8369;<?= number_format($total_ytd) ?></p>
                                <p style="margin-top:3rem;">Total Campaign</p>
                                <p style="font-size:2rem;">&#8369;<?= number_format($total_campaign) ?></p>
                            </div>
                            <div class="tab-pane fade <?= $active_tab === 'profile' ? 'show active' : null ?>" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <form method="POST">
                                    <div class="form-group">
                                        <label for="client-firstname">First Name</label>
                                        <input type="text" class="form-control" name="firstname" id="client-firstname" value="<?= $selected_user->profile->firstname ?>" <?= $logged_user->role->rank > (isset($selected_user->role->rank) ? $selected_user->role->rank : 0 ) ? 'disabled' : 'null' ?>>
                                    </div>
                                    <div class="form-group">
                                        <label for="client-lastname">Middle Name</label>
                                        <input type="text" class="form-control" name="middlename" id="client-middlename" value="<?= $selected_user->profile->middlename ?>" <?= $logged_user->role->rank > (isset($selected_user->role->rank) ? $selected_user->role->rank : 0 ) ? 'disabled' : 'null' ?>>
                                    </div>
                                    <div class="form-group">
                                        <label for="client-lastname">Last Name</label>
                                        <input type="text" class="form-control" name="lastname" id="client-lastname" value="<?= $selected_user->profile->lastname ?>" <?= $logged_user->role->rank > (isset($selected_user->role->rank) ? $selected_user->role->rank : 0 ) ? 'disabled' : 'null' ?>>
                                    </div>
                                    <div class="form-group">
                                        <label for="client-email">Email</label>
                                        <input type="email" class="form-control" id="client-email" name="email" id="client-email" value="<?= $selected_user->email ?>" <?= $logged_user->role->rank > (isset($selected_user->role->rank) ? $selected_user->role->rank : 0 ) ? 'disabled' : 'null' ?>>
                                    </div>
                                    <div class="input-group form-group position-relative info">
                                        <label>Date of Birth</label>
                                        <input type="text" class="form-control datepicker" id="client-dob" name="clientDob" value="<?= date('m/d/Y', strtotime($selected_user->profile->dob)) ?>" style="width: 100%;" <?= $logged_user->role->rank > (isset($selected_user->role->rank) ? $selected_user->role->rank : 0 ) ? 'disabled' : 'null' ?>>
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Advisor</label> 
                                        <select class="form-control select2-single" id="client-advisor" name="advisor" data-width="100%" <?= $logged_user->role->rank > (isset($selected_user->role->rank) ? $selected_user->role->rank : 0 ) ? 'disabled' : 'null' ?>>
                                            <option label="&nbsp;">&nbsp;</option>
                                            <?php foreach($advisors as $key => $value): ?>
                                                <option
                                                    <?= isset($advisor->advisor->user_id) && $value->id === $advisor->advisor->user_id
                                                        ? 'selected'
                                                        : null
                                                    ?>
                                                    value="<?= $value->id ?>">
                                                    <?= $value->profile->firstname.' '.$value->profile->lastname ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <?php if($selected_user->role_id): ?>
                                    <div class="form-group">
                                        <label>Unit</label>
                                        <select class="form-control select2-single" name="unit" data-width="100%" <?= $logged_user->role->rank > (isset($selected_user->role->rank) ? $selected_user->role->rank : 0 ) ? 'disabled' : 'null' ?>>
                                            <option label="&nbsp;">&nbsp;</option>
                                            <?php foreach($units as $key => $value): ?>
                                                <option
                                                    <?= isset($selected_user->profile->unit->id) && $value->id === $selected_user->profile->unit->id
                                                        ? 'selected'
                                                        : null
                                                    ?>
                                                    value="<?= $value->id ?>">
                                                    <?= $value->name ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control select2-single" name="status" data-width="100%" <?= $logged_user->role->rank > (isset($selected_user->role->rank) ? $selected_user->role->rank : 0 ) ? 'disabled' : 'null' ?>>
                                            <option label="&nbsp;">&nbsp;</option>
                                            <?php foreach($status as $key => $value): ?>
                                                <option
                                                    <?= isset($selected_user->profile->status->id) && $value->id === $selected_user->profile->status->id
                                                        ? 'selected'
                                                        : null
                                                    ?>
                                                    value="<?= $value->id ?>">
                                                    <?= $value->name ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="input-group form-group position-relative info">
                                        <label>Coding Date</label>
                                        <input type="text" class="form-control datepicker" id="client-coding_date" name="coding_date" value="<?= date('m/d/Y', strtotime($selected_user->profile->coding_date)) ?>" style="width: 100%;" placeholder="Coding Date" <?= $logged_user->role->rank > $selected_user->role->rank ? 'disabled' : 'null' ?>>
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php if(isset($selected_user->role->rank) && $logged_user->role->rank < $selected_user->role->rank): ?>
                                    <div class="form-group">
                                        <label>Role</label>
                                        <select class="form-control select2-single" name="role" data-width="100%">
                                            <option label="&nbsp;">&nbsp;</option>
                                            <?php foreach($roles as $key => $value): ?>
                                                <?php if(isset($selected_user->role->rank) && $logged_user->role->rank <= $value->rank): ?>
                                                <option
                                                    <?= isset($selected_user->role->id) && $value->id === $selected_user->role->id
                                                        ? 'selected'
                                                        : null
                                                    ?>
                                                    value="<?= $value->id ?>">
                                                    <?= $value->name ?>
                                                </option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <?php endif; ?>
                                    <?php if ($selected_user->role_id === null): ?>
                                    <div class="form-group">
                                        <label for="clientNumber">Client Number</label>
                                        <input type="text" class="form-control" name="client_number" id="clientNumber" value="<?= $selected_user->profile->client_number ?>">
                                    </div>
                                    <?php endif; ?>
                                    <input type="hidden" value="<?= $_GET['id'] ?>" name="user_id">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                            <div class="tab-pane fade <?= $active_tab === 'policy' ? 'show active' : null ?>" id="pills-policy" role="tabpanel" aria-labelledby="pills-policy-tab">
                                <input type="hidden" value="policy" name="action">
                                <input type="hidden" value="<?= $_GET['id'] ?>" name="user_id">
                                <?php if(isset($_GET['policy_id'])): ?>
                                    <h2><?= $selected_user_policy->policy->name ?></h2>
                                    <form class="tooltip-right-top" id="addToDatatableForm" novalidate method="POST">
                                        <div class="form-group position-relative">
                                            <label>Face Amount</label> 
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="face_amount" placeholder="Face Amount" value="<?= '&#8369;'.number_format($policy->face_amount) ?>" disabled>
                                            </div>
                                        </div>
                                        <!-- <div class="form-group">
                                            <label>Benefits</label> 
                                            <select class="form-control select2-multiple" multiple="multiple"  name="benefits[]" disabled>
                                                <?php foreach($benefits as $key => $value): ?>
                                                <option
                                                    <?= isset($benefitsData->benefits[$key]) && $value->id === $benefitsData->benefits[$key]->benefits->id
                                                        ? 'selected'
                                                        : null
                                                    ?>
                                                    value="<?= $value->id ?>"><?= $value->name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div> -->
                                        <div class="form-group position-relative">
                                            <label>Annual Premium Amount</label> 
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="annual_premium_amount" autocomplete="off" value="<?= $selected_user_policy->annual_premium_amount ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group position-relative">
                                            <label>Excess Premium Amount</label> 
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="excess_premium_amount" autocomplete="off" value="<?= $policy->face_amount * ($policy->excess_premium / 100) ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group position-relative">
                                            <label>Mode of Payment</label> 
                                            <select class="form-control select2-single" name="mode_of_payment" data-width="100%">
                                                <?php foreach ($mode_of_payment as $value): ?>
                                                <option <?= $value === $selected_user_policy->mode_of_payment
                                                        ? 'selected'
                                                        : null
                                                    ?> value=<?= $value ?>><?= $value ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="input-group date form-group position-relative info">
                                            <label>Issue Date</label>
                                            <input type="text" class="form-control" name="issue_date" style="width: 100%;" value="<?= $selected_user_policy->issue_date ?>" disabled>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <input type="hidden" name="update_policy" value="update_policy">
                                        <input type="hidden" name="profile_user_id" value="<?= $_GET['id'] ?>">
                                        <input type="hidden" name="profile_policy_id" value="<?= $_GET['policy_id'] ?>">
                                        <a href="profile.php?id=<?= $selected_user_policy->user_id ?>&tab=policy">
                                            <button type="button" class="btn btn-secondary">Back</button>
                                        </a>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                <?php elseif($user_policies->count()): ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach($user_policies as $key => $user_policy): ?>
                                    <a href="profile.php?policy_id=<?= $user_policy->policy_id.'&id='.$user_policy->user_id.'&tab=policy' ?>">
                                        <li class="list-group-item"><?= $user_policy->policy->name ?></li>
                                    </a>
                                    <?php endforeach; ?>
                                </ul>
                                <?php else: ?>
                                <h1>No records found.</h1>
                                <?php endif; ?>
                            </div>
                            <div class="tab-pane fade <?= $active_tab === 'payment-history' ? 'show active' : null ?>" id="pills-payment-history" role="tabpanel" aria-labelledby="pills-payment-history-tab">
                                <?php if($payment_history->count()): ?>
                                <?php foreach($payment_history as $key => $value): ?>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                            <th scope="col">Policy</th>
                                            <th scope="col">Amount Paid</th>
                                            <th scope="col">Date Payment</th>
                                            <th scope="col">Created By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?= $value->policy->name ?></td>
                                                <td><?= $value->amount_paid ?></td>
                                                <td><?= $value->created_at ?></td>
                                                <td><?= $value->profile->firstname.' '.$value->profile->lastname ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <h1>No records found.</h1>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 history-pane">
                        <h4>History</h4>
                        <ul class="timeline">
                            <?php foreach ($audit_trails as $key => $value): ?>
                            <li>
                                <a href="#"><?= $value->action ?></a>
                                <a href="#" class="float-right"><?= date('d M, Y', strtotime($value->created_at)) ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
        <script src="js/vendor/jquery-3.3.1.min.js"></script>
        <script src="js/vendor/bootstrap.bundle.min.js"></script>
        <script src="js/vendor/perfect-scrollbar.min.js"></script>
        <script src="js/vendor/jquery.validate/jquery.validate.min.js"></script>
        <script src="js/vendor/jquery.validate/additional-methods.min.js"></script>
        <script src="js/vendor/datatables.min.js"></script>
        <script src="js/vendor/bootstrap-datepicker.js"></script>
        <script src="js/vendor/bootstrap-tagsinput.min.js"></script>
        <script src="js/vendor/typeahead.bundle.js"></script>
        <script src="js/vendor/select2.full.js"></script>
        <script src="js/dore.script.js"></script>
        <script src="js/scripts.js"></script>
        <script>
            $('#addToDatatable').click(function () {
                $('#addToDatatableForm').submit();
            })
            $('.alert-success').fadeIn('fast').fadeOut(8000);
            const url = window.location.href
            var parser = new URL(url)
            var n = parser.search.search("edit");
            if (n >= 0) {
                $("#client-firstname").prop('disabled', false);
                $("#client-middlename").prop('disabled', false);
                $("#client-lastname").prop('disabled', false);
                $("#client-email").prop('disabled', false);
                $("#client-dob").prop('disabled', false);
                $("#client-advisor").prop('disabled', false);
                $("#client-coding_date").prop('disabled', false);
            }
        </script>
    </body>
</html>