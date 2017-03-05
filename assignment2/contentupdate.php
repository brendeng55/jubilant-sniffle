<?php
include "style.php";
include "header.php";
require('connect.php');
session_start();
/*  ****************************************************************************
    CHECK TO SEE IF LOGGED IN
    ****************************************************************************
*/
if(!isset($_SESSION['id']))
{
    echo '<p>This page requires you to <a href="../login.php">log in</a>.';
    include_once "footer.php";
    exit();
}
//NECESSARY VARIABLES
$showform = 1;
$errormsg = "";

if(isset($_POST['submit'])) {
    $_GET['id'] = $_POST['id'];
    $updatetime = time();

    /*  ****************************************************************************
                     CLEANSE DATA
     **************************************************************************** */

    $formfield['title'] = trim(strtolower($_POST['title']));
    $formfield['description'] = trim($_POST['description']);

    /*  ****************************************************************************
            CHECK FOR EMPTY REQUIED FIELDS
    **************************************************************************** */

    if (empty($formfield['title'])) {
        $errormsg .= "<p>Your title is empty.</p>";
    }
    if (empty($formfield['description'])) {
        $errormsg .= "<p>Your description is empty.</p>";
    }



    /*  ****************************************************************************
    DISPLAY ERRORS
    **************************************************************************** */
    if($errormsg != "")
    {
        echo "<div class='error'><p>THERE ARE ERRORS!</p>";
        echo $errormsg;
        echo "</div>";
    }
    else {
        /*  ****************************************************************************
        INSERT INTO DATABASE TABLE
        **************************************************************************** */
        try {
            //enter data into database
            $sqlupdate = 'UPDATE content SET title = :title, description = :description, updateTime = :updateTime WHERE id = :id';
            $stmtupdate = $pdo->prepare($sqlupdate);
            $stmtupdate->bindvalue(':title', $formfield['title']);
            $stmtupdate->bindvalue(':description', $formfield['description']);
            $stmtupdate->bindvalue(':updateTime', $updatetime);
            $stmtupdate->bindvalue(':id', $_POST['id']);
            $stmtupdate->execute();
            //hide the form
            $showform = 0;
            echo "<div class='main_content_success'><p>Content Updated.</p>";
            echo "<p>Back to <a href='mycontent.php'>My Content</a></div>";
        }//try
        catch (PDOException $e) {
            echo "<p class= 'error'>THERE ARE ERRORS! REPOPULATING FORM WITH ORIGINAL VALUES.</p>";
            echo 'ERROR!!!' . $e->getMessage();
            exit();
        }//catch
    }//else errors
}//if submit

if($showform ==1) {
    try {
        $sql = 'SELECT * FROM content WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $_GET['id']);
        $stmt->execute();
        $row = $stmt->fetch();

        //populate the form from the result set
        ?>
        <form method="post" action="contentupdate.php" name="myform">
            <table>

                <tr>
                    <th><label for="title">Title:</label></th>
                    <td><input type="text" name="title" id="title" size="45" value="<?php echo $row['title']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <th><label for="description">Description:</label></th>
                    <td><textarea name="description" id="description"><?php echo $row['description']; ?></textarea></td>
                </tr>
                <tr>
                    <th><label for="submit">Submit Form</label></th>
                    <input type="hidden" name="origtitle" id="origtitle" value="<?php echo $row['title']; ?>"/>
                    <input type="hidden" name="id" id="id" value="<?php echo $row['id']; ?>"/>
                    <td><input type="submit" name="submit" id="submit" value="submit"/><input type="button" name="noupdate" value="Cancel" onClick="window.location = 'index.php'" /></td>
                </tr>
            </table>
        </form>
        <?php
    }//try
    catch (PDOException $e) {
        echo 'Error fetching users: <br />ERROR MESSAGE:<br />' . $e->getMessage();
        exit();
    }
}//if showform

include_once "footer.php";
?>