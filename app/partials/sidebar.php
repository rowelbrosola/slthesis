<div class="menu">
    <div class="main-menu">
        <div class="scroll">
            <ul class="list-unstyled">
                <li class="<?= $active === 'dashboard' ? 'active' : '' ?>"><a href="index.php"><i class="iconsminds-home-1"></i> <span>Dashboard</span></a></li>
                <li class="dd-menu <?= $active === 'reports' ? 'active' : '' ?>"><a href="#reports"><i class="iconsminds-digital-drawing"></i> Reports</a></li>
                <li class="<?= $active === 'payments'  ? 'active' : '' ?>"><a href="payments.php"><i class="iconsminds-cash-register-2"></i> Payments</a></li>
                <li class="<?= $active === 'appointments'  ? 'active' : '' ?>"><a href="appointments.php"><i class="iconsminds-calendar-1"></i> Appointments</a></li>
                <li class="<?= $active === 'users'  ? 'active' : '' ?>"><a href="users.php"><i class="iconsminds-user"></i> Users</a></li>
            </ul>
        </div>
    </div>
    <div class="sub-menu">
        <div class="scroll">
            <ul class="list-unstyled" data-link="reports" id="reports">
                <li><a href="#"><i class="simple-icon-doc"></i> <span class="d-inline-block">Generate Report</span></a></li>
            </ul>
        </div>
    </div>
</div>