<?php
include "style.php";
include "header.php";
require('connect.php');
session_start();

if (isset($_POST["ResetPasswordForm"]))
{

    // Gather the post data
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];
    $hash = $_POST["q"];
    $secretAnswer = strtolower($_POST["secretAnswer"]);
    $errormsg = "";

    try {
        $sqllogin = "SELECT secretAnswer FROM users WHERE email = :email";
        $slogin = $pdo->prepare($sqllogin);
        $slogin->bindValue(':email', $email);
        $slogin->execute();
        $rowlogin = $slogin->fetch();;
        $sa = strtolower($rowlogin['secretAnswer']);
    }catch (PDOException $e)
    {
        echo 'Error fetching users: ' . $e->getMessage();
        exit();
    }

    // Use the same salt from the forgot_password.php file
    $salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

    // Generate the reset key
    $resetkey = hash('sha512', $salt.$email);

    if ($secretAnswer != $sa) {
        $errormsg .= "<p class='main_content_error'>Your secret answer is incorrect. Did you enter the wrong email?</p>";
    }
    /*  ****************************************************************************
     		CHECK PASSWORD COMPLEXITY
		**************************************************************************** */
    $validUpper = preg_match('/[A-Z]/', $password);
    $validLower = preg_match('/[a-z]/', $password);
    $validNumber = preg_match('/[0-9]/', $password);

    if(!$validUpper){
        $errormsg .= "<p class='main_content_error'>Password must have at least 1 uppercase</p>";
    }
    if(!$validLower){
        $errormsg .= "<p class='main_content_error'>Password must have at least 1 lowercase</p>";
    }
    if(!$validNumber){
        $errormsg .= "<p class='main_content_error'>Password must have at least 1 number</p>";
    }
    if(strlen($password) < 8){
        $errormsg .= "<p class='main_content_error'>Password must be longer than 8 characters</p>";
    }

    /*  ****************************************************************************
     		COMPARE PASSWORDS
		**************************************************************************** */
    if($password != $confirmpassword) {
        $errormsg .= "<p class='main_content_error'>Passwords do not match</p>";
    }

    //DISPLAY ERRORS
    if($errormsg !="")
    {
        echo "<p class='main_content_error'>There are errors:  <br> " . $errormsg . "</p>";
        echo "<p class='main_content_error'><a href='#' onClick='history.go(-1);return true;'>Try again</a></p>";

    }

    // Does the new reset key match the old one?
    if ($resetkey == $hash)
    {
        if ($errormsg == "")
        {
            /*  ****************************************************************************
               HASH PASSWORD BEFORE INSERTING INTO DATABASE
               **************************************************************************** */
            $options = [
                'cost' => 12,
            ];
            //has and secure the password
            $password = password_hash($password, PASSWORD_BCRYPT, $options);

            /*  ****************************************************************************
                UPDATE DATABASE TABLE
                **************************************************************************** */
            try {
                //enter data into database
                $sqlinsert = 'UPDATE users SET password = :password WHERE email = :email';
                $stmtinsert = $pdo->prepare($sqlinsert);
                $stmtinsert->bindvalue(':password', $password);
                $stmtinsert->bindvalue(':email', $email);
                $stmtinsert->execute();

                //hide the form
                //$showform = 0;
                echo "<p class='main_content_success'>Your password has been successfully reset. <a href='login.php'>Login</a></p></d>";
            }//try
            catch (PDOException $e) {
                echo 'ERROR!!!' . $e->getMessage();
                exit();
            }//catch
        }
        else{
            echo "";
        }
        }

    else
        echo "<p class='main_content_error'>Your password reset key is invalid, you must use the same email address used to send the link.</p>";
}

