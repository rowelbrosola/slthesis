<?php
$active = 'dashboard';
require_once 'init.php';
use App\Redirect;
use App\User;
use App\Session;
use App\Event;

$currentMonth = date('m');
$new_clients = User::whereRaw('MONTH(created_at) = ?',[$currentMonth])
    ->whereNull('role_id')->get();

if(isset($_GET['logout'])) {
    User::logout();
}
User::isLogged();
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
                                <h6 class="mb-0">Completed <br/> <small><i>This Month</i></small></h6>
                                <div role="progressbar" class="progress-bar-circle position-relative" data-color="#922c88" data-trailcolor="#d7d7d7" aria-valuemax="100" aria-valuenow="64" data-show-percent="true"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Follow up <br/> <small><i>This Month</i></small></h6>
                                <div role="progressbar" class="progress-bar-circle position-relative" data-color="#922c88" data-trailcolor="#d7d7d7" aria-valuemax="100" aria-valuenow="75" data-show-percent="true"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Payment Due <br/> <small><i>This Month</i></small></h6>
                                <div role="progressbar" class="progress-bar-circle position-relative" data-color="#922c88" data-trailcolor="#d7d7d7" aria-valuemax="100" aria-valuenow="32" data-show-percent="true"></div>
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
                                <h5 class="card-title">For Follow up (7)</h5>
                                <div class="scroll dashboard-list-with-thumbs">
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#">
                                                <p class="list-item-heading">John Estrella</p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small">Latashia Nagy - 100-148 Warwick Trfy, Kansas City, USA</p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block">January 09, 2018</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#">
                                                <p class="list-item-heading">Fruitcake</p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small">Marty Otte - 166-156 Rue de Varennes, Gatineau, QC J8T 8G4, Canada</p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block">January 09, 2018</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#">
                                                <p class="list-item-heading">Chocolate Cake</p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small">Linn Ronning - Rasen 2-14, 98547 Kühndorf, Germany</p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block">January 09, 2018</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#">
                                                <p class="list-item-heading">Fat Rascal</p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small">Rasheeda Vaquera - 37 Rue des Grands Prés, 03100 Montluçon, France</p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block">January 09, 2018</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#">
                                                <p class="list-item-heading">Marble Cake</p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small">Latashia Nagy - 100-148 Warwick Trfy, Kansas City, USA</p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block">January 09, 2018</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#">
                                                <p class="list-item-heading">Fruitcake</p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small">Marty Otte - 166-156 Rue de Varennes, Gatineau, QC J8T 8G4, Canada</p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block">January 09, 2018</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#">
                                                <p class="list-item-heading">Streuselkuchen</p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small">Mimi Carreira - 36-71 Victoria St, Birmingham, UK</p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block">January 09, 2018</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#">
                                                <p class="list-item-heading">Cremeschnitte</p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small">Lenna Majeed - 6 Hertford St Mayfair, London, UK</p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block">January 09, 2018</div>
                                            </a>
                                        </div>
                                    </div>
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
                                <h5 class="card-title">Prospects (11)</h5>
                                <div class="scroll dashboard-list-with-thumbs">
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#">
                                                <p class="list-item-heading">John Estrella</p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small">Latashia Nagy - 100-148 Warwick Trfy, Kansas City, USA</p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block">January 09, 2018</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#">
                                                <p class="list-item-heading">Fruitcake</p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small">Marty Otte - 166-156 Rue de Varennes, Gatineau, QC J8T 8G4, Canada</p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block">January 09, 2018</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#">
                                                <p class="list-item-heading">Chocolate Cake</p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small">Linn Ronning - Rasen 2-14, 98547 Kühndorf, Germany</p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block">January 09, 2018</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#">
                                                <p class="list-item-heading">Fat Rascal</p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small">Rasheeda Vaquera - 37 Rue des Grands Prés, 03100 Montluçon, France</p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block">January 09, 2018</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#">
                                                <p class="list-item-heading">Marble Cake</p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small">Latashia Nagy - 100-148 Warwick Trfy, Kansas City, USA</p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block">January 09, 2018</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#">
                                                <p class="list-item-heading">Fruitcake</p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small">Marty Otte - 166-156 Rue de Varennes, Gatineau, QC J8T 8G4, Canada</p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block">January 09, 2018</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#">
                                                <p class="list-item-heading">Streuselkuchen</p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small">Mimi Carreira - 36-71 Victoria St, Birmingham, UK</p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block">January 09, 2018</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row mb-3">
                                        <div class="">
                                            <a href="#">
                                                <p class="list-item-heading">Cremeschnitte</p>
                                                <div class="pr-4 d-none d-sm-block">
                                                    <p class="text-muted mb-1 text-small">Lenna Majeed - 6 Hertford St Mayfair, London, UK</p>
                                                </div>
                                                <div class="text-primary text-small font-weight-medium d-none d-sm-block">January 09, 2018</div>
                                            </a>
                                        </div>
                                    </div>
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
                    <p>From: <span class="from"></span></p>
                    <p>To: <span class="to"></span></p>
                    <div class="form-group">
                        <label for="event-title">Title</label>
                        <input type="text" class="form-control" id="event-title" aria-describedby="titleHelp" placeholder="Enter title">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" rows="3"></textarea>
                    </div>
                    <!-- <div class="row">
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
                    </div> -->
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
                    <button type="button" class="btn btn-primary" id="save-event-modal">Save</button>
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
                        $.ajax({
                            url:"functions/insert.php",
                            type:"POST",
                            data:{
                                title: title,
                                start: start,
                                end: end,
                                description: description,
                                audience: audience
                            },
                            success:function() {
                                calendar.fullCalendar('refetchEvents');
                                alert("Added Successfully");
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