<?php
session_start();
?>
<div class="image">
<img class='banner' src="img/securityBanner.jpg" alt="security banner">

</div>
<ul class="nav">
    <li><a href="index.php">Home</a></li>
    <li><a href="register.php">Register</a></li>

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
    }


    ?>
    <hr class="menu_line">
</ul>