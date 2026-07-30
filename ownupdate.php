 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Update | City Catering Admin</title>
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Branding */
        header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            text-align: center;
            padding: 30px 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        header h1 { font-family: Forte, cursive; font-size: 2.2rem; }

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
            display: block; padding: 18px 25px;
            color: white; text-decoration: none; font-weight: bold;
            text-transform: uppercase; font-size: 0.9rem; transition: 0.3s;
        }
        .navbar ul li a:hover { background: #76dadf; color: var(--dark); }

        /* Result Card */
        .result-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .message-card {
            background: white;
            width: 100%;
            max-width: 500px;
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            text-align: center;
            border-top: 10px solid var(--primary);
            animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes popIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            background: #ecfdf5;
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 45px;
            margin: 0 auto 25px;
            border: 2px solid var(--primary);
        }

        h2 { color: var(--dark); margin-bottom: 12px; font-weight: 800; }
        p { color: #64748b; margin-bottom: 30px; line-height: 1.6; }

        /* Action Buttons */
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn {
            padding: 14px 25px;
            text-decoration: none;
            border-radius: 12px;
            font-weight: bold;
            font-size: 14px;
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-view { background: var(--secondary); color: white; box-shadow: 0 4px 15px rgba(7, 110, 147, 0.2); }
        .btn-view:hover { background: var(--dark); transform: translateY(-2px); }

        .btn-home { background: #f1f5f9; color: #475569; }
        .btn-home:hover { background: #e2e8f0; }

        footer {
            background: var(--dark);
            color: #fed7aa;
            text-align: center;
            padding: 30px;
            margin-top: auto;
            border-top: 4px solid var(--accent);
        }
    </style>
</head>

<body>

<header>
    <h1>City Catering Admin Dashboard</h1>
</header>

<nav class="navbar">
    <ul>
        <li><a href="adminf.html">Back</a></li>
        <li><a href="otable.php">Owner Table</a></li>
    </ul>
</nav>

<div class="result-container">
    <div class="message-card">
        <?php
        // 1. DATABASE CONNECTION (Using mysqli for compatibility)
        $con = mysqli_connect("localhost", "root", "", "citycatering");

        if (!$con) { die("Connection failed: " . mysqli_connect_error()); }

        if(isset($_POST['id']))
        {
            $id = mysqli_real_escape_string($con, $_POST['id']);   
            $name = mysqli_real_escape_string($con, $_POST['cname']);
            $email = mysqli_real_escape_string($con, $_POST['email']);
            $pho = mysqli_real_escape_string($con, $_POST['pho']);
            $add = mysqli_real_escape_string($con, $_POST['add']);
            $csn = mysqli_real_escape_string($con, $_POST['csn']);
            $pass = mysqli_real_escape_string($con, $_POST['pass']);
            $st = mysqli_real_escape_string($con, $_POST['status']);

            $sql= "UPDATE owner SET oname='$name',
                    email='$email',
                    phno='$pho',
                    oadd='$add',
                    csname='$csn',
                    pass='$pass',
                    status='$st'
                    WHERE oid='$id'";

            if(mysqli_query($con, $sql)){
                echo "<div class='icon-circle'>✔</div>";
                echo "<h2>Update Successful!</h2>";
                echo "<p>Owner <b>#$id ($csn)</b> details and status have been synchronized with the database.</p>";
            } else {
                echo "<div class='icon-circle' style='color:#dc2626; background:#fee2e2; border-color:#dc2626;'>✖</div>";
                echo "<h2>Update Failed</h2>";
                echo "<p style='color:#dc2626;'>Error: " . mysqli_error($con) . "</p>";
            }
        }
        ?>
        
        <div class="btn-group">
            <a href="otable.php" class="btn btn-view">Owner List</a>
            <a href="home.php" class="btn btn-home">Home</a>
        </div>
    </div>
</div>

<footer>
    <p>© 2026 City Catering Management System | Administrator Portal</p>
</footer>

</body>
</html>
