<?php
require_once "SessionController.php";
   class Dashboard extends SessionController
   {
   
    function Display()
       {
        $atype = Dashboard::GetType();
        switch($atype)
        {
            case 1:
                echo "
            <main>          
                <h1>Admin Dashboard</h1>
                <div class=horizontal_line>
                    <hr>
                </div>
                <div>
                    <button class=\"button_large\" type=\"button\" onclick=\"location.href = 'create_account.php'\">Create Account</button>
                </div>
                <br>
                <button class=\"button_large\" type=\"button\" onclick=\"location.href = 'user_search.php'\">User Search</button>
            </main>";
                break;
            case 2:
                echo "
           <main>         
                <h1>Faculty Dashboard</h1>
                <div class=horizontal_line>
                    <hr>
                </div>
                <div>
                    <button class=\"button_large\" type=\"button\" onclick=\"location.href = 'enter_grades.php'\">Enter Grades</button>
                </div>
            </main>";
                 break;
            case 3:
                echo "
           <main>        
                <h1>Student Dashboard</h1>
                <div class=horizontal_line>
                    <hr>
                </div>
                <div>
                    <button class=\"button_large\" type=\"button\" onclick=\"location.href = 'course_search.php'\">Course Search</button>
                </div>
            </main>";
                break;
            case -1:
                echo "Invalid response?";
                break;
            default:
                echo("Invalid login!");
            break;
        }
       }
       function LoadPage()
       {
        echo "
        <main>          
            <h1>Your Dashboard</h1>
            <div class=horizontal_line>
                <hr>
            </div>";
            if(Dashboard::HasAdminRights())
            {
                echo"<div>
                <button class=\"button_large\" type=\"button\" onclick=\"location.href = 'create_account.php'\">Create Account</button>
            </div>
            <br>
            <button class=\"button_large\" type=\"button\" onclick=\"location.href = 'user_search.php'\">User Search</button>
            <br>";
            }
            if(Dashboard::HasFacultyRights())
            {
                echo"<div>
                <button class=\"button_large\" type=\"button\" onclick=\"location.href = 'enter_grades.php'\">Enter Grades</button>
            </div>
            <br>";
            }
            if(Dashboard::HasStudentRights())
            {
                echo"<div>
                <button class=\"button_large\" type=\"button\" onclick=\"location.href = 'course_search.php'\">Course Search</button>
                </div>";
            }
            echo"</main>";
       }
   }
?>