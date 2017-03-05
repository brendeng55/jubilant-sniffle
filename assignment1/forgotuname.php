<?php
include "style.php";
include "header.php";
require('connect.php');
session_start();
$showform = 1;

if(isset ($_POST['submit']) && $showform == 1) {
    //CLEANSE DATA
    $formfield['email'] = strtolower(trim($_POST['email']));
    $formfield['sa'] = trim($_POST['sa']);
    //CHECKING FOR EMPTY FIELDS
    if (empty($formfield['email'])) {
        $errormsg .= "<p class='main_content_error'>EMAIL IS MISSING.</p>";
    }
    if (empty($formfield['sa'])) {
        $errormsg .= "<p class='main_content_error'>SECRET ANSWER IS MISSING.</p>";
    }
    //DISPLAY ERRORS
    if ($errormsg != "") {
        echo "<p class='main_content_error'>There are errors:  <br> " . $errormsg . " <a href='forgotuname.php'>Try again</a></p>";
    } else {
        //GET THE EMAIL AND SECRET QUESTION
        try
        {
            $sqllogin = "SELECT * FROM users WHERE email = :email";
            $slogin = $pdo->prepare($sqllogin);
            $slogin->bindValue(':email', $formfield['email']);
            $slogin->execute();
            $rowlogin = $slogin->fetch();
            $countlogin = $slogin->rowCount();
            $sq = $rowlogin['secretQuestion'];
            $sa = $rowlogin['secretAnswer'];
            $username = $rowlogin['uname'];

            //If query okay, see if there is a result
            if ($countlogin < 1)
            {
                echo  "<p class='main_content_error'>Email not found. <a href='forgotuname.php'>Try again</a></p>";
            }
            else if ($formfield['sa'] != $sa){
                echo  "<p class='main_content_error'>Secret Answer is incorrect. <a href='forgotuname.php'>Try again</a></p>";
            }
            else
            {

                /*  ****************************************************************************
                SEND EMAIL THAT CONTAINS USERNAME
                **************************************************************************** */
                $showform = 0;
                echo "<p class='main_content_success'>Your username has been mailed to you. <a href='index.php'>Home</a></p>";
                /************************* Send email to new user *************************/
                $to = $formfield['email'];
                $email_subject = "Forgotten Username!";
                $email_body = "
                                <html>
                                <head>
                                  <title>Forgotten Username!</title>
                                </head>
                                <body>
                                  <h1>It happens to the best of us.</h1>
                                  <p>Username: " . $username .
                                 "</p><a href='http://ccuresearch.coastal.edu/bmgoldman/csci409sp17/login.php'>Click here to login</a>
                                 </body>
                                </html>";
                $headers = "From: noreply@bmgoldman.com\n";
                $headers .= "Reply-To: noreply@bmgoldman.com\n";

                // To send HTML mail, the Content-type header must be set
                $headers .= 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                mail($to,$email_subject,$email_body,$headers);

            }// end else
        }//try
        catch (PDOException $e)
        {
            echo 'Error fetching users: ' . $e->getMessage();
            exit();
        }
    }
}//end iffset
else{
?>

<div class="form">

    <h1>Username Recovery</h1>

    <form action="forgotuname.php" method="post" name="forgotuname">
        <div class="field-wrap">
            <label for="email" >Email<span class="req">*</span></label>
            <input type="email" name="email" value="<?php echo $formfield['email']; ?>" required autocomplete="off"/>
        </div>
        <div class="field-wrap">
            <label for="sq" ><?php echo "Secret Answer" ?><span class="req">*</span></label>
            <input type="text" name="sa" value="<?php echo $formfield['sa']; ?>" required autocomplete="off"/>
        </div>
        <input class="button" name="submit" type="submit" value="Submit" />
    </form>
    <br />
    <a class="homelink" href="index.php">Home</a>
</div>
<?php }
include "footer.php";
?>