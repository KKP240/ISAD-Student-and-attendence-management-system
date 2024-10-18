<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

class ClassArmManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function saveClassArm($classId, $classArmName) {
        $query = mysqli_query($this->conn, "SELECT * FROM tblclassarms WHERE classArmName = '$classArmName' AND classId = '$classId'");
        $ret = mysqli_fetch_array($query);

        if ($ret > 0) {
            return "<div class='alert alert-danger'>This Class Arm Already Exists!</div>";
        } else {
            $query = mysqli_query($this->conn, "INSERT INTO tblclassarms (classId, classArmName, isAssigned) VALUES ('$classId', '$classArmName', '0')");
            return $query ? "<div class='alert alert-success'>Created Successfully!</div>" : "<div class='alert alert-danger'>An error Occurred!</div>";
        }
    }

    public function updateClassArm($id, $classId, $classArmName) {
        $query = mysqli_query($this->conn, "UPDATE tblclassarms SET classId = '$classId', classArmName = '$classArmName' WHERE Id = '$id'");
        return $query ? true : false;
    }

    public function deleteClassArm($id) {
        $query = mysqli_query($this->conn, "DELETE FROM tblclassarms WHERE Id = '$id'");
        return $query === TRUE;
    }

    public function getClassArmById($id) {
        $query = mysqli_query($this->conn, "SELECT * FROM tblclassarms WHERE Id = '$id'");
        return mysqli_fetch_array($query);
    }

    public function getAllClassArms() {
        $query = "SELECT tblclassarms.Id, tblclassarms.isAssigned, tblclass.className, tblclassarms.classArmName 
                  FROM tblclassarms
                  INNER JOIN tblclass ON tblclass.Id = tblclassarms.classId";
        return $this->conn->query($query);
    }
}