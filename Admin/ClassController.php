<?php
include '../Includes/dbcon.php';

class ClassController {        // OOP
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function saveClass($className) {
        $query = mysqli_query($this->conn, "SELECT * FROM tblclass WHERE className = '$className'");
        if (mysqli_fetch_array($query)) {
            return "<div class='alert alert-danger'>This Class Already Exists!</div>";
        } else {
            $query = mysqli_query($this->conn, "INSERT INTO tblclass(className) VALUES ('$className')");
            return $query ? 
                "<div class='alert alert-success'>Created Successfully!</div>" :
                "<div class='alert alert-danger'>An error Occurred!</div>";
        }
    }

    public function updateClass($id, $className) {
        $query = mysqli_query($this->conn, "UPDATE tblclass SET className='$className' WHERE Id='$id'");
        if ($query) {
            header("Location: createClass.php");
        } else {
            return "<div class='alert alert-danger'>An error Occurred!</div>";
        }
    }

    public function deleteClass($id) {
        $query = mysqli_query($this->conn, "DELETE FROM tblclass WHERE Id='$id'");
        if ($query) {
            header("Location: createClass.php");
        } else {
            return "<div class='alert alert-danger'>An error Occurred!</div>";
        }
    }

    public function getClassById($id) {
        $query = mysqli_query($this->conn, "SELECT * FROM tblclass WHERE Id='$id'");
        return mysqli_fetch_array($query);
    }

    public function getAllClasses() {
        $query = "SELECT * FROM tblclass";
        return $this->conn->query($query);
    }
}
