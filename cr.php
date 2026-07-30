<!DOCTYPE html>
<html>
<head>
<title>Customer Registration</title>

<style>
body {
    font-family: Arial;
    margin: 0;
    background: #f5f5f5;
}

/* Header */
header {
    background: linear-gradient(to right, #088f62, #076e93);
    color: white;
    text-align: center;
    padding: 20px;
}

/* Navbar */
.navbar {
    background: linear-gradient(to right, #011811bf, #022e216f);
}

.navbar ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
}

.navbar ul li a {
    display: block;
    padding: 15px 20px;
    color: white;
    text-decoration: none;
}

.navbar ul li a:hover {
    background: rgba(220, 250, 242, 0.764);
    color: brown;
}

/* Result Box */
.box {
    width: 400px;
    margin: 40px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px gray;
    text-align: center;
}

.success {
    color: green;
    font-size: 18px;
}

</style>

</head>

<body>

<header>
    <h1>City Catering Management System</h1>
</header>

<div class="navbar">
<ul>
    <li><a href="hh.html">Home</a></li>
   
</ul>
</div>

<div class="box">

<?php 

$con = mysql_connect("localhost","root","");
mysql_select_db("citycatering",$con);

/* Get ID */
$rs = mysql_query("SELECT * FROM cout");
$row = mysql_fetch_array($rs);
$id = $row[0];

/* Form data */
$n = $_POST["uname"];
$e = $_POST["mail"];
$cn = $_POST["num"];
$ad = $_POST["area"];

/* Safe password */
$pass = strtoupper($n[0]) . $id . strtoupper($n[3]) . strtoupper($e[2]) . strtoupper($ad[1]);

/* Display */
echo "<div class='success'>";
echo "<br>User Name: ".$n;
echo "<br>Your Password: ".$pass;
echo "<br>Added Successfully";
echo "</div>";

/* Insert */
$m="New";
$sql="INSERT INTO custemer VALUES('$id','$n','$e','$cn','$ad','$pass','$m')";
mysql_query($sql);

/* Update ID */
$id = $id + 2;
mysql_query("UPDATE cout SET cnt='$id'");

?>

<br><br>
<a href="hh.html">Go to Home</a>

</div>

</body>
</html>