<?php

require_once("dbcon.php"); 
require_once("student.php");

class incorrectvalues extends Exception {
    public function agecheck() {
        return 'Enter the age between 0 and 120. ';
    }
    public function classcheck() {
        return 'Enter the class';
    }
    public function emailcheck() {
        return 'Enter a valid email ID';
    }
    public function namecheck() {
        return 'Enter a valid name';
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update_student'])) {

    $age = $_POST["age"];
    $email = $_POST["email"];
    $name = $_POST["name"];
    $class = $_POST['class'];

    

    $studentname = '';
    $studentage = '';
    $studentclass = '';
    $studentemail = '';

    $id = $_POST['id'] ?? null;
    
    try {
 
        if (!filter_var($age, FILTER_VALIDATE_INT, array("options" => array("min_range" => 0, "max_range" => 120)))) {
            throw new incorrectvalues($age);
        }
        $studentage = $age; 


        if (empty($class)) {
            throw new incorrectvalues($class);
        }
        $studentclass = $class; 


        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            throw new incorrectvalues($email);
        }
        $studentemail = $email;


        $pattern = "/^[a-zA-Z\s']+$/";
        if (!preg_match($pattern, $name)) {
            throw new incorrectvalues($name);
        }
        $studentname = $name; 


        $studentObj = new Student($studentname, $studentage, $studentclass, $studentemail);
        if ($studentObj->updateStudents($id)) {
            header("Location: index.php?success=updated");
            exit();
        } else {
            header("Location: index.php?error=update");
            exit();
        }
    } catch (incorrectvalues $e) {
        echo $e->getMessage() . '<br>';
    } catch (Exception $e) {
        echo 'An unexpected error occurred: ' . $e->getMessage();
    }
} else {
    header("Location: index.php");
    exit();
}

?>
