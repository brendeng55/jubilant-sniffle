<html>
<?php include "style.php" ;?>
<body class="main_content">


    <nav>
        <?php include "menu.php" ;?>
    </nav>

    <?php
    if(!isset($_SESSION['uname']) OR $_SESSION['uname']=="")
    {
        echo '<p>This is my security project. Please register and login.</p>';
    }
    else
    {
        echo '<p>Thanks for logging in ' . $_SESSION['uname'] . '</p>';

    }


    ?>

    <header>
        <h1>Features</h1>
    </header>

    <div id="features">

        <section id="content">
            <h2 id="feature_header">Register</h2>
            <p><a href="register.php">Register</a> by entering your information. Your username and email address must be unique. Choose your own security question and make sure you remember the answer, you'll need it if you ever forget your password.</p>
        </section>

        <section id="content">
            <h2 id="feature_header">Login</h2>
            <p><a href="login.php">Login</a> using your username and password. Don't worry, your password is encrypted before being stored in the database. So even if I do get hacked, you're still good.</p>
        </section>

        <section id="content">
            <h2 id="feature_header">Password Recovery</h2>
            <p>Forgot your password? It happens to the best of us. Just click <a href="forgot_password.php">here</a> and enter the email address you signed up with. You will recieve an email immeditaley with a link to reset your password.</p>
        </section>
        <section id="content">
            <h2 id="feature_header">Username Recovery</h2>
            <p>Forgot your username? Just enter your <a href="forgotuname.php">email</a> address and it will get mailed to you.</p>
        </section>

    </div>

<?php include "footer.php"; ?>

</body>
</html>