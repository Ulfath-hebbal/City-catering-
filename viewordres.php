 <?php
session_start();
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Connection Error"); }

if (!isset($_SESSION['oid'])) { header("Location: ownlog.php"); exit(); }
$oid = $_SESSION['oid'];

/* UPDATE STATUS LOGIC */
if(isset($_POST['update_status'])) {
    $id = mysqli_real_escape_string($con, $_POST['order_row_id']);
    $new_status = mysqli_real_escape_string($con, $_POST['status_val']);
    mysqli_query($con, "UPDATE orders SET status='$new_status' WHERE id='$id'");
}

$orders = mysqli_query($con, "SELECT * FROM orders WHERE oid='$oid' ORDER BY event_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Orders | City Catering</title>
    <style>
        body { margin: 0; font-family: 'Segoe UI', Tahoma, sans-serif; display: flex; background: #fdfaf7; color: #333; }
        
        /* SIDEBAR - PRESERVED FROM DASHBOARD */
        .sidebar { width: 250px; height: 100vh; background: #064e3b; padding: 20px 0; position: fixed; box-shadow: 4px 0 10px rgba(0,0,0,0.1); z-index: 1000; }
        .sidebar h2 { color: #f97316; text-align: center; font-family: 'Forte', sans-serif; margin-bottom: 30px; font-size: 24px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar a, .sidebar summary { display: block; color: #ecfdf5; padding: 15px 25px; text-decoration: none; transition: 0.3s; cursor: pointer; font-weight: 500; }
        .sidebar a:hover, .sidebar summary:hover { background: #f97316; color: white; padding-left: 35px; }

        /* Payment Management Dropdown logic */
        details summary { list-style: none; outline: none; }
        details summary::-webkit-details-marker { display: none; }
        .dropdown-content { background: #043a2c; }
        .dropdown-content a { font-size: 14px; padding-left: 50px; }
        .dropdown-content a:hover { padding-left: 60px; }

        /* MAIN CONTENT */
        .main { flex: 1; margin-left: 250px; padding: 30px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #eee; }
        .oid-badge { background: linear-gradient(135deg, #f97316, #ea580c); color: white; padding: 8px 18px; border-radius: 50px; font-weight: bold; font-size: 13px; }

        .table-container { background: white; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden; border-top: 5px solid #064e3b; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8fafc; color: #64748b; text-transform: uppercase; font-size: 11px; padding: 15px; text-align: left; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
        
        .food-item { background: #f1f5f9; border: 1px solid #cbd5e1; padding: 2px 6px; border-radius: 4px; margin: 2px; display: inline-block; font-size: 11px; }
        .session-tag { padding: 3px 7px; border-radius: 4px; color: white; font-size: 10px; font-weight: bold; }

        /* STATUS BADGES */
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; display: inline-block; }
        .Pending { background: #fff3cd; color: #856404; }
        .Confirmed { background: #dcfce7; color: #166534; }
        .Completed, .Paid { background: #064e3b; color: white; }

        .btn-update { background: #064e3b; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .btn-update:hover { background: #f97316; }
        .logout-btn { border-top: 1px solid rgba(255,255,255,0.1); margin-top: 20px; color: #fca5a5 !important; }
    </style>
</head>
<body>

    <!-- SIDEBAR - PRESERVED -->
    <div class="sidebar">
        <h2>City Catering</h2>
        <ul>
            <li><a href="owndash.php">🏠 Dashboard</a></li>
            <li><a href="manu.php">🍴 Menu Manager</a></li>
            <li><a href="viewordres.php">📅 Customer Orders</a></li>
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

    <div class="main">
        <div class="page-header">
            <h1 style="margin:0; font-size: 22px; color: #064e3b;">Customer Bookings</h1>
            <div class="oid-badge">ID: #<?php echo $oid; ?></div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Event & Date</th>
                        <th>Session Details</th>
                        <th>Menu Selection</th>
                        <th>Bill</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($orders)): ?>
                    <tr>
                        <td><b>#<?php echo $row['order_id']; ?></b></td>
                        <td><?php echo htmlspecialchars($row['customer_name']); ?><br><small><?php echo $row['customer_phone']; ?></small></td>
                        <td><?php echo $row['event_type']; ?><br><small><?php echo $row['event_date']; ?></small></td>
                        <td>
                            <?php if(strpos(strtolower($row['event_type']), 'wedding') !== false): ?>
                                <small>M:</small><?php echo $row['members_morning']; ?> <small>A:</small><?php echo $row['members_afternoon']; ?> <small>N:</small><?php echo $row['members_night']; ?>
                            <?php else: ?>
                                <?php 
                                    if($row['members_morning'] > 0) echo "<span class='session-tag' style='background:#064e3b;'>MORNING</span> ".$row['members_morning'];
                                    elseif($row['members_afternoon'] > 0) echo "<span class='session-tag' style='background:#076e93;'>AFTERNOON</span> ".$row['members_afternoon'];
                                    elseif($row['members_night'] > 0) echo "<span class='session-tag' style='background:#f97316;'>NIGHT</span> ".$row['members_night'];
                                ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                                $foods = explode(", ", $row['extra_items']);
                                foreach($foods as $f) { echo "<span class='food-item'>".htmlspecialchars($f)."</span>"; }
                            ?>
                        </td>
                        <td><b>₹<?php echo number_format($row['total_bill']); ?></b></td>
                        
                        <!-- STATUS COLUMN -->
                        <td>
                            <span class="status-badge <?php echo $row['status']; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>

                        <!-- ACTION COLUMN -->
                        <td>
                            <?php if($row['status'] == 'Paid' || $row['status'] == 'Completed'): ?>
                                <span style="color: #064e3b; font-weight: bold; font-size: 12px;">✅ Finalised</span>
                            <?php else: ?>
                                <form method="post" style="display:flex; gap:5px;">
                                    <input type="hidden" name="order_row_id" value="<?php echo $row['id']; ?>">
                                    <select name="status_val" style="padding:4px; font-size:11px;">
                                        <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="Confirmed" <?php if($row['status']=='Confirmed') echo 'selected'; ?>>Accept</option>
                                        <option value="Completed" <?php if($row['status']=='Completed') echo 'selected'; ?>>Complete</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn-update">Set</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
