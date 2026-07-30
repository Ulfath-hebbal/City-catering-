 <?php
session_start(); 
$con = mysqli_connect("localhost", "root", "", "citycatering");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch only active catering services
$query = "SELECT * FROM owner WHERE status='Active'";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Catering Services | City Catering</title>
    <style>
        /* MATCHING HOME PAGE THEME */
        :root {
            --primary: #088f62;
            --secondary: #076e93;
            --accent: #f97316;
            --dark: #011811;
            --light: #fff7ed;
            --glass: rgba(1, 24, 17, 0.85);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, sans-serif; }
        
        body { 
            background: var(--light); 
            color: #334155; 
            line-height: 1.6;
        }

        /* HEADER STYLE */
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
            transition: transform 0.6s ease;
            object-fit: cover;
        }
        .logo:hover { transform: rotate(360deg); }
        
        /* GLASSMORPHIC NAVBAR */
        .navbar { 
            background: var(--glass); 
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            padding: 0 50px;
            position: sticky; 
            top: 0; 
            z-index: 1000; 
            height: 70px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .navbar ul { list-style: none; display: flex; align-items: center; gap: 10px; }
        
        .navbar ul li a { 
            display: block; 
            padding: 10px 15px; 
            color: #f1f5f9; 
            text-decoration: none; 
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            transition: all 0.3s ease; 
            border-radius: 8px;
        }

        .navbar ul li a:hover { 
            background: rgba(255,255,255,0.1); 
            color: var(--accent); 
        }

        /* PAGE TITLE */
        .page-title {
            text-align: center; 
            color: var(--dark); 
            margin-top: 50px; 
            font-size: 32px;
            font-weight: 800;
        }

        /* CONTAINER & CARDS */
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            display: flex; 
            flex-wrap: wrap; 
            gap: 30px; 
            justify-content: center; 
            padding: 40px 20px; 
        }

        .card { 
            width: 320px; 
            background: white; 
            border-radius: 20px; 
            overflow: hidden; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); 
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            border: 1px solid rgba(0,0,0,0.02);
        }
        
        .card:hover { 
            transform: translateY(-12px); 
            box-shadow: 0 20px 40px rgba(0,0,0,0.12); 
        }

        .card img { width: 100%; height: 200px; object-fit: cover; }
        
        .card-body { padding: 25px; }

        .service-name { 
            font-weight: bold; 
            color: var(--primary); 
            font-size: 20px; 
            margin-bottom: 5px;
        }

        .owner-name { font-size: 14px; color: #64748b; margin-bottom: 10px; }

        .location-info { 
            font-size: 13px; 
            color: var(--secondary); 
            background: #f0f9ff;
            padding: 8px 12px;
            border-radius: 8px;
            display: inline-block;
            width: 100%;
        }
        
        /* FOOTER */
        footer {
            background: var(--dark);
            color: #fed7aa;
            text-align: center; 
            padding: 60px 20px; 
            margin-top: 80px;
            border-top: 4px solid var(--accent);
        }

        /* MOBILE RESPONSIVE */
        @media (max-width: 768px) {
            .navbar { height: auto; padding: 15px; flex-direction: column; }
            .navbar ul { flex-wrap: wrap; justify-content: center; }
            header h1 { font-size: 2.2rem; }
        }
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
        <li><a href="search_result.php">🔍 Search Food </a></li>
        <li><a href="search_event.php">🔍 Search Event </a></li>
        <li><a href="booking.php">Bookings</a></li>
    </ul>
</div>

<h2 class="page-title">Our Registered Catering Services</h2>

<div class="container">
    <?php while($row = mysqli_fetch_array($result)) { ?>
        <div class="card">
            <img src="<?php echo !empty($row['img']) ? $row['img'] : 'https://unsplash.com'; ?>" alt="Catering Service">
            <div class="card-body">
                <div class="service-name">🍴 <?php echo htmlspecialchars($row['csname']); ?></div>
                <div class="owner-name">👤 Owner: <?php echo htmlspecialchars($row['oname']); ?></div>
                
                <div class="location-info">
                    📍 <?php 
                        if(isset($row['oadd'])) {
                            echo htmlspecialchars($row['oadd']);
                        } elseif(isset($row['location'])) {
                            echo htmlspecialchars($row['location']);
                        } else {
                            echo "Location not specified";
                        }
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> City Catering Management System. All Rights Reserved.</p>
</footer>

</body>
</html>
