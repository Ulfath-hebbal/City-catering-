 <?php
// PHP logic at the top of the same file
session_start();
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Connection Failed"); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_order_id = mysqli_real_escape_string($con, $_POST['cname']); 
    $input_phone    = mysqli_real_escape_string($con, $_POST['pass']);  

    $query = "SELECT * FROM orders WHERE order_id = '$input_order_id' AND customer_phone = '$input_phone'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['cid'] = $row['order_id']; 
        $_SESSION['customer_name'] = $row['customer_name'];
        header("Location:custpay.php"); 
        exit();
    } else {
        header("Location: ?error=invalid"); 
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login | City Catering</title>
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cloudflare.com">

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

        /* GLASS NAVBAR */
        .navbar { 
            background: var(--glass); 
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            display: flex;  align-items: center; 
            padding: 25px 50px; position: sticky; top: 0; z-index: 1000; height: 70px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .navbar ul { list-style: none; display: flex; gap: 10px; }
        .navbar ul li a { 
            padding: 10px 25px; color: #f1f5f9; text-decoration: none; 
            font-weight: 500; text-transform: uppercase; font-size: 0.85rem; border-radius: 8px; transition: 0.3s;
        }
        .navbar ul li a:hover { background: rgba(255,255,255,0.1); color: var(--accent); }

        /* FORM BOX SECTION */
        .page-content { flex: 1; display: flex; align-items: center; justify-content: center; padding: 50px 20px; }
        
        .formbox { 
            width: 100%; max-width: 450px; background: white; border-radius: 25px; 
            overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
            border-top: 8px solid var(--primary);
            animation: slideUp 0.6s ease-out;
        }

        .tab-nav { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 20px; text-align: center; }
        .tab-nav label { font-weight: 800; color: var(--primary); font-size: 1.2rem; text-transform: uppercase; letter-spacing: 1px; }

        .form-content { padding: 40px; }
        h3 { color: var(--dark); margin-bottom: 25px; text-align: center; font-size: 1.6rem; font-weight: 700; }
        
        .input-group { position: relative; margin-bottom: 20px; }
        .input-group i { position: absolute; left: 18px; top: 15px; color: var(--primary); z-index: 1; font-size: 1.1rem; }
        
        .input-group input { 
            width: 100%; padding: 14px 15px 14px 50px; border: 2px solid #e2e8f0; 
            border-radius: 12px; outline: none; transition: 0.3s; font-size: 1rem; background: #fcfdfd;
        }
        .input-group input:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 10px rgba(8, 143, 98, 0.1); }
        
        .btn-customer { 
            background: var(--accent); color: white; width: 100%; padding: 15px; 
            border: none; border-radius: 12px; font-weight: bold; cursor: pointer; 
            font-size: 1.1rem; transition: 0.3s; box-shadow: 0 4px 15px rgba(249, 115, 22, 0.3);
            text-transform: uppercase; letter-spacing: 1px;
        }
        .btn-customer:hover { background: #ea580c; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4); }

        .error-msg { background: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 10px; text-align: center; margin-bottom: 20px; font-size: 14px; font-weight: 600; border: 1px solid #fecaca; }

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

    <script>
        function validateLogin() {
            var orderId = document.forms["loginForm"]["cname"].value;
            var phone = document.forms["loginForm"]["pass"].value;

            if (!orderId.startsWith("ORD-")) {
                alert("Invalid Order ID format. It must start with 'ORD-' (e.g., ORD-24)");
                return false;
            }

            var phonePattern = /^[0-9]{10}$/;
            if (!phone.match(phonePattern)) {
                alert("Please enter a valid 10-digit Phone Number.");
                return false;
            }
            return true;
        }
    </script>
</head>

<body>

<header>
    <img src="hg.jpeg" class="logo" alt="Logo">
    <h1>City Catering Management System</h1>
</header>
<div class="navbar">
        <ul>
            <li><a href="booking.php">Back</a></li>
            <li><a href="venues.php">View All Catering</a></li>
            <li><a href="search_result.php">🔍 Search Food</a></li>
            <li><a href="search_event.php">🔍 Search Event</a></li>
            <li><a href="booking.php">Bookings</a></li>
        </ul>
    </div>

<div class="page-content">
    <div class="formbox">
        <div class="tab-nav">
            <label><i class="fa-solid fa-circle-user"></i> Payment Login</label>
        </div>

        <div class="form-content">
            <h3>Track & Pay</h3>

            <?php if(isset($_GET['error'])) { echo '<div class="error-msg">⚠️ Invalid Order ID or Phone Number</div>'; } ?>

            <form name="loginForm" method="post" onsubmit="return validateLogin()">
                <div class="input-group">
                    <i class="fa-solid fa-hashtag"></i>
                    <input type="text" name="cname" placeholder="Order ID (e.g. ORD-24)" required>
                </div>
                
                <div class="input-group">
                    <i class="fa-solid fa-phone"></i>
                    <input type="password" name="pass" placeholder="10-Digit Phone Number" required>
                </div>
                
                <button type="submit" class="btn-customer">Access Payment Portal</button>
            </form>
        </div>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> City Catering Management System. All Rights Reserved.</p>
</footer>

</body>
</html>
