<?php
include "style.php";
include "header.php";
require('connect.php');
session_start();

//This gets passed to change.php and will be the start of the timer for the expiration of the link
$requestTime = time();
?>

<div class="form">
    <h1>Password Recovery</h1>
<form action="change.php" method="POST">
    <div class="field-wrap">
    <label>E-mail Address: </label><input type="text" name="email" size="20" /><br />
        </div>
    <div class="field-wrap">
        <label>Username: </label><input type="text" name="uname" size="20" />
        </div>
    <input type="hidden" name="requestTime" value="<?php echo $requestTime; ?>" />
    <input class="button" type="submit" name="ForgotPassword" value=" Submit " />
</form>
    <br />
    <a class="homelink" href="index.php">Home</a>
</div>

<?php
include "footer.php";
?>