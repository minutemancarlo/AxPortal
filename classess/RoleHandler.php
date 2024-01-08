<?php

class RoleHandler {
    public function getRoleName($roleValue) {
        // Return role name based on the role value
        return ($roleValue === 0) ? 'Admin' : 'Standard User';
    }

    public function getMenuTags($roleValue) {
        // Return menu tags based on the role value
        if ($roleValue === '0') {
            // Admin menu tags
            return '
            <li>
              <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
            </li>
            <li>
              <a href="member.php"><i class="fas fa-user-friends"></i> Members</a>
            </li>
            <li>
              <a href="books.php"><i class="fas fa-book"></i> Catalogs</a>
            </li>
            <li>
              <a href="circulations.php"><i class="fas fa-book-reader"></i> Circulations</a>
            </li>
            <li>
              <a href="notif.php"><i class="fas fa-mail-bulk"></i> Notifications</a>
            </li>
            <li>
              <a href="settings.php"><i class="fas fa-cog"></i> System</a>
            </li>

            ';
        } else {
            // Standard User menu tags
            return '
            <li>
            <a href="books.php"><i class="fas fa-book"></i> Catalogs</a>
            </li>

            <li>
              <a href="history.php"><i class="fas fa-hourglass"></i> History</a>
            </li>

            <li>
            <a href="checkout.php">
<i class="fas fa-book-reader"></i> Borrow Books
<span class="badge bg-primary " id="cardCount" style="font-size: 10px;">0</span>
</a>
            </li>';
        }
    }

    public function getCards($roleValue,$borrowed,$overdue,$users,$unverified){
      // Return menu tags based on the role value
      if ($roleValue === '0') {
        return '<div class="row">
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="icon-big text-center">
                                    <i class="teal fas fa-atlas"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="detail">
                                    <p class="detail-subtitle">Borrowed</p>
                                    <span class="number">'.$borrowed.'</span>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <hr />
                            <div class="stats">
                                Total Books Borrowed
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="icon-big text-center">
                                    <i class="fas fa-book-dead"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="detail">
                                    <p class="detail-subtitle">Overdue</p>
                                    <span class="number">'.$overdue.'</span>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <hr />
                            <div class="stats">
                              Total Books with penalty
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="icon-big text-center">
                                    <i class="violet fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="detail">
                                    <p class="detail-subtitle">Users</p>
                                    <span class="number">'.$users.'</span>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <hr />
                            <div class="stats">
                                Total Number of Members
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="icon-big text-center">
                                    <i class="orange fas fa-user-clock"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="detail">
                                    <p class="detail-subtitle">Not Verified</p>
                                    <span class="number">'.$unverified.'</span>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <hr />
                            <div class="stats">
                                Total unverified members
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
      }else{
        return '<div class="row">
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="icon-big text-center">
                                    <i class="teal fas fa-shopping-cart"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="detail">
                                    <p class="detail-subtitle">New Orders</p>
                                    <span class="number">6,267</span>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <hr />
                            <div class="stats">
                                <i class="fas fa-calendar"></i> For this Week
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="icon-big text-center">
                                    <i class="olive fas fa-money-bill-alt"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="detail">
                                    <p class="detail-subtitle">Revenue</p>
                                    <span class="number">$180,900</span>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <hr />
                            <div class="stats">
                                <i class="fas fa-calendar"></i> For this Month
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="icon-big text-center">
                                    <i class="violet fas fa-eye"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="detail">
                                    <p class="detail-subtitle">Page views</p>
                                    <span class="number">28,210</span>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <hr />
                            <div class="stats">
                                <i class="fas fa-stopwatch"></i> For this Month
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="icon-big text-center">
                                    <i class="orange fas fa-envelope"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="detail">
                                    <p class="detail-subtitle">Support Request</p>
                                    <span class="number">75</span>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <hr />
                            <div class="stats">
                                <i class="fas fa-envelope-open-text"></i> For this week
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
      }
    }
}


?>
