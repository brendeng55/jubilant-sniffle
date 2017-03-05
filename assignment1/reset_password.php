<?php
include "style.php";
include "header.php";
require('connect.php');
session_start();

$email = $_SESSION['email'];

//Alloted Time is in seconds 3600 = 1 day
$allotedTime = 3600;
$requestTime = $_SESSION['requestTime'];

//Response time is the unix time stamp from when the user entered his email
//on forgot_password.php plus the alloted time we want to give the link before it expires
$responseTime = $requestTime + $allotedTime;
$currentTime = time();

if($responseTime > $currentTime) {
      //Used for debugging
      //echo 'Request Time:' . $requestTime . '<br>';
      //echo 'Response Time:' . $responseTime . '<br>';
    try {
        $sqllogin = "SELECT secretQuestion, secretAnswer FROM users WHERE email = :email";
        $slogin = $pdo->prepare($sqllogin);
        $slogin->bindValue(':email', $email);
        $slogin->execute();
        $rowlogin = $slogin->fetch();
        $sq = strtolower($rowlogin['secretQuestion']);

    } catch (PDOException $e) {
        echo 'Error fetching users: ' . $e->getMessage();
        exit();
    }
    echo '
<div class="form">
<form action="reset.php" method="POST">
<h1>Reset Password</h1>
<div class="field-wrap"><label>E-mail Address: </label><input type="text" name="email" size="20" /><br /></div>
<div class="field-wrap"><label>' . $sq . '</label><input type="text" name="secretAnswer" size="20" /><br /></div>
<div class="field-wrap"><label>New Password: </label><input type="password" name="password" size="20" /><br />
<h6 class="password_reqs">Password must contain 1 uppercase, 1 lowercase, 1 digit, and be at least 8 characters long</h6></div>
<div class="field-wrap"><label>Confirm Password: </label><input type="password" name="confirmpassword" size="20" /><br /></div>
<input type="hidden" name="q" value="';

    if (isset($_GET["q"])) {
        echo $_GET["q"];
    }
    echo '" /><input class="button" type="submit" name="ResetPasswordForm" value=" Reset Password " />
</form>
<br />
    <a class="homelink" href="index.php">Home</a>
</div>';

    include "footer.php";
}else{
    echo "<p class='main_content_error'>Your link expired, you have to be faster than that. <a href='forgot_password.php'>Try again</a></p>";
}

?>
