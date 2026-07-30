 <?php
session_start();
/* DB CONNECTION */
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) {
    die("Database Connection Error: " . mysqli_connect_error());
}

/* FETCH ACTIVE CATERERS */
$query = "SELECT * FROM owner WHERE status='Active'";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catering Services | City Catering</title>
    <style>
        /* CSS FROM HOME PAGE */
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
            color: #333; 
        }

        header { 
            background: linear-gradient(135deg, var(--primary), var(--secondary)); 
            color: white; 
            text-align: center; 
            padding: 40px 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        
        header h1 {
            font-family: 'Forte', sans-serif;
            font-size: 2.8rem;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.2);
        }

        .logo { 
            width: 100px; 
            height: 100px; 
            border-radius: 50%; 
            border: 4px solid rgba(255,255,255,0.3); 
            margin-bottom: 10px;
            transition: transform 0.6s ease;
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

        .navbar ul { list-style: none; display: flex; align-items: center; }
        
        .navbar ul li a { 
            display: block; 
            padding: 15px 20px; 
            color: #f1f5f9; 
            text-decoration: none; 
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            transition: all 0.3s ease; 
        }

        .navbar ul li a:hover, .navbar ul li a.active { 
            background: var(--accent); 
            color: white; 
        }

        /* USER CORNER */
        .user-corner { display: flex; align-items: center; gap: 10px; }
        .user-name { color: #fbbf24; font-weight: bold; font-size: 14px; }
        .logout-btn { 
            background: #dc2626; 
            color: white !important; 
            padding: 8px 15px !important; 
            border-radius: 5px; 
            font-size: 13px;
            text-decoration: none;
        }

        /* IMAGE SCROLL SECTION */
        .image-container {
            width: 100%;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.5);
            padding: 40px 0;
            margin-top: 20px;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
        }

        .image-track {
            display: flex;
            width: calc(450px * 10); 
            animation: scroll 25s linear infinite;
        }

        .image-track:hover { animation-play-state: paused; }

        .image-track img {
            width: 400px;
            height: 300px;
            margin: 0 15px;
            border-radius: 15px;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        footer {
            text-align: center; 
            padding: 40px; 
            background: var(--dark);
            color: #fed7aa; 
            font-size: 14px; 
            border-top: 4px solid var(--accent);
            margin-top: 50px;
        }

    </style>
</head>
<body>

<header>
    <img src="hg.jpeg" class="logo">
    <h1>City Catering Management system</h1>
    <p>Premium food services for your events</p>
</header>

<div class="navbar">
     <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="venues.php">View All Catering</a></li>
        <li><a href="search_result.php">🔍 Search Food</a></li>
        <li><a href="search_event.php" >🔍 Search Event</a></li>
        <li><a href="booking.php">Bookings</a></li>
    </ul>

    <?php if(isset($_SESSION['user_name'])): ?>
    <div class="user-corner">
        <span class="user-name">Welcome, <?php echo $_SESSION['user_name']; ?></span>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
    <?php endif; ?>
</div>

<div class="image-container">
    <div class="image-track">
        <img src="as.jpg" alt="Dish 1">
        <img src="asp.jpg" alt="Dish 2">
        <img src="ml.jpg" alt="Dish 3">
        <img src="ni.jpg" alt="Dish 4">
        <img src="hello.jpg" alt="Dish 5">
        <img src="as.jpg" alt="Dish 1">
        <img src="asp.jpg" alt="Dish 2">
        <img src="ml.jpg" alt="Dish 3">
        <img src="ni.jpg" alt="Dish 4">
        <img src="hello.jpg" alt="Dish 5">
    </div>
</div>

<footer>
    &copy; <?php echo date("Y"); ?> City Catering.
</footer>

</body>
</html>
