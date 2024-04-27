<?php
require_once("../controllers/headers.php");
$db = new DatabaseHandler();
$settings = new SystemSettings();
$baseUrl = $settings->getBaseURL();
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $websiteTitle ?> | Search</title>
  <?php echo $styles; ?>

</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-primary navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">




    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="../dist/img/favicon_io/favicon-32x32.png" alt="App Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Sukisok</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <li class="nav-item">
                 <a href="index.php" class="nav-link">
                   <i class="nav-icon fas fa-home"></i>
                   <p>
                     Home
                   </p>
                 </a>
               </li>
          <li class="nav-item">
            <a href="../auth/login.php" class="nav-link">
              <i class="nav-icon fas fa-sign-in-alt"></i>
              <p>
                Login
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../auth/register.php" class="nav-link">
              <i class="nav-icon fas fa-user-plus"></i>
              <p>
                Register
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
<br>
      <div class="col-md-8 offset-md-2">
        <form id="searchForm" action="search.php">
          <div class="input-group">
            <input id="searchInput" type="search" class="form-control form-control-lg" name="query" placeholder="Search here" value="<?php echo isset($_GET['query'])?$_GET['query']:''; ?>">
            <div class="input-group-append">
              <button type="submit" class="btn btn-lg btn-default">
                <i class="fa fa-search"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
      <hr>

      <div class="col-md-8 offset-md-2">
        <!-- /.row -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"> <?php echo isset($_GET['query'])?($_GET['query']=='')?'':'Search Results for "<i class="font-weight-bold">'.$_GET['query'].'</i>" </h3>':''; ?>
              </div>
              <!-- ./card-header -->
              <div class="card-body">
                <table class="table table-bordered table-hover table-header-fixed">
                  <thead>
                    <tr>
                      <th class="text-center">Title</th>
                      <th class="text-center">Author</th>
                      <th class="text-center">Published Date</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $where='';
                    if (isset($_GET['query'])) {
                      if ($_GET['query']) {
                        $where="tags LIKE '%".$_GET['query']."%' or title LIKE '%".$_GET['query']."%' and";
                      }
                    }
                    $result=$db->select("manuscript a inner join users b on a.user_id=b.id","a.*,b.name,(SELECT filename FROM files WHERE manuscript_id = a.m_id ORDER BY created_on DESC LIMIT 1) as file,(SELECT filepath FROM files WHERE manuscript_id = a.m_id ORDER BY created_on DESC LIMIT 1) as filepath,a.updated_on as publishedDate",$where." a.status='Published' order by a.updated_on desc");
                    if ($result->num_rows>0) {
                      while ($row = $result->fetch_assoc()) {
                        $date = new DateTime($row['publishedDate']);
                        $formattedDate = $date->modify('+1 day')->format('F j, Y');
                          echo '<tr data-widget="expandable-table" aria-expanded="false">
                              <td>'.$row['title'].'</td>
                              <td>'.$row['name'].'</td>
                              <td>'.$formattedDate.'</td>
                              <td>  <button type="button" name="button" class="btn btn-success view-file" data-id='.$row['m_id'].' data-file="'.$baseUrl."Files/Manuscripts/".$row['file'].'" > <i class="fas fa-eye"></i></button>
                                <a href="../pages/download.php?id='.$row['m_id'].'&file='.$row['filepath'].'&filename='.$row['file'].'" class="btn btn-primary"> <i class="fas fa-download"></i></a></td>
                            </tr>
                            <tr class="expandable-body">
                              <td colspan="4">
                                <p>
                                  <div class="row">
                                    <div class="col-md-12">
                                    <p> <strong>Title:</strong> '.$row['title'].'</p>
                                    <p> <strong>Abstract:</strong> '.$row['abstract'].'</p>
                                    <button type="button" name="button" class="btn btn-success view-file" data-id="'.$row['m_id'].'" data-file="'.$baseUrl."Files/Manuscripts/".$row['file'].'" > <i class="fas fa-eye"></i> View Manuscript</button>
                                    <a href="../pages/download.php?id='.$row['m_id'].'&file='.$row['filepath'].'&filename='.$row['file'].'" class="btn btn-primary"> <i class="fas fa-download"></i> Download Manuscript</a>
                                    </div>
                                  </div>
                                </p>
                              </td>
                            </tr>';
                      }
                    }else{
                      echo '<td colspan="3">
                        <div class="text-center">
                          <i>No Results Found.</i>
                        </div>
                      </td>';
                    }
                     ?>

                     <!-- <tr data-widget="expandable-table" aria-expanded="false">
                       <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                       <td>John Doe</td>
                       <td>February 14, 2024</td>
                     </tr>
                     <tr class="expandable-body">
                       <td colspan="3">
                         <p>
                           <div class="row">
                             <div class="col-md-6">
                               <p> <strong>Title:</strong> </p>
                               <p> <strong>Abstract:</strong> </p>

                             </div>
                           </div>
                         </p>
                       </td>
                     </tr> -->
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
        <!-- <div class="table-responsive">
          <table id="searchResults" class="table table-bordered table-striped">

          </table>
        </div> -->
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="#">Sukisok</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php echo $scripts; ?>
<script type="text/javascript">
$(document).ready(function() {
  $(document).on('click', '.view-file', function() {
    event.preventDefault();
    var pdfUrl = $(this).data('file');
    var id= $(this).data('id');
    var form = $('<form action="../pages/viewer.php" target="_blank" method="POST"></form>');
    form.append('<input type="hidden" name="id" value="' + id + '">');
    form.append('<input type="hidden" name="url" value="' + pdfUrl + '">');
    $('body').append(form);
    form.submit();
  });
});


</script>
</body>
</html>
