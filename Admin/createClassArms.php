<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';
include 'ClassArmController.php';

$classArmManager = new ClassArmManager($conn);
$statusMsg = "";

// Save
if (isset($_POST['save'])) {
    $statusMsg = $classArmManager->saveClassArm($_POST['classId'], $_POST['classArmName']);
}

// Edit
$editRow = null;
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $editRow = $classArmManager->getClassArmById($_GET['Id']);
    if (isset($_POST['update'])) {
        $statusMsg = $classArmManager->updateClassArm($_GET['Id'], $_POST['classId'], $_POST['classArmName']) ? 
                     "<script>window.location = 'createClassArms.php';</script>" : 
                     "<div class='alert alert-danger'>An error Occurred!</div>";
    }
}

// Delete
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    if ($classArmManager->deleteClassArm($_GET['Id'])) {
        echo "<script>window.location = 'createClassArms.php';</script>";
    } else {
        $statusMsg = "<div class='alert alert-danger'>An error Occurred!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <?php include 'includes/title.php';?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
      <?php include "Includes/sidebar.php";?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
       <?php include "Includes/topbar.php";?>
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Class</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Class</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Class</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Course<span class="text-danger ml-2">*</span></label>
                         <?php
                        $qry= "SELECT * FROM tblclass ORDER BY className ASC";
                        $result = $conn->query($qry);
                        if ($result->num_rows > 0) {
                          echo '<select required name="classId" class="form-control mb-3">';
                          echo '<option value="">--Select Course--</option>';
                          while ($rows = $result->fetch_assoc()) {
                            echo '<option value="'.$rows['Id'].'" '.(isset($editRow) && $editRow['classId'] == $rows['Id'] ? 'selected' : '').'>'.$rows['className'].'</option>';
                          }
                          echo '</select>';
                        }
                        ?>  
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Class Name<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="classArmName" value="<?php echo isset($editRow) ? $editRow['classArmName'] : ''; ?>" id="exampleInputFirstName" placeholder="Class Name">
                      </div>
                    </div>
                    <?php if (isset($_GET['Id'])) { ?>
                      <button type="submit" name="update" class="btn btn-warning">Update</button>
                    <?php } else { ?>
                      <button type="submit" name="save" class="btn btn-primary">Save</button>
                    <?php } ?>
                  </form>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Class</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Course Name</th>
                            <th>Class Name</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $query = "SELECT tblclassarms.Id, tblclassarms.isAssigned, tblclass.className, tblclassarms.classArmName 
                                    FROM tblclassarms
                                    INNER JOIN tblclass ON tblclass.Id = tblclassarms.classId";
                          $rs = $conn->query($query);
                          if ($rs && $rs->num_rows > 0) {
                            $sn = 0;
                            while ($rows = $rs->fetch_assoc()) {
                              $status = $rows['isAssigned'] == '1' ? "Assigned" : "UnAssigned";
                              $sn++;
                              echo "
                                <tr>
                                  <td>".$sn."</td>
                                  <td>".$rows['className']."</td>
                                  <td>".$rows['classArmName']."</td>
                                  <td>".$status."</td>
                                  <td><a href='?action=edit&Id=".$rows['Id']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                  <td><a href='?action=delete&Id=".$rows['Id']."'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                                </tr>";
                            }
                          } else {
                            echo "<tr><td colspan='6' class='text-center'>No Record Found!</td></tr>";
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
      </div>
      <?php include "Includes/footer.php";?>
    </div>
  </div>

  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <script>
    $(document).ready(function () {
      $('#dataTableHover').DataTable();
    });
  </script>
</body>

</html>
