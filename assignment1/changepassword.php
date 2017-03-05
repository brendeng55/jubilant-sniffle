<?php
include "style.php";
include "header.php";
require('connect.php');
session_start();
$showform = 1;
$errormsg = "";

if(isset($_POST['submit'])) {
    $email = $_SESSION['email'];
    $formfield['pwd'] = trim($_POST['pwd']);
    $formfield['pwd2'] = trim($_POST['pwd2']);

    /*  ****************************************************************************
     		CHECK PASSWORD COMPLEXITY
		**************************************************************************** */
    $validUpper = preg_match('/[A-Z]/', $formfield['pwd']);
    $validLower = preg_match('/[a-z]/', $formfield['pwd']);
    $validNumber = preg_match('/[0-9]/', $formfield['pwd']);

    if(!$validUpper){
        $errormsg .= "<p class='main_content_error'>Password must have at least 1 uppercase</p>";
    }
    if(!$validLower){
        $errormsg .= "<p class='main_content_error'>Password must have at least 1 lowercase</p>";
    }
    if(!$validNumber){
        $errormsg .= "<p class='main_content_error'>Password must have at least 1 number</p>";
    }
    if(strlen($formfield['pwd']) < 8){
        $errormsg .= "<p class='main_content_error'>Password must be longer than 8 characters</p>";
    }

    /*  ****************************************************************************
     		COMPARE PASSWORDS
		**************************************************************************** */
    if($formfield['pwd'] != $formfield['pwd2']) {
        $errormsg .= "<p class='main_content_error'>Passwords do not match</p>";
    }

    //DISPLAY ERRORS
    if($errormsg !="")
    {
        $showform = 0;
        echo "<p class='main_content_error'>There are errors:  <br> " . $errormsg . "</p>";
        echo "<p class='main_content_error'><a href='#' onClick='history.go(-1);return true;'>Try again</a></p>";

    }

    if ($errormsg == "") {
        /*  ****************************************************************************
                   HASH PASSWORD BEFORE INSERTING INTO DATABASE
            **************************************************************************** */
        $options = [
            'cost' => 12,
        ];
        $formfield['pwd'] = password_hash($formfield['pwd'], PASSWORD_BCRYPT, $options);

        /*  ****************************************************************************
                   UPDATE DATABASE TABLE
            **************************************************************************** */
        try {
            //enter data into database
            $sqlinsert = 'UPDATE users SET password = :password WHERE email = :email';
            $stmtinsert = $pdo->prepare($sqlinsert);
            $stmtinsert->bindvalue(':password', $formfield['pwd']);
            $stmtinsert->bindvalue(':email', $email);
            $stmtinsert->execute();

            //hide the form
            $showform = 0;
            echo "<p class='main_content_success'>Your password has been changed successfully. <a href='index.php'>Home</a></p></d>";
        }//try
        catch (PDOException $e) {
            echo 'ERROR!!!' . $e->getMessage();
            exit();
        }//catch
    }//close if errormsg
}
?>

<?php

//SHOW FORM ONLY IF = 1
if($showform == 1) {
    ?>
    <div class="form">
        <form action="changepassword.php" method="POST">
            <h1>Change Password</h1>

            <div class="field-wrap">
                <label>Set A Password<span class="req">*</span></label>
                <input type="password" name="pwd" value="<?php echo $formfield['pwd']; ?>" required/><br/>
                <h6 class="password_reqs">Password must contain 1 uppercase, 1 lowercase, 1 digit, and be at least 8
                    characters long</h6>
            </div>
            <div class="field-wrap">
                <label>Confirm Password<span class="req">*</span></label>
                <input type="password" name="pwd2" value="<?php echo $formfield['pwd2']; ?>" required/><br/>
            </div>
            <input class="button" type="submit" name="submit" value=" Change Password "/>
        </form>
        <br/>
        <a class="homelink" href="index.php">Home</a>
    </div>';

    <?php
}
include "footer.php";
?>