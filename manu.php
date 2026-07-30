 <?php
session_start();

/* 1. DATABASE CONNECTION (Updated to MySQLi) */
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Database Connection Error"); }

if (!isset($_SESSION['oid'])) {
    die("<script>alert('Please login first as owner.'); window.location='ownlog.php';</script>");
}

$oid = $_SESSION['oid'];

/* 2. INSERT MENU ITEM */
if (isset($_POST['add'])) {
    $fo = mysqli_real_escape_string($con, $_POST["food"]);
    $pr = mysqli_real_escape_string($con, $_POST["price"]);
    $cat = mysqli_real_escape_string($con, $_POST["event_type"]);
    
    // Check for Wedding Schedule
    if($cat == "Wedding" && !empty($_POST["wedding_schedule"])) {
        $cat = "Wedding (" . mysqli_real_escape_string($con, $_POST["wedding_schedule"]) . ")";
    }

    if (!empty($fo) && !empty($pr)) {
        $sql_insert = "INSERT INTO manu (oid, food, price, meal_type) VALUES ('$oid', '$fo', '$pr', '$cat')";
        mysqli_query($con, $sql_insert);
        header("Location: manu.php");
        exit();
    }
}

/* 3. DELETE LOGIC */
if (isset($_GET['del_name'])) {
    $del_name = mysqli_real_escape_string($con, $_GET['del_name']);
    mysqli_query($con, "DELETE FROM manu WHERE food='$del_name' AND oid='$oid'");
    header("Location: manu.php");
    exit();
}

/* 4. FETCH MENU */
$result = mysqli_query($con, "SELECT * FROM manu WHERE oid='$oid' ORDER BY meal_type DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu Management | Owner Portal</title>
    <style>
        /* OWNER THEME CSS */
        body { margin: 0; font-family: 'Segoe UI', Tahoma, sans-serif; display: flex; background: #fdfaf7; color: #333; }
        
        .sidebar { width: 250px; height: 100vh; background: #064e3b; padding: 20px 0; position: fixed; }
        .sidebar h2 { color: #f97316; text-align: center; font-family: 'Forte', sans-serif; margin-bottom: 30px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar a { display: block; color: #ecfdf5; padding: 15px 25px; text-decoration: none; transition: 0.3s; }
        .sidebar a:hover { background: #f97316; color: white; padding-left: 35px; }

        .main { flex: 1; margin-left: 250px; padding: 30px; min-height: 100vh; }

        .page-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #eee;
        }
        .oid-badge {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white; padding: 10px 20px; border-radius: 50px;
            font-weight: bold; font-size: 14px; box-shadow: 0 4px 10px rgba(234, 88, 12, 0.2);
        }

        .box-add {
            background: white; padding: 30px; border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05); margin-bottom: 40px;
            border-top: 6px solid #f97316;
        }

        .box-show {
            background: white; border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden;
        }

        .form-row { display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end; }
        .input-item { display: flex; flex-direction: column; gap: 8px; }

        input, select {
            padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px;
            font-size: 14px; outline: none; transition: 0.3s; background: #f8fafc;
        }
        input:focus, select:focus { border-color: #f97316; background: #fff; }

        .btn-add {
            background: #064e3b; color: white; border: none; padding: 13px 30px;
            border-radius: 10px; cursor: pointer; font-weight: bold; transition: 0.3s;
        }
        .btn-add:hover { background: #059669; transform: scale(1.02); }

        table { width: 100%; border-collapse: collapse; }
        th { background: #f8fafc; padding: 15px; text-align: left; color: #64748b; font-size: 12px; text-transform: uppercase; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; }
        tr:hover { background: #fffaf5; }
        
        .meal-tag { background: #dcfce7; color: #166534; padding: 5px 12px; border-radius: 20px; font-weight: bold; font-size: 11px; }
        .btn-del { color: #ef4444; font-weight: bold; text-decoration: none; transition: 0.2s; }
        .btn-del:hover { text-decoration: underline; color: #b91c1c; }

        details summary { cursor: pointer; color: #ecfdf5; padding: 15px 25px; outline: none; }
        details summary:hover { background: #f97316; }
        .dropdown-content a { padding-left: 50px; font-size: 13px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>City Catering</h2>
        <ul>
            <li><a href="owndash.php">🏠 Dashboard</a></li>
            <li><a href="manu.php">🍴 Menu Manager</a></li>
            <li><a href="viewordres.php">📅 Customer Orders</a></li>
            <li>
                <details>
                    <summary>💰Payments Management ▾</summary>
                    <div class="dropdown-content">
                        <a href="viewpay.php">View Payments</a>
                        <a href="updatepay.php">Update Status</a>
                    </div>
                </details>
            </li>
            <li><a href="home.php" style="margin-top:20px; color:#fca5a5;">🚪 Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="page-header">
            <h2 style="margin:0; color:#064e3b;">Manage Food Menu</h2>
            <div class="oid-badge">Owner ID: <?php echo $oid; ?></div>
        </div>

        <div class="box-add">
            <h3 style="margin-top:0; color:#064e3b; margin-bottom:20px;">Add New Menu Item</h3>
            <form method="POST" class="form-row">
                <div class="input-item">
                    <label>Category</label>
                    <select name="event_type" id="eventType" required onchange="toggleWeddingSession()">
                        <option value="">-- Select Event --</option>
                        <option value="Wedding">Wedding</option>
                        <option value="Birthday">Birthday</option>
                        <option value="Corporate">Corporate</option>
                        <option value="House Ceremony">House Ceremony</option>
                    </select>
                </div>
                
                <div class="input-item" id="weddingSession" style="display:none;">
                    <label>Wedding Session</label>
                    <select name="wedding_schedule">
                        <option value="">-- Select Session --</option>
                        <option value="Morning">Morning</option>
                        <option value="Afternoon">Afternoon</option>
                        <option value="Night">Night</option>
                    </select>
                </div>

                <div class="input-item">
                    <label>Food Name</label>
                    <input type="text" name="food" placeholder="e.g. Biriyani + Salad" required>
                </div>

                <div class="input-item">
                    <label>Price (₹)</label>
                    <input type="number" name="price" placeholder="250" required>
                </div>

                <button type="submit" class="btn-add" name="add">Save to Menu</button>
            </form>
        </div>

        <div class="box-show">
            <div style="padding: 20px; border-bottom: 1px solid #f1f5f9;">
                <h3 style="margin:0; color:#064e3b;">Current Live Menu</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Event Type</th>
                        <th>Food Details</th>
                        <th>Price</th>
                        <th style="text-align:right; padding-right:30px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_array($result)) { ?>
                    <tr>
                        <td><span class="meal-tag"><?php echo $row['meal_type']; ?></span></td>
                        <td style="font-weight:600;"><?php echo $row['food']; ?></td>
                        <td style="color:#059669; font-weight:700;">₹<?php echo $row['price']; ?></td>
                        <td style="text-align:right; padding-right:30px;">
                            <a href="manu.php?del_name=<?php echo urlencode($row['food']); ?>" 
                               class="btn-del" onclick="return confirm('Delete this item?')">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleWeddingSession() {
            var type = document.getElementById("eventType").value;
            var session = document.getElementById("weddingSession");
            session.style.display = (type === "Wedding") ? "block" : "none";
        }
    </script>

</body>
</html>
