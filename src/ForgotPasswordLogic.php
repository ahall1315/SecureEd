<?php
//ensuring database connection
    include_once '../config/ConfigV2.php';

//Variables and Email gained from user entry------------------
$GLOBALS['email'];
$GLOBALS['SecQuestion'];
Global $email = strtolower($_POST['email']);
Global $SecQuestion;
$SecAnswer = "";
//$mySAnswer = "";
//$NewPassword = "";
//$NewPasswordConfirm = "";

//checks if given email exists-------------
$query = "SELECT COUNT(*) as count FROM User WHERE Email ='$email'";
$count = $db->querySingle($query);

if($count =0)
{
//Invalid Email
header("Location: ../public/ForgotPassword.php?emailcheck=fail");
}

else	
{
$query = "SELECT  SQuestion FROM User WHERE Email ='$email'";
Global $SecQuestion = $db->query($query);
header("Location:../public/ForgotPasswordSecQ.php");
}	
     
?>




