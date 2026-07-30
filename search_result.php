 <?php
session_start();
/* DB CONNECTION */
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Database Connection Error"); }

/* LOGIC: ONLY FETCH DATA IF SEARCH IS NOT EMPTY */
$food_search = isset($_GET['food']) ? mysqli_real_escape_string($con, $_GET['food']) : '';

$data = array();
if (!empty($food_search)) {
    $sql = "SELECT owner.*, manu.food, manu.price, manu.meal_type 
            FROM owner 
            INNER JOIN manu ON owner.oid = manu.oid 
            WHERE owner.status='Active' 
            AND (LOWER(manu.food) LIKE LOWER('%$food_search%') 
                 OR LOWER(manu.meal_type) LIKE LOWER('%$food_search%'))
            ORDER BY owner.csname ASC";
    
    $rs = mysqli_query($con, $sql);

    if ($rs) {
        while ($row = mysqli_fetch_assoc($rs)) {
            $oid = $row['oid'];
            $event = $row['meal_type'];
            if (!isset($data[$oid])) {
                $data[$oid]['info'] = $row;
            }
            $data[$oid]['events'][$event][] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Food & Events | City Catering</title>
    <style>
        :root {
            --primary: #088f62;
            --secondary: #076e93;
            --accent: #f97316;
            --dark: #011811;
            --light: #fff7ed;
            --glass: rgba(1, 24, 17, 0.85);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, sans-serif; }
        
        /* FOOTER FIX: Push footer to bottom */
        html, body { height: 100%; }
        body { 
            background: var(--light); 
            color: #334155; 
            line-height: 1.6;
            display: flex;
            flex-direction: column;
        }

        header { 
            background: linear-gradient(135deg, var(--primary), var(--secondary)); 
            color: white; 
            text-align: center; 
            padding: 50px 10px 30px 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        
        header h1 {
            font-family: 'Forte', sans-serif;
            font-size: 2.8rem;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.2);
            margin-top: 10px;
        }

        .logo { 
            width: 100px; 
            height: 100px; 
            border-radius: 50%; 
            border: 4px solid rgba(255,255,255,0.3); 
            margin-bottom: 10px;
            object-fit: cover;
        }
        
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
            display: block; 
            padding: 10px 20px; 
            color: #f1f5f9; 
            text-decoration: none; 
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            transition: all 0.3s ease; 
            border-radius: 8px;
        }

        .navbar ul li a:hover, .navbar ul li a.active { 
            background: rgba(255,255,255,0.1); 
            color: var(--accent); 
        }

        /* Main content wrapper */
        .content { flex: 1 0 auto; }

        .search-wrapper { 
            max-width: 650px; 
            margin: 40px auto; 
            background: white; 
            padding: 8px; 
            border-radius: 50px; 
            display: flex; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
            border: 2px solid var(--primary); 
        }

        .search-wrapper input { 
            flex: 1; border: none; outline: none; padding: 12px 25px; 
            font-size: 16px; border-radius: 50px; 
        }

        .search-wrapper button { 
            background: var(--accent); color: white; border: none; 
            padding: 10px 35px; border-radius: 50px; cursor: pointer; 
            font-weight: bold; transition: 0.3s;
        }

        .container { 
            max-width: 1200px; margin: 0 auto; display: flex; 
            flex-wrap: wrap; gap: 30px; justify-content: center; padding: 20px; 
        }

        .card { 
            width: 350px; background: white; border-radius: 20px; 
            overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08); 
            transition: all 0.4s ease; border: 1px solid rgba(0,0,0,0.02);
        }
        
        .card img { width: 100%; height: 200px; object-fit: cover; }
        .card-body { padding: 25px; }
        .card-title { color: var(--primary); font-size: 22px; font-weight: 700; }
        
        .event-box { 
            margin-top: 15px; border: 1px solid #f1f5f9; 
            border-radius: 12px; padding: 15px; background: #f8fafc; 
        }

        .event-label { 
            font-size: 11px; font-weight: 800; color: var(--secondary); 
            text-transform: uppercase; border-bottom: 2px solid #e2e8f0; 
            padding-bottom: 5px; margin-bottom: 10px; display: block; 
        }

        .food-row { display: flex; justify-content: space-between; margin-top: 8px; font-size: 14px; }
        .price { color: var(--accent); font-weight: 700; }
        
        /* FOOTER STYLES */
        footer {
            flex-shrink: 0;
            background: var(--dark);
            color: #fed7aa;
            text-align: center; 
            padding: 40px 20px; 
            border-top: 4px solid var(--accent);
        }

        @media (max-width: 768px) {
            .navbar { height: auto; padding: 20px; flex-direction: column; }
            .search-wrapper { width: 95%; }
        }
    </style>
</head>
<body>

<div class="content">
    <header>
        <img src="hg.jpeg" class="logo" alt="Logo">
        <h1>City Catering Management System</h1>
    </header>

    <div class="navbar">
        <ul>
            <li><a href="publicmain.php">Back</a></li>
            <li><a href="venues.php">View All catering</a></li>
            <li><a href="search_result.php" >🔍 Search Food</a></li>
            <li><a href="search_event.php">🔍 Search Event</a></li>
            <li><a href="booking.php">Bookings</a></li>
        </ul>
    </div>

    <form action="" method="GET" class="search-wrapper">
        <input type="text" name="food" placeholder="Search for food or meal type..." value="<?php echo htmlspecialchars($food_search); ?>" required>
        <button type="submit">Search</button>
    </form>

    <div class="container">
        <?php if (empty($food_search)): ?>
            <div style="text-align: center; color: #64748b; margin-top: 50px; width: 100%;">
                <h3 style="font-size: 24px;">🥗 Enter a food name to see menus.</h3>
            </div>
        <?php elseif (empty($data)): ?>
            <div style="text-align: center; color: #64748b; margin-top: 50px; width: 100%;">
                <h3 style="font-size: 24px;">😕 No results for "<?php echo htmlspecialchars($food_search); ?>".</h3>
            </div>
        <?php else: ?>
            <?php foreach ($data as $oid => $res): ?>
                <div class="card">
                    <img src="<?php echo !empty($res['info']['img']) ? $res['info']['img'] : 'https://unsplash.com'; ?>">
                    <div class="card-body">
                        <div class="card-title"><?php echo htmlspecialchars($res['info']['csname']); ?></div>
                        <p style="font-size: 13px; color: #64748b; margin-bottom: 10px;">📍 <?php echo htmlspecialchars($res['info']['oadd']); ?></p>
                        
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

<footer>
    <p>&copy; <?php echo date("Y"); ?> City Catering Management System. All Rights Reserved.</p>
</footer>

</body>
</html>
