<?php
require_once "User.php";
require_once "../src/DBConnector.php";

define("admin", 1);
define("faculty", 2);
define("student", 3);

class SessionController{
    static function CreateSession($User,$uname, $pword){
        
        $acctype = $User->GetAccType(); //determines which dashboard to present
        if (isset($_SESSION))
                {
                    //a session already existed
                    session_destroy();
                    session_start();
                    $_SESSION['email'] = $uname;
                    $_SESSION['acctype'] = $acctype;
                } 
                
                else
                {
                    //a session did not exist
                    session_start();
                    $_SESSION['email'] = $uname;
                    $_SESSION['acctype'] = $acctype;
                }
    }
    function ValidateSession()
    {
        return (isset($_SESSION));
    }
    function ValidateLogin()
    {
        session_start();
        if(isset($_SESSION['acctype']))
        {
            return true;
        }
        else
        {
            return false;
        } 
    }
    static function GetType()
    {
        session_start();
        if(isset($_SESSION['acctype']))
        {
            return $_SESSION['acctype'];
        }
        else
        {
            return -1;
        }
    }
    function ValidateEmail()
    {
        session_start(); //required to bring session variables into context

        if (!isset($_SESSION['email']) or (empty($_SESSION['email']))) //check that session exists and is nonempty
        {
            return false;
        }
        else {return true;}
    } 
    function GetEmail()
    {
        session_start(); //required to bring session variables into context

        if (!isset($_SESSION['email']) or (empty($_SESSION['email']))) //check that session exists and is nonempty
        {
            return "empty";
        }
        else {return $_SESSION['email'];}
    }
    function HasStudentRights()
    {
        session_start();
        if(SessionController::authorize(SessionController::GetEmail(), "course_search.php"))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function HasAdminRights()
    {
        session_start();
        if(SessionController::authorize(SessionController::GetEmail(), "create_account.php"))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function HasFacultyRights()
    {
        session_start();
        if(SessionController::authorize(SessionController::GetEmail(), "enter_grades.php"))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    static function authenticate(){
        session_start();
        if (isset($_SESSION['acctype'])) {
            //a session exists
            return true;
        }
        else{ 
            return false;
        }
    }
    static function authorize($un, $res){
        if (DBConnector::CheckRights($un, $res))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    static function closeSession(){
        
            session_destroy(); //clear all session variables
        
    //redirect
    
    }

}       
?>