 <?php
session_start();
$con = mysqli_connect("localhost", "root", "", "citycatering");

// Check if user is logged in (used for button logic, not for blocking access)
$is_logged_in = isset($_SESSION['cname']);

// Get parameters
$oid = isset($_GET['oid']) ? mysqli_real_escape_string($con, $_GET['oid']) : '';
$event_type = isset($_GET['event']) ? mysqli_real_escape_string($con, $_GET['event']) : '';

// Fetch Catering Service Name
$owner_res = mysqli_query($con, "SELECT csname FROM owner WHERE oid = '$oid'");
$owner_data = mysqli_fetch_assoc($owner_res);

// Define Meal Slots logic
if ($event_type == 'Wedding') {
    $slots = array("Morning Feast" => "Morning", "Afternoon Feast" => "Afternoon", "Night Feast" => "Night");
} else {
    $selected_slot = isset($_POST['preferred_slot']) ? $_POST['preferred_slot'] : 'Morning';
    $slots = array($event_type . " ($selected_slot)" => $selected_slot);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu - <?php echo htmlspecialchars($owner_data['csname']); ?></title>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #fff7ed; padding-bottom: 120px; }
        header { background: linear-gradient(to right, #088f62, #076e93); color: white; text-align: center; padding: 25px; }
        .container { width: 95%; max-width: 1200px; margin: 30px auto; }
        .selection-panel { background: white; padding: 20px; border-radius: 12px; margin-bottom: 30px; display: flex; gap: 20px; align-items: flex-end; border-left: 5px solid #f97316; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .meal-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; }
        .price-panel { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; }
        .panel-header { padding: 15px; text-align: center; color: white; background: #076e93; font-weight: bold; }
        .food-list { padding: 20px; height: 160px; overflow-y: auto; }
        .plate-price { background: #f0fdf4; color: #16a34a; text-align: center; padding: 15px; font-size: 20px; font-weight: 800; border-top: 1px solid #eee; }
        .footer-bar { position: fixed; bottom: 0; width: 100%; background: #011811; padding: 20px; text-align: center; color: white; }
        .confirm-btn { background: #16a34a; color: white; border: none; padding: 12px 50px; font-size: 18px; font-weight: bold; border-radius: 50px; cursor: pointer; text-decoration:none; display:inline-block; }
        .login-btn { background: #f97316; color: white; border: none; padding: 12px 50px; font-size: 18px; font-weight: bold; border-radius: 50px; text-decoration:none; display:inline-block; }
    </style>
</head>
<body>

<header>
    <h1><?php echo htmlspecialchars($owner_data['csname']); ?> Menu</h1>
    <p>Event: <?php echo htmlspecialchars($event_type); ?></p>
</header>

<div class="container">
    <form method="POST" class="selection-panel">
        <div><label>Event Date</label><br><input type="date" name="event_date" style="padding:10px; border-radius:5px; border:1px solid #ccc;" min="<?php echo date('Y-m-d'); ?>"></div>
        <?php if($event_type != 'Wedding'): ?>
        <div><label>Time Slot</label><br>
            <select name="preferred_slot" style="padding:10px; border-radius:5px;">
                <option value="Morning">Morning</option>
                <option value="Afternoon">Afternoon</option>
                <option value="Night">Night</option>
            </select>
        </div>
        <button type="submit" style="padding:10px 20px; background:#076e93; color:white; border:none; border-radius:5px; cursor:pointer;">Update</button>
        <?php endif; ?>
    </form>

    <form action="order.php" method="POST">
        <div class="meal-grid">
            <?php
            foreach($slots as $displayName => $searchTerm) {
                $q = ($event_type == 'Wedding') ? "SELECT * FROM manu WHERE oid='$oid' AND meal_type LIKE 'Wedding (%$searchTerm%)'" : "SELECT * FROM manu WHERE oid='$oid' AND meal_type = '$event_type'";
                $res = mysqli_query($con, $q);
                if(mysqli_num_rows($res) > 0) {
                    echo "<div class='price-panel'><div class='panel-header'>$displayName</div><div class='food-list'>";
                    $total_rate = 0;
                    while($f = mysqli_fetch_assoc($res)) { 
                        echo "<div style='display:flex; justify-content:space-between; margin-bottom:5px;'><span>".$f['food']."</span><span>₹".$f['price']."</span></div>"; 
                        $total_rate += $f['price']; 
                    }
                    echo "</div><div class='plate-price'>₹$total_rate / Plate</div>";
                    
                    if($is_logged_in) {
                        echo "<div style='padding:15px; border-top:1px solid #eee;'>
                                <label>GUESTS: </label><input type='number' name='qty[]' min='1' required style='width:80px; padding:5px;'>
                                <input type='hidden' name='meal_type[]' value='$displayName'>
                                <input type='hidden' name='rate[]' value='$total_rate'>
                              </div>";
                    }
                    echo "</div>";
                }
            }
            ?>
        </div>

        <div class="footer-bar">
            <?php if($is_logged_in): ?>
                <button type="submit" class="confirm-btn">CONFIRM ORDER</button>
            <?php else: ?>
                <a href="hh.html" class="login-btn">LOGIN TO BOOK NOW</a>
            <?php endif; ?>
        </div>
    </form>
</div>

</body>
</html>
