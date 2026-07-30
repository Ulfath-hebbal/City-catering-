 <?php
session_start();
/* 1. DB CONNECTION */
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Connection Error: " . mysqli_connect_error()); }

/* 2. AUTH CHECK */
if (!isset($_SESSION['oid'])) { header("Location: ownlog.php"); exit(); }
$oid = $_SESSION['oid'];

/* 3. FETCH COMPLETED OR PAID ORDERS */
$sql = "SELECT * FROM orders WHERE oid = '$oid' AND status IN ('Completed', 'Paid') ORDER BY id DESC";
$rs = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Payment | City Catering</title>
    <style>
        /* MATCHING OWNER THEME FLOW */
        body { margin: 0; font-family: 'Segoe UI', Tahoma, sans-serif; display: flex; background: #fdfaf7; color: #333; }
        
        /* SIDEBAR */
        .sidebar { width: 250px; height: 100vh; background: #064e3b; padding: 20px 0; position: fixed; box-shadow: 4px 0 10px rgba(0,0,0,0.1); }
        .sidebar h2 { color: #f97316; text-align: center; font-family: 'Forte', sans-serif; margin-bottom: 30px; font-size: 24px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar a, .sidebar summary { display: block; color: #ecfdf5; padding: 15px 25px; text-decoration: none; transition: 0.3s; cursor: pointer; }
        .sidebar a:hover, .sidebar summary:hover { background: #f97316; color: white; padding-left: 35px; }
        
        details summary { list-style: none; outline: none; }
        details summary::-webkit-details-marker { display: none; }
        .dropdown-content { background: #043a2c; }
        .dropdown-content a { font-size: 14px; padding-left: 45px; }

        /* MAIN AREA */
        .main { flex: 1; margin-left: 250px; padding: 30px; }

        /* HEADER */
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #eee; }
        .oid-badge { background: linear-gradient(135deg, #f97316, #ea580c); color: white; padding: 10px 20px; border-radius: 50px; font-weight: bold; font-size: 14px; }

        /* TABLE CARD */
        .table-card { 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05); 
            overflow: hidden; 
            border-top: 6px solid #f97316; 
        }
        
        .card-header { padding: 20px; border-bottom: 1px solid #f1f5f9; background: #fff; }
        .card-header h3 { color: #064e3b; margin: 0; }

        table { width: 100%; border-collapse: collapse; }
        th { background: #f8fafc; padding: 15px; text-align: left; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        tr:hover { background: #fffaf5; }

        /* BADGES & BUTTONS */
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; }
        .Completed { background: #dbeafe; color: #1e40af; }
        .Paid { background: #dcfce7; color: #166534; }
        
        .btn-update { 
            background: #f97316; color: white; padding: 8px 16px; 
            border-radius: 8px; text-decoration: none; font-size: 12px; 
            font-weight: bold; transition: 0.3s; display: inline-block;
        }
        .btn-update:hover { background: #ea580c; transform: scale(1.05); }

        .logout-btn { border-top: 1px solid rgba(255,255,255,0.1); margin-top: 20px; color: #fca5a5 !important; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>City Catering</h2>
        <ul>
            <li><a href="owndash.php">🏠 Dashboard</a></li>
            <li><a href="manu.php">🍴 Menu Manager</a></li>
            <li><a href="viewordres.php">📅 Customer Orders</a></li>
            <li>
                <details open>
                    <summary >💰 Payment Management ▾</summary>
                    <div class="dropdown-content">
                        <a href="viewpay.php">View Payments</a>
                        <a href="updatepay.php" >Update Payment</a>
                    </div>
                </details>
            </li>
            <li><a href="home.php" class="logout-btn">🚪 Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="page-header">
            <h2 style="margin:0; color:#064e3b;">Manage Collection</h2>
            <div class="oid-badge">OWNER ID: <?php echo htmlspecialchars($oid); ?></div>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #bbf7d0; font-weight: bold;">
                ✅ <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <div class="table-card">
            <div class="card-header">
                <h3>Receive & Update Customer Payments</h3>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Bill Amount</th>
                        <th>Received to Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($rs) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($rs)): ?>
                        <tr>
                            <td><b>#<?php echo $row['order_id']; ?></b></td>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td>₹<?php echo number_format($row['total_bill'], 2); ?></td>
                            <td style="color: #088f62; font-weight: 800;">
                                ₹<?php echo number_format($row['paid_amount'], 2); ?>
                            </td>
                            <td><span class="status-badge <?php echo $row['status']; ?>"><?php echo strtoupper($row['status']); ?></span></td>
                            <td>
                                <?php if($row['status'] != 'Paid'): ?>
                                    <a href="receive_pay.php?id=<?php echo $row['id']; ?>" class="btn-update">Update Payment</a>
                                <?php else: ?>
                                    <span style="color:#10b981; font-weight:bold; font-size: 13px;">Fully Settled ✅</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #94a3b8; padding: 40px; font-style: italic;">No completed orders waiting for payment updates.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
