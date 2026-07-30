<!DOCTYPE html>
<html>
<head>
<title>Delete Customer</title>

<style>
body {
    font-family: Arial;
    margin: 0;
    width: 1500px;
    
  background-image:url("ni.jpg");
}

/* Header */
header {
    background: linear-gradient(to right, #088f62, #076e93);
    color: white;
    text-align: center;
    padding: 20px;
}

/* 🔵 NAVBAR */
.navbar {
    background: linear-gradient(to right, #011811bf, #022e216f);
}

.navbar ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
}

.navbar ul li {
    position: relative;
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

/* Form */
.form-box {
    width: 400px;
    margin: 30px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px gray;
}

input[type=text] {
    width: 95%;
    padding: 8px;
    margin: 8px 0;
}

.btn {
    padding: 10px;
    margin: 5px;
    border: none;
    cursor: pointer;
    width: 45%;
}

.delete {
    background: green;
    color: white;
}


h2 {
    text-align: center;
    color: red;
}
</style>
</head>

<body>

<header>
    <h1>City Catering Management System</h1>
</header>

<!-- 🔵 NAVBAR WITH HOME BUTTON -->
<div class="navbar">
<ul>
    <li><a href="hh.html">Home</a></li>
    <li><a href="ctable.php">View Customer</a></li>
    </ul>
</div>

<h2>Searched Customer Details for Deletion</h2>
<?php
$con = mysql_connect("localhost", "root", "");
mysql_select_db("citycatering", $con);

if(isset($_GET['id']))
{
    $r = $_GET['id'];

    $sql = "select * from custemer where id=$r";
    $rs = mysql_query($sql);

    if ($re = mysql_fetch_array($rs))
    {
?>

<div class="form-box">

<form method="post" action="custupdate.php">

<label>Id:</label>
<input type="text" name="id" readonly value="<?php echo $re[0]; ?>"><br>

<label>Customer Name:</label>
<input type="text" name="cname" value="<?php echo $re[1]; ?>"><br>

<label>Email:</label>
<input type="text" name="email" value="<?php echo $re[2]; ?>"><br>

<label>Phone Number:</label>
<input type="text" name="pho" value="<?php echo $re[3]; ?>"><br>

<label>Address:</label>
<input type="text" name="add" value="<?php echo $re[4]; ?>"><br>

<label>Password:</label>
<input type="text" name="pass" value="<?php echo $re[5]; ?>"><br><br>

<label>Status:</label>
<select name="status" >
    <option ><?php echo $re[6]; ?></option>
    <option  >New</option>
    <option  >Active</option>
    <option  >Rejected</option>

</select>
<br><br>
<input type="submit" value="Update">

</form>

</div>

<?php
    }
    else
    {
        echo "Invalid ID";
    }
}
else
{
    echo "No ID received";
}
?>

</body>
</html>