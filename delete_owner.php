 <?php
session_start();

// 1. DATABASE CONNECTION
$con = mysqli_connect("localhost", "root", "", "citycatering");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// 2. CHECK IF ID IS PROVIDED 
// Using 'id' to match the link in your otable.php
if (isset($_GET['oid'])) {
    $id = mysqli_real_escape_string($con, $_GET['oid']);

    // 3. DELETE QUERY
    // NOTE: If your column name in the database is 'oid', change 'id' to 'oid' below
    $sql = "DELETE FROM owner WHERE oid = '$id'";

    if (mysqli_query($con, $sql)) {
        // Redirect back with a success message
        header("Location: otable.php?msg=Owner Deleted Successfully");
        exit();
    } else {
        $error = "Error deleting record: " . mysqli_error($con);
    }
} else {
    $error = "No ID provided for deletion.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Owner | City Catering</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, sans-serif; }
        body { background: #fff7ed; display: flex; justify-content: center; align-items: center; height: 100vh; }
        
        .error-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 8px solid #dc2626;
            max-width: 420px;
            width: 90%;
        }

        h2 { color: #dc2626; margin-bottom: 15px; font-size: 24px; }
        p { color: #64748b; margin-bottom: 25px; line-height: 1.5; }

        .btn-back {
            display: inline-block;
            background: #088f62;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
            transition: 0.3s;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
        }
        .btn-back:hover { background: #066b4a; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(8, 143, 98, 0.3); }
    </style>
</head>
<body>

    <div class="error-card">
        <?php if(isset($error)): ?>
            <h2>Action Failed!</h2>
            <p><?php echo $error; ?></p>
            <a href="otable.php" class="btn-back">Go Back to Table</a>
        <?php else: ?>
            <h2 style="color: #088f62;">Processing...</h2>
            <p>We are processing your request. If you are not redirected automatically, please click below.</p>
            <a href="otable.php" class="btn-back">Return to Table</a>
        <?php endif; ?>
    </div>

</body>
</html>
