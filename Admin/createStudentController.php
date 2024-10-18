<?php
include '../Includes/dbcon.php';  // include your database connection

class Student {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Method to save a new student
    public function save($firstName, $lastName, $otherName, $admissionNumber, $classId, $classArmId) {
        $firstName = mysqli_real_escape_string($this->conn, $firstName);
        $lastName = mysqli_real_escape_string($this->conn, $lastName);
        $otherName = mysqli_real_escape_string($this->conn, $otherName);
        $admissionNumber = mysqli_real_escape_string($this->conn, $admissionNumber);
        $dateCreated = date("Y-m-d");

        // Check if admission number exists
        $query = "SELECT * FROM tblstudents WHERE admissionNumber = '$admissionNumber'";
        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) > 0) {
            return "<div class='alert alert-danger'>This Admission Number Already Exists!</div>";
        } else {
            // Insert student record
            $query = "INSERT INTO tblstudents (firstName, lastName, otherName, admissionNumber, password, classId, classArmId, dateCreated)
                      VALUES ('$firstName', '$lastName', '$otherName', '$admissionNumber', '12345', '$classId', '$classArmId', '$dateCreated')";
            
            if (mysqli_query($this->conn, $query)) {
                return "<div class='alert alert-success'>Created Successfully!</div>";
            } else {
                return "<div class='alert alert-danger'>An error Occurred!</div>";
            }
        }
    }

    // Method to update an existing student
    public function update($id, $firstName, $lastName, $otherName, $admissionNumber, $classId, $classArmId) {
        $firstName = mysqli_real_escape_string($this->conn, $firstName);
        $lastName = mysqli_real_escape_string($this->conn, $lastName);
        $otherName = mysqli_real_escape_string($this->conn, $otherName);
        $admissionNumber = mysqli_real_escape_string($this->conn, $admissionNumber);
        
        $query = "UPDATE tblstudents SET firstName='$firstName', lastName='$lastName', otherName='$otherName', 
                  admissionNumber='$admissionNumber', classId='$classId', classArmId='$classArmId' WHERE Id='$id'";

        if (mysqli_query($this->conn, $query)) {
            return true;
        } else {
            return false;
        }
    }

    // Method to delete a student
    public function delete($id) {
        $query = "DELETE FROM tblstudents WHERE Id='$id'";
        return mysqli_query($this->conn, $query);
    }

    // Method to get all students
    public function getAllStudents() {
        $query = "SELECT tblstudents.Id, tblclass.className, tblclassarms.classArmName, tblstudents.firstName, tblstudents.lastName,
                  tblstudents.otherName, tblstudents.admissionNumber, tblstudents.dateCreated
                  FROM tblstudents
                  INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                  INNER JOIN tblclassarms ON tblclassarms.Id = tblstudents.classArmId";
        return mysqli_query($this->conn, $query);
    }

    public function getStudentById($id) {
        $query = "SELECT * FROM tblstudents WHERE Id='$id'";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }
}
?>
