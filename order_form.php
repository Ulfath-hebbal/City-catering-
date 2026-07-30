 <?php
// Backward compatible session start for older PHP versions
if (session_id() == '') {
    session_start();
}

/* DB CONNECTION */
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Database Connection Error"); }

/* 1. SECURE THE PAGE */
if (!isset($_SESSION['cname'])) {
    header("Location: hh.html");
    exit();
}

// Get Parameters from URL
$oid = isset($_GET['oid']) ? mysqli_real_escape_string($con, $_GET['oid']) : '';
$event = isset($_GET['event']) ? mysqli_real_escape_string($con, $_GET['event']) : 'General Event';

/* 2. LOAD CUSTOMER DETAILS */
$current_user = $_SESSION['cname'];
$user_phone = "Not Provided";
$user_full_name = $current_user;

$user_query = "SELECT * FROM custemer WHERE cname='$current_user'";
$user_res = mysqli_query($con, $user_query);
if ($user_res && mysqli_num_rows($user_res) > 0) {
    $u_row = mysqli_fetch_assoc($user_res);
    $user_full_name = $u_row['cname'];
    if(isset($u_row['phnum'])) { $user_phone = $u_row['phnum']; }
}

// Fetch Caterer Name & Menu Prices
$caterer_name = "Catering Service";
$package_price_per_plate = 0;
if(!empty($oid)) {
    $res = mysqli_query($con, "SELECT csname FROM owner WHERE oid='$oid'");
    if($row = mysqli_fetch_assoc($res)) { $caterer_name = $row['csname']; }
    
    $price_res = mysqli_query($con, "SELECT SUM(price) as total_p FROM manu WHERE oid='$oid' AND LOWER(meal_type) LIKE LOWER('%$event%')");
    $p_row = mysqli_fetch_assoc($price_res);
    $package_price_per_plate = $p_row['total_p'] ? $p_row['total_p'] : 0;
    
    if($package_price_per_plate == 0) {
        $fallback = mysqli_query($con, "SELECT MIN(price) as min_p FROM manu WHERE oid='$oid'");
        $f_row = mysqli_fetch_assoc($fallback);
        $package_price_per_plate = $f_row['min_p'] ? $f_row['min_p'] : 100;
    }
}

/* 3. HANDLE ORDER SUBMISSION */
$msg = "";
if(isset($_POST['submit_order'])) {
    $cust_name = mysqli_real_escape_string($con, $_POST['customer_name']);
    $event_date = $_POST['event_date'];
    $location_coords = mysqli_real_escape_string($con, $_POST['location_coords']); // NEW FIELD
    
    if (strpos(strtolower($event), 'wedding') !== false) {
        $m_morning = (int)$_POST['members_morning'];
        $m_afternoon = (int)$_POST['members_afternoon'];
        $m_night = (int)$_POST['members_night'];
    } else {
        $selected_session = $_POST['session_choice'];
        $guest_count = (int)$_POST['single_guest_count'];
        $m_morning = ($selected_session == 'Morning') ? $guest_count : 0;
        $m_afternoon = ($selected_session == 'Afternoon') ? $guest_count : 0;
        $m_night = ($selected_session == 'Night') ? $guest_count : 0;
    }
    
    $total_guests = $m_morning + $m_afternoon + $m_night;
    $total_bill = $total_guests * $package_price_per_plate;
    
    $venue_info = !empty($_POST['extra_items']) ? $_POST['extra_items'] : "No specific instructions";
    $extra = mysqli_real_escape_string($con, $venue_info . " | Contact: " . $_POST['customer_phone']);
    
    // UPDATED QUERY TO INCLUDE location_coords
    $query = "INSERT INTO orders (customer_name, catering_service, oid, total_bill, event_date, event_type, members_morning, members_afternoon, members_night, extra_items, location_coords, status)
              VALUES ('$cust_name', '$caterer_name', '$oid', '$total_bill', '$event_date', '$event', '$m_morning', '$m_afternoon', '$m_night', '$extra', '$location_coords', 'Pending')";
    
    if(mysqli_query($con, $query)) {
        echo "<script>window.location.href='ordsucess.php?status=success&event=".urlencode($event)."';</script>";
        exit();
    } else {
        $msg = "<div style='background:#fee2e2; color:#dc2626; padding:15px; border-radius:8px;'>Error: " . mysqli_error($con) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking - City Catering</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f8fafc; margin: 0; }
        header { background: #fff; padding: 15px; text-align: center; border-bottom: 1px solid #e2e8f0; }
        .order-container { max-width: 600px; margin: 2rem auto; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .input-group { margin-bottom: 1.2rem; }
        .input-group label { display: block; font-weight: 600; color: #475569; margin-bottom: 0.4rem; }
        .input-group input, .input-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; }
        .guest-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        .bill-summary { background: #f0fdf4; border: 1px solid #bcf0da; padding: 1rem; border-radius: 8px; margin: 1.5rem 0; }
        .btn-submit { background: #059669; color: white; border: none; padding: 1rem; width: 100%; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 1rem; }
        .btn-submit:hover { background: #047857; }
        .location-link { font-size: 0.8rem; color: #2563eb; text-decoration: none; display: block; margin-top: 5px; }
    </style>
</head>
<body>

<header>
    <h1>City Catering Managemet System</h1>
</header>

<div class="order-container">
    <?php echo $msg; ?>
    <h2 style="margin-top:0;">Booking Details</h2>
    <p style="color: #64748b;">Event: <strong><?php echo htmlspecialchars($event); ?></strong> | Caterer: <strong><?php echo htmlspecialchars($caterer_name); ?></strong></p>

    <form method="POST">
        <div class="input-group">
            <label>Customer Name</label>
            <input type="text" name="customer_name" value="<?php echo htmlspecialchars($user_full_name); ?>" readonly>
        </div>

        <div class="input-group">
            <label>Event Date</label>
            <input type="date" name="event_date" required min="<?php echo date('Y-m-d'); ?>">
        </div>

        <?php if (strpos(strtolower($event), 'wedding') !== false): ?>
            <label style="display:block; margin-bottom:0.5rem; font-weight:600;">Guest Counts</label>
            <div class="guest-grid">
                <div><label style="font-size:0.8rem;">Morning</label><input type="number" name="members_morning" class="calc-input" value="0" min="0"></div>
                <div><label style="font-size:0.8rem;">Afternoon</label><input type="number" name="members_afternoon" class="calc-input" value="0" min="0"></div>
                <div><label style="font-size:0.8rem;">Night</label><input type="number" name="members_night" class="calc-input" value="0" min="0"></div>
            </div>
        <?php else: ?>
            <div class="input-group">
                <label>Select Session</label>
                <select name="session_choice" style="width:100%; padding:0.75rem; border-radius:8px; border:1px solid #cbd5e1;">
                    <option value="Morning">Morning</option>
                    <option value="Afternoon">Afternoon</option>
                    <option value="Night">Night</option>
                </select>
            </div>
            <div class="input-group">
                <label>Total Number of Guests</label>
                <input type="number" name="single_guest_count" class="calc-input" value="0" min="1" required>
            </div>
        <?php endif; ?>

        <div class="bill-summary">
            <p style="margin:0; font-size: 0.9rem; color: #166534;">Price per plate: ₹<span id="price_plate"><?php echo $package_price_per_plate; ?></span></p>
            <h3 style="margin: 0.5rem 0 0 0; color: #14532d;">Total Estimated Bill: ₹<span id="total_bill_ui">0</span></h3>
        </div>

        <div class="input-group">
            <label> Location Coords </label>
            <input type="text" name="location_coords" placeholder="enter address" required>
                 </div>

        <div class="input-group">
            <label>Extra Requirmentes</label>
            <textarea name="extra_items" rows="3" placeholder="exter requirmentes..."></textarea>
        </div>

        <input type="hidden" name="customer_phone" value="<?php echo htmlspecialchars($user_phone); ?>">
        <button type="submit" name="submit_order" class="btn-submit">Place Booking Order</button>
    </form>
</div>

<script>
    const ppp = parseFloat(document.getElementById('price_plate').innerText);
    const display = document.getElementById('total_bill_ui');
    const inputs = document.querySelectorAll('.calc-input');

    function calculate() {
        let totalGuests = 0;
        inputs.forEach(input => {
            totalGuests += parseInt(input.value) || 0;
        });
        const finalAmount = totalGuests * ppp;
        display.innerText = finalAmount.toLocaleString('en-IN');
    }

    inputs.forEach(input => {
        input.addEventListener('input', calculate);
    });
    
    calculate();
</script>

</body>
</html>
