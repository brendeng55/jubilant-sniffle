<?php
include "style.php";
include "header.php";
require('connect.php');
session_start();

if(!isset($_SESSION['id']))
{
    echo '<p>This page requires you to <a href="login.php">log in</a>.';
    include_once "footer.php";
    exit();
}

//NECESSARY VARIABLES
$showform = 1;
$errormsg = "";

if(isset($_POST['submit'])) {

    //CLEANSE DATA
    $formfield['title'] = trim($_POST['title']);
    $formfield['description'] = trim($_POST['description']);
    $inputtime= time();
    $updatetime = time();
    $userid = $_SESSION['id'];

    //CHECKING FOR EMPTY FIELDS
    if (empty($formfield['title'])) {
        $errormsg .= "<p class='main_content_error'>TITLE IS MISSING.</p>";
    }
    if (empty($formfield['description'])) {
        $errormsg .= "<p class='main_content_error'>DESCRIPTION IS MISSING.</p>";
    }

    //DISPLAY ERRORS
    if ($errormsg != "") {
        echo "<p class='main_content_error'>There are errors:  <br> " . $errormsg . "</p>";
    } else {
        //SQL
        try {
            $sqlinsert = 'INSERT INTO content (title, description, inputtime, updatetime, userid) VALUES (:title, :description, :inputtime, :updatetime, :userid)';
            $stmtinsert = $pdo->prepare($sqlinsert);
            $stmtinsert->bindvalue(':title', $formfield['title']);
            $stmtinsert->bindvalue(':description', $formfield['description']);
            $stmtinsert->bindvalue(':inputtime', $inputtime);
            $stmtinsert->bindvalue(':updatetime', $updatetime);
            $stmtinsert->bindvalue(':userid', $userid);
            $stmtinsert->execute();
            $showform = 0;
            echo "<div class='main_content_success'><p>Content Added!</p>";
            echo "<p>Back to <a href='mycontent.php'>My Content</a></div>";
        } catch (PDOException $e) {
            echo 'ERROR!!!' . $e->getMessage();
            exit();
        }//catch
    }
}//end if
    if($showform ==1) {
    ?>
        <div class="form">

        <h1>Add Content</h1>
    <form method="post" action="addcontent.php" name="myform">
        <div class="field-wrap">
                <label for="title">Title:</label><input type="text" name="title" id="title" size="45" value="<?php echo $_POST['title']; ?>" />
        </div>
        <div class="field-wrap">
                <label for="description">Description:</label><textarea name="description" id="description"><?php echo $_POST['description']; ?></textarea>

        </div>
        <div class="field-wrap">
                <label for="submit">Submit Form: </label><input type="submit" name="submit" id="submit" value="submit"/>
                <input type="button" name="noupdate" value="Cancel" onClick="window.location = 'index.php'" />
        </div>

    </form>
</div>
    <?php
}
include "footer.php";
?>