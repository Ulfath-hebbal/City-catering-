<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Catering</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* HEADER */
header {
    background: linear-gradient(to right, #088f62, #076e93);
    color: white;
    text-align: center;
    padding: 20px;
}

/* NAVBAR */
.navbar {
    background: linear-gradient(to right, #011811bf, #022e216f);
}

.navbar ul {
    list-style: none;
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

/* CONTENT */
.container {
    text-align: center;
    padding: 50px;
    background-image: url("ni.jpg");
    height: 500px;
    color: white;
}

/* FOOTER */
footer {
    background: #333;
    color: white;
    text-align: center;
    padding: 10px;
}
</style>

</head>

<body>

<header>
    <h1>City Catering System</h1>
</header>

<!-- ✅ NAVBAR -->
<nav class="navbar">
    <ul>
        <li><a href="home.html">Home</a></li>
        <li><a href="adminf.html">Back</a></li>
    </ul>
</nav>

<div class="container">

<h2>Student Details Updation Saving Form</h2>
<hr><br>
<?php
$con = mysql_connect("localhost","root","");
mysql_select_db("citycatering",$con);


if(isset($_POST['id']))
{
    $id = $_POST['id'];   
    $name = $_POST['cname'];
    $email = $_POST['email'];
    $pho = $_POST['pho'];
    $add = $_POST['add'];
    $pass = $_POST['pass'];
    $st = $_POST['status'];
    $sql= "update custemer set cname='$name',email='$email', phnum='$pho',custadd='$add',pass='$pass',status='$st' where id=$id ";
    mysql_query($sql);

    echo "<h3 style='color:green;'>Updated Successfully</h3>";
}
?>
</div>

</body>
</html>