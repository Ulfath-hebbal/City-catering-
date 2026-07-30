 <?php
session_start();
/* 1. DB CONNECTION */
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Database Connection Error: " . mysqli_connect_error()); }

/* SECURITY CHECK */
if (!isset($_SESSION['oid'])) { header("Location: ownlog.php"); exit(); }
$oid = $_SESSION['oid'];

/* 2. FETCH DATA */
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $res = mysqli_query($con, "SELECT * FROM orders WHERE id = '$id' AND oid = '$oid'");
    $data = mysqli_fetch_assoc($res);
    
    if (!$data) { die("Order not found or unauthorized access."); }
}

/* 3. UPDATE LOGIC */
if (isset($_POST['save_payment'])) {
    $db_id = mysqli_real_escape_string($con, $_POST['db_id']);
    $received = mysqli_real_escape_string($con, $_POST['received_amt']);
    
    $sql = "UPDATE orders SET 
            status = 'Paid', 
            paid_amount = '$received' 
            WHERE id = '$db_id' AND oid = '$oid'";
            
    if (mysqli_query($con, $sql)) {
        header("Location: updatepay.php?msg=Payment of ₹$received Recorded Successfully");
        exit();
    } else {
        die("Query Failed: " . mysqli_error($con));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Payment | City Catering</title>
    <style>
        /* MATCHING OWNER THEME FLOW */
        body { margin: 0; font-family: 'Segoe UI', Tahoma, sans-serif; display: flex; background: #fdfaf7; color: #333; }
        
        /* SIDEBAR */
        .sidebar { width: 250px; height: 100vh; background: #064e3b; padding: 20px 0; position: fixed; box-shadow: 4px 0 10px rgba(0,0,0,0.1); }
        .sidebar h2 { color: #f97316; text-align: center; font-family: 'Forte', sans-serif; margin-bottom: 30px; font-size: 24px; }
        .sidebar a, .sidebar summary { display: block; color: #ecfdf5; padding: 15px 25px; text-decoration: none; transition: 0.3s; cursor: pointer; }
        .sidebar a:hover, .sidebar summary:hover { background: #f97316; color: white; padding-left: 35px; }
        
        details summary { list-style: none; outline: none; }
        details summary::-webkit-details-marker { display: none; }
        .dropdown-content { background: #043a2c; }
        .dropdown-content a { font-size: 14px; padding-left: 45px; }

        /* MAIN AREA */
        .main { flex: 1; margin-left: 250px; padding: 30px; display: flex; flex-direction: column; align-items: center; }

        /* TOP HEADER */
        .page-header { width: 100%; display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #eee; }
        .oid-badge { background: linear-gradient(135deg, #f97316, #ea580c); color: white; padding: 10px 20px; border-radius: 50px; font-weight: bold; font-size: 14px; }

        /* PAYMENT CARD */
        .pay-card { 
            width: 100%; 
            max-width: 450px; 
            background: white; 
            padding: 40px; 
            border-radius: 20px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.08); 
            border-top: 8px solid #064e3b; 
        }
        
        .info-box { 
            background: #f8fafc; 
            padding: 20px; 
            border-radius: 12px; 
            margin-bottom: 25px; 
            font-size: 15px; 
            border: 1px solid #e2e8f0; 
        }
        .info-box p { margin: 8px 0; color: #475569; }
        .info-box b { color: #064e3b; float: right; }

        label { display: block; margin-top: 20px; font-weight: 800; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }
        
        .input-style { 
            width: 100%; padding: 14px; border: 2px solid #e2e8f0; border-radius: 10px; 
            font-size: 16px; margin-top: 8px; outline: none; transition: 0.3s; background: #fcfdfd;
        }
        .input-style:focus { border-color: #f97316; background: #fff; }

        .btn-confirm { 
            width: 100%; background: #064e3b; color: white; padding: 16px; border: none; 
            border-radius: 10px; font-weight: bold; margin-top: 30px; cursor: pointer; 
            font-size: 16px; transition: 0.3s; box-shadow: 0 4px 12px rgba(6, 78, 59, 0.2);
        }
        .btn-confirm:hover { background: #059669; transform: translateY(-2px); }

        .btn-cancel { display: block; text-align: center; margin-top: 20px; color: #ef4444; text-decoration: none; font-size: 14px; font-weight: 600; }
        .btn-cancel:hover { text-decoration: underline; }
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
            <li><a href="home.php" style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 20px; color: #fca5a5;">🚪 Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="page-header">
            <h2 style="margin:0; color:#064e3b;">Record Payment</h2>
            <div class="oid-badge">OWNER ID: <?php echo htmlspecialchars($oid); ?></div>
        </div>

        <div class="pay-card">
            <h3 style="color: #064e3b; margin-top:0; text-align: center;">Settlement Details</h3>
            
            <div class="info-box">
                <p>Order ID: <b>#<?php echo $data['order_id']; ?></b></p>
                <p>Customer: <b><?php echo htmlspecialchars($data['customer_name']); ?></b></p>
                <p>Total Bill: <b>₹<?php echo number_format($data['total_bill'], 2); ?></b></p>
            </div>

            <form method="POST">
                <input type="hidden" name="db_id" value="<?php echo $data['id']; ?>">

                <label>Payment Mode</label>
                <select class="input-style">
                    <option>Cash Handover</option>
                    <option>UPI / Bank Transfer</option>
                    <option>Cheque</option>
                </select>

                <label>Final Amount Received (₹)</label>
                <input type="number" name="received_amt" class="input-style" 
                       value="<?php echo $data['total_bill']; ?>" 
                       placeholder="Enter amount" required autofocus>

                <button type="submit" name="save_payment" class="btn-confirm">Mark as Fully Paid</button>
                <a href="updatepay.php" class="btn-cancel">Back to List</a>
            </form>
        </div>
    </div>

</body>
</html>
