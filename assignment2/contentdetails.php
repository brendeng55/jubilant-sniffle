<?php
include "style.php";
include "header.php";
require('connect.php');
session_start();


try
{
    $sql = 'SELECT * FROM content WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_GET['id']);
    $stmt->execute();
    $result = $stmt->fetch();

    //original post date conversion
    $unixPostDate = $result['inputTime'];
    $formattedPostDate = gmdate("m-d-Y", $unixPostDate);

    //update post date conversion
    $unixUpdateDate = $result['updateTime'];
    $formattedUpdateDate = gmdate("m-d-Y", $unixUpdateDate);

    echo '<table>';
    //foreach ($result as $row)
    //{
        echo    '   <tr><td><th>ID:</th></td><td>'. $result['id'] . '</td></tr>
					<tr><td><th>Title:</th></td><td>'. $result['title'] . '</td></tr>
					<tr><td><th>Description:</th></td><td>'. $result['description'] . '</td></tr>
					<tr><td><th>Originally Posted:</th></td><td>'. $formattedPostDate . '</td></tr>
					<tr><td><th>Last Updated:</th></td><td>'. $formattedUpdateDate . '</td></tr>';
    //}
    echo '</table>';
    echo '<div id="centerMe"><a href="contentlist.php">Back</a></div>';
}//try
catch (PDOException $e)
{
    echo 'Error fetching users: <br />ERROR MESSAGE:<br />' .$e->getMessage();
    exit();
}

include "footer.php";
?>
