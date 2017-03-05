<?php
include "style.php";
require('connect.php');
include "header.php";
session_start();
// If form submitted, insert values into the database.
if(isset ($_POST['submit']))
{

    //CLEANSE DATA
    $formfield['fname'] = trim($_POST['fname']);
    $formfield['lname'] = trim($_POST['lname']);
    $formfield['uname'] = strtolower(trim($_POST['uname']));
    $formfield['email'] = strtolower(trim($_POST['email']));
    $formfield['secretQuestion'] = trim($_POST['secretQuestion']);
    $formfield['secretAnswer'] = trim($_POST['secretAnswer']);
    $formfield['pwd'] = trim($_POST['pwd']);
    $formfield['pwd2'] = trim($_POST['pwd2']);

    //CHECKING FOR EMPTY FIELDS
    if (empty($formfield['fname'])){$errormsg .= "<p class='main_content_error'>FIRST NAME IS MISSING.</p>";}
    if (empty($formfield['lname'])){$errormsg .= "<p class='main_content_error'>LAST NAME IS MISSING.</p>";}
    if (empty($formfield['uname'])){$errormsg .= "<p class='main_content_error'>USERNAME IS MISSING.</p>";}
    if (empty($formfield['pwd'])){$errormsg .= "<p class='main_content_error'>PASSWORD IS MISSING.</p>";}
    if (empty($formfield['pwd2'])){$errormsg .= "<p class='main_content_error'>VERIFY PASSWORD IS MISSING.</p>";}
    if (empty($formfield['email'])){$errormsg .= "<p class='main_content_error'>EMAIL IS MISSING.</p>";}
    if (empty($formfield['secretQuestion'])){$errormsg .= "<p class='main_content_error'>SECRET QUESTION IS MISSING.</p>";}
    if (empty($formfield['secretAnswer'])){$errormsg .= "<p class='main_content_error'>SECRET ANSWER IS MISSING.</p>";}

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
        echo "<p class='main_content_error'>There are errors:  <br> " . $errormsg . "</p>";
    }
    else
    {
        //CHECK FOR DUPLICATE USERNAME OR EMAIL
        try
        {
            $sqllogin = "SELECT * FROM users WHERE uname = :uname OR email = :email";
            $slogin = $pdo->prepare($sqllogin);
            $slogin->bindValue(':uname', $formfield['uname']);
            $slogin->bindValue(':email', $formfield['email']);
            $slogin->execute();
            $rowlogin = $slogin->fetch();
            $countlogin = $slogin->rowCount();

            //If we find a row show the error message
            if ($countlogin > 0)
            {
                echo  "<p class='main_content_error'>This username/email is already registered. <a href='register.php'>Try again</a></p>";
            }
            else
            {

                /*  ****************************************************************************
                HASH PASSWORD BEFORE INSERTING INTO DATABASE
                **************************************************************************** */
                $options = [
                    'cost' => 12,
                ];
                $formfield['pwd'] = password_hash($formfield['pwd'], PASSWORD_BCRYPT, $options);

                /*  ****************************************************************************
                INSERT INTO DATABASE TABLE
                **************************************************************************** */
                try {
                    $sqlinsert = 'INSERT INTO users (fname, lname, uname, email, password, secretQuestion, secretAnswer) VALUES (:fname, :lname, :uname, :email, :password, :secretQuestion, :secretAnswer)';
                    $stmtinsert = $pdo->prepare($sqlinsert);
                    $stmtinsert->bindvalue(':fname', $formfield['fname']);
                    $stmtinsert->bindvalue(':lname', $formfield['lname']);
                    $stmtinsert->bindvalue(':uname', $formfield['uname']);
                    $stmtinsert->bindvalue(':email', $formfield['email']);
                    $stmtinsert->bindvalue(':password', $formfield['pwd']);
                    $stmtinsert->bindvalue(':secretQuestion', $formfield['secretQuestion']);
                    $stmtinsert->bindvalue(':secretAnswer', $formfield['secretAnswer']);
                    $stmtinsert->execute();
                    $showform = 0;
                    echo "<div class='main_content_success'><p>You're registered! <a href=\"login.php\">Login</a>.</p></div>";
                    $_SESSION['uname'] = $rowlogin['uname'];
                }//try
                catch (PDOException $e) {
                    echo 'ERROR!!!' . $e->getMessage();
                    exit();
                }//catch
            }//username exists
        }//try
        catch (PDOException $e)
        {
            echo 'Error fetching users: ' . $e->getMessage();
            exit();
        }//end catch

    }//else errormessage

}//ifisset

else{
    ?>
    <div class="form">
            <h1>Sign Up for Free</h1>
            <form action="" name="registration" method="post">
                <div class="field-wrap">
                    <label>First Name<span class="req">*</span></label>
                    <input type="text" name="fname" value="<?php echo $formfield['fname']; ?>" required /><br/>
                </div>
                <div class="field-wrap">
                    <label>Last Name<span class="req">*</span></label>
                    <input type="text" name="lname" value="<?php echo $formfield['lname']; ?>" required /><br/>
                </div>
                <div class="field-wrap">
                    <label>Username<span class="req">*</span></label>
                    <input type="text" name="uname" value="<?php echo $formfield['uname']; ?>" required /><br/>
                </div>
                <div class="field-wrap">
                    <label>Email Address<span class="req">*</span></label>
                    <input type="email" name="email" value="<?php echo $formfield['email']; ?>" required /><br/>
                </div>
                <div class="field-wrap">
                    <label>Set A Password<span class="req">*</span></label>
                    <input type="password" name="pwd" value="<?php echo $formfield['pwd']; ?>" required /><br/>
                    <h6 class="password_reqs">Password must contain 1 uppercase, 1 lowercase, 1 digit, and be at least 8 characters long</h6>
                </div>
                <div class="field-wrap">
                    <label>Confirm Password<span class="req">*</span></label>
                    <input type="password" name="pwd2" value="<?php echo $formfield['pwd2']; ?>" required /><br/>
                </div>
                <div class="field-wrap">
                    <label>Secret Question<span class="req">*</span></label>
                    <input type="text" name="secretQuestion" value="<?php echo $formfield['secretQuestion']; ?>" required /><br/>
                </div>
                <div class="field-wrap">
                    <label>Secret Answer<span class="req">*</span></label>
                    <input type="text" name="secretAnswer" value="<?php echo $formfield['secretAnswer']; ?>" required /><br/>
                </div>
                <p class="req">* = required</p>
                <input class="button" type="submit" name="submit" value="Register" />
            </form>
        <br />
        <a class="homelink" href="index.php">Home</a>
    </div> <!-- /form -->


<?php }
include "footer.php";
?>
