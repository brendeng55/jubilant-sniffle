<?php
include "style.php";
include "header.php";
require('connect.php');
session_start();
$showform = 1;
$errormsg = "";

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
    /*  ****************************************************************************
                     GET AND CLEANSE DATA
     **************************************************************************** */

    if ($_POST['uservalue'] === '1') {
        $formfield['usertype'] = 1;
    }
    elseif ( $_POST['uservalue'] === '0' ) {
        $formfield['usertype'] = 0;
    }
    else{
        $formfield['usertype'] = "";
    }

    $formfield['id'] = trim($_POST['id']);

    /*  ****************************************************************************
            CHECK FOR EMPTY REQUIED FIELDS
    **************************************************************************** */
    if (empty($formfield['id'])) {
        $errormsg .= "<p class='main_content_error'>Enter an ID to update.</p>";
    }
    if ($formfield['usertype'] === "") {
        $errormsg .= "<p class='main_content_error'>Choose user access.</p>";
    }


    /*  ****************************************************************************
    DISPLAY ERRORS
    **************************************************************************** */
    if($errormsg != "")
    {
        echo "<div class='main_content_error'><p>THERE ARE ERRORS!</p>";
        echo $errormsg;
        echo "</div>";
    }
    else {
        /*  ****************************************************************************
        UPDATE DATABASE TABLE
        **************************************************************************** */
        try {
            //enter data into database
            $sqlupdate = 'UPDATE users SET usertype = :usertype WHERE ID = :ID';
            $stmtupdate = $pdo->prepare($sqlupdate);
            $stmtupdate->bindvalue(':usertype', $formfield['usertype']);
            $stmtupdate->bindvalue(':ID', $formfield['id']);
            $stmtupdate->execute();
            //hide the form
            //$showform = 0;
            echo "<div><p class='main_content_success'>Update completed.</p></div>";
            //echo "<p>Back to <a href='usermanagement.php'>User Management</a>";
        }//try
        catch (PDOException $e) {
            echo "<p class= 'main_content_error'>THERE ARE ERRORS! REPOPULATING FORM WITH ORIGINAL VALUES.</p>";
            echo 'ERROR!!!' . $e->getMessage();
            exit();
        }//catch
    }//else errors
}//if submit
if($showform ==1) {
    try {
        $sql = 'SELECT * FROM users';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        echo '<table>';
        echo '<tr><th>ID</th><th>User</th><th>Type</th></tr>';
        foreach ($result as $row)
        {
            if ($row['usertype'] == 1) $usertype = "Admin";
            else $usertype = "User";
            echo '<tr>
                    <td>'.$row['id'] . '</td>
                    <td>'.$row['uname'] . '</td>
                    <td>'.$usertype . '</td>

              </tr>';
        }
        echo '</table>';

    }//try
    catch (PDOException $e)
    {
        echo 'Error fetching users: <br />ERROR MESSAGE:<br />' .$e->getMessage();
        exit();
    }
    ?>

    <div class="form">

    <h1>Update User Access</h1>

    <form action="usermanagement.php" method="post" name="usermanagement">
        <div class="field-wrap">
            <label for="id" >User ID:<span class="req">*</span></label>
            <input type="text" name="id" value="<?php echo $formfield['id']; ?>" required autocomplete="off"/>
        </div>
        <div class="field-wrap">
            <label>User Access:<span class="req">*</span></label>
            <select name="uservalue">
                <option></option>
                <option value="0">User</option>
                <option value="1">Admin</option>
            </select>
        </div>

        <input class="button" name="submit" type="submit" value="Update" />
    </form>
</div>
<?php
    }
    include "footer.php";
    ?>