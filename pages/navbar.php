<!-- navbar navigation component -->

<nav class="navbar navbar-expand-lg navbar-white bg-white">
    <button type="button" id="sidebarCollapse" class="btn btn-light">
        <i class="fas fa-bars"></i><span></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="nav navbar-nav ms-auto">
          <?php
            if($session->getSessionVariable('Role')=='0'){
                echo '
                <li class="nav-item dropdown">
                    <div class="nav-dropdown">
                        <a href="#" id="nav1" class="nav-item nav-link dropdown-toggle text-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-link"></i> <span>Quick Links</span> <i style="font-size: .8em;" class="fas fa-caret-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end nav-link-menu" aria-labelledby="nav1">
                            <ul class="nav-list">
                                <li><a href="logs.php" class="dropdown-item"><i class="fas fa-list"></i> Logs</a></li>
                                <!--div class="dropdown-divider"></div>
                                <li><a href="../controllers/backup.php" class="dropdown-item"><i class="fas fa-database"></i> Back ups</a></li-->
                            </ul>
                        </div>
                    </div>
                </li>';
            }
            ?>


            <li class="nav-item dropdown">
                <div class="nav-dropdown">
                    <a href="#" id="nav2" class="nav-item nav-link dropdown-toggle text-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i> <span><?php echo $session->getSessionVariable("Name"); ?></span> <i style="font-size: .8em;" class="fas fa-caret-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nav-link-menu">
                        <ul class="nav-list">
                            <li><a href="profile.php" class="dropdown-item"><i class="fas fa-address-card"></i> Profile</a></li>
                            <div class="dropdown-divider"></div>
                            <li><a href="logout.php" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>
<!-- end of navbar navigation -->
