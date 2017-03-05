<?php
/* CREATE A CONNECTION TO THE SERVER */
$dsn = 'mysql:host=localhost;dbname=cs409bmgoldman';
$user = 'cs409bmgoldman';
$pwd = 'csci409sp17!';
try{
    $pdo = new PDO($dsn,$user,$pwd);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    echo 'ERROR connecting to database! ' . $e->getMessage();
    exit();
}
?>
