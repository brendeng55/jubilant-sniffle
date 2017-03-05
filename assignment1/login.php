<?php
include "style.php";
include "header.php";
require('connect.php');
session_start();
// If form submitted, insert values into the database.
if(isset ($_POST['submit']))
{

    //CLEANSE DATA THE SAME AS THE REGISTRATION PAGE
    $formfield['uname'] = strtolower(trim($_POST['uname']));
    $formfield['pwd'] = trim($_POST['pwd']);

    //CHECKING FOR EMPTY FIELDS THE SAME AS THE REGISTRATION PAGE
    if (empty($formfield['uname'])){$errormsg .= "<p class=''main_content_error'>USERNAME IS MISSING.</p>";}
    if (empty($formfield['pwd'])){$errormsg .= "<p class=''main_content_error'>PASSWORD IS MISSING.</p>";}

    //DISPLAY ERRORS
    if($errormsg !="")
    {
        echo "<p class=''main_content_error'>There are errors:  <br> " . $errormsg . " <a href='login.php'>Try again</a></p>";
    }
    else
    {
        //GET THE USER FROM THE DATABASE
        try
        {
            $sqllogin = "SELECT * FROM users WHERE uname = :uname";
            $slogin = $pdo->prepare($sqllogin);
            $slogin->bindValue(':uname', $formfield['uname']);
            $slogin->execute();
            $rowlogin = $slogin->fetch();
            $countlogin = $slogin->rowCount();
            $hash = $rowlogin['password'];

            //If query okay, see if there is a result
            if ($countlogin < 1)
            {
                echo  "<p class='main_content_error'>This username is not registered. <a href='login.php'>Try again</a></p>";
            }
            else
            {

                /*  ****************************************************************************
                VERIFY PASSWORD USING HASH FROM DATABASE
                **************************************************************************** */

                if (!password_verify($formfield['pwd'], $hash))
                {
                    echo "<p class='main_content_error'>The username was found but the password is incorrect. <a href='login.php'>Try again</a></p>";
                }
                //Do this if passwords match
                else
                {
                    $_SESSION['uname'] = $rowlogin['uname'];
                    $_SESSION['email'] = $rowlogin['email'];
                    echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
                }
            }//username exists
        }//try
        catch (PDOException $e)
        {
            echo 'Error fetching users: ' . $e->getMessage();
            exit();
        }


    }//else errormessage

}//end if isset

//FORM AREA
else{
    ?>
    <div class="form">

                <h1>Welcome Back!</h1>

                <form action="login.php" method="post" name="login">
                    <div class="field-wrap">
                        <label for="uname" >Username<span class="req">*</span></label>
                        <input type="text" name="uname" value="<?php echo $formfield['uname']; ?>" required autocomplete="off"/>
                    </div>
                    <div class="field-wrap">
                        <label>Password<span class="req">*</span></label>
                        <input type="password" name="pwd" value="<?php echo $formfield['pwd']; ?>" required autocomplete="off"/>
                        <h6 class="password_reqs">Reminder: Password must contain 1 uppercase, 1 lowercase, 1 digit and be at least 8 characters long</h6>
                    </div>

                    <p class="forgot"><a href="forgot_password.php">Forgot Password?</a></p>
                    <p class="forgot"><a href="forgotuname.php">Forgot Username?</a></p>

                    <input class="button" name="submit" type="submit" value="Log In" />
                </form>
        <br />
        <a class="homelink" href="index.php">Home</a>
    </div>



<?php }
include "footer.php";
?>
