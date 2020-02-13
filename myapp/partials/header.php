<nav class="navbar fixed-top">
    <div class="d-flex align-items-center navbar-left">
        <a href="#" class="menu-button d-none d-md-block">
            <svg class="main" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 17">
                <rect x="0.48" y="0.5" width="7" height="1"/>
                <rect x="0.48" y="7.5" width="7" height="1"/>
                <rect x="0.48" y="15.5" width="7" height="1"/>
            </svg>
            <svg class="sub" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17">
                <rect x="1.56" y="0.5" width="16" height="1"/>
                <rect x="1.56" y="7.5" width="16" height="1"/>
                <rect x="1.56" y="15.5" width="16" height="1"/>
            </svg>
        </a>
        <a href="#" class="menu-button-mobile d-xs-block d-sm-block d-md-none">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 17">
                <rect x="0.5" y="0.5" width="25" height="1"/>
                <rect x="0.5" y="7.5" width="25" height="1"/>
                <rect x="0.5" y="15.5" width="25" height="1"/>
            </svg>
        </a>
    </div>
    <div class="navbar-right">
        <div class="header-icons d-inline-block align-middle">
            <div class="position-relative d-none d-sm-inline-block">
                <button class="header-icon btn btn-empty" type="button" id="iconMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="simple-icon-grid"></i></button>
                <div class="dropdown-menu dropdown-menu-right mt-3 position-absolute" id="iconMenuDropdown">
                    <a href="#" class="icon-menu-item"><i class="iconsminds-equalizer d-block"></i> <span>Clients</span> </a>
                    <a href="#" class="icon-menu-item"><i class="iconsminds-male-female d-block"></i> <span>Users</span> </a>
                    <a href="#" class="icon-menu-item"><i class="iconsminds-file d-block"></i> <span>Payment</span> </a>
                    <a href="#" class="icon-menu-item"><i class="iconsminds-suitcase d-block"></i> <span>Appointment</span></a>
                </div>
            </div>
            <button class="header-icon btn btn-empty d-none d-sm-inline-block" type="button" id="fullScreenButton"><i class="simple-icon-size-fullscreen"></i> <i class="simple-icon-size-actual"></i></button>
        </div>
        <div class="user d-inline-block">
            <button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="name">Sarah Celeste</span> <span><img alt="Profile Picture" src="img/profile-pic-l.jpg"></span></button>
            <div class="dropdown-menu dropdown-menu-right mt-3">
                <a class="dropdown-item" href="#">My Account</a>
                <a class="dropdown-item" href="#">Payment</a>
                <a class="dropdown-item" href="#">History</a>
                <a class="dropdown-item" href="#">Support</a>
                <a class="dropdown-item" href="index.php?logout=true">Sign out</a>
            </div>
        </div>
    </div>
</nav>