<?php
include "style.php";
include "header.php";
require('connect.php');
session_start();

// Was the form submitted?
if (isset($_POST["ForgotPassword"])) {

//CLEANSE DATA
    $formfield['email'] = strtolower(trim($_POST['email']));
    $formfield['uname'] = strtolower(trim($_POST['uname']));
    $_SESSION['email'] = $formfield['email'];
    $requestTime = $_POST["requestTime"];

    //Create a session varible since we will need this on reset_password.php
    $_SESSION['requestTime'] = $requestTime;

//GET THE EMAIL AND SECRET QUESTION
try
{
    $sqllogin = "SELECT * FROM users WHERE email = :email AND uname = :uname";
    $slogin = $pdo->prepare($sqllogin);
    $slogin->bindValue(':email', $formfield['email']);
    $slogin->bindValue(':uname', $formfield['uname']);
    $slogin->execute();
    $rowlogin = $slogin->fetch();
    $countlogin = $slogin->rowCount();
    $sq = $rowlogin['secretQuestion'];
    $username = $rowlogin['uname'];

    //If query okay, see if there is a result
    if ($countlogin < 1)
    {
        echo  "<p class='main_content_error'>Email/Username not found. <a href='forgot_password.php'>Try again</a></p>";
    }

    else{

        // Create a unique salt. This will never leave PHP unencrypted.
        $salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

        // Create the unique user password reset key
        $password = hash('sha512', $salt.$formfield['email']);

        // Create a url which we will direct them to reset their password
        $pwrurl = "http://ccuresearch.coastal.edu/bmgoldman/csci409sp17/reset_password.php?q=".$password;

        $headers = "From: noreply@bmgoldman.com\n";
        $headers .= "Reply-To: noreply@bmgoldman.com\n";

        // Mail them their key
        $mailbody = "Dear user,\n\nIf this e-mail does not apply to you please ignore it.\nTo reset your password, please click the link below.\n\n" . $pwrurl . "\n\nThanks,\nBrenden Goldman";
        mail($formfield['email'], "Password Reset", $mailbody, $headers);



        echo "<p class='main_content_success'>Your password recovery key has been sent to your e-mail address. It will expire in 1 hour. <br /> <a href='index.php'>Home</a></p>";
    }

} catch (PDOException $e)
{
    echo 'Error fetching users: ' . $e->getMessage();
    exit();
}
}
include "footer.php";
