 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Status - City Catering</title>
    <style>
        /* Base Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
        
        body { 
            background: #fff7ed; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Branding */
        header {
            background: linear-gradient(to right, #088f62, #076e93);
            color: white;
            text-align: center;
            padding: 20px;
            font-family: Forte, cursive;
        }

        /* Navbar Left-Aligned */
        .navbar {
            background: linear-gradient(to right, #011811bf, #022e216f);
            display: flex;
            padding-left: 20px;
        }
        .navbar ul { list-style: none; display: flex; }
        .navbar ul li a {
            display: block; padding: 15px 25px;
            color: white; text-decoration: none; font-weight: bold;
        }
        .navbar ul li a:hover { background: #76dadf; color: #011811; }

        /* Result Card */
        .result-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .message-card {
            background: white;
            width: 450px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            border-top: 8px solid #ef4444; /* Red for Deletion */
        }

        .icon-trash {
            width: 70px;
            height: 70px;
            background: #fee2e2;
            color: #ef4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 20px;
            border: 2px solid #fecaca;
        }

        h2 { color: #991b1b; margin-bottom: 10px; }
        p { color: #666; margin-bottom: 25px; font-size: 15px; }

        /* Action Buttons */
        .btn-group {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-back { background: #076e93; color: white; }
        .btn-back:hover { background: #088f62; }

        .btn-home { background: #eee; color: #333; }
        .btn-home:hover { background: #ddd; }
    </style>
</head>

<body>

<header>
    <h1>City Catering Management System</h1>
</header>

<nav class="navbar">
    <ul>
        <li><a href="hh.html">Home</a></li>
        <li><a href="ctable.php">Customer Table</a></li>
    </ul>
</nav>

<div class="result-container">
    <div class="message-card">
        <?php
        $con = mysql_connect("localhost","root","");
        mysql_select_db("citycatering",$con);

        if(isset($_POST['id']))
        {
            $id = $_POST['id'];

            // Logic to handle potential errors (Foreign Key check)
            $sql = "DELETE FROM custemer WHERE id='$id'";
            
            if(mysql_query($sql)) {
                echo "<div class='icon-trash'>🗑</div>";
                echo "<h2>Deleted Successfully</h2>";
                echo "<p>Customer record <b>#$id</b> has been permanently removed from the system.</p>";
            } else {
                echo "<div class='icon-trash' style='color:#f97316; background:#fff7ed; border-color:#fed7aa;'>⚠</div>";
                echo "<h2>Deletion Failed</h2>";
                echo "<p style='color:#b45309;'>This customer cannot be deleted because they have active orders or records linked to them.</p>";
                // echo mysql_error(); // Uncomment this for debugging if needed
            }
        }
        ?>
        
        <div class="btn-group">
            <a href="ctable.php" class="btn btn-back">Return to Table</a>
            <a href="hh.html" class="btn btn-home">Home</a>
        </div>
    </div>
</div>

</body>
</html>
