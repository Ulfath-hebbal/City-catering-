 <!DOCTYPE html>
<html>
<head>
<title>Delete Customer - City Catering</title>

<style>
/* Base Reset */
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }

body { 
    background: #fff7ed; 
    color: #333; 
}

/* Header Branding */
header {
    background: linear-gradient(to right, #088f62, #076e93);
    color: white;
    text-align: center;
    padding: 20px;
    font-family: Forte, cursive;
}

/* Navbar */
.navbar {
    background: linear-gradient(to right, #011811bf, #022e216f);
    display: flex;
    padding-left: 20px;
}

.navbar ul {
    list-style: none;
    display: flex;
}

.navbar ul li a {
    display: block;
    padding: 15px 25px;
    color: white;
    text-decoration: none;
    font-weight: bold;
}

.navbar ul li a:hover {
    background: #76dadf;
    color: #011811;
}

/* Main Heading */
h2 {
    text-align: center;
    color: #b91c1c; /* Darker red for delete warning */
    margin-top: 30px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Updated Form Box */
.form-box {
    width: 450px;
    margin: 30px auto 60px;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-top: 8px solid #ef4444; /* Red accent for deletion */
}

label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
    color: #14532d;
    font-size: 14px;
}

/* Input Styling */
input[type=text] {
    width: 100%;
    padding: 12px;
    margin-top: 5px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f9f9f9;
    font-size: 14px;
}

input[readonly] {
    background: #eee;
    color: #666;
    cursor: not-allowed;
}

/* Delete Button */
input[type="submit"] {
    width: 100%;
    background: #ef4444; /* Bright Red */
    color: white;
    border: none;
    padding: 15px;
    margin-top: 25px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    text-transform: uppercase;
    transition: 0.3s;
}

input[type="submit"]:hover {
    background: #dc2626;
    transform: scale(1.02);
    box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4);
}

.warning-text {
    background: #fee2e2;
    color: #991b1b;
    padding: 10px;
    border-radius: 5px;
    font-size: 13px;
    margin-bottom: 15px;
    text-align: center;
    border: 1px solid #fecaca;
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
    <li><a href="ctable.php">View Customer</a></li>
</ul>
</div>

<h2>Confirm Customer Deletion</h2>

<?php
$con = mysql_connect("localhost", "root", "");
mysql_select_db("citycatering", $con);

if(isset($_GET['id']))
{
    $r = $_GET['id'];
    // Fixed: Standardized query to match your table
    $sql = "select * from custemer where id='$r'";
    $rs = mysql_query($sql);

    if ($re = mysql_fetch_array($rs))
    {
?>

<div class="form-box">
    <div class="warning-text">
         Warning: This action cannot be undone.
    </div>

    <form method="post" action="custdelete.php">

    <label>Customer ID:</label>
    <input type="text" name="id" readonly value="<?php echo $re[0]; ?>">

    <label>Full Name:</label>
    <input type="text" name="cname" readonly value="<?php echo $re[1]; ?>">

    <label>Email Address:</label>
    <input type="text" name="email" readonly value="<?php echo $re[2]; ?>">

    <label>Phone Number:</label>
    <input type="text" name="pho" readonly value="<?php echo $re[3]; ?>">

    <label>Location:</label>
    <input type="text" name="add" readonly value="<?php echo $re[4]; ?>">

    <input type="submit" value="Confirm Permanent Delete">

    </form>
</div>

<?php
    }
    else { echo "<center><p style='color:red; margin-top:20px;'>Error: Invalid Customer ID</p></center>"; }
}
else { echo "<center><p style='color:red; margin-top:20px;'>No ID received</p></center>"; }
?>

</body>
</html>
