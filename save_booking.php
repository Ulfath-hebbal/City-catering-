 <?php
$con = mysqli_connect("localhost", "root", "", "citycatering");
if (!$con) { die("Connection Error"); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. COLLECT POST DATA
    $form_order_id = mysqli_real_escape_string($con, $_POST['order_id']); 
    $oid           = mysqli_real_escape_string($con, $_POST['oid']);
    $event_type    = mysqli_real_escape_string($con, $_POST['event_type']);
    $name          = mysqli_real_escape_string($con, $_POST['customer_name']);
    $phone         = mysqli_real_escape_string($con, $_POST['customer_phone']);
    $location      = mysqli_real_escape_string($con, $_POST['location_coords']);
    
    // extra_items now receives the food string passed from public_registration
    $user_reqs     = mysqli_real_escape_string($con, $_POST['extra_items']);
    $e_date        = mysqli_real_escape_string($con, $_POST['event_date']);

    // 2. CATERER NAME FETCH
    $caterer_name = "Catering Service";
    $c_res = mysqli_query($con, "SELECT csname FROM owner WHERE oid='$oid'");
    if($c_row = mysqli_fetch_assoc($c_res)) { 
        $caterer_name = mysqli_real_escape_string($con, $c_row['csname']); 
    }

    // 3. GUEST & SESSION MAPPING
    $m_m = 0; $m_a = 0; $m_n = 0;
    
    // Check if it's a Wedding (which uses the 3-input grid)
    if (isset($_POST['m_morning']) && strpos(strtolower($event_type), 'wedding') !== false) {
        $m_m = (int)$_POST['m_morning']; 
        $m_a = (int)$_POST['m_afternoon']; 
        $m_n = (int)$_POST['m_night'];
    } else {
        // For Birthday/Corporate: Map single count to the CHOSEN session column
        $count = (int)$_POST['single_guest_count'];
        $session = $_POST['selected_session']; // Ensure your <select> is named 'selected_session'
        
        if ($session == 'Morning') $m_m = $count;
        elseif ($session == 'Afternoon') $m_a = $count;
        elseif ($session == 'Night') $m_n = $count;
    }

    $price_plate = (float)$_POST['price_plate'];
    $total_bill  = ($m_m + $m_a + $m_n) * $price_plate;

    // 4. EXECUTE INSERT
    $sql = "INSERT INTO orders (
                customer_name, catering_service, total_bill, extra_items, 
                location_coords, oid, event_date, status, 
                event_type, members_morning, members_afternoon, members_night, 
                customer_phone, order_id
            ) VALUES (
                '$name', '$caterer_name', '$total_bill', '$user_reqs', 
                '$location', '$oid', '$e_date', 'Pending', 
                '$event_type', '$m_m', '$m_a', '$m_n', 
                '$phone', '$form_order_id'
            )";

    if (mysqli_query($con, $sql)) {
        // Redirect to success page
        header("Location: ordsucess.php?oid=$form_order_id&bill=$total_bill");
        exit();
    } else {
        echo "Database Error: " . mysqli_error($con);
    }
}
?>
