 <?php
session_start();
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Database Connection Error"); }

/* 1. GET DATA FROM PREVIOUS PAGE */
$oid = isset($_POST['oid']) ? mysqli_real_escape_string($con, $_POST['oid']) : '';
$event = isset($_POST['event_type']) ? mysqli_real_escape_string($con, $_POST['event_type']) : 'General Event';

/* 2. HANDLE SELECTED MENU ITEMS (LEGACY ARRAY) */
$selected_food_array = isset($_POST['food_items']) ? $_POST['food_items'] : array();
$food_string = implode(", ", $selected_food_array);

/* 3. CALCULATE TOTAL PRICE */
$package_price = 0;
if(!empty($selected_food_array)) {
    foreach($selected_food_array as $full_name) {
        $parts = explode(" (", $full_name);
        $clean_name = mysqli_real_escape_string($con, $parts[0]);
        $p_query = mysqli_query($con, "SELECT price FROM manu WHERE oid='$oid' AND food='$clean_name' LIMIT 1");
        if($p_row = mysqli_fetch_assoc($p_query)) {
            $package_price += isset($p_row['price']) ? $p_row['price'] : 0;
        }
    }
}

/* 4. GENERATE SEQUENTIAL ORDER ID */
$last_q = mysqli_query($con, "SELECT id FROM orders ORDER BY id DESC LIMIT 1");
$next_num_data = ($last_q && mysqli_num_rows($last_q) > 0) ? mysqli_fetch_assoc($last_q) : array('id' => 0);
$generated_id = "ORD-" . ($next_num_data['id'] + 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration | City Catering</title>
    <style>
        /* MATCHING GLASSMORPHIC THEME */
        :root {
            --primary: #088f62;
            --secondary: #076e93;
            --accent: #f97316;
            --dark: #011811;
            --light: #fff7ed;
            --glass: rgba(1, 24, 17, 0.85);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: var(--light); color: #334155; }

        /* HEADER & GLASS NAVBAR */
        header { 
            background: linear-gradient(135deg, var(--primary), var(--secondary)); 
            color: white; text-align: center; padding: 40px 10px 30px 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        header h1 { font-family: 'Forte', sans-serif; font-size: 2.8rem; }
        .logo { width: 90px; height: 90px; border-radius: 50%; border: 4px solid rgba(255,255,255,0.3); object-fit: cover; }

        .navbar { 
            background: var(--glass); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            display: flex;  align-items: center;
            position: sticky; top: 0; z-index: 1000; height: 70px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .navbar ul { list-style: none; display: flex; gap: 20px; }
        .navbar a { color: #f1f5f9; text-decoration: none; font-weight: 500; text-transform: uppercase; font-size: 0.85rem; }
        .navbar a:hover { color: var(--accent); }

        /* REGISTRATION CARD */
        .page-content { display: flex; justify-content: center; padding: 50px 20px; }
        .card { 
            width: 100%; max-width: 650px; background: white; border-radius: 20px; 
            padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border-top: 8px solid var(--primary);
        }

        .selected-menu { 
            background: #f0f9ff; border-radius: 12px; padding: 20px; margin-bottom: 30px; 
            border-left: 5px solid var(--secondary);
        }
        .selected-menu b { color: var(--secondary); display: block; margin-bottom: 5px; }

        /* FORM CONTROLS */
        .input-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 8px; color: var(--dark); font-size: 14px; }
        input, select { width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px; }
        input:focus { border-color: var(--primary); outline: none; }

        .guest-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .guest-grid small { font-weight: 800; color: var(--secondary); font-size: 10px; display: block; margin-bottom: 5px; }

        .btn-submit { 
            width: 100%; padding: 16px; background: var(--primary); color: white; border: none; 
            border-radius: 12px; font-size: 18px; font-weight: bold; cursor: pointer; transition: 0.3s;
        }
        .btn-submit:hover { background: var(--dark); transform: scale(1.02); }

        footer { background: var(--dark); color: #fed7aa; text-align: center; padding: 40px; border-top: 4px solid var(--accent); }
    </style>
</head>
<body>

<header>
    <img src="hg.jpeg" class="logo" alt="Logo">
    <h1>City Catering Management System</h1>
</header>

<div class="navbar">
    <ul>
        <li><a href="booking.php">Back</a></li>
        <li><a href="venues.php">All Catering</a></li>
        <li><a href="search_result.php">🔍 Search food</a></li>
        <li><a href="search_event.php">🔍 Search event</a></li>
        <li><a href="booking.php">bookings</a></li>
    </ul>
</div>

<div class="page-content">
    <div class="card">
        <h2 style="text-align:center; color:var(--primary); margin-bottom: 20px;">Register Your Booking</h2>
        
        <div class="selected-menu">
            <b>Your Selection:</b>
            <p style="font-size: 14px;"><?php echo !empty($food_string) ? $food_string : "None"; ?></p>
            <p style="margin-top:10px; font-weight:bold; color:var(--accent);">Plate Price: ₹<?php echo number_format($package_price, 2); ?></p>
        </div>

        <form action="save_booking.php" method="POST">
            <div class="input-group">
                <label>Order ID</label>
                <input type="text" name="order_id" value="<?php echo $generated_id; ?>" readonly style="background:#f8fafc;">
            </div>

            <input type="hidden" name="oid" value="<?php echo $oid; ?>">
            <input type="hidden" name="event_type" value="<?php echo $event; ?>">
            <input type="hidden" name="price_plate" value="<?php echo $package_price; ?>">
            <input type="hidden" name="extra_items" value="<?php echo htmlspecialchars($food_string); ?>">

            <div class="input-group">
                <label>Full Name</label>
                <input type="text" name="customer_name" required placeholder="Enter Name">
            </div>

            <div class="input-group">
                <label>Mobile Number</label>
                <input type="text" name="customer_phone" maxlength="10" required placeholder="Enter Mobile">
            </div>

            <div class="input-group">
                <label>Date of Event</label>
                <input type="date" name="event_date" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            
            <?php if (strpos(strtolower($event), 'wedding') !== false): ?>
                <label>Guest Counts</label>
                <div class="guest-grid">
                    <div><small>MORNING</small><input type="number" name="m_morning" value="0"></div>
                    <div><small>AFTERNOON</small><input type="number" name="m_afternoon" value="0"></div>
                    <div><small>NIGHT</small><input type="number" name="m_night" value="0"></div>
                </div>
            <?php else: ?>
                <div class="input-group">
                    <label>Select Session</label>
                    <select name="selected_session">
                        <option value="Morning">Morning</option>
                        <option value="Afternoon">Afternoon</option>
                        <option value="Night">Night</option>
                    </select>
                </div>
                <div class="input-group">
                    <label>Guest Count</label>
                    <input type="number" name="single_guest_count" value="1" min="1">
                </div>
            <?php endif; ?>

            <div class="input-group" style="margin-top:15px;">
                <label>Venue Address</label>
                <input type="text" name="location_coords" required placeholder="Hall Name / City">
            </div>
            
            <button type="submit" class="btn-submit">Confirm Booking</button>
        </form>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> City Catering Management System. All Rights Reserved.</p>
</footer>

</body>
</html>
