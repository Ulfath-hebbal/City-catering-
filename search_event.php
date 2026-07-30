 <?php
session_start();
/* DB CONNECTION */
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Database Connection Error"); }

/* FETCH ALL UNIQUE EVENT TYPES FOR THE DROPDOWN OPTIONS */
$event_options = array();
$opt_sql = "SELECT DISTINCT meal_type FROM manu WHERE meal_type != '' ORDER BY meal_type ASC";
$opt_rs = mysqli_query($con, $opt_sql);
while($opt_row = mysqli_fetch_assoc($opt_rs)) {
    $event_options[] = $opt_row['meal_type'];
}

/* LOGIC FOR EVENT SEARCH */
$event_search = isset($_GET['event']) ? mysqli_real_escape_string($con, $_GET['event']) : '';

$data = array();
if (!empty($event_search)) {
    $sql = "SELECT owner.*, manu.food, manu.price, manu.meal_type 
            FROM owner 
            INNER JOIN manu ON owner.oid = manu.oid 
            WHERE owner.status='Active' 
            AND LOWER(manu.meal_type) LIKE LOWER('%$event_search%')
            ORDER BY owner.csname ASC";
    
    $rs = mysqli_query($con, $sql);

    if ($rs) {
        while ($row = mysqli_fetch_assoc($rs)) {
            $oid = $row['oid'];
            $event_label = $row['meal_type'];
            if (!isset($data[$oid])) {
                $data[$oid]['info'] = $row;
            }
            $data[$oid]['events'][$event_label][] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Events - City Catering</title>
    <style>
        :root {
            --primary: #088f62;
            --secondary: #076e93;
            --accent: #f97316;
            --dark: #011811;
            --light: #fff7ed;
            --glass: rgba(1, 24, 17, 0.85);
        }

        /* FOOTER FIX: Push footer to bottom */
        html, body { height: 100%; }
        body { 
            background: var(--light); 
            color: #334155; 
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        /* CONTENT WRAPPER */
        .page-wrapper { flex: 1 0 auto; }

        header { 
            background: linear-gradient(135deg, var(--primary), var(--secondary)); 
            color: white; 
            text-align: center; 
            padding: 40px 10px 25px 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        
        header h1 {
            font-family: 'Forte', sans-serif;
            font-size: 2.8rem;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.2);
            margin-top: 10px;
        }

        .logo { width: 90px; height: 90px; border-radius: 50%; border: 3px solid white; margin-bottom: 10px; object-fit: cover; transition: 0.5s; }
        .logo:hover { transform: rotate(360deg); }

        .navbar { 
            background: var(--glass); 
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            display: flex; 
            
            align-items: center;
            padding: 0 50px;
            position: sticky; 
            top: 0; 
            z-index: 1000; 
            height: 70px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .navbar ul { list-style: none; display: flex; align-items: center; gap: 5px; }
        .navbar ul li a { 
            display: block; padding: 10px 20px; color: #f1f5f9; text-decoration: none; 
            font-weight: 500; text-transform: uppercase; font-size: 0.85rem; transition: 0.3s; border-radius: 8px;
        }
        .navbar ul li a:hover, .navbar ul li a.active { background: rgba(255,255,255,0.1); color: var(--accent); }

        .main-layout { display: flex; max-width: 1300px; margin: 40px auto; gap: 30px; padding: 0 20px; }

        /* SIDEBAR STYLING */
        .sidebar { 
            width: 300px; background: white; border-radius: 20px; padding: 25px; height: fit-content; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.02);
        }
        .sidebar h3 { color: var(--primary); font-size: 20px; margin-bottom: 20px; border-bottom: 3px solid var(--accent); padding-bottom: 8px; }
        
        .event-select { 
            width: 100%; padding: 12px; border-radius: 10px; border: 2px solid #e2e8f0; 
            font-size: 15px; background: #f8fafc; cursor: pointer; outline: none; transition: 0.3s;
        }
        .event-select:focus { border-color: var(--primary); }

        .btn-filter { 
            width: 100%; margin-top: 15px; padding: 12px; background: var(--primary); color: white; 
            border: none; border-radius: 10px; font-weight: bold; cursor: pointer; transition: 0.3s;
        }
        .btn-filter:hover { background: var(--dark); transform: translateY(-2px); }

        .content-area { flex: 1; }
        .results-container { display: flex; flex-wrap: wrap; gap: 25px; justify-content: center; }
        
        /* CARD STYLING */
        .card { 
            width: 340px; background: white; border-radius: 20px; overflow: hidden; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); transition: 0.4s; border: 1px solid rgba(0,0,0,0.02); 
        }
        .card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.12); }
        .card img { width: 100%; height: 180px; object-fit: cover; }
        .card-body { padding: 20px; }
        .card-title { color: var(--primary); font-size: 20px; font-weight: 700; margin-bottom: 5px; }
        
        .event-box { margin-top: 15px; border-radius: 12px; padding: 15px; background: #f8fafc; border: 1px solid #f1f5f9; }
        .event-label { font-size: 11px; font-weight: 800; color: var(--secondary); text-transform: uppercase; border-bottom: 2px solid #e2e8f0; padding-bottom: 5px; margin-bottom: 10px; display: block; }
        .food-row { display: flex; justify-content: space-between; margin-top: 8px; font-size: 14px; color: #475569; }
        .price { color: var(--accent); font-weight: 700; }
        
        .empty-state { 
            text-align: center; color: #64748b; padding: 60px; background: white; 
            border-radius: 20px; border: 2px dashed #cbd5e1; width: 100%;
        }

        /* FOOTER */
        footer {
            flex-shrink: 0;
            background: var(--dark);
            color: #fed7aa;
            text-align: center; 
            padding: 40px 20px; 
            border-top: 4px solid var(--accent);
        }

        @media (max-width: 900px) {
            .main-layout { flex-direction: column; }
            .sidebar { width: 100%; }
            .navbar { height: auto; padding: 20px; }
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    <header>
        <img src="hg.jpeg" class="logo">
        <h1>City Catering Management System</h1>
    </header>

    <div class="navbar">
        <ul>
            <li><a href="publicmain.php">Back</a></li>
            <li><a href="venues.php">View All catering</a></li>
            <li><a href="search_result.php">🔍 Search Food</a></li>
            <li><a href="search_event.php" >🔍 Search Event</a></li>
            <li><a href="booking.php">Bookings</a></li>
        </ul>
    </div>

    <div class="main-layout">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <h3>Availble Event</h3>
            <form action="search_event.php" method="GET">
                <select name="event" class="event-select">
                    <option value="">-- Select Event Type --</option>
                    <?php foreach($event_options as $opt): ?>
                        <option value="<?php echo htmlspecialchars($opt); ?>" <?php if($event_search == $opt) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($opt); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn-filter">Show Menus</button>
            </form>
        </aside>

        <!-- CONTENT AREA -->
        <div class="content-area">
            <div class="results-container">
                <?php if (empty($event_search)): ?>
                    <div class="empty-state">
                        <h2 style="color: var(--primary);">Discover Menus</h2>
                        <p>Select an event type from the sidebar to see what our caterers offer.</p>
                    </div>
                <?php elseif (empty($data)): ?>
                    <div class="empty-state">
                        <h3>No menus found for "<?php echo htmlspecialchars($event_search); ?>"</h3>
                        <p>Try searching a different event category.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($data as $oid => $res): ?>
                        <div class="card">
                            <img src="<?php echo !empty($res['info']['img']) ? $res['info']['img'] : 'https://unsplash.com'; ?>">
                            <div class="card-body">
                                <div class="card-title"><?php echo htmlspecialchars($res['info']['csname']); ?></div>
                                <p style="font-size: 13px; color: #64748b; margin-bottom: 10px;">📍 
                                    <?php 
                                    if(!empty($res['info']['oadd'])) { echo htmlspecialchars($res['info']['oadd']); }
                                    else { echo "Location not specified"; }
                                    ?>
                                </p>
                                
                                <?php foreach ($res['events'] as $eventName => $items): ?>
                                    <div class="event-box">
                                        <span class="event-label"><?php echo htmlspecialchars($eventName); ?></span>
                                        <?php foreach ($items as $item): ?>
                                            <div class="food-row">
                                                <span><?php echo htmlspecialchars($item['food']); ?></span>
                                                <span class="price">₹<?php echo number_format($item['price'], 0); ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> City Catering Management System. All Rights Reserved.</p>
</footer>

</body>
</html>
