  <?php
session_start();

/* 1. DATABASE CONNECTION */
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) {
    die("Connection Failed: " . mysqli_connect_error());
}

/* 2. PROCESSING LOGIN LOGIC */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $n = mysqli_real_escape_string($con, $_POST["id"]);
    $p = mysqli_real_escape_string($con, $_POST["pass"]);

    $sql = "SELECT * FROM owner WHERE oid='$n' AND pass='$p'";
    $rs = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_array($rs)) {
        $_SESSION['oid'] = $row['oid'];
        $_SESSION['owner_name'] = $row['oname'];

        if ($row['status'] == "Active") {
            header("location:ownmain.php");
            exit();
        } else if ($row['status'] == "Rejected") {
            echo "<script>alert('Account Rejected Due To Some Reason'); window.location='ownlog.php';</script>";
        } else if ($row['status'] == "New") {
            echo "<script>alert('You are a new user waiting for approval'); window.location='ownlog.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid Login - Please Register'); </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Login | City Catering</title>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        :root {
            --primary: #088f62;
            --secondary: #076e93;
            --accent: #f97316;
            --dark: #011811;
            --light: #fff7ed;
            --glass: rgba(1, 24, 17, 0.85);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, sans-serif; }
        
        body { 
            background: var(--light); 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header { 
            background: linear-gradient(135deg, var(--primary), var(--secondary)); 
            color: white; text-align: center; padding: 40px 10px 30px 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        header h1 { font-family: 'Forte', sans-serif; font-size: 2.8rem; text-shadow: 2px 2px 5px rgba(0,0,0,0.2); }

        .logo { 
            width: 100px; height: 100px; border-radius: 50%; 
            border: 4px solid rgba(255,255,255,0.3); margin-bottom: 10px;
            transition: transform 0.6s ease; object-fit: cover;
        }
        .logo:hover { transform: rotate(360deg); }

        .navbar { 
            background: var(--glass); 
            backdrop-filter: blur(12px);
            display: flex; height: 65px; align-items: center;
            position: sticky; top: 0; z-index: 1000;
        }

        .navbar a { color: white; text-decoration: none; font-weight: bold; text-transform: uppercase; font-size: 0.9rem; }

        .login-container { flex: 1; display: flex; justify-content: center; align-items: center; padding: 40px 20px; }

        .login-card { 
            background: white; width: 100%; max-width: 420px; padding: 40px; 
            border-radius: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
            border-top: 8px solid var(--primary);
        }

        .input-group { position: relative; margin-bottom: 20px; }
        .input-group i { position: absolute; left: 15px; top: 15px; color: var(--primary); }
        .input-group input { 
            width: 100%; padding: 13px 15px 13px 45px; border: 2px solid #e2e8f0; 
            border-radius: 12px; outline: none; transition: 0.3s;
        }
        .input-group input:focus { border-color: var(--primary); background: #fff; }

        .btn-row { display: flex; gap: 10px; margin-top: 10px; }

        .login-btn { 
            flex: 2; background: var(--accent); color: white; padding: 15px; 
            border: none; border-radius: 12px; font-weight: bold; cursor: pointer; 
            font-size: 1.1rem; transition: 0.3s;
        }
        .login-btn:hover { background: #ea580c; transform: translateY(-2px); }

        .clear-btn { 
            flex: 1; background: #94a3b8; color: white; padding: 15px; 
            border: none; border-radius: 12px; font-weight: bold; cursor: pointer; 
            font-size: 1rem; transition: 0.3s;
        }
        .clear-btn:hover { background: #64748b; }

        .reg-link { display: block; text-align: center; margin-top: 25px; color: var(--secondary); text-decoration: none; font-weight: bold; font-size: 14px; }
        .reg-link span { color: var(--accent); text-decoration: underline; }

        footer { background: var(--dark); color: #fed7aa; text-align: center; padding: 30px; border-top: 4px solid var(--accent); }
    </style>
</head>
<body>

<header>
    <img src="hg.jpeg" class="logo" alt="Logo">
    <h1>City Catering Management system</h1>
</header>

<div class="navbar">
    <a href="home.php"><i class="fa fa-home"></i> Back to Home</a>
</div>

<div class="login-container">
    <div class="login-card">
        <h2 style="text-align:center; color:var(--dark); margin-bottom:25px;">Owner Login</h2>

        <form id="loginForm" method="post">
            <div class="input-group">
                <i class="fa fa-user"></i>
                <input type="text" name="id" placeholder="User ID" required>
            </div>
            <div class="input-group">
                <i class="fa fa-lock"></i>
                <input type="password" name="pass" placeholder="Password" required>
            </div>
            
            <div class="btn-row">
                <button type="submit" class="login-btn">Login Now</button><br><br>
                <button type="reset" class="clear-btn">Clear</button>
            </div>
        </form>

        <a href="ownreg.php" class="reg-link">Don't have an account? <span>Register New Business</span></a>
    </div>
</div>

<footer>
    <p>&copy; 2024 City Catering Management System. All Rights Reserved.</p>
</footer>

</body>
</html>
