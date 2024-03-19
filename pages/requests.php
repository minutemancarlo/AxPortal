<?php
require_once '../classess/SessionHandler.php';
$session = new CustomSessionHandler();
if(!$session->isSessionVariableSet("Role")){
  header("Location: ../");
}
require_once '../classess/DatabaseHandler.php';
require_once '../classess/SystemSettings.php';
require_once '../classess/RoleHandler.php';
$settings = new SystemSettings();
$db = new DatabaseHandler();
$roleHandler = new RoleHandler();
$websiteTitle = $settings->getWebsiteTitle();
$styles = $settings->getStyles();
$scripts = $settings->getScripts();
$sweetAlert = $settings->getSweetAlertInit();
$ajax = $settings->getAjaxInit();
$settings->setDefaultTimezone();
$baseURL = $settings->getBaseURL();
$brlogo = $settings->getBLogo();
$logo = $settings->getLogo();
$roleValue = $session->getSessionVariable("Role");
$uid = $session->getSessionVariable("Id");
$roleName = $roleHandler->getRoleName($roleValue);
$menuTags = $roleHandler->getMenuTags($roleValue);
$newUsers = $roleHandler->getNewUsers();
$validate = $settings->validateForms();

 ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="rl" content="<?php echo $roleValue; ?>">
    <title>Requests | <?php echo $websiteTitle; ?></title>
    <link href="../assets/vendor/fontawesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="../assets/vendor/fontawesome/css/solid.min.css" rel="stylesheet">
    <link href="../assets/vendor/fontawesome/css/brands.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/master.css" rel="stylesheet">
    <style media="screen">
    .nowrap {
    white-space: nowrap !important;
    }
      label{
        font-size: 18px;
      }
      table{
        font-size: 18px;
      }
      .inactive-row td {
          color: red;
        }
        .timeline-with-icons {
    border-left: 1px solid hsl(0, 0%, 90%);
    position: relative;
    list-style: none;
  }

  .timeline-with-icons .timeline-item {
    position: relative;
  }

  .timeline-with-icons .timeline-item:after {
    position: absolute;
    display: block;
    top: 0;
  }

  .timeline-with-icons .timeline-icon {
    position: absolute;
    left: -48px;
    background-color: hsl(217, 88.2%, 90%);
    color: hsl(217, 88.8%, 35.1%);
    border-radius: 50%;
    height: 31px;
    width: 31px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

    </style>
</head>

<body>
<input type="text" id="roleid" value="<?php echo $roleValue; ?>" hidden>
    <div class="wrapper">
      <?php include 'sidebar.php'; ?>
      <div id="body" class="active">
          <?php include 'navbar.php'; ?>
            <div class="content">
                <div class="container-fluid">
                    <div class="page-title">
                        <h3>Requests</h3>
                        <div class="card"></div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card" <?php if($roleValue!=2){ echo "hidden";} ?>>
                                    <div class="card-header">Request Form</div>
                                    <div class="card-body">
                                        <h5 class="card-title"></h5>
                                        <form method="post" action="" id="requestForm" novalidate>
                                          <input type="text" name="user_id" value="<?php echo $uid; ?>" hidden>
                                            <div class="mb-3">
                                                <label for="project" class="form-label fw-bold">Purpose of the service requested</label>
                                                <select class="form-select" name="project_id" aria-label="project" id="projects" required>
                                                  <option selected>Select</option>
                                                  <?php
                                                      $result = $db->select('projects');
                                                      while ($row = $result->fetch_assoc()) {
                                                          echo '<option value="'.$row['id'].'">'.$row['project_name'].'</option>';
                                                      }
                                                   ?>
                                                </select>
                                                <div class="invalid-feedback">
                                                  Please select purpose of the service requested
                                                </div>
                                            </div>
                                            <div class="mb-3" id="otherproject" hidden>
                                              <label for="Description" class="form-label fw-bold">Other Service Request Description</label>
                                              <textarea class="form-control" name="projectdescription" id="otherprojectdescription" rows="3"></textarea>

                                            </div>
                                            <div class="mb-3">
                                              <label for="service" class="form-label fw-bold">Description of the job requested</label>
                                              <select class="form-select" name="job_id" aria-label="service" id="service" required>
                                                <option selected>Select</option>
                                                <?php
                                                    $result = $db->select('jobs');
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo '<option value="'.$row['id'].'">'.$row['category_name'].'</option>';
                                                    }
                                                 ?>
                                              </select>
                                              <div class="invalid-feedback">
                                                Please select job requested
                                              </div>
                                            </div>
                                            <div class="mb-3" id="otherjob" hidden>
                                              <label for="Description" class="form-label fw-bold">Other Job Request Description</label>
                                              <textarea class="form-control" name="jobdescription" id="otherjobdescription" rows="3"></textarea>
                                            </div>
                                            <div class="mb-3">
                                              <label for="Description" class="form-label fw-bold">Description</label>
                                              <textarea class="form-control" name="description" id="Description" rows="3" required></textarea>
                                              <div class="invalid-feedback">
                                                Please provide Description
                                              </div>
                                            </div>
                                            <div class="mb-3">
                                                <input type="submit" class="btn btn-primary">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card"></div>
                                <div class="card">
                                  <div class="box box-primary">
                                      <div class="box-body">
                                        <div class="table-responsive">
                                          <table width="100%" class="table table-hover" id="requestsTable" style="font-size: 15px;">
                                          </table>
                                        </div>
                                      </div>
                                  </div>
                                </div>
                                <br>
                                <h5 <?php if($roleValue!=2){ echo "hidden";} ?>>Approved Requests</h5>
                                <div class="card" <?php if($roleValue!=2){ echo "hidden";} ?>>
                                  <div class="box box-primary">
                                      <div class="box-body">
                                        <div class="table-responsive">
                                          <table width="100%" class="table table-hover" id="ApproverequestsTable" style="font-size: 15px;">
                                          </table>
                                        </div>
                                      </div>
                                  </div>
                                </div>

                            </div>
                            <div class="col-md-4">
                              <div class="card" <?php if($roleValue==2){ echo "hidden";} ?>>
                                <div class="card-body" style="font-size: 18px;">
                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="card">
                                          <div class="content">
                                              <div class="row">
                                                  <div class="col-sm-6">
                                                      <div class="icon-big text-center">
                                                          <i class="text-danger fas fa-stamp"></i>
                                                      </div>
                                                  </div>
                                                  <div class="col-sm-6">
                                                      <div class="detail text-center">
                                                          <p class="detail-subtitle">For My Approval</p>
                                                        <span class="number" id="approvalCount">0</span>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="footer">
                                                  <hr />
                                              </div>
                                          </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="card"  <?php if($roleValue!=2){ echo "hidden";} ?>>
                                <div class="card-header">
                                  My Requests
                                </div>
                                <div class="card-body" style="font-size: 18px;">
                                  <div class="row">
                                      <div class="col-md-12">
                                          <div class="card">
                                              <div class="content">
                                                  <div class="row">
                                                      <div class="col-sm-6">
                                                          <div class="icon-big text-center">
                                                              <i class="text-danger fas fa-stamp"></i>
                                                          </div>
                                                      </div>
                                                      <div class="col-sm-6">
                                                          <div class="detail text-center">
                                                              <p class="detail-subtitle">Pending</p>
                                                          <span class="number" id="pendingCount">0</span>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="footer">
                                                      <hr />
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="content">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="icon-big text-center">
                                                                <i class="text-warning fas fa-angle-double-down"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="detail text-center">
                                                                <p class="detail-subtitle">Auxiliary</p>
                                                                <span class="number" id="auxiliaryCount">0</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="footer">
                                                        <hr />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-md-12">
                                              <div class="card">
                                                  <div class="content">
                                                      <div class="row">
                                                          <div class="col-sm-6">
                                                              <div class="icon-big text-center">
                                                                  <i class="text-primary fas fa-angle-double-down"></i>
                                                              </div>
                                                          </div>
                                                          <div class="col-sm-6">
                                                              <div class="detail text-center">
                                                                  <p class="detail-subtitle">Councilor</p>
                                                                  <span class="number" id="councilorCount">0</span>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="footer">
                                                          <hr />
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                        </div>
                                        <!-- <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="content">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="icon-big text-center">
                                                                    <i class="teal fas fa-angle-double-down"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="detail text-center">
                                                                    <p class="detail-subtitle">Final Approval</p>
                                                                    <span class="number" id="finalApprovalCount">0</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="footer">
                                                            <hr />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                          </div> -->
                                          <div class="row">
                                              <div class="col-md-12">
                                                  <div class="card">
                                                      <div class="content">
                                                          <div class="row">
                                                              <div class="col-sm-6">
                                                                  <div class="icon-big text-center">
                                                                      <i class="text-success fas fa-check"></i>
                                                                  </div>
                                                              </div>
                                                              <div class="col-sm-6">
                                                                  <div class="detail text-center">
                                                                      <p class="detail-subtitle">Approved</p>
                                                                      <span class="number" id="approvedCount">0</span>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                          <div class="footer">
                                                              <hr />
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="content">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="icon-big text-center">
                                                                        <i class="text-danger fas fa-times"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="detail text-center">
                                                                        <p class="detail-subtitle">Rejected</p>
                                                                        <span class="number" id="rejectedCount">0</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="footer">
                                                                <hr />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                              </div>
                                </div>
                              </div>
                            </div>
                    </div>

                </div>
            </div>

        </div>
    </div>


    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Request ID: <strong id="requestModal_id"></strong> </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="" id="requestFormUpdate" novalidate>
          <input type="text" name="user_id" value="<?php echo $uid; ?>" hidden>
          <input type="text" name="level" hidden>
          <p>Name of Requesting Unit: <strong id="r_name"></strong> </p>
          <p>Date Requested: <strong id="r_date"></strong> </p>
          <hr>
            <div class="mb-3">
                <label for="project" class="form-label fw-bold">Purpose of the service requested</label>
                <select class="form-select" name="project_id" aria-label="project" id="projects" disabled>
                  <option selected>Select</option>
                  <?php
                      $result = $db->select('projects');
                      while ($row = $result->fetch_assoc()) {
                          echo '<option value="'.$row['id'].'">'.$row['project_name'].'</option>';
                      }
                   ?>
                </select>

            </div>
            <div class="mb-3" id="otherproject" hidden>
              <label for="Description" class="form-label fw-bold">Other Service Request Description</label>
              <textarea class="form-control" name="projectdescription" id="otherprojectdescription" rows="3" disabled></textarea>
            </div>
            <div class="mb-3">
              <label for="service" class="form-label fw-bold">Description of the job requested</label>
              <select class="form-select" name="job_id" aria-label="service" id="service" disabled>
                <option selected>Select</option>
                <?php
                    $result = $db->select('jobs');
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="'.$row['id'].'">'.$row['category_name'].'</option>';
                    }
                 ?>
              </select>
            </div>
            <div class="mb-3" id="otherjob" hidden>
              <label for="Description" class="form-label fw-bold">Other Job Request Description</label>
              <textarea class="form-control" name="jobdescription" id="otherjobdescription" rows="3" disabled></textarea>
            </div>
            <div class="mb-3">
              <label for="Description" class="form-label fw-bold">Description</label>
              <textarea class="form-control" name="description" id="Description" rows="3" disabled></textarea>
            </div>
            <div class="mb-3" <?php if($roleValue==2){ echo "hidden";} ?>>
              <label for="approval_status" id="approval_label" class="form-label fw-bold">Approval Status</label>

              <select class="form-select" name="approval_status" aria-label="approval_status" id="approval_status" required>
                <option value='1'>Approved</option>
                <option value='0'>Reject</option>
              </select>
              <div class="invalid-feedback">
                Approval Status is required
              </div>
          </div>

          <div class="mb-3" id="reject_description_container" hidden>
            <label for="Description" class="form-label fw-bold">Rejection Reason</label>
            <textarea class="form-control" name="reject_description" id="reject_description" rows="3"></textarea>
          </div>

          <div class="mb-3 p-5 card">
            <label for="" id="" class="form-label fw-bold">Approval History</label>
            <section class="py-5" id="request_timeline">
              <ul class="timeline-with-icons">

              </ul>

              <div id="divFeedback">

                <div class="mb-3 mt-3">
                  <label for="comment" id="feedbackLabel">Feedback:</label>
                  <textarea class="form-control" rows="5" id="feedback" name="feedback"></textarea>
                </div>
                <button type="button" id="submitBtn" class="btn btn-primary" >Submit</button>


            </div>

              <?php
              // if($roleValue==2){
              //     echo '<form  method="post" action="" id="requestFeedback" novalidate>
              //       <div class="mb-3 mt-3">
              //         <label for="comment">Feedback:</label>
              //         <textarea class="form-control" rows="5" id="feedback" name="feedback"></textarea>
              //       </div>
              //       <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
              //     </form>';
              // }else{
              //   echo '  <div class="mb-3 mt-3">
              //       <label for="comment">Feedback:</label>
              //       <textarea class="form-control" rows="5" id="feedback" name="feedback" disabled></textarea>
              //     </div>';
              // }
               ?>
            </section>
          </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <?php if($roleValue!=2){
            echo '<button type="submit" class="btn btn-primary" id="submit">Save changes</button>';
        }
        ?>

      </form>
      </div>
    </div>
  </div>
</div>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/datatables/datatables.min.js"></script>
    <script src="../assets/js/initiate-datatables.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
    <script type="text/javascript">
      <?php echo $sweetAlert; ?>
      <?php echo $ajax; ?>
      <?php echo $validate; ?>
      <?php echo "var r=".$roleValue; ?>
    </script>
    <script src="../assets/js/pages/<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>.js"></script>
</body>

</html>
