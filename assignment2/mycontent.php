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

try
{
    $sql = 'SELECT * FROM content WHERE userid = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->fetchAll();

    echo '<h1>My Content</h1>';
    echo '<table>';
    foreach ($result as $row)
    {
        //original post date conversion
        $unixPostDate = $row['inputTime'];
        $formattedPostDate = gmdate("m-d-Y", $unixPostDate);

        //update post date conversion
        $unixUpdateDate = $row['updateTime'];
        $formattedUpdateDate = gmdate("m-d-Y", $unixUpdateDate);

    echo        '   <tr><td><th>ID:</th></td><td>'. $row['id'] . '</td></tr>
					<tr><td><th>Title:</th></td><td>'. $row['title'] . '</td></tr>
					<tr><td><th>Description:</th></td><td>'. $row['description'] . '</td></tr>
					<tr><td><th>Originally Posted:</th></td><td>'. $formattedPostDate . '</td></tr>
					<tr><td><th>Last Updated:</th></td><td>'. $formattedUpdateDate . '</td></tr>
					<tr><td><th>Options:</th></td>
					<td><a href="contentupdate.php?id=' .$row['id'] .'">Update</a>
                    <a href="contentdelete.php?id=' .$row['id'] .'">Delete</a></td></tr>
					';
    }
    echo '</table>';
}//try
catch (PDOException $e)
{
    echo 'Error fetching users: <br />ERROR MESSAGE:<br />' .$e->getMessage();
    exit();
}

include "footer.php";
?>