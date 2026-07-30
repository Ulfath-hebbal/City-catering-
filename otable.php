 <?php
// 1. DATABASE CONNECTION (Using mysqli to fix the warning)
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Connection failed: " . mysqli_connect_error()); }

// 2. FILTER LOGIC
$filter = isset($_GET['status']) ? mysqli_real_escape_string($con, $_GET['status']) : 'all';

if ($filter == 'all') {
    $sql = "SELECT * FROM owner ORDER BY FIELD(status, 'new', 'active', 'rejected')";
} else {
    $sql = "SELECT * FROM owner WHERE status='$filter' ORDER BY oid DESC";
}

$rs = mysqli_query($con, $sql);

// Get Total Count
$count_rs = mysqli_query($con, "SELECT COUNT(*) as total FROM owner");
$total_data = mysqli_fetch_assoc($count_rs);
$total_owners = $total_data['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Owner Management | City Catering</title>
    <style>
        /* BASE THEME - MATCHING ADMINF.PHP */
        :root {
            --primary: #088f62;
            --secondary: #076e93;
            --dark: #011811;
            --accent: #76dadf;
            --light: #fff7ed;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, sans-serif; }
        body { background: var(--light); color: #333; overflow-x: hidden; }

        /* Header Style from Adminf */
        header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            text-align: center;
            padding: 30px 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        header h1 { font-family: Forte, cursive; font-size: 2.2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); }
        .logo-top { width: 80px; height: 80px; border-radius: 50%; border: 3px solid white; margin-bottom: 10px; transition: 0.5s; }
        .logo-top:hover { transform: rotate(360deg); }

        /* Glassmorphism Navbar */
        .navbar {
            background: rgba(1, 24, 17, 0.9);
            backdrop-filter: blur(10px);
            display: flex;
            padding-left: 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 2px solid var(--accent);
        }
        .navbar ul { list-style: none; display: flex; }
        .navbar ul li a { display: block; padding: 18px 25px; color: white; text-decoration: none; font-weight: bold; font-size: 0.9rem; text-transform: uppercase; transition: 0.3s; }
        .navbar ul li a:hover { background: var(--accent); color: var(--dark); }

        .container { width: 95%; margin: 30px auto; animation: fadeIn 0.8s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* FILTER BUTTONS SECTION */
        .filter-wrapper { display: flex; justify-content: center; gap: 15px; margin-bottom: 30px; flex-wrap: wrap; }
        .btn-filter { 
            padding: 12px 25px; border-radius: 50px; text-decoration: none; font-weight: 800; 
            font-size: 13px; text-transform: uppercase; transition: 0.3s; border: 2px solid transparent;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .f-all { background: white; color: var(--secondary); border-color: var(--secondary); }
        .f-new { background: #f97316; color: white; }
        .f-active { background: #16a34a; color: white; }
        .f-reject { background: #dc2626; color: white; }
        .btn-filter:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.1); opacity: 0.9; }

        /* SEARCH BAR */
        .top-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; background: white; padding: 15px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); }
        .search-box { padding: 12px 20px; width: 350px; border: 2px solid #e2e8f0; border-radius: 10px; outline: none; transition: 0.3s; }
        .search-box:focus { border-color: var(--primary); box-shadow: 0 0 10px rgba(8, 143, 98, 0.1); }

        /* TABLE STYLING */
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        th { background: #0f766e; color: white; padding: 18px; text-align: left; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        tr:hover { background-color: #f8fafc; }

        /* STATUS BADGES */
        .status-badge { padding: 6px 14px; border-radius: 50px; font-size: 11px; font-weight: 800; text-transform: uppercase; border: 1px solid transparent; }
        .bg-active { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
        .bg-new { background: #ffedd5; color: #9a3412; border-color: #fed7aa; }
        .bg-rejected { background: #fee2e2; color: #991b1b; border-color: #fecaca; }

        /* ACTION BUTTONS */
        .btn-action { text-decoration: none; font-size: 11px; font-weight: 800; padding: 8px 15px; border-radius: 8px; transition: 0.3s; display: inline-block; margin-right: 5px; }
        .btn-up { background: var(--secondary); color: white; }
        .btn-del { background: #dc2626; color: white; }
        .btn-action:hover { transform: scale(1.05); filter: brightness(1.1); }

        .cat-thumb { width: 55px; height: 55px; border-radius: 10px; object-fit: cover; border: 2px solid #eee; }
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
        <li><a href="otable.php">Owner Management</a></li>
        <li><a href="ctable.php">Customer Management</a></li>
        
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="container">
    
    <!-- FILTER BUTTONS -->
    <div class="filter-wrapper">
        <a href="otable.php?status=all" class="btn-filter f-all">View All (<?php echo $total_owners; ?>)</a>
        <a href="otable.php?status=new" class="btn-filter f-new">New Applications</a>
        <a href="otable.php?status=active" class="btn-filter f-active">Active Owners</a>
        <a href="otable.php?status=rejected" class="btn-filter f-reject">Rejected List</a>
    </div>

    <div class="top-controls">
        <h2 style="color: #0f766e; text-transform: uppercase; font-size: 18px;">
            Owner Listing: <span style="color: #f97316;"><?php echo ucfirst($filter); ?></span>
        </h2>
        <input type="text" id="myInput" onkeyup="searchTable()" class="search-box" placeholder="Search catering or owner name...">
    </div>

    <table id="ownerTable">
        <thead>
            <tr>
                <th>Profile</th>
                <th>ID</th>
                <th>Catering/Owner</th>
                <th>Contact</th>
                <th>Location</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
           <?php
while($re = mysqli_fetch_array($rs)) {
    $status = strtolower($re['status']); 
    $badgeClass = ($status == 'active') ? "bg-active" : (($status == 'rejected') ? "bg-rejected" : "bg-new");
    
    // 1. CHANGE HERE: Make sure 'img' matches your database column name
    $imagePath = !empty($re['img']) ? $re['img'] : 'hg.jpeg';

    echo "<tr>";
    // This displays the image from your database
    echo "<td><img src='$imagePath' class='cat-thumb' onerror=\"this.src='hg.jpeg'\"></td>";
    echo "<td><b>#".$re['oid']."</b></td>";
    echo "<td><b style='color:#076e93'>".$re['csname']."</b><br><small>".$re['oname']."</small></td>";
    echo "<td style='font-size:12px;'>".$re['phno']."<br>".$re['email']."</td>";
      echo "<td style='font-size:12px;'>".$re['oadd']."</td>";
    echo "<td><span class='status-badge $badgeClass'>".$re['status']."</span></td>";
    echo "<td>";

    // 2. CHANGE HERE: Logic for the buttons
    // Only show UPDATE if the status is NEW
    if($status == 'new') {
        echo "<a href='ownsearch.php?id=".$re['oid']."' class='btn-action btn-up'>UPDATE</a>";
    }

    // Always show DELETE for all statuses
   // Use 'oid' here to match your delete script
echo "<a href='delete_owner.php?oid=".$re['oid']."' class='btn-action btn-del' onclick='return confirm(\"Permanently delete this owner?\")'>DELETE</a>";

    echo "</td>";
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
  table = document.getElementById("ownerTable");
  tr = table.getElementsByTagName("tr");
  for (i = 1; i < tr.length; i++) {
    // Search in the 3rd column (Catering & Owner)
    td = tr[i].getElementsByTagName("td")[2]; 
    if (td) {
      txtValue = td.textContent || td.innerText;
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
