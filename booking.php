 <?php
session_start();
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Database Connection Error"); }

/* 1. GET SIDEBAR NAMES (FOR LIST) */
$sidebar_names = array();
$side_sql = "SELECT oid, csname FROM owner WHERE status='Active' ORDER BY csname ASC";
$side_rs = mysqli_query($con, $side_sql);
while($srow = mysqli_fetch_assoc($side_rs)) { $sidebar_names[] = $srow; }

/* 2. MAIN SEARCH & GROUPING LOGIC */
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
$data = array();

if (!empty($search_query)) {
    $sql = "SELECT owner.*, manu.food, manu.price, manu.meal_type 
            FROM owner INNER JOIN manu ON owner.oid = manu.oid 
            WHERE owner.status='Active' AND LOWER(owner.csname) LIKE LOWER('%$search_query%')
            ORDER BY owner.csname ASC";
    $rs = mysqli_query($con, $sql);
    
    if ($rs) {
        while ($row = mysqli_fetch_assoc($rs)) {
            $oid = $row['oid'];
            $m_type = $row['meal_type'];
            
            // Logic: Group Wedding sessions into one box, others stay separate
            if (strpos(strtolower($m_type), 'wedding') !== false) {
                $event_key = "Wedding Package";
                $section_key = $m_type; 
            } else {
                $event_key = ucwords($m_type);
                $section_key = "Menu Items"; 
            }

            if (!isset($data[$oid])) { $data[$oid]['info'] = $row; }
            $data[$oid]['events'][$event_key][$section_key][] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking | City Catering</title>
    <style>
        /* THEME VARIABLES */
        :root {
            --primary: #088f62;
            --secondary: #076e93;
            --accent: #f97316;
            --dark: #011811;
            --light: #fff7ed;
            --glass: rgba(1, 24, 17, 0.85);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, sans-serif; }
        
        body { background: var(--light); color: #334155; line-height: 1.6; }

        /* HEADER */
        header { 
            background: linear-gradient(135deg, var(--primary), var(--secondary)); 
            color: white; text-align: center; padding: 40px 10px 30px 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        header h1 { font-family: 'Forte', sans-serif; font-size: 2.8rem; text-shadow: 2px 2px 5px rgba(0,0,0,0.2); }
        .logo { width: 90px; height: 90px; border-radius: 50%; border: 4px solid rgba(255,255,255,0.3); margin-bottom: 10px; object-fit: cover; }
        
        /* GLASS NAVBAR */
        .navbar { 
            background: var(--glass); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            display: flex; justify-content: space-between; align-items: center;
            padding: 0 50px; position: sticky; top: 0; z-index: 1000; height: 70px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .navbar ul { list-style: none; display: flex; align-items: center; gap: 10px; }
        .navbar ul li a, .dropbtn { 
            display: block; padding: 10px 18px; color: #f1f5f9; text-decoration: none; 
            font-weight: 500; text-transform: uppercase; font-size: 0.8rem; transition: 0.3s; border-radius: 8px;
            cursor: pointer;
        }
        .navbar ul li a:hover, .dropbtn:hover { background: rgba(255,255,255,0.1); color: var(--accent); }

        /* DROPDOWN LOGIC */
        summary { list-style: none; outline: none; }
        summary::-webkit-details-marker { display: none; }
        .dropdown { position: relative; }
        .dropdown-content {
            position: absolute; top: 60px; left: 0; background: var(--glass);
            min-width: 200px; box-shadow: 0px 8px 16px rgba(0,0,0,0.3);
            border-radius: 10px; display: flex; flex-direction: column; overflow: hidden;
            backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.1);
        }
        .dropdown-content a { padding: 12px 20px !important; color: #f1f5f9 !important; font-size: 13px !important; text-transform: none !important; }
        .dropdown-content a:hover { background: var(--primary); color: white !important; }

        /* LAYOUT */
        .main-layout { display: flex; max-width: 1400px; margin: 40px auto; gap: 20px; padding: 0 20px; }
        
        .sidebar { 
            width: 280px; background: white; border-radius: 20px; padding: 25px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); height: fit-content;
        }
        .sidebar h3 { margin-bottom: 15px; color: var(--primary); border-bottom: 3px solid var(--accent); padding-bottom: 8px; font-size: 18px; }
        .sidebar a { 
            display: block; padding: 10px; background: #f8fafc; margin-bottom: 6px; 
            border-radius: 8px; text-decoration: none; color: #475569; font-size: 13px; 
            transition: 0.3s; border-left: 4px solid transparent;
        }
        .sidebar a:hover { border-left-color: var(--accent); background: #f1f5f9; transform: translateX(5px); }

        /* RESULTS GRID */
        .results-grid { flex: 1; display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 20px; }
        
        .event-box { 
            background: white; border-radius: 20px; padding: 25px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); border-top: 6px solid var(--primary);
            transition: 0.3s;
        }
        .event-box:hover { transform: translateY(-5px); }

        .section-header { 
            background: #f0f9ff; padding: 8px 12px; font-size: 12px; font-weight: bold; 
            color: var(--secondary); margin: 15px 0 10px 0; border-radius: 6px; border-left: 4px solid var(--accent); 
        }

        .item-row { 
            display: flex; align-items: center; gap: 10px; padding: 10px 5px; 
            border-bottom: 1px dashed #e2e8f0; cursor: pointer; font-size: 14px;
        }
        .item-row:hover { background: #fafafa; }
        .price { margin-left: auto; color: var(--accent); font-weight: bold; }

        .order-btn { 
            width: 100%; padding: 12px; background: var(--primary); color: white; 
            border: none; border-radius: 10px; font-weight: bold; cursor: pointer; 
            margin-top: 20px; font-size: 14px; transition: 0.3s;
        }
        .order-btn:hover { background: var(--dark); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }

        footer {
            background: var(--dark); color: #fed7aa;
            text-align: center; padding: 50px 20px; border-top: 4px solid var(--accent);
            margin-top: 300px;
        }

        input[type="checkbox"] { accent-color: var(--primary); transform: scale(1.2); }
    </style>
</head>
<body>

<header>
    <img src="hg.jpeg" class="logo" alt="Logo">
    <h1>City Catering Management System</h1>
</header>

<div class="navbar">
    <ul>
        <li><a href="publicmain.php">Back</a></li>
        <li><a href="venues.php">View All Catering</a></li>
        <li><a href="search_result.php">🔍 Search Food</a></li>
        <li><a href="search_event.php">🔍 Search event</a></li>
        <li class="dropdown">
            <details>
                <summary class="dropbtn">Manage Bookings ▾</summary>
                <div class="dropdown-content">
                    <a href="hh.php">📅 View My Bookings</a>
                    <a href="custlog.php">💰 View Payments</a>
                </div>
            </details>
        </li>
    </ul>
</div>

<div class="main-layout">
    <aside class="sidebar">
        <h3>Caterers List</h3>
        <?php foreach($sidebar_names as $name): ?>
            <a href="booking.php?search=<?php echo urlencode($name['csname']); ?>">🍴 <?php echo htmlspecialchars($name['csname']); ?></a>
        <?php endforeach; ?>
    </aside>

    <div class="results-grid">
        <?php if (!empty($data)): ?>
            <?php foreach ($data as $oid => $res): ?>
                <?php foreach ($res['events'] as $eventName => $sections): ?>
                    <div class="event-box">
                        <h2 style="color:var(--primary); margin-bottom:5px;"><?php echo $eventName; ?></h2>
                        <p style="font-size:12px; color:#64748b; margin-bottom:15px;">Provided by: <b><?php echo $res['info']['csname']; ?></b></p>
                        
                        <form action="public_registration.php" method="POST">
                            <input type="hidden" name="oid" value="<?php echo $oid; ?>">
                            <input type="hidden" name="event_type" value="<?php echo $eventName; ?>">
                            <input type="hidden" name="catering_service" value="<?php echo $res['info']['csname']; ?>">

                            <?php foreach ($sections as $sectionName => $items): ?>
                                <?php if($eventName == "Wedding Package"): ?>
                                    <div class="section-header"><?php echo $sectionName; ?></div>
                                <?php endif; ?>

                                <?php foreach ($items as $i): ?>
                                    <label class="item-row">
                                        <input type="checkbox" name="food_items[]" value="<?php echo htmlspecialchars($i['food']." (".$sectionName.")"); ?>">
                                        <span style="flex:1;"><?php echo htmlspecialchars($i['food']); ?></span>
                                        <b class="price">₹<?php echo number_format($i['price'], 0); ?></b>
                                    </label>
                                <?php endforeach; ?>
                            <?php endforeach; ?>

                            <button type="submit" class="order-btn">Book Package Now</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 80px; background: white; border-radius: 20px; border: 2px dashed #cbd5e1; color: #64748b;">
                <h3>Start Your Search</h3>
                <p>Select a caterer from the sidebar to view detailed menu sessions.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> City Catering Management System. All Rights Reserved.</p>
</footer>

</body>
</html>
