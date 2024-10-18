<?php
include '../Includes/dbcon.php';

class Database { // OOP
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "username", "password", "database_name");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function close() {
        $this->conn->close();
    }
}

class viewAttendance { // OOP
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAttendance($dateTaken, $classId, $classArmId) {
        $query = "SELECT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, tblclass.className,
                  tblclassarms.classArmName, tblsessionterm.sessionName, tblsessionterm.termId, tblterm.termName,
                  tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber
                  FROM tblattendance
                  INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
                  INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
                  INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
                  INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
                  INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
                  WHERE tblattendance.dateTimeTaken = '$dateTaken'
                  AND tblattendance.classId = '$classId'
                  AND tblattendance.classArmId = '$classArmId'";
        
        return $this->db->query($query);
    }
}

?>