 <?php
session_start();

/* DB CONNECTION - Updated to mysqli */
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Connection Failed"); }

/* SECURITY CHECK */
if (!isset($_SESSION['oid'])) {
    header("Location: ownlog.php");
    exit();
}

$oid = $_SESSION['oid'];

/* FETCH STATS */
$total_q = mysqli_query($con, "SELECT COUNT(*) as total FROM orders WHERE oid = '$oid'");
$pending_q = mysqli_query($con, "SELECT COUNT(*) as total FROM orders WHERE oid = '$oid' AND status = 'Pending'");
$confirmed_q = mysqli_query($con, "SELECT COUNT(*) as total FROM orders WHERE oid = '$oid' AND status = 'Confirmed'");

$total = ($row = mysqli_fetch_assoc($total_q)) ? $row['total'] : 0;
$pending = ($row = mysqli_fetch_assoc($pending_q)) ? $row['total'] : 0;
$confirmed = ($row = mysqli_fetch_assoc($confirmed_q)) ? $row['total'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Owner Dashboard - City Catering</title>
    <style>
        /* MATCHING MANU.PHP CSS EXACTLY */
        body { margin: 0; font-family: 'Segoe UI', Tahoma, sans-serif; display: flex; background: #fdfaf7; color: #333; }
        
        /* Sidebar */
        .sidebar { width: 250px; height: 100vh; background: #064e3b; padding: 20px 0; position: fixed; }
        .sidebar h2 { color: #f97316; text-align: center; font-family: 'Forte', sans-serif; margin-bottom: 30px; }
        .sidebar a, .sidebar summary { display: block; color: #ecfdf5; padding: 15px 25px; text-decoration: none; transition: 0.3s; cursor: pointer; }
        .sidebar a:hover, .sidebar summary:hover { background: #f97316; color: white; padding-left: 35px; }

        /* Main Area */
        .main { flex: 1; margin-left: 250px; padding: 30px; }

        /* TOP HEADER WITH OID */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
        }
        .oid-badge {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(234, 88, 12, 0.2);
            font-size: 14px;
        }

        /* Dashboard Cards */
        .box-welcome {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            margin-bottom: 40px;
            border-top: 6px solid #f97316;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            text-align: center;
            transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        
        .stat-card h3 { color: #64748b; font-size: 12px; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 1px; }
        .stat-card .value { font-size: 36px; font-weight: bold; color: #064e3b; }

        .dropdown-content { background: #043a2c; }
        .dropdown-content a { font-size: 14px; padding-left: 40px; }
        
        .logout-btn { border-top: 1px solid rgba(255,255,255,0.1); margin-top: 20px; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>City Catering </h2>
        <ul>
            <li><a href="owndash.php">🏠 Dashboard</a></li>
            <li><a href="manu.php">🍴 Menu Manager</a></li>
            <li><a href="viewordres.php">📅 Customer Orders</a></li>
            
            <li>
                <details>
                    <summary>💰 Payment Management ▾</summary>
                    <div class="dropdown-content">
                        <a href="viewpay.php">View payments</a>
                        <a href="updatepay.php">Update payment</a>
                    </div>
                </details>
            </li>
            
            <li><a href="home.php" class="logout-btn">🚪 Logout</a></li>
        </ul>
    </div>

    <!-- MAIN AREA -->
    <div class="main">
        <div class="page-header">
            <h2 style="margin:0;">Business Dashboard</h2>
            <div class="oid-badge">
                Logged in Owner ID: <?php echo $oid; ?>
            </div>
        </div>


</body>
</html>
