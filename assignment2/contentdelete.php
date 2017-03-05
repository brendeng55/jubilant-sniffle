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

$errormsg = "";
$showform = 1;

//ONCE WE HAVE PRESSED SUBMIT, DO SOMETHING....
if(isset ($_POST['delete']) && $_POST['delete'] == "YES")
{

    try
    {
        $sql = 'DELETE FROM content WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']); // using data from form
        $s->execute();
    }
    catch(PDOException $e)
    {
        echo 'Error deleting from database ' . $e->getMessage();
        exit();
    }
    //confirmation
    echo '<div id="centerMe>"<p>The item number: ' . $_POST['id'] . ' has been deleted.</p></div>';
    $showform=0;
}

if($showform == 1)
{


    try
    {
        $sql = 'SELECT * FROM content WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_GET['id']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        echo 'Error fetching users: ' . $e->getMessage();
        exit();
    }

    $row = $s->fetch();
    echo '<div id="centerMe">Are you sure you want to delete book No. ' . $_GET['id'];
    echo ' (' . $row['title'] . ')?</div>';

    ?>
    <?php echo '<div id="centerMe">'?>
    <form name="contentdelete" id="contentdelete" method="post" action="contentdelete.php">
        <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
        <input type="submit" name="delete" value="YES">
        <input type="button" name="nodelete" value="NO" onClick="window.location = 'mycontent.php'" />
    </form>
    <?php echo '</div>'?>
    <?php


}//showform
include "footer.php";
?>