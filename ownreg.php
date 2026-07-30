 <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Owner Registration - City Catering</title>

<style>

* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
body { background: #fff7ed; }

header { background: linear-gradient(to right, #088f62, #076e93); color: white; text-align: center; padding: 20px; font-family: Forte, cursive; }
.logo { width: 80px; height: 80px; border-radius: 50%; border: 3px solid white; display: block; margin: 0 auto 10px; }


.navbar { background: linear-gradient(to right, #011811bf, #022e216f); display: flex; justify-content: flex-start; padding-left: 20px; }
.navbar ul { list-style: none; display: flex; }
.navbar ul li a { display: block; padding: 15px 25px; color: white; text-decoration: none; font-weight: bold; }
.navbar ul li a:hover { background: #76dadf; color: #011811; }

.formbox { width: 550px; background: white; padding: 30px; margin: 40px auto; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-top: 8px solid #f97316; }
h2 { text-align: center; margin-bottom: 25px; color: #076e93; text-transform: uppercase; font-size: 22px; letter-spacing: 1px; }

label { font-weight: bold; color: #14532d; display: block; margin-top: 15px; font-size: 14px; }
input[type="text"], input[type="email"], input[type="tel"], input[type="password"], input[type="file"], textarea {
    width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #f9f9f9;
}
input:focus, textarea:focus { outline: none; border-color: #f97316; background: #fff; }
textarea { resize: none; height: 80px; }


.btn-group { display: flex; gap: 10px; margin-top: 25px; }
input[type="submit"] { flex: 2; background: #16a34a; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 16px; transition: 0.3s; }
input[type="submit"]:hover { background: #15803d; }
input[type="reset"] { flex: 1; background: #ef4444; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }

footer { background: #011811; color: #fed7aa; text-align: center; padding: 15px; width: 100%; }
</style>

<script>
function validateForm() {
    var name = document.forms["regForm"]["uname"].value;
    var phone = document.forms["regForm"]["num"].value;
    var namePattern = /^[A-Za-z\s]+$/;
    var phonePattern = /^[0-9]{10}$/;

    
    if (!name.match(namePattern)) {
        alert("Owner Name should only contain alphabets.");
        return false;
    }

    
    if (!phone.match(phonePattern)) {
        alert("Phone Number must be exactly 10 digits.");
        return false;
    }

    return true;
}
</script>

</head>
<body>

<header>
    <img src="hg.jpeg" alt="Catering Logo" class="logo">
    <h1>City Catering Management System</h1>
</header>

<div class="navbar">
    <ul><li><a href="home.php">Home</a></li></ul>
</div>

<div class="formbox">
    <h2>Owner Registration</h2>

    <form name="regForm" method="post" action="or.php" enctype="multipart/form-data" onsubmit="return validateForm()">

        <?php
         
            $con = mysqli_connect("localhost","root","","citycatering");
            $rs = mysqli_query($con, "select * from ocout");
            $record = mysqli_fetch_array($rs);
            $id = $record[0];
            $i = "CCS100".$id;
        ?>

        <label>Generated Owner ID</label>
        <input type="text" value="<?php echo $i; ?>" disabled style="background:#eee; font-weight:bold; color:#076e93;">

        <label>Owner Name</label>
        <input type="text" name="uname" placeholder="Enter Full Name" required>

        <label>Email Address</label>
        <input type="email" name="mail" placeholder="owner@example.com" required>

        <label>Phone Number</label>
        <input type="tel" name="num" placeholder="10-digit number" required>

        <label>Owner Address</label>
        <textarea name="area" placeholder="Enter detailed address..." required></textarea>

        <label>Catering Service Name</label>
        <input type="text" name="scadd" placeholder="E.g. Royal Catering" required>

        <label>Catering Service Image</label>
        <input type="file" name="cat_img" accept="image/*" required>
        <small style="color: #666; font-size: 11px;">Upload a high-quality photo.</small>

        <label>Create Password</label>
        <input type="password" name="pass" placeholder="Minimum 6 characters" required>

        <div class="btn-group">
            <input type="submit" value="Register Now">
            <input type="reset" value="Clear">
        </div>

    </form>
</div>

<footer>
    <p>© 2026 City Catering Management System | Managed by Administrator</p>
</footer>

</body>
</html>
