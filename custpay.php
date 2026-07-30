 <?php
session_start();
/* 1. DB CONNECTION */
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Connection Error: " . mysqli_connect_error()); }

/* 2. AUTH CHECK */
if (!isset($_SESSION['cid'])) { 
    header("Location: custlog.php"); 
    exit(); 
}
$order_id = $_SESSION['cid'];

/* 3. FETCH PAYMENTS + OWNER DETAILS */
$sql = "SELECT o.*, own.oname, own.phno as owner_phone 
        FROM orders o 
        JOIN owner own ON o.oid = own.oid 
        WHERE o.order_id = '$order_id' 
        ORDER BY o.id DESC";

$rs = mysqli_query($con, $sql);
if (!$rs) { die("Query Error: " . mysqli_error($con)); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Payments | City Catering</title>
    <style>
        /* MATCHING HOME & PUBLIC PAGE THEME */
        :root {
            --primary: #088f62;
            --secondary: #076e93;
            --accent: #f97316;
            --dark: #011811;
            --light: #fff7ed;
            --glass: rgba(1, 24, 17, 0.85);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, sans-serif; }
        
        html, body { height: 100%; }
        body { 
            background: var(--light); 
            color: #334155; 
            line-height: 1.6;
            display: flex;
            flex-direction: column;
        }

        /* HEADER SECTION */
        header { 
            background: linear-gradient(135deg, var(--primary), var(--secondary)); 
            color: white; text-align: center; padding: 50px 10px 30px 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        header h1 { font-family: 'Forte', sans-serif; font-size: 2.8rem; text-shadow: 2px 2px 5px rgba(0,0,0,0.2); margin-top: 10px; }
        
        .logo { 
            width: 100px; height: 100px; border-radius: 50%; 
            border: 4px solid rgba(255,255,255,0.3); margin-bottom: 10px;
            transition: transform 0.6s ease; object-fit: cover;
        }
        .logo:hover { transform: rotate(360deg); }

        /* GLASS NAVBAR */
        .navbar { 
            background: var(--glass); 
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            display: flex;  align-items: center; 
            padding: 25px 50px; position: sticky; top: 0; z-index: 1000; height: 70px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .navbar ul { list-style: none; display: flex; gap: 5px; }
        .navbar ul li a { 
            padding: 10px 15px; color: #f1f5f9; text-decoration: none; 
            font-weight: 500; text-transform: uppercase; font-size: 0.8rem; border-radius: 8px; transition: 0.3s;
        }
        .navbar ul li a:hover, .navbar ul li a.active { background: rgba(255,255,255,0.1); color: var(--accent); }

        /* TABLE CARD STYLING */
        .page-content { padding: 50px 20px; max-width: 1200px; margin: 0 auto; flex: 1; }

        .table-card { 
            background: white; border-radius: 20px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.08); overflow: hidden; 
            border: 1px solid rgba(0,0,0,0.02);
            animation: fadeInUp 0.6s ease;
        }
        
        .card-header { 
            background: var(--dark); color: white; padding: 30px; 
            text-align: center;
        }

        table { width: 100%; border-collapse: collapse; background: white; }
        th { 
            background: #f8fafc; padding: 18px 15px; text-align: left; 
            color: #64748b; font-size: 11px; text-transform: uppercase; 
            letter-spacing: 1px; border-bottom: 2px solid #f1f5f9; 
        }
        td { padding: 20px 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 14px; }
        
        .status-pill { padding: 6px 12px; border-radius: 50px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .status-paid { background: #dcfce7; color: #15803d; }
        .status-approved { background: #dbeafe; color: #1e40af; }
        .status-pending { background: #fef3c7; color: #92400e; }

        .btn-pay { 
            background: var(--accent); color: white; padding: 10px 20px; 
            border-radius: 50px; text-decoration: none; font-weight: bold; 
            font-size: 12px; transition: 0.3s; box-shadow: 0 4px 10px rgba(249, 115, 22, 0.2);
        }
        .btn-pay:hover { background: #ea580c; transform: scale(1.05); }

        .thank-you-msg { 
            text-align: center; padding: 40px; color: var(--primary); 
            font-family: 'Forte', sans-serif; font-size: 1.5rem; background: #f0fdf4;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        footer {
            flex-shrink: 0; background: var(--dark); color: #fed7aa;
            text-align: center; padding: 40px; border-top: 4px solid var(--accent);
        }
    </style>
</head>
<body>

<header>
    <img src="hg.jpeg" class="logo" alt="Logo">
    <h1>City Catering Management system</h1>
    <p>Premium food services for your events</p>
</header>

<div class="navbar">
    <ul>
        <li><a href="booking.php">Back</a></li>
        <li><a href="venues.php">View All Catering</a></li>
        <li><a href="search_result.php">🔍 Search Food</a></li>
        <li><a href="search_event.php">🔍 Search Event</a></li>
        <li><a href="booking.php">Bookings</a></li>
        <li><a href="custpay.php" class="active">Payments</a></li>
    </ul>
</div>

<div class="page-content">
    <div class="table-card">
        <div class="card-header">
            <h2 style="margin: 0; font-size: 1.5rem;">Payment Dashboard</h2>
            <p style="font-size: 13px; opacity: 0.8; margin-top: 5px;">Client: <?php echo $_SESSION['customer_name']; ?> | Order: <?php echo $order_id; ?></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Order Details</th>
                    <th>Caterer Info</th>
                    <th>event_type</th>
                    <th>Total Bill</th>
                    <th>paid_amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($rs)) { ?>
                <tr>
                    <td>
                        <strong style="color: var(--secondary);"><?php echo $row['order_id']; ?></strong><br>
                        <small style="color: #64748b; font-weight: 600;"><?php echo date("d M Y", strtotime($row['event_date'])); ?></small>
                    </td>
                    <td>
                        <span style="font-weight: 700; color: var(--primary);"><?php echo $row['catering_service']; ?></span><br>
                        <small style="color: #64748b;">📞 <?php echo $row['owner_phone']; ?></small>
                    </td>
                    <td>
                        <span style="background: #fff7ed; color: #c2410c; padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: bold;">
                            <?php echo $row['event_type']; ?>
                        </span>
                    </td>
                    <td>
                        <strong style="color: #1e293b;">₹<?php echo number_format($row['total_bill'], 2); ?></strong>
                    </td>
                    <td>
                        <strong style="color: var(--primary);">₹<?php echo number_format($row['paid_amount'], 2); ?></strong>
                    </td>
                    <td>
                        <span class="status-pill status-<?php echo strtolower($row['status']); ?>">
                            ● <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if($row['status'] == 'Approved') { ?>
                            <a href="process_payment.php?id=<?php echo $row['id']; ?>" class="btn-pay">Pay Now</a>
                        <?php } elseif($row['status'] == 'Paid' || $row['status'] == 'Completed') { ?>
                            <span style="color: var(--primary); font-weight: bold; font-size: 12px;">✅ Settle</span>
                        <?php } else { ?>
                            <span style="color: #94a3b8; font-style: italic; font-size: 12px;">Processing...</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <div class="thank-you-msg">
            Thank you for choosing City Catering ! <br>
            <span style="font-size: 1rem; font-family: sans-serif; font-weight: normal; color: #64748b;">We look forward to serving you again.</span>
        </div>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> City Catering Management System. All Rights Reserved.</p>
</footer>

</body>
</html>
