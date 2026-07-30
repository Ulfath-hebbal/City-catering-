 <?php
session_start();
$con = mysqli_connect("localhost", "root", "", "citycatering");

if (!$con) { 
    die("Connection Failed: " . mysqli_connect_error()); 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect data from the form
    $input_order_id = mysqli_real_escape_string($con, $_POST['cname']); 
    $input_phone    = mysqli_real_escape_string($con, $_POST['pass']);  

    // Query matching your backend table columns
    $query = "SELECT * FROM orders WHERE order_id = '$input_order_id' AND customer_phone = '$input_phone'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // IMPORTANT: Store order_id in session
        $_SESSION['customer_order_id'] = $row['order_id']; 
        $_SESSION['customer_name'] = $row['customer_name'];
        
        // REDIRECT TO NEXT PAGE
        header("Location: custpay.php"); 
        exit();
    } else {
        // Redirect back with error if login fails
        header("Location: custlog.php?error=invalid");
        exit();
    }
}
?>
