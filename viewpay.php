 <?php
session_start();
/* 1. DB CONNECTION */
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Database Connection Error"); }

/* 2. AUTH CHECK */
if (!isset($_SESSION['oid'])) { 
    header("Location: ownlog.php"); 
    exit(); 
}
$oid = $_SESSION['oid']; 

/* 3. FETCH PAYMENTS */
$sql = "SELECT order_id, customer_name, total_bill, paid_amount, status 
        FROM orders 
        WHERE oid = '$oid' AND (paid_amount > 0 OR status = 'Paid') 
        ORDER BY id DESC";
$rs = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Records | City Catering</title>
    <style>
        /* OWNER THEME FLOW */
        body { margin: 0; font-family: 'Segoe UI', Tahoma, sans-serif; display: flex; background: #fdfaf7; color: #333; }
        
        /* Sidebar */
        .sidebar { width: 250px; height: 100vh; background: #064e3b; padding: 20px 0; position: fixed; box-shadow: 4px 0 10px rgba(0,0,0,0.1); }
        .sidebar h2 { color: #f97316; text-align: center; font-family: 'Forte', sans-serif; margin-bottom: 30px; font-size: 24px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar a, .sidebar summary { display: block; color: #ecfdf5; padding: 15px 25px; text-decoration: none; transition: 0.3s; cursor: pointer; }
        .sidebar a:hover, .sidebar summary:hover { background: #f97316; color: white; padding-left: 35px; }
        
        details summary { list-style: none; outline: none; }
        details summary::-webkit-details-marker { display: none; }
        .dropdown-content { background: #043a2c; }
        .dropdown-content a { font-size: 14px; padding-left: 45px; }

        /* Main Area */
        .main { flex: 1; margin-left: 250px; padding: 30px; }

        /* Header */
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #eee; }
        .oid-badge { background: linear-gradient(135deg, #f97316, #ea580c); color: white; padding: 10px 20px; border-radius: 50px; font-weight: bold; font-size: 14px; }

        /* Table Card */
        .table-card { 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05); 
            overflow: hidden; 
            border-top: 6px solid #088f62; 
        }
        
        .card-header { padding: 20px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
        .search-input { padding: 10px 15px; border: 2px solid #e2e8f0; border-radius: 10px; outline: none; transition: 0.3s; width: 280px; font-size: 14px; }
        .search-input:focus { border-color: #f97316; box-shadow: 0 0 8px rgba(249, 115, 22, 0.1); }

        table { width: 100%; border-collapse: collapse; }
        th { background: #f8fafc; padding: 18px 15px; text-align: left; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 18px 15px; border-bottom: 1px solid #f1f5f9; font-size: 14.5px; }
        tr:hover { background: #fffaf5; }

        /* Status & Amount Styling */
        .status-pill { padding: 6px 16px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; display: inline-block; border: 1px solid #bbf7d0; background: #dcfce7; color: #166534; }
        
        .amt-total { color: #1e293b; font-weight: 700; }
        .amt-paid { color: #088f62; font-weight: 800; font-size: 16px; }

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
                        <a href="viewpay.php" >View Payments</a>
                        <a href="updatepay.php">Update Payment</a>
                    </div>
                </details>
            </li>
            <li><a href="home.php" class="logout-btn">🚪 Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="page-header">
            <h2 style="margin:0; color:#064e3b;">Payment Settlement Records</h2>
            <div class="oid-badge">OWNER ID: <?php echo htmlspecialchars($oid); ?></div>
        </div>

        <div class="table-card">
            <div class="card-header">
                <h3 style="color: #064e3b; margin:0;">Collections Overview</h3>
                <input type="text" id="paySearch" onkeyup="searchTable()" class="search-input" placeholder="Search Customer or Order ID...">
            </div>
            
            <table id="payTable">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Total Amount</th>
                        <th>Amount Paid</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($rs) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($rs)): ?>
                        <tr>
                            <td><b style="color: #076e93;">#<?php echo htmlspecialchars($row['order_id']); ?></b></td>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><span class="amt-total">₹<?php echo number_format($row['total_bill'], 2); ?></span></td>
                            <td><span class="amt-paid">₹<?php echo number_format($row['paid_amount'], 2); ?></span></td>
                            <td>
                                <span class="status-pill">PAID</span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #94a3b8; padding: 50px; font-style: italic;">No payment data found for your account.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function searchTable() {
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById("paySearch");
        filter = input.value.toUpperCase();
        table = document.getElementById("payTable");
        tr = table.getElementsByTagName("tr");
        for (i = 1; i < tr.length; i++) {
            tr[i].style.display = "none";
            td = tr[i].getElementsByTagName("td");
            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        break;
                    }
                }
            }
        }
    }
    </script>
</body>
</html>
