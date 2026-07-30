 <?php
session_start();
$con = mysqli_connect("localhost","root","","citycatering");
if (!$con) { die("Database Connection Error"); }

$is_logged_in = isset($_SESSION['cname']);
$oid = isset($_GET['oid']) ? mysqli_real_escape_string($con, $_GET['oid']) : die("Invalid Access");

/* OWNER DATA */
$owner_query = mysqli_query($con,"SELECT * FROM owner WHERE oid='$oid'");
$owner = mysqli_fetch_assoc($owner_query);

/* MENU QUERY */
$menu_rs = mysqli_query($con, "SELECT * FROM manu WHERE oid='$oid'");

$data = array();
while($row = mysqli_fetch_assoc($menu_rs)){
    $type = $row['meal_type'];
    
    if(stripos($type, 'Wedding') !== false){
        $main = "Wedding";
        if(stripos($type, 'Morning') !== false) $sub = "Morning";
        elseif(stripos($type, 'Afternoon') !== false) $sub = "Afternoon";
        elseif(stripos($type, 'Night') !== false) $sub = "Night";
        else $sub = "General";
    } else {
        $main = $type; 
        $sub = "Menu Items";
    }
    $data[$main][$sub][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - <?php echo htmlspecialchars($owner['csname']); ?></title>
    <style>
        /* NEW PREMIUM CSS */
        :root {
            --primary: #064e3b;
            --secondary: #059669;
            --accent: #f97316;
            --bg: #fff7ed;
            --white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Roboto, sans-serif; }
        
        body { background: var(--bg); color: #1e293b; line-height: 1.6; }

        /* Sticky Navbar */
        .navbar {
            background: var(--primary);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .navbar .brand { color: white; font-size: 20px; font-weight: bold; text-decoration: none; }
        .navbar .user-info { display: flex; align-items: center; gap: 15px; }
        .navbar a.btn-back { color: white; text-decoration: none; font-size: 14px; background: rgba(255,255,255,0.1); padding: 5px 12px; border-radius: 5px; }
        .navbar .user-name { color: #fbbf24; font-weight: 600; font-size: 14px; }
        .btn-logout { background: #dc2626; color: white; padding: 6px 12px; border-radius: 5px; text-decoration: none; font-size: 13px; }

        /* Main Container */
        .container { max-width: 800px; margin: 30px auto; background: var(--white); border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); overflow: hidden; }
        
        .header-img { width: 100%; height: 350px; object-fit: cover; border-bottom: 5px solid var(--accent); }

        .content { padding: 40px; }
        .content h2 { color: var(--primary); font-size: 32px; margin-bottom: 10px; display: flex; align-items: center; gap: 10px; }
        .content h2::before { content: '🍴'; font-size: 24px; }

        .service-loc { color: #64748b; margin-bottom: 30px; font-size: 15px; }

        /* Menu Boxes */
        .event-box { 
            background: #fafafa; 
            border: 1px solid #f1f5f9;
            border-radius: 15px; 
            padding: 25px; 
            margin-top: 30px; 
            transition: 0.3s;
        }
        .event-box:hover { border-color: var(--accent); box-shadow: 0 10px 20px rgba(0,0,0,0.02); }
        
        .event-title { 
            font-size: 24px; 
            color: var(--primary); 
            font-weight: 800; 
            border-left: 5px solid var(--accent); 
            padding-left: 15px;
            margin-bottom: 20px; 
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sub-header { 
            font-size: 14px; 
            color: var(--accent); 
            font-weight: bold; 
            margin: 20px 0 10px 0; 
            background: #fff7ed;
            display: inline-block;
            padding: 2px 10px;
            border-radius: 4px;
        }

        .item { 
            display: flex; 
            justify-content: space-between; 
            padding: 12px 0; 
            border-bottom: 1px solid #f1f5f9; 
            font-size: 16px;
        }
        .item span:first-child { color: #334155; font-weight: 500; }
        .price { color: var(--secondary); font-weight: bold; }

        /* Modern Form */
        .order-form { 
            margin-top: 30px; 
            padding: 25px; 
            background: #f8fafc; 
            border-radius: 12px; 
            border-top: 4px solid var(--primary); 
        }
        .order-form h4 { color: var(--primary); margin-bottom: 15px; }
        .order-form label { display: block; font-size: 13px; font-weight: 600; color: #475569; margin-top: 15px; margin-bottom: 5px; }
        
        .order-form input, .order-form textarea { 
            width: 100%; 
            padding: 12px 15px; 
            border: 1px solid #cbd5e1; 
            border-radius: 8px; 
            font-size: 15px; 
            transition: 0.3s;
            background: white;
        }
        .order-form input:focus, .order-form textarea:focus { outline: none; border-color: var(--secondary); box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1); }
        
        .order-form textarea { resize: vertical; min-height: 100px; }

        .btn-order { 
            background: var(--primary); 
            color: white; 
            border: none; 
            width: 100%; 
            padding: 16px; 
            margin-top: 25px; 
            border-radius: 8px; 
            font-weight: bold; 
            font-size: 16px;
            cursor: pointer; 
            transition: 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .btn-order:hover { background: var(--accent); transform: translateY(-2px); }

        .login-msg { text-align: center; color: #ef4444; padding: 20px; font-weight: 600; border: 1px dashed #fecaca; border-radius: 10px; margin-top: 20px; }
    </style>
</head>
<body>

<!-- NAVIGATION -->
<div class="navbar">
    <div style="display:flex; align-items:center; gap:15px;">
        <a href="venues.php" class="btn-back">← Back</a>
        <a href="#" class="brand">City Catering</a>
    </div>
    
    <div class="user-info">
        <?php if($is_logged_in): ?>
            <span class="user-name">👤 <?php echo htmlspecialchars($_SESSION['cname']); ?></span>
            <a href="logout.php" class="btn-logout">Logout</a>
        <?php else: ?>
            <a href="hh.html" class="btn-back" style="background:var(--accent)">Login to Order</a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <img src="<?php echo $owner['img']; ?>" class="header-img" onerror="this.src='https://placeholder.com'">
    
    <div class="content">
        <h2><?php echo htmlspecialchars($owner['csname']); ?></h2>
        <p class="service-loc">📍 Location: <?php echo htmlspecialchars($owner['oadd']); ?></p>

        <?php foreach($data as $mainE => $subs): ?>
            <div class="event-box">
                <div class="event-title"><?php echo $mainE; ?> Packages</div>
                
                <?php foreach($subs as $subName => $items): ?>
                    <div class="sub-header"><?php echo $subName; ?> Selection</div>
                    <?php foreach($items as $it): ?>
                        <div class="item">
                            <span><?php echo htmlspecialchars($it['food']); ?></span>
                            <span class="price">₹<?php echo number_format($it['price'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <?php if($is_logged_in): ?>
                    <form class="order-form" method="POST" action="order.php">
                        <h4>Book this Event</h4>
                        <input type="hidden" name="oid" value="<?php echo $oid; ?>">
                        <input type="hidden" name="event_name" value="<?php echo $mainE; ?>">
                        
                        <label>Expected Event Date</label>
                        <input type="date" name="event_date" required>

                        <?php if($mainE == "Wedding"): ?>
                            <label>Morning Guests (Breakfast)</label>
                            <input type="number" name="members_morning" placeholder="0" min="0">
                            
                            <label>Afternoon Guests (Lunch)</label>
                            <input type="number" name="members_afternoon" placeholder="0" min="0">
                            
                            <label>Night Guests (Dinner)</label>
                            <input type="number" name="members_night" placeholder="0" min="0">
                        <?php else: ?>
                            <label>Total Number of Guests</label>
                            <input type="number" name="total_guests" required placeholder="Ex: 50" min="1">
                        <?php endif; ?>

                        <label>Special Instructions (Extra items, spice level, etc.)</label>
                        <textarea name="extra_info" placeholder="Tell us if you have any specific requirements..."></textarea>

                        <button class="btn-order">Confirm Order Request</button>
                    </form>
                <?php else: ?>
                    <div class="login-msg">⚠️ Please Login to place an order for this package.</div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
