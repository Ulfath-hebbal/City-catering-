<?php
session_start();
$con = mysql_connect("localhost", "root", "");
mysql_select_db("citycatering", $con);

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "UPDATE orders SET status = 'Approved' WHERE id = '$id'";
    if(mysql_query($sql)) {
        header("Location: viewordres.php");
    } else {
        echo "Error updating record: " . mysql_error();
    }
}
?>
