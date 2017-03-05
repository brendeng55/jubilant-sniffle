<?php
include "style.php";
include "header.php";
require('connect.php');
session_start();

try{
    $sql = 'SELECT * FROM content ORDER BY title';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':title', $formfield['title']);
    $stmt->execute();
    $result = $stmt->fetchAll();
    echo '<h1>Content List</h1>';
    echo '<table>';
    echo '<tr><th>ID</th><th>Title</th><th colspan="3">Details</th></tr>';
    foreach ($result as $row)
    {
        echo '<tr>
                    <td>'.$row['id'] . '</td>
                    <td>'.$row['title'] . '</td>
                    <td><a href="contentdetails.php?id=' .$row['id'] .'">Details</a></td>
              </tr>';
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
