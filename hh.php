  <?php
session_start();
/* 1. DATABASE CONNECTION */
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Database Connection Error"); }

$search_result = null;
$error_message = "";

/* 2. HANDLE SEARCH REQUEST */
if (isset($_POST['search_id'])) {
    $order_id = mysqli_real_escape_string($con, $_POST['order_id']);
    
    // Fetch the specific order by ID
    $sql = "SELECT * FROM orders WHERE order_id = '$order_id'";
    $rs = mysqli_query($con, $sql);
    
    if ($rs && mysqli_num_rows($rs) > 0) {
        $search_result = mysqli_fetch_assoc($rs);
    } else {
        $error_message = "No booking found with ID #$order_id";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Booking | City Catering</title>
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
            display: flex; align-items: center; 
            padding: 25px 50px; position: sticky; top: 0; z-index: 1000; height: 70px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .navbar ul { list-style: none; display: flex; gap: 10px; }
        .navbar ul li a { 
            padding: 10px 20px; color: #f1f5f9; text-decoration: none; 
            font-weight: 500; text-transform: uppercase; font-size: 0.85rem; border-radius: 8px; transition: 0.3s;
        }
        .navbar ul li a:hover { background: rgba(255,255,255,0.1); color: var(--accent); }

        /* MAIN CONTENT AREA */
        .page-content { flex: 1; padding: 50px 20px; display: flex; flex-direction: column; align-items: center; }
        
        /* SEARCH CARD */
        .search-card { 
            background: white; 
            width: 100%; 
            max-width: 550px; 
            padding: 40px; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); 
            margin-bottom: 30px; 
            border-top: 8px solid var(--primary); 
        }
        .search-card h2 { color: var(--primary); margin-bottom: 25px; text-align: center; font-weight: 800; }
        
        .input-group { display: flex; border: 2px solid #e2e8f0; border-radius: 50px; overflow: hidden; transition: 0.3s; padding: 5px; }
        .input-group:focus-within { border-color: var(--primary); }
        
        input[type="text"] { 
            flex: 1; padding: 12px 20px; border: none; outline: none; font-size: 16px; background: transparent;
        }
        .btn-search { 
            background: var(--accent); color: white; border: none; padding: 0 30px; 
            border-radius: 50px; font-weight: bold; cursor: pointer; transition: 0.3s;
        }
        .btn-search:hover { background: #ea580c; transform: scale(1.05); }

        /* RESULT CARD */
        .result-card { 
            background: white; width: 100%; max-width: 600px; border-radius: 20px; overflow: hidden; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1); animation: slideUp 0.5s ease-out; 
            border: 1px solid #f1f5f9;
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        
        .result-header { background: #f8fafc; padding: 25px; border-bottom: 2px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
        .status-badge { padding: 6px 16px; border-radius: 50px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
        .Pending { background: #fef3c7; color: #92400e; }
        .Confirmed { background: #dcfce7; color: #166534; }

        .result-body { padding: 30px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #f8fafc; }
        .label { color: #64748b; font-weight: 600; font-size: 12px; text-transform: uppercase; }
        .value { font-weight: 700; color: var(--dark); font-size: 15px; }
        
        .total-bill-box { 
            background: #f0fdf4; padding: 20px; border-radius: 15px; 
            display: flex; justify-content: space-between; align-items: center; margin-top: 20px; 
            border: 1px solid #dcfce7;
        }
        
        .error { color: #b91c1c; background: #fee2e2; padding: 15px; border-radius: 12px; width: 100%; max-width: 550px; text-align: center; font-weight: 600; border: 1px solid #fecaca; }

        /* FOOTER */
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
</header>
 <div class="navbar">
        <ul>
            <li><a href="publicmain.php">Home</a></li>
            <li><a href="venues.php">View All Catering</a></li>
            <li><a href="search_result.php">🔍 Search Food</a></li>
            <li><a href="search_event.php">🔍 Search Event</a></li>
            <li><a href="booking.php">Bookings</a></li>
        </ul>
    </div>

<div class="page-content">
    <!-- SEARCH FORM -->
    <div class="search-card">
        <h2>Track Booking</h2>
        <form method="post">
            <div class="input-group">
                <input type="text" name="order_id" placeholder="Enter Order ID (e.g. ORD-1)" required>
                <button type="submit" name="search_id" class="btn-search">Track</button>
            </div>
        </form>
    </div>

    <!-- ERROR MESSAGE -->
    <?php if($error_message): ?>
        <div class="error">⚠️ <?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- SEARCH RESULTS -->
    <?php if($search_result): ?>
        <div class="result-card">
            <div class="result-header">
                <div>
                    <span style="display:block; font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase;">Reference ID</span>
                    <span style="font-weight: 800; color: var(--secondary); font-size: 22px;"><?php echo $search_result['order_id']; ?></span>
                </div>
                <span class="status-badge <?php echo $search_result['status']; ?>">
                    ● <?php echo $search_result['status']; ?>
                </span>
            </div>
            
            <div class="result-body">
                <div class="info-row">
                    <span class="label">Customer Name</span>
                    <span class="value"><?php echo htmlspecialchars($search_result['customer_name']); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Date of Event</span>
                    <span class="value"><?php echo date("d M Y", strtotime($search_result['event_date'])); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Occasion Type</span>
                    <span class="value"><?php echo htmlspecialchars($search_result['event_type']); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Total Headcount</span>
                    <span class="value"><?php echo ($search_result['members_morning'] + $search_result['members_afternoon'] + $search_result['members_night']); ?> Guests</span>
                </div>
                
                <div class="total-bill-box">
                    <div>
                        <span style="display:block; font-size: 12px; color: #166534; font-weight: bold; text-transform: uppercase;">Estimated Bill</span>
                        <span style="font-size: 24px; font-weight: 800; color: var(--primary);">₹<?php echo number_format($search_result['total_bill'], 2); ?></span>
                    </div>
                    <span style="font-size: 20px;">💰</span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> City Catering Management System. All Rights Reserved.</p>
</footer>

</body>
</html>
