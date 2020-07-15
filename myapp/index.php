<?php
$active = 'dashboard';
require_once 'init.php';
use App\Redirect;
use App\User;
use App\Session;
use App\Event;
use App\People;
use App\Payment;

$currentMonth = date('m');
$new_clients = User::whereRaw('MONTH(created_at) = ?',[$currentMonth])
    ->whereNull('role_id')->get();

$follow_ups = People::followUps();
$prospects = People::prospects();

$follow_up_count = People::followUpsThisMonth();
$prospects_count = People::prospectsThisMonth();
$payment_due = Payment::paymentsDueThisMonth();

if(isset($_GET['logout'])) {
    User::logout();
}
User::isLogged();
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        People::deleteRecord($_POST);
    } else if (isset($_POST['action']) && $_POST['action'] == 'update') {
        People::updateRecord($_POST);
    } else {
        People::add($_POST);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Dashboard - Personal Production and Client Monitoring System for Financial Advisors</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
        <link rel="stylesheet" href="font/iconsmind-s/css/iconsminds.css">
        <link rel="stylesheet" href="font/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.min.css">
        <link rel="stylesheet" href="css/vendor/glide.core.min.css">
        <link rel="stylesheet" href="css/vendor/bootstrap.rtl.only.min.css">
        <link rel="stylesheet" href="css/vendor/component-custom-switch.min.css">
        <link rel="stylesheet" href="css/vendor/perfect-scrollbar.css">
        <link rel="stylesheet" href="css/vendor/bootstrap-datepicker3.min.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/vendor/fullcalendar.min.css">
        <link rel="stylesheet" href="../components/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
        <link rel='stylesheet' href='../components/glyphicons-only-bootstrap/css/bootstrap.min.css' />
    </head>
    <body id="app-container" class="menu-default show-spinner">
        <?php include 'partials/header.php'; ?>
        <?php include 'partials/sidebar.php' ?>
        <main>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <?php include 'partials/message.php' ?>
                        <?php include 'partials/error-message.php' ?>
                        <h1>Dashboard</h1>
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

                <div class="row sortable">
                    <div class="col-xl-3 col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">New Clients <br/> <small><i>This Month</i></small></h6>
                                <div role="progressbar" class="progress-bar-circle position-relative" data-color="#922c88" data-trailcolor="#d7d7d7" aria-valuemax="100" aria-valuenow="<?= $new_clients->count() ?>" data-show-percent="true"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Prospects <br/> <small><i>This Month</i></small></h6>
                                <div role="progressbar" class="progress-bar-circle position-relative" data-color="#922c88" data-trailcolor="#d7d7d7" aria-valuemax="100" aria-valuenow="<?= $prospects_count ?>" data-show-percent="true"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Follow up <br/> <small><i>This Month</i></small></h6>
                                <div role="progressbar" class="progress-bar-circle position-relative" data-color="#922c88" data-trailcolor="#d7d7d7" aria-valuemax="100" aria-valuenow="<?= $follow_up_count ?>" data-show-percent="true"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Payment Due <br/> <small><i>This Month</i></small></h6>
                                <div role="progressbar" class="progress-bar-circle position-relative" data-color="#922c88" data-trailcolor="#d7d7d7" aria-valuemax="100" aria-valuenow="<?= $payment_due ?>" data-show-percent="true"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">                    
                    <div class="col-lg-12 col-xl-6">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Clients this year and previous year</h5>
                                        <div class="dashboard-line-chart chart">
                                            <canvas id="salesChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <button type="button" style="float: right;" class="btn btn-primary follow-up" data-toggle="modal" data-target="#addFollowUp">
                                    Add new
                                </button>
                                <h5 class="card-title">For Follow up (<?= $follow_ups->count() ?>)</h5>
                                <div class="scroll dashboard-list-with-thumbs">
                                    <?php foreach ( $follow_ups as $key => $value): ?>
                                    <div class="d-flex flex-row mb-3" style="float: left;">
                                        <div class="">
                                            <a href="#" onClick="showPeople(<?= $value->id ?>)">
                                                <p class="list-item-heading"><?= $value->firstname.' '.$value->lastname ?></p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small"><?= $value->address ?></p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block"><?= date('F j, Y', strtotime($value->created_at)) ?></div>
                                            </a>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-12 mb-4">
                        <div id="calendar" style="border: 2px solid #eee;"></div>
                    </div>
                    <div class="col-xl-6 col-lg-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <button type="button" style="float: right;" class="btn btn-primary prospects" data-toggle="modal" data-target="#addProspects">
                                    Add new
                                </button>
                                <h5 class="card-title">Prospects (<?= $prospects->count() ?>)</h5>
                                <div class="scroll dashboard-list-with-thumbs">
                                    <?php foreach ( $prospects as $key => $value): ?>
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#" onClick="showPeople(<?= $value->id ?>)">
                                                <p class="list-item-heading"><?= $value->firstname.' '.$value->lastname ?></p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small"><?= $value->address ?></p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block"><?= date('F j, Y', strtotime($value->created_at)) ?></div>
                                            </a>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="event-modal" tabindex="-1" role="dialog" aria-labelledby="event-modal-label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="event-modal-label">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <form>
                        <!-- <p>From: <span class="from"></span></p>
                        <p>To: <span class="to"></span></p> -->
                        <div class="form-group">
                            <label for="event-title">Title</label>
                            <input type="text" class="form-control" id="event-title" aria-describedby="titleHelp" placeholder="Enter title">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="timeStarts">Time Starts</label>
                                    <div class='input-group date' id='date-time-starts'>
                                        <input type='text' name="time-starts" id="timeStarts" class="form-control" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="timeEnds">Time Ends</label>
                                    <div class='input-group date' id='date-time-ends'>
                                        <input type='text' name="time-ends" id="timeEnds" class="form-control" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="audience">Who should see this?</label>
                            <select class="form-control" id="audience">
                            <option>All Units</option>
                            <option>My Unit Only</option>
                            <option>Only Me</option>
                            </select>
                        </div>

                    </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="save-event-modal">Save</button>
                    </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="addFollowUp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Follow Up</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="follow-up-form" method="post">
                            <div class="form-group">
                                <label for="event-title">First Name</label>
                                <input type="text" class="form-control" name="firstname" aria-describedby="titleHelp" placeholder="Enter first name" required>
                            </div>
                            <div class="form-group">
                                <label for="event-title">Middle Name</label>
                                <input type="text" class="form-control" name="middlename" aria-describedby="titleHelp" placeholder="Enter middle name" required>
                            </div>
                            <div class="form-group">
                                <label for="event-title">Last Name</label>
                                <input type="text" class="form-control" name="lastname" aria-describedby="titleHelp" placeholder="Enter last name" required>
                            </div>
                            <div class="form-group">
                                <label for="event-title">Address</label>
                                <input type="text" class="form-control" name="address" aria-describedby="titleHelp" placeholder="Enter address" required>
                            </div>
                            <div class="input-group date form-group position-relative info">
                                <label>Birth Date</label>
                                <input type="text" class="form-control" name="birthdate" style="width: 100%;" placeholder="Birth Date" required>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            <input type="hidden" name="status" value="follow_up">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="follow-up-btn">Save changes</button>
                    </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="addProspects" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Prospect</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="prospect-form" method="post">
                            <div class="form-group">
                                <label for="event-title">First Name</label>
                                <input type="text" class="form-control" name="firstname" aria-describedby="titleHelp" placeholder="Enter first name" required>
                            </div>
                            <div class="form-group">
                                <label for="event-title">Middle Name</label>
                                <input type="text" class="form-control" name="middlename" aria-describedby="titleHelp" placeholder="Enter middle name" required>
                            </div>
                            <div class="form-group">
                                <label for="event-title">Last Name</label>
                                <input type="text" class="form-control" name="lastname" aria-describedby="titleHelp" placeholder="Enter last name" required>
                            </div>
                            <div class="form-group">
                                <label for="event-title">Address</label>
                                <input type="text" class="form-control" name="address" aria-describedby="titleHelp" placeholder="Enter address" required>
                            </div>
                            <div class="input-group date form-group position-relative info">
                                <label>Birth Date</label>
                                <input type="text" class="form-control" name="birthdate" style="width: 100%;" placeholder="Birth Date" required>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            <input type="hidden" name="status" value="prospect">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="prospect-btn">Save changes</button>
                    </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="viewPeople" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">View</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="view-form" method="post">
                            <div class="form-group">
                                <label for="event-title">First Name</label>
                                <input type="text" class="form-control" name="firstname" id="firstname" aria-describedby="titleHelp" placeholder="Enter first name" required>
                            </div>
                            <div class="form-group">
                                <label for="event-title">Middle Name</label>
                                <input type="text" class="form-control" name="middlename" id="middlename" aria-describedby="titleHelp" placeholder="Enter middle name" required>
                            </div>
                            <div class="form-group">
                                <label for="event-title">Last Name</label>
                                <input type="text" class="form-control" name="lastname" id="lastname" aria-describedby="titleHelp" placeholder="Enter last name" required>
                            </div>
                            <div class="form-group">
                                <label for="event-title">Address</label>
                                <input type="text" class="form-control" name="address" id="address" aria-describedby="titleHelp" placeholder="Enter address" required>
                            </div>
                            <div class="input-group date form-group position-relative info">
                                <label>Birth Date</label>
                                <input type="text" class="form-control" name="birthdate" id="birthdate" style="width: 100%;" placeholder="Birth Date" required>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            <input type="hidden" name="status" value="prospect" id="status">
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="action" id="action">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="delete-btn" style="position: absolute; left: 15px;" onClick="deleteThis()" data-dismiss="modal">Delete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onClick="updateThis()" id="view-btn">Save changes</button>
                    </div>
                    </div>
                </div>
            </div>
        </main>
        <script src="js/vendor/jquery-3.3.1.min.js"></script>
        <script src="js/vendor/bootstrap.bundle.min.js"></script>
        <script src="js/vendor/perfect-scrollbar.min.js"></script>
        <script src="js/vendor/mousetrap.min.js"></script>
        <script src="js/vendor/glide.min.js"></script>
        <script src="js/vendor/Chart.bundle.min.js"></script>
        <script src="js/vendor/chartjs-plugin-datalabels.js"></script>
        <script src="js/vendor/progressbar.min.js"></script>
        <script src="js/vendor/bootstrap-datepicker.js"></script>
        <script src="js/dore.script.js"></script>
        <script src="js/scripts.js"></script>
        <script src="js/vendor/moment.min.js"></script>
        <script src="../components/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
        <script src="js/vendor/fullcalendar.min.js"></script>
        <script>
            $(function () {
                $('#timeStarts').datetimepicker({
                    format: 'LT'
                });
                $('#timeEnds').datetimepicker({
                    format: 'LT'
                });
            });
            $('#follow-up-btn').click(function () {
                $('#follow-up-form').submit();
            });
            $('#prospect-btn').click(function () {
                $('#prospect-form').submit();
            });
            $('#view-btn').click(function () {
                $('#view-form').submit();
            });
            function showPeople(id) {
                $('#viewPeople').modal('toggle');
                $.ajax({
                    url:"functions/fetch-people.php",
                    type:"POST",
                    data:{id},
                    success:function(data) {
                        var parsed = JSON.parse(data)
                        $('#firstname').val(parsed.firstname); 
                        $('#middlename').val(parsed.middlename); 
                        $('#lastname').val(parsed.lastname); 
                        $('#address').val(parsed.address); 
                        $('#birthdate').val(parsed.birthdate); 
                        $('#status').val(parsed.status); 
                        $('#id').val(parsed.id); 
                    }
                })
            };
            function deleteThis() {
                $('#action').val('delete');
                $('#view-form').submit();
            };
            function updateThis() {
                $('#action').val('update');
                $('#view-form').submit();
            };
            $('.alert-success').fadeIn('fast').fadeOut(8000);
            $('.alert-danger').fadeIn('fast').fadeOut(8000);
            var calendar = $('#calendar').fullCalendar({
                editable:true,
                header: {
                    left:'prev,next today',
                    center:'title',
                    right:'month,agendaWeek,agendaDay'
                },
                events: 'functions/load.php',
                selectable:true,
                selectHelper:true,
                select: function(start, end, allDay) {
                    $('#event-modal').modal('show');
                    $('#event-modal-label').html('Add an event');
                    var title = $('#event-title').val();
                    var description = $('#description').val();
                    var audience = $('#audience').val();
                    var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm");
                    var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm");
                    $('.from').html(start);
                    $('.to').html(end);
                    $("#save-event-modal").click(function() {
                        const pamagat = $('#event-title').val();
                        const paglalarawan = $('#description').val();
                        const madla = $('#audience').val();
                        const simula = $('#timeStarts').val();
                        const huli = $('#timeEnds').val();
                        $.ajax({
                            url:"functions/insert.php",
                            type:"POST",
                            data:{
                                title: pamagat,
                                start: start,
                                end: end,
                                description: paglalarawan,
                                audience: madla,
                                am: simula,
                                pm: huli
                            },
                            success:function() {
                                $('#event-title').val('');
                                $('#description').val('');
                                $('#audience').val('');
                                $('#event-modal').modal('hide');
                                $('#save-event-modal').unbind('click');
                                calendar.fullCalendar('refetchEvents');
                            },
                            error: function(err) {
                                console.log(err)
                            }
                        })
                    });
                    // var title = prompt("Enter Event Title");
                    // if (title) {
                        // var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                        // var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                    //     $.ajax({
                    //         url:"functions/insert.php",
                    //         type:"POST",
                    //         data:{title:title, start:start, end:end},
                    //         success:function() {
                    //             calendar.fullCalendar('refetchEvents');
                    //             alert("Added Successfully");
                    //         },
                    //         error: function(err) {
                    //             console.log(err)
                    //         }
                    //     })
                    // }
                },
                editable:true,
                
                eventResize:function(event) {
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                    var title = event.title;
                    var id = event.id;
                    $.ajax({
                        url:"functions/update.php",
                        type:"POST",
                        data:{title:title, start:start, end:end, id:id},
                        success:function() {
                            calendar.fullCalendar('refetchEvents');
                            alert('Event Update');
                        }
                    })
                },

                eventDrop:function(event) {
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                    var title = event.title;
                    var id = event.id;
                    $.ajax({
                        url:"functions/update.php",
                        type:"POST",
                        data:{title:title, start:start, end:end, id:id},
                        success:function() {
                            calendar.fullCalendar('refetchEvents');
                            alert("Event Updated");
                        }
                    });
                },

                eventClick:function(event) {
                    console.log(event.id)
                    if(confirm("Are you sure you want to remove it?")) {
                        var id = event.id;
                        $.ajax({
                            url:"functions/delete.php",
                            type:"POST",
                            data:{id:id},
                            success:function() {
                                calendar.fullCalendar('refetchEvents');
                                alert("Event Removed");
                            }
                        })
                    }
                },
            });
        </script>
    </body>
</html>