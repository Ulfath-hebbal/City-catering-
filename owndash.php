 <?php
session_start();

/* DB CONNECTION */
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Connection failed: " . mysqli_connect_error()); }

/* SECURITY CHECK */
if (!isset($_SESSION['oid'])) {
    header("Location: ownlog.php");
    exit();
}
$oid = $_SESSION['oid'];

/* --- FETCH TOTAL STATS --- */
$menu_q = mysqli_query($con, "SELECT COUNT(*) as total FROM manu WHERE oid = '$oid'");
$menu_row = mysqli_fetch_assoc($menu_q);
$total_menu = $menu_row['total'];

$order_q = mysqli_query($con, "SELECT COUNT(*) as total FROM orders WHERE oid = '$oid'");
$order_row = mysqli_fetch_assoc($order_q);
$total_orders = $order_row['total'];

$total_rev_q = mysqli_query($con, "SELECT SUM(total_bill) as revenue FROM orders WHERE oid = '$oid'");
$rev_row = mysqli_fetch_assoc($total_rev_q);
$total_revenue = ($rev_row['revenue'] > 0) ? $rev_row['revenue'] : 0;

/* --- REVENUE FILTER LOGIC --- */
$filtered_revenue = 0;
$filter_applied = false;
$date_val = isset($_GET['date_val']) ? $_GET['date_val'] : '';
$month_val = isset($_GET['month_val']) ? $_GET['month_val'] : '';
$label = "";

if (!empty($date_val)) {
    $filter_sql = "SELECT SUM(total_bill) as rev FROM orders WHERE oid = '$oid' AND event_date = '$date_val'";
    $filter_applied = true;
    $label = "Date: " . date("d M Y", strtotime($date_val));
} elseif (!empty($month_val)) {
    $filter_sql = "SELECT SUM(total_bill) as rev FROM orders WHERE oid = '$oid' AND event_date LIKE '$month_val%'";
    $filter_applied = true;
    $label = "Month: " . date("F Y", strtotime($month_val."-01"));
}

if($filter_applied) {
    $f_res = mysqli_query($con, $filter_sql);
    $f_row = mysqli_fetch_assoc($f_res);
    $filtered_revenue = ($f_row['rev'] > 0) ? $f_row['rev'] : 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Owner Dashboard | City Catering</title>
    <style>
        body { margin: 0; font-family: 'Segoe UI', Tahoma, sans-serif; display: flex; background: #fdfaf7; color: #333; }
        
        /* SIDEBAR (Matches Menu Page) */
        .sidebar { width: 250px; height: 100vh; background: #064e3b; padding: 20px 0; position: fixed; box-shadow: 4px 0 10px rgba(0,0,0,0.1); z-index: 1000; }
        .sidebar h2 { color: #f97316; text-align: center; font-family: 'Forte', sans-serif; margin-bottom: 30px; font-size: 24px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar a, .sidebar summary { display: block; color: #ecfdf5; padding: 15px 25px; text-decoration: none; transition: 0.3s; cursor: pointer; font-weight: 500; }
        .sidebar a:hover, .sidebar summary:hover { background: #f97316; color: white; padding-left: 35px; }

        /* Sidebar Dropdown for Payment Management */
        details summary { list-style: none; outline: none; }
        details summary::-webkit-details-marker { display: none; }
        .dropdown-content { background: #043a2c; }
        .dropdown-content a { font-size: 14px; padding-left: 50px; }
        .dropdown-content a:hover { padding-left: 60px; }

        /* MAIN AREA */
        .main { flex: 1; margin-left: 250px; padding: 30px; }
        
        /* TOP HEADER */
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #eee; }
        .oid-badge { background: linear-gradient(135deg, #f97316, #ea580c); color: white; padding: 10px 20px; border-radius: 50px; font-weight: bold; font-size: 14px; box-shadow: 0 4px 10px rgba(234, 88, 12, 0.2); }

        /* FILTER BOXES */
        .filter-container { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .filter-box { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-top: 5px solid #064e3b; }
        .filter-box h4 { margin: 0 0 15px 0; color: #064e3b; text-transform: uppercase; font-size: 13px; letter-spacing: 1px; }
        
        .input-row { display: flex; gap: 10px; }
        input { flex: 1; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; outline: none; }
        
        .btn-filter { background: #064e3b; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-filter:hover { background: #f97316; }

        /* STATS GRID */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }
        .stat-box { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); text-align: center; transition: 0.3s; border-bottom: 4px solid transparent; }
        .stat-box:hover { transform: translateY(-5px); border-bottom-color: #f97316; }
        .stat-box h3 { font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800; margin: 0; }
        .stat-box .number { display: block; font-size: 28px; font-weight: 800; color: #064e3b; margin: 10px 0; }
        
        /* RESULT HIGHLIGHT */
        .revenue-highlight { background: #fff7ed; border: 2px dashed #f97316 !important; }

        .logout-btn { border-top: 1px solid rgba(255,255,255,0.1); margin-top: 20px; color: #fca5a5 !important; }
        .logout-btn:hover { background: #991b1b !important; padding-left: 25px !important; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>City Catering</h2>
        <ul>
            <li><a href="owndash.php">🏠 Dashboard</a></li>
            <li><a href="manu.php">🍴 Menu Manager</a></li>
            <li><a href="viewordres.php">📅 Customer Orders</a></li>
            
            <!-- Payment Management Dropdown -->
            <li>
                <details>
                    <summary>💰 Payment Management ▾</summary>
                    <div class="dropdown-content">
                        <a href="viewpay.php">View Payments</a>
                        <a href="updatepay.php">Update Payment</a>
                    </div>
                </details>
            </li>
            
            <li><a href="home.php" class="logout-btn">🚪 Logout</a></li>
        </ul>
    </div>

    <!-- MAIN AREA -->
    <div class="main">
        <div class="page-header">
            <h1 style="margin:0; font-size: 24px; color: #064e3b;">Analytics & Revenue</h1>
            <div class="oid-badge">OWNER ID: #<?php echo $oid; ?></div>
        </div>

        <!-- REVENUE FILTERS -->
        <div class="filter-container">
            <div class="filter-box">
                <h4>Check Daily Revenue</h4>
                <form action="" method="GET" class="input-row">
                    <input type="date" name="date_val" value="<?php echo $date_val; ?>" required>
                    <button type="submit" class="btn-filter">Check</button>
                </form>
            </div>

            <div class="filter-box">
                <h4>Check Monthly Revenue</h4>
                <form action="" method="GET" class="input-row">
                    <input type="month" name="month_val" value="<?php echo $month_val; ?>" required>
                    <button type="submit" class="btn-filter" style="background:#f97316;">Check</button>
                </form>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-box">
                <h3>Total Lifetime Revenue</h3>
                <span class="number">₹<?php echo number_format($total_revenue, 2); ?></span>
                <span style="font-size:10px; color:#059669; font-weight:bold;">ALL TIME EARNINGS</span>
            </div>

            <?php if($filter_applied) { ?>
            <div class="stat-box revenue-highlight">
                <h3>Result for <?php echo $label; ?></h3>
                <span class="number" style="color: #f97316;">₹<?php echo number_format($filtered_revenue, 2); ?></span>
                <a href="owndash.php" style="font-size:10px; color:#64748b; text-decoration:none;">[Reset Filter]</a>
            </div>
            <?php } ?>

            <div class="stat-box">
                <h3>Customer Bookings</h3>
                <span class="number"><?php echo $total_orders; ?></span>
            </div>

            <div class="stat-box">
                <h3>Food Menu Items</h3>
                <span class="number"><?php echo $total_menu; ?></span>
            </div>
        </div>
    </div>

</body>
</html>
