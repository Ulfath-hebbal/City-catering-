 <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Owner | City Catering Admin</title>

<style>
    /* BASE THEME FLOW */
    :root {
        --primary: #088f62;
        --secondary: #076e93;
        --accent: #f97316;
        --dark: #011811;
        --light: #fff7ed;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, sans-serif; }

    body { 
        background: var(--light); 
        color: #333; 
        min-height: 100vh;
    }

    /* Header Branding */
    header {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        text-align: center;
        padding: 40px 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    header h1 {
        font-family: Forte, cursive;
        font-size: 2.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    /* Navbar Glassmorphism */
    .navbar {
        background: rgba(1, 24, 17, 0.9);
        backdrop-filter: blur(10px);
        display: flex;
       
        position: sticky;
        top: 0;
        z-index: 1000;
        border-bottom: 2px solid #76dadf;
    }

    .navbar ul { list-style: none; display: flex; }
    .navbar ul li a {
        display: block;
        padding: 18px 25px;
        color: white;
        text-decoration: none;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 0.9rem;
        transition: 0.3s;
    }

    .navbar ul li a:hover {
        background: #76dadf;
        color: var(--dark);
    }

    /* Main Heading */
    h2 {
        text-align: center;
        color: var(--secondary);
        margin-top: 40px;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 800;
    }

    /* Form Card Container */
    .form-box {
        width: 100%;
        max-width: 600px;
        margin: 30px auto 80px;
        background: white;
        padding: 45px;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        border-top: 10px solid var(--accent);
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    label {
        display: block;
        margin-top: 20px;
        font-weight: 800;
        color: var(--dark);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Input & Select Styling */
    input[type=text], select {
        width: 100%;
        padding: 14px;
        margin-top: 8px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        font-size: 15px;
        transition: 0.3s;
        outline: none;
    }

    input[readonly] {
        background: #f1f5f9;
        color: #94a3b8;
        border-style: dashed;
        cursor: not-allowed;
    }

    input:focus, select:focus {
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 10px rgba(8, 143, 98, 0.1);
    }

    /* Update Button */
    input[type="submit"] {
        width: 100%;
        background: var(--primary);
        color: white;
        border: none;
        padding: 18px;
        margin-top: 35px;
        border-radius: 12px;
        cursor: pointer;
        font-size: 17px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.4s;
        box-shadow: 0 5px 15px rgba(8, 143, 98, 0.3);
    }

    input[type="submit"]:hover {
        background: var(--dark);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(8, 143, 98, 0.4);
    }

    .error-msg {
        text-align: center;
        background: #fee2e2;
        color: #b91c1c;
        padding: 15px;
        border-radius: 10px;
        max-width: 400px;
        margin: 20px auto;
        font-weight: bold;
    }

</style>
</head>

<body>

<header>
    <h1>City Catering Admin Dashboard</h1>
</header>

<div class="navbar">
<ul>
    <li><a href="adminf.html">Back</a></li>
    <li><a href="otable.php">Owners Management</a></li>
    <li><a href="ctable.php">Customers Management</a></li>
</ul>
</div>

<h2>Owner Approval & Account Settings</h2>

<?php
// 1. DATABASE CONNECTION (Using mysqli for modern compatibility)
$con = mysqli_connect("localhost", "root", "", "citycatering");

if(!$con) { die("Connection failed: " . mysqli_connect_error()); }

if(isset($_GET['id']))
{
    $r = mysqli_real_escape_string($con, $_GET['id']);
    $sql = "select * from owner where oid='$r'";
    $rs = mysqli_query($con, $sql);

    if ($re = mysqli_fetch_array($rs))
    {
?>

<div class="form-box">
    <form method="post" action="ownupdate.php">
        
        <label>System Owner ID (Read Only)</label>
        <input type="text" name="id" readonly value="<?php echo $re[0]; ?>">

        <label>Full Name</label>
        <input type="text" name="cname" value="<?php echo $re[1]; ?>">

        <label>Email Address</label>
        <input type="text" name="email" value="<?php echo $re[2]; ?>">

        <label>Mobile Number</label>
        <input type="text" name="pho" value="<?php echo $re[3]; ?>">

        <label>Business Address</label>
        <input type="text" name="add" value="<?php echo $re[4]; ?>">

        <label>Catering Brand Name</label>
        <input type="text" name="csn" value="<?php echo $re[5]; ?>">

        <label>User Password</label>
        <input type="text" name="pass" value="<?php echo $re[6]; ?>">

        <label>Verification Status</label>
        <select name="status">
            <option value="<?php echo $re[7]; ?>">Currently: <?php echo $re[7]; ?></option>
            <option value="New">New (Pending)</option>
            <option value="Active">Active (Approved)</option>
            <option value="Rejected">Rejected (Disabled)</option>
        </select>

        <input type="submit" value="Save Changes & Approve">
    </form>
</div>

<?php
    }
    else { echo "<div class='error-msg'>⚠️ Invalid Owner ID: Record not found.</div>"; }
}
else { echo "<div class='error-msg'>⚠️ No ID received for update. Please go back to Owner List.</div>"; }
?>

</body>
</html>
