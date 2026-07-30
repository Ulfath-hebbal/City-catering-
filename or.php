 <!DOCTYPE html>
<html>
<head>
    <title>Registration Status - City Catering</title>
    <style>
        /* Base Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
        
        body { 
            background: #fff7ed; 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
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
            padding: 10px 5%;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        /* Success Box Container */
        .box {
            width: 450px;
            background: white;
            margin: 60px auto;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            border-top: 8px solid #16a34a; /* Success Green */
        }

        .success-icon {
            font-size: 60px;
            color: #16a34a;
            margin-bottom: 15px;
        }

        h2 { color: #14532d; margin-bottom: 10px; }

        /* Information Card */
        .info-card {
            background: #f0fdf4;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border: 1px solid #bbf7d0;
            text-align: left;
        }

        .info-card p {
            margin: 8px 0;
            font-size: 15px;
            color: #333;
        }

        .info-card b { color: #14532d; }
        .highlight { color: #076e93; font-weight: bold; }

        .status-text {
            color: #f97316; /* Orange for Pending */
            font-weight: bold;
        }

        /* Home Button */
        .btn-home {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 30px;
            background: #076e93;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-home:hover { background: #088f62; transform: scale(1.05); }

    </style>
</head>

<body>

<header>
    <h1>City Catering Management System</h1>
</header>

<div class="navbar">
    <a href="home.php">← Back to Home</a>
</div>

<div class="box">
    <?php 
    $con = mysql_connect("localhost","root","");
    mysql_select_db("citycatering",$con);

    
    $sqll = "select cnt from ocout";
    $rs = mysql_query($sqll);
    $row = mysql_fetch_array($rs);
    $id = $row['cnt'];
    $i = "CCS100" . $id;

    $n = $_POST["uname"];
    $e = $_POST["mail"];
    $cn = $_POST["num"];
    $ad = $_POST["area"];
    $sd = $_POST["scadd"];
    $pass = $_POST['pass'];
    $m = "New";   


    $folder = ""; 
    if(isset($_FILES['cat_img']) && $_FILES['cat_img']['error'] == 0) {
        $img_name = $_FILES['cat_img']['name'];
        $img_tmp = $_FILES['cat_img']['tmp_name'];
        if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
        $folder = "uploads/" . time() . "_" . $img_name;
        move_uploaded_file($img_tmp, $folder);
    }


    $sqll = "INSERT INTO owner VALUES('$i','$n','$e','$cn','$ad','$sd','$pass','$m','$folder')";
    $res = mysql_query($sqll);

    if($res) {
        echo "<div class='success-icon'>✔</div>";
        echo "<h2>Registration Successful!</h2>";
        echo "<p style='color: #666; font-size: 14px;'>Welcome to our network.</p>";
        
        echo "<div class='info-card'>";
        echo "<p><b>Owner ID:</b> <span class='highlight'>" . $i . "</span></p>";   
        echo "<p><b>Password:</b> <span class='highlight'>" . $pass . "</span></p>";
        echo "<p><b>Status:</b> <span class='status-text'>Pending Approval</span></p>";
        echo "</div>";

        echo "<p style='font-size: 12px; color: #888;'>Please save your credentials for login.</p>";

        
        $new = $id + 1;
        mysql_query("update ocout set cnt='$new'");
    } else {
    
        echo "<h2 style='color: #ef4444;'>Database Error</h2>";
        echo "<p style='color: #666; padding: 10px;'>" . mysql_error() . "</p>";
    }
    ?>

    <a href="home.php" class="btn-home">Return to Home</a>
</div>

</body>
</html>
