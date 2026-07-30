 <?php
session_start();
$con = mysqli_connect("localhost", "root", "", "citycatering");

// Check if the user logged in successfully
if (!isset($_SESSION['view_id'])) { 
    header("Location: custlo.php"); 
    exit(); 
}

$view_id = $_SESSION['view_id'];

// Fetch only this specific order
$sql = "SELECT * FROM orders WHERE order_id = '$view_id'";
$rs = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($rs);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Booking Details - City Catering</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f8fafc; padding: 40px; color: #1e293b; display: flex; justify-content: center; }
        .container { width: 100%; max-width: 600px; }
        .header { margin-bottom: 30px; text-align: center; }
        
        .order-card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .status-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #f1f5f9; }
        
        .order-id { font-weight: 800; color: #0369a1; font-size: 1.2rem; }
        .status-badge { padding: 6px 15px; border-radius: 30px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .Pending { background: #fef3c7; color: #92400e; }
        .Confirmed { background: #dcfce7; color: #166534; }
        
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .label { color: #64748b; font-weight: 600; }
        .value { color: #1e293b; font-weight: 700; text-align: right; }
        
        .total-box { margin-top: 20px; padding: 20px; background: #f1f5f9; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; }
        .total-price { font-size: 24px; color: #10b981; font-weight: 800; }
        .btn-logout { display: block; text-align: center; margin-top: 20px; color: #ef4444; text-decoration: none; font-size: 14px; font-weight: 600; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Booking Summary</h1>
    </div>

    <?php if($row) { ?>
    <div class="order-card">
        <div class="status-header">
            <span class="order-id">Order #<?php echo $row['order_id']; ?></span>
            <span class="status-badge <?php echo $row['status']; ?>">
                <?php echo $row['status']; ?>
            </span>
        </div>

        <div class="detail-row">
            <span class="label">Customer Name</span>
            <span class="value"><?php echo htmlspecialchars($row['customer_name']); ?></span>
        </div>
    
        <div class="detail-row">
            <span class="label">Event Date</span>
            <span class="value"><?php echo date("d M Y", strtotime($row['event_date'])); ?></span>
        </div>

        <div class="detail-row">
            <span class="label">Event Type</span>
            <span class="value"><?php echo htmlspecialchars($row['event_type']); ?></span>
        </div>

        <div class="detail-row">
            <span class="label">Total Guests</span>
            <span class="value"><?php echo ($row['members_morning'] + $row['members_afternoon'] + $row['members_night']); ?></span>
        </div>

        <?php if(!empty($row['extra_items'])) { ?>
        <div style="margin-top: 10px; font-size: 13px; color: #64748b; font-style: italic;">
            <strong>Note:</strong> <?php echo htmlspecialchars($row['extra_items']); ?>
        </div>
        <?php } ?>

        <div class="total-box">
            <span class="label" style="color: #1e293b;">Amount Paid/Due</span>
            <span class="total-price">₹<?php echo number_format($row['total_bill'], 2); ?></span>
        </div>

        <a href="logout.php" class="btn-logout">Exit View</a>
    </div>
    <?php } else { ?>
        <p>No booking details found.</p>
    <?php } ?>
</div>

</body>
</html>
