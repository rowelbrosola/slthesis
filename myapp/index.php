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
                    <div class="col-xl-8 col-lg-12 mb-4">
                        <div id="calendar" style="border: 2px solid #eee;"></div>
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
        <script src="js/vendor/fullcalendar.min.js"></script>
        <script>
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
                    var title = prompt("Enter Event Title");
                    if (title) {
                        var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                        var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                        $.ajax({
                            url:"functions/insert.php",
                            type:"POST",
                            data:{title:title, start:start, end:end},
                            success:function() {
                                calendar.fullCalendar('refetchEvents');
                                alert("Added Successfully");
                            },
                            error: function(err) {
                                console.log(err)
                            }
                        })
                    }
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