<?php
require_once 'init.php';
use App\UserProfile;
use App\User;
use App\Session;
$profile = UserProfile::where('user_id', Session::get('user_id'))->with('unit')->first();
$user_role = User::find(Session::get('user_id'));
?>
<div class="menu">
    <div class="main-menu">
        <div class="scroll">
            <ul class="list-unstyled">
                <li class="<?= $active === 'dashboard' ? 'active' : '' ?>"><a href="index.php"><i class="iconsminds-home-1"></i> <span>Dashboard</span></a></li>
                <?php if (isset($profile->unit->name) && $user_role->role_id != 2): ?>
                <li class="<?= $active === 'my_unit'  ? 'active' : '' ?>"><a href="unit.php?unit_id=<?= $profile->unit->id ?>"><i class="iconsminds-network"></i>My Unit</a></li>
                <?php endif; ?>
                <?php if($user_role->role_id != 2 && $user_role->role_id != 3): ?>
                <li class="<?= $active === 'units'  ? 'active' : '' ?>"><a href="units.php"><i class="iconsminds-data-cloud"></i> Sales Team</a></li>
                <?php endif; ?>
                <li class="<?= $active === 'due-dates'  ? 'active' : '' ?>"><a href="due-dates.php"><i class="iconsminds-calendar-4"></i> Due Dates</a></li>
                <li class="<?= $active === 'payments'  ? 'active' : '' ?>"><a href="payments.php"><i class="iconsminds-mail-money"></i> Payments</a></li>
                <!-- <li class="<?= $active === 'productions'  ? 'active' : '' ?>"><a href="productions.php"><i class="iconsminds-money-bag"></i> Productions</a></li> -->
                <li class="<?= $active === 'policies'  ? 'active' : '' ?>"><a href="products.php"><i class="iconsminds-file-clipboard-file---text"></i> Products</a></li>
                <li class="<?= $active === 'clients'  ? 'active' : '' ?>"><a href="clients.php"><i class="iconsminds-mens"></i> Clients</a></li>
                <?php if($user_role->role_id != 2): ?>
                <li class="<?= $active === 'users'  ? 'active' : '' ?>"><a href="users.php"><i class="iconsminds-mens"></i> Users</a></li>
                <?php endif; ?>
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