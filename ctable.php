 <?php
// 1. DATABASE CONNECTION
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Connection failed: " . mysqli_connect_error()); }

// 2. FILTER LOGIC
$filter = isset($_GET['status']) ? mysqli_real_escape_string($con, $_GET['status']) : 'all';

if ($filter == 'all') {
    $sql = "SELECT * FROM orders ORDER BY FIELD(status, 'Pending', 'Accepted', 'Paid', 'Completed', 'Rejected')";
} else {
    $sql = "SELECT * FROM orders WHERE status='$filter' ORDER BY id DESC";
}

$rs = mysqli_query($con, $sql);

// Get Total Count for stats
$count_rs = mysqli_query($con, "SELECT COUNT(*) as total FROM orders");
$total_data = mysqli_fetch_assoc($count_rs);
$total_bookings = $total_data['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Bookings | City Catering Admin</title>
    <style>
        /* BASE THEME FLOW */
        :root {
            --primary: #088f62;
            --secondary: #076e93;
            --dark: #011811;
            --accent: #76dadf;
            --light: #fff7ed;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, sans-serif; }
        body { background: var(--light); color: #333; overflow-x: hidden; }

        header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white; text-align: center; padding: 30px 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        header h1 { font-family: Forte, cursive; font-size: 2.2rem; }
        .logo-top { width: 80px; height: 80px; border-radius: 50%; border: 3px solid white; margin-bottom: 10px; }

        .navbar {
            background: rgba(1, 24, 17, 0.9); backdrop-filter: blur(10px);
            display: flex; padding-left: 20px; position: sticky; top: 0; z-index: 1000; border-bottom: 2px solid var(--accent);
        }
        .navbar ul { list-style: none; display: flex; }
        .navbar ul li a { display: block; padding: 18px 25px; color: white; text-decoration: none; font-weight: bold; font-size: 0.9rem; text-transform: uppercase; transition: 0.3s; }
        .navbar ul li a:hover { background: var(--accent); color: var(--dark); }

        .container { width: 95%; margin: 30px auto; animation: fadeIn 0.8s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* FILTER BUTTONS */
        .filter-wrapper { display: flex; justify-content: center; gap: 12px; margin-bottom: 30px; flex-wrap: wrap; }
        .btn-filter { 
            padding: 10px 20px; border-radius: 50px; text-decoration: none; font-weight: 800; 
            font-size: 12px; text-transform: uppercase; transition: 0.3s; border: 2px solid transparent;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .f-all { background: white; color: var(--secondary); border-color: var(--secondary); }
        .f-pending { background: #f97316; color: white; }
        .f-approve { background: #3b82f6; color: white; }
        .f-paid { background: #16a34a; color: white; }
        .f-reject { background: #dc2626; color: white; }
        .btn-filter:hover { transform: translateY(-2px); opacity: 0.9; }

        /* SEARCH & TABLE */
        .top-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; background: white; padding: 15px; border-radius: 15px; }
        .search-box { padding: 10px 15px; width: 300px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; }

        table { width: 100%; border-collapse: collapse; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        th { background: #0f766e; color: white; padding: 18px; text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 18px 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 14px; }
        tr:hover { background-color: #f8fafc; }

        /* STATUS BADGES */
        .status-badge { padding: 5px 12px; border-radius: 50px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .bg-pending { background: #ffedd5; color: #9a3412; }
        .bg-accepted { background: #dbeafe; color: #1e40af; }
        .bg-paid { background: #dcfce7; color: #166534; }
        .bg-rejected { background: #fee2e2; color: #991b1b; }

        .bill-text { font-weight: 800; color: #088f62; font-size: 15px; }
        .sub-text { font-size: 12px; color: #64748b; display: block; margin-top: 2px; }
    </style>
</head>
<body>

<header>
    <img src="hg.jpeg" class="logo-top">
    <h1>City Catering Admin Dashboard</h1>
</header>

<div class="navbar">
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="otable.php">Owner Details</a></li>
        <li><a href="ctable.php">Customer Details</a></li>
      
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="container">
    <!-- FILTER BUTTONS -->
    <div class="filter-wrapper">
        <a href="ctable.php?status=all" class="btn-filter f-all">All Bookings (<?php echo $total_bookings; ?>)</a>
        <a href="ctable.php?status=Pending" class="btn-filter f-pending">Pending</a>
        <a href="ctable.php?status=Approved" class="btn-filter f-approve">Approved</a>
        <a href="ctable.php?status=Paid" class="btn-filter f-paid">Paid</a>
        <a href="ctable.php?status=Rejected" class="btn-filter f-reject">Rejected</a>
    </div>

    <div class="top-controls">
        <h2 style="color: #0f766e; font-size: 18px;">CUSTOMER DATA VIEW: <span style="color: #f97316;"><?php echo strtoupper($filter); ?></span></h2>
        <input type="text" id="myInput" onkeyup="searchTable()" class="search-box" placeholder="Search customer or ID...">
    </div>

    <table id="custTable">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Customer Information</th>
                <th>Event & Location</th>
                <th>Billing Info</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while($re = mysqli_fetch_assoc($rs)) {
                $status = $re['status'];
                $badgeClass = "bg-pending";
                if($status == 'Paid' || $status == 'Completed') $badgeClass = "bg-paid";
                if($status == 'Rejected') $badgeClass = "bg-rejected";
                if($status == 'Accepted') $badgeClass = "bg-accepted";
                
                echo "<tr>";
                echo "<td><b>#".$re['order_id']."</b><span class='sub-text'>Ordered: ".date('d-M-Y', strtotime($re['order_date']))."</span></td>";
                echo "<td><b>".$re['customer_name']."</b><span class='sub-text'>📞 ".$re['customer_phone']."</span></td>";
                echo "<td>".$re['event_type']."<span class='sub-text'>📍 ".$re['location_coords']."</span></td>";
                echo "<td><span class='bill-text'>₹".number_format($re['total_bill'], 2)."</span><span class='sub-text'>Received: ₹".number_format($re['paid_amount'], 2)."</span></td>";
                echo "<td><span class='status-badge $badgeClass'>".$status."</span></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
function searchTable() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("custTable");
  tr = table.getElementsByTagName("tr");
  for (i = 1; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td"); 
    if (td) {
      // Searches across Name and ID
      txtValue = td[0].textContent + td[1].textContent;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
</script>

</body>
</html>
