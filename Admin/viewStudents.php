
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

  if (isset($_POST['search'])) {
    $classId = $_POST['classId'];
    $classArmId = $_POST['classArmId'];

    // ค้นหาเฉพาะนักเรียนที่อยู่ใน class และ classArm ที่เลือก
    $query = "SELECT tblclass.className,tblclassarms.classArmName 
              FROM tblclassteacher
              INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
              INNER JOIN tblclassarms ON tblclassarms.Id = tblclassteacher.classArmId
              Where tblclassteacher.Id = '$_SESSION[userId]'";
        $rs = $conn->query($query);
        $num = $rs->num_rows;
        $rrw = $rs->fetch_assoc();
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



   <script>
    function classArmDropdown(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","ajaxClassArms2.php?cid="+str,true);
        xmlhttp.send();
    }
}
</script>
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
      <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
       <?php include "Includes/topbar.php";?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">View Student</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Student</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">View Student</h6>
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
                        $num = $result->num_rows;		
                        if ($num > 0){
                          echo ' <select required name="classId" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                          echo'<option value="">--Select Course--</option>';
                          while ($rows = $result->fetch_assoc()){
                          echo'<option value="'.$rows['Id'].'" >'.$rows['className'].'</option>';
                              }
                                  echo '</select>';
                              }
                            ?>  
                        </div>
                        <div class="col-xl-6">
                        <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>
                            <?php
                                echo"<div id='txtHint'></div>";
                            ?>
                        </div>
                    </div>
                      <?php
                    if (isset($Id))
                    {
                    ?>
                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                    } else {           
                    ?>
                    <button type="submit" name="search" class="btn btn-primary">Search</button>
                    <?php
                    }         
                    ?>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
                 <div class="row">
              <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">All Student</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Other Name</th>
                        <th>Admission No</th>
                        <th>Class</th>
                        <th>Class Arm</th>
                        <th>Date Created</th>
                         <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                
                    <tbody>

                    <?php
                        if (isset($_POST['search'])) {
                          $classId = $_POST['classId'];
                          $classArmId = $_POST['classArmId'];

                          if (!empty($classId) && !empty($classArmId)) {
                            // ใช้ prepared statement เพื่อป้องกัน SQL Injection
                            $query = "SELECT tblstudents.Id, tblclass.className, tblclassarms.classArmName, tblstudents.firstName,
                                      tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber, tblstudents.dateCreated
                                      FROM tblstudents
                                      INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                                      INNER JOIN tblclassarms ON tblclassarms.Id = tblstudents.classArmId
                                      WHERE tblstudents.classId = ? AND tblstudents.classArmId = ?";

                            // เตรียม query
                            if ($stmt = $conn->prepare($query)) {
                              // bind parameter เพื่อแทนค่าลงใน query
                              $stmt->bind_param("ii", $classId, $classArmId);

                              // execute query
                              $stmt->execute();

                              // ดึงผลลัพธ์
                              $rs = $stmt->get_result();
                              $num = $rs->num_rows;
                              $sn = 0;

                              if ($num > 0) {
                                // แสดงผลลัพธ์
                                while ($rows = $rs->fetch_assoc()) {
                                  $sn++;
                                  echo "
                                    <tr>
                                      <td>{$sn}</td>
                                      <td>{$rows['firstName']}</td>
                                      <td>{$rows['lastName']}</td>
                                      <td>{$rows['otherName']}</td>
                                      <td>{$rows['admissionNumber']}</td>
                                      <td>{$rows['className']}</td>
                                      <td>{$rows['classArmName']}</td>
                                      <td>{$rows['dateCreated']}</td>
                                      <td><a href='?action=edit&Id={$rows['Id']}'><i class='fas fa-fw fa-edit'></i></a></td>
                                      <td><a href='?action=delete&Id={$rows['Id']}'><i class='fas fa-fw fa-trash'></i></a></td>
                                    </tr>";
                                }
                              } else {
                                echo "<div class='alert alert-danger'>No Record Found!</div>";
                              }
                            } else {
                              echo "<div class='alert alert-danger'>Failed to prepare the query.</div>";
                            }
                          } else {
                            echo "<div class='alert alert-danger'>Please select both Course and Class.</div>";
                          }
                        }
                        ?>

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            </div>
          </div>
          <!--Row-->

          <!-- Documentation Link -->
          <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
       <?php include "Includes/footer.php";?>
      <!-- Footer -->
    </div>
  </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
   <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>

</html>
