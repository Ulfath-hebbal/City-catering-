 <?php
session_start();
$con = mysqli_connect("localhost", "root", "", "citycatering");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Basic Data
    $cust_name = $_SESSION['cname']; 
    $catering  = mysqli_real_escape_string($con, $_POST['catering_service']);
    $oid       = mysqli_real_escape_string($con, $_POST['oid']);
    $event     = mysqli_real_escape_string($con, $_POST['event_name']);
    $event_date = mysqli_real_escape_string($con, $_POST['event_date']);

    // 2. Members Count
    $m_qty = isset($_POST['members_morning']) ? intval($_POST['members_morning']) : 0;
    $a_qty = isset($_POST['members_afternoon']) ? intval($_POST['members_afternoon']) : 0;
    $n_qty = isset($_POST['members_night']) ? intval($_POST['members_night']) : 0;
    $total_members = $m_qty + $a_qty + $n_qty;

    // 3. Bill Calculation & Item List
    $total_bill = 0;
    $items_array = array();
    
    if(isset($_POST['rate'])){
        foreach($_POST['rate'] as $key => $price){
            $food = $_POST['food_name'][$key];
            $items_array[] = $food;
            $total_bill += ($price * $total_members);
        }
    }
    
    $extra_items = mysqli_real_escape_string($con, implode(", ", $items_array));

    // 4. INSERT INTO TABLE
    $sql = "INSERT INTO orders (
                customer_name, catering_service, total_bill, extra_items, 
                oid, event_date, status, event_type, 
                members_morning, members_afternoon, members_night
            ) VALUES (
                '$cust_name', '$catering', '$total_bill', '$extra_items', 
                '$oid', '$event_date', 'Pending', '$event', 
                '$m_qty', '$a_qty', '$n_qty'
            )";

    if (mysqli_query($con, $sql)) {
        header("Location: venues.php?msg=success");
        exit();
    } else {
        // die(mysqli_error($con)); // Uncomment this if it still fails to see the error
        header("Location: venues.php?msg=error");
        exit();
    }
}
?>
