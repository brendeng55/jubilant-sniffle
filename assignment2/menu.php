<?php
session_start();
?>
<div class="image">
<img class='banner' src="img/securityBanner.jpg" alt="security banner">

</div>
<ul class="nav">
    <li><a href="index.php">Home</a></li>
    <li><a href="register.php">Register</a></li>
    <li><a href="contentlist.php">Content List</a></li>

    <?php
    if(!isset($_SESSION['uname']) OR $_SESSION['uname']=="")
    {
        echo '<li><a href="login.php">Log In</a></li>';
        echo '<li><a href="forgotuname.php">Forgot Username</a></li>';
        echo '<li><a href="forgot_password.php">Forgot Password</a></li>';
    }
    else
    {
        echo '<li><a href="logout.php">Log Out</a></li>';
        echo '<li><a href="changepassword.php">Change Password</a></li>';
        echo '<li><a href="mycontent.php">My Content</a></li>';
        echo '<li><a href="addcontent.php">Add Content</a></li>';
    }
    if ($_SESSION['usertype'] == 1){
        echo '<li><a href="managecontent.php">Manage Content</a></li>';
        echo '<li><a href="usermanagement.php">Manage Users</a></li>';

    }


    ?>
    <hr class="menu_line">
</ul>