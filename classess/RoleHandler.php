<?php
require_once 'SystemSettings.php';
require_once 'DatabaseHandler.php';

class RoleHandler {

  private $dbHandler;

    public function __construct() {
        $this->dbHandler = new DatabaseHandler();
    }

  public function getRoleName($roleValue) {
  switch ($roleValue) {
      case 0:
          return 'Superuser';
      case 1:
          return 'Standard User';
      case 2:
          return 'Auxiliary';
      case 3:
          return 'Councilor';
  }
}

public function getNewUsers() {
        $table = 'users';
        $columns = '*';
        $where = 'role IS NULL';

        $result = $this->dbHandler->select($table, $columns, $where);

        // Count the number of rows returned
        $rowCount = $result->num_rows;
        // Log or process the result as needed
        return $rowCount;
    }

    public function getUserRequests($userid,$status, $is_rejected){
      $table = 'requests';
      $columns = '*';
      $where = 'user_id='.$userid.' and level='.$status.' and is_rejected='.$is_rejected;
      $result = $this->dbHandler->select($table, $columns, $where);
      $rowCount = $result->num_rows;
      return $rowCount;
    }




    public function getMenuTags($roleValue) {
        // Return menu tags based on the role value
        if ($roleValue === '0') {
            $newUsers = $this->getNewUsers();
            // Admin menu tags
            return '
            <li>
              <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
            </li>
            <li>
              <a href="users.php"><i class="fas fa-user-friends"></i> Users <!--span class="badge rounded-pill bg-primary">'.($newUsers!=0?$newUsers:'').'</span--></a>
            </li>
            <li>
              <a href="requests.php"><i class="fas fa-book"></i> Requests <!--span class="badge rounded-pill bg-primary">1</span--></a>
            </li>            
            ';
        } else {
            // Standard User menu tags
            return '
            <li>
              <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
            </li>
            <li>
              <a href="requests.php"><i class="fas fa-book"></i> Requests <!--span class="badge rounded-pill bg-primary">1</span--></a>
            </li>
            ';
        }
    }

   public function getCards($roleValue,$users,$pending,$completed,$inProgress){
     if ($roleValue==0||$roleValue==1||$roleValue==3) {

       $newUsers = $this->getNewUsers();
         return  '<div class="row">
               <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                   <div class="card">
                       <div class="content">
                           <div class="row">
                               <div class="col-sm-4">
                                   <div class="icon-big text-center">
                                       <i class="olive fas fa-stamp"></i>
                                   </div>
                               </div>
                               <div class="col-sm-8 text-center">
                                   <div class="detail">
                                       <p class="detail-subtitle">For Approvals</p>
                                       <span class="number"  id="approvalsCount">0</span>
                                   </div>
                               </div>
                           </div>
                           <div class="footer">
                               <hr />
                               <div class="stats">
                                   <i class="fas fa-exclamation-circle"></i> For my Approvals Requests
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
                                       <i class="violet fas fa-stopwatch"></i>
                                   </div>
                               </div>
                               <div class="col-sm-8 text-center">
                                   <div class="detail">
                                       <p class="detail-subtitle">In-Progress</p>
                                       <span class="number" id="inprogressCount">0</span>
                                   </div>
                               </div>
                           </div>
                           <div class="footer">
                               <hr />
                               <div class="stats">
                                   <i class="fas fa-exclamation-circle"></i> In-Progress Requests
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
                                       <i class="orange fas fa-check"></i>
                                   </div>
                               </div>
                               <div class="col-sm-8 text-center">
                                   <div class="detail">
                                       <p class="detail-subtitle">Approved</p>
                                       <span class="number"  id="approvedCount">0</span>
                                   </div>
                               </div>
                           </div>
                           <div class="footer">
                               <hr />
                               <div class="stats">
                                   <i class="fas fa-exclamation-circle"></i> Approved Requests
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
                                       <i class="text-danger fas fa-times"></i>
                                   </div>
                               </div>
                               <div class="col-sm-8 text-center">
                                   <div class="detail">
                                       <p class="detail-subtitle">Rejected</p>
                                       <span class="number"  id="rejectedCount">0</span>
                                   </div>
                               </div>
                           </div>
                           <div class="footer">
                               <hr />
                               <div class="stats">
                                   <i class="fas fa-exclamation-circle"></i> Rejected Requests
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
