<?php
require_once "../src/RequestController.php";

try {
    /*Get DB connection*/
    require_once "../src/DBConnector.php";

    /*Get information from the search (post) request*/
    $acctype = $_POST['acctype'];
    $password = $_POST['password'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $dob = $_POST['dob']; //date obtained is already UTC
    $email = strtolower($_POST['email']); //is converted to lower
    $studentyear = $_POST['studentyear']; //only if student, ensure null otherwise
    $facultyrank = $_POST['facultyrank']; //only if faculty, ensure null otherwise
    $squestion = $_POST['squestion'];
    $sanswer = $_POST['sanswer'];
    $prevemail = $_POST['prevemail']; //required to find the user being updated

    if($acctype==null)
    {throw new Exception("input did not exist");}

    /*Validate Input*/
    if (RequestController::ValidateEmail($email) == false)
    {
        throw new Exception("Invalid email");
    }

    /*Validate Input*/
    if (RequestController::ValidatePassword($password) == false)
    {
        throw new Exception("Invalid password");
    }

    /*Validate Input*/
    if (RequestController::ValidateName($fname) == false)
    {
        throw new Exception("Invalid name");
    }

    /*Validate Input*/
    if (RequestController::ValidateName($lname) == false)
    {
        throw new Exception("Invalid name");
    }
    
    /*Prevent XSS*/
    $password = RequestController::XssValidation($password);
    $fname = RequestController::XssValidation($fname);
    $lname = RequestController::XssValidation($lname);
    $squestion = RequestController::XssValidation($squestion);
    $sanswer = RequestController::XssValidation($sanswer);
    $prevemail = RequestController::XssValidation($prevemail);

    $password = hash('ripemd256', $password); //convert password to 80 byte hash using ripemd256 before saving

    /*Checking studentyear and facultyrank*/
    if ($acctype === "3") {
        $facultyrank = null;
    } else if ($acctype === "2") {
        $studentyear = null;
    }


    /*Update the database with the new info*/
    $query = "UPDATE User 
            SET Email = :email, Password = :password, FName = :fname, LName = :lname, DOB = :dob, Year = :studentyear, Rank = :facultyrank, SQuestion = :squestion, SAnswer = :sanswer 
            WHERE Email = :prevemail";
    $stmt = $db->prepare($query); //prevents SQL injection by escaping SQLite characters
    $stmt->bindParam(':email', $email, SQLITE3_TEXT);
    $stmt->bindParam(':password', $password, SQLITE3_TEXT);
    $stmt->bindParam(':fname', $fname, SQLITE3_TEXT);
    $stmt->bindParam(':lname', $lname, SQLITE3_TEXT);
    $stmt->bindParam(':dob', $dob, SQLITE3_TEXT);
    $stmt->bindParam(':studentyear', $studentyear, SQLITE3_INTEGER);
    $stmt->bindParam(':facultyrank', $facultyrank, SQLITE3_TEXT);
    $stmt->bindParam(':squestion', $squestion, SQLITE3_TEXT);
    $stmt->bindParam(':sanswer', $sanswer, SQLITE3_TEXT);
    $stmt->bindParam(':prevemail', $prevemail, SQLITE3_TEXT);
    $results = $stmt->execute();

    if($results){//query to User table is successful
        $query = "SELECT * FROM User 
                WHERE Email = :email";
        $stmt = $db->prepare($query); //prevents SQL injection by escaping SQLite characters
        $stmt->bindParam(':email', $email, SQLITE3_TEXT);
        $results = $stmt->execute();
    }

    if(($userinfo = $results->fetchArray()) !== null){//checks if rows exist
        $query = "UPDATE UserRole 
                SET AccType = :acctype
                WHERE :userid = uid";
        $stmt = $db->prepare($query); //prevents SQL injection by escaping SQLite characters
        $stmt->bindParam(':acctype', $acctype, SQLITE3_INTEGER);
        $stmt->bindParam(':userid', $userinfo[0], SQLITE3_INTEGER);
        $results = $stmt->execute();
    }

//is true on success and false on failure
    if (!$results) {
        throw new Exception("edit failed");
    } else {
        //backup database
        $db->backup($db, "temp", $GLOBALS['dbPath']);
        //redirect
        header("Location: ../public/user_search.php");

    }
}
catch(Exception $e)
{
    //prepare page for content
    include_once "ErrorHeader.php";

    //Display error information
    echo 'Caught exception: ',  $e->getMessage(), "<br>";
}