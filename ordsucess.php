 <?php 
$oid  = isset($_GET['oid']) ? $_GET['oid'] : 'N/A'; 
$bill = isset($_GET['bill']) ? (float)$_GET['bill'] : 0; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed | City Catering</title>
    <style>
        /* MATCHING PUBLIC & HOME PAGE THEME */
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

        /* GLASS NAVBAR (Same as Public Main) */
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

        /* SUCCESS CARD SECTION */
        .page-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 20px;
        }

        .card { 
            max-width: 450px; 
            width: 100%; 
            background: white; 
            padding: 40px; 
            border-radius: 25px; 
            text-align: center; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
            border-top: 10px solid var(--primary);
            animation: slideUp 0.6s ease-out;
        }

        .success-icon {
            width: 80px; height: 80px; background: #f0fdf4; color: var(--primary);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 40px; margin: 0 auto 20px; border: 2px solid var(--primary);
        }

        .order-box { 
            background: #f8fafc; border: 2px dashed #cbd5e1; 
            padding: 20px; border-radius: 15px; margin: 25px 0; 
        }

        .order-id { font-size: 28px; color: var(--secondary); font-weight: 800; display: block; letter-spacing: 1px; }
        .bill-amt { color: var(--primary); font-size: 32px; font-weight: 800; display: block; margin-top: 5px; }

        /* ACTION BUTTON */
        .btn-home { 
            display: block; background: var(--accent); color: white; 
            padding: 15px; border-radius: 12px; text-decoration: none; 
            font-weight: bold; margin-top: 30px; transition: 0.3s;
            box-shadow: 0 4px 15px rgba(249, 115, 22, 0.3);
            text-transform: uppercase; letter-spacing: 1px;
        }
        .btn-home:hover { background: #ea580c; transform: translateY(-3px); box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4); }

        /* FOOTER */
        footer {
            flex-shrink: 0; background: var(--dark); color: #fed7aa;
            text-align: center; padding: 40px; border-top: 4px solid var(--accent);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <header>
        <img src="hg.jpeg" class="logo" alt="Logo">
        <h1>City Catering</h1>
    </header>

    <!-- Navbar with all buttons from Public Page -->
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
        <div class="card">
            <div class="success-icon">✓</div>
            <h2 style="color: var(--dark); font-size: 24px;">Booking Confirmed!</h2>
            <p style="color: #64748b; margin-top: 5px;">Your request has been sent to the caterer.</p>

            <div class="order-box">
                <span style="font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: bold;">Tracking Order ID</span>
                <span class="order-id"><?php echo htmlspecialchars($oid); ?></span>
            </div>

            <p style="color: #64748b; font-weight: 500;">Estimated Total Bill</p>
            <span class="bill-amt">₹<?php echo number_format($bill, 2); ?></span>

            <a href="booking.php" class="btn-home">Back</a>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> City Catering Management System. All Rights Reserved.</p>
    </footer>

</body>
</html>
