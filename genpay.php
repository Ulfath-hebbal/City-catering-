 <?php
session_start();
$con = mysqli_connect("localhost", "root", "", "citycatering");

if (!isset($_SESSION['oid'])) { header("Location: ownlog.php"); exit(); }
$oid = $_SESSION['oid'];

// 1. GET ALL ORDERS FOR THE DROPDOWN
$dropdown_query = mysqli_query($con, "SELECT id, customer_name, event_type FROM orders WHERE oid = '$oid' AND status != 'Cancelled'");

// 2. GET SELECTED ORDER DETAILS (When "Load Details" is clicked)
$bill = null;
if (isset($_POST['load_order'])) {
    $selected_id = mysqli_real_escape_string($con, $_POST['order_id']);
    $bill_query = mysqli_query($con, "SELECT * FROM orders WHERE id = '$selected_id' AND oid = '$oid'");
    $bill = mysqli_fetch_assoc($bill_query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Generate Bill - City Catering</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #fdfaf7; }
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            #printableBill { border: none !important; box-shadow: none !important; width: 100% !important; margin: 0 !important; }
        }
    </style>
</head>
<body>

<div style="max-width: 900px; margin: 20px auto;" class="no-print">
    <!-- SELECTION FORM -->
    <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 5px solid #f97316; margin-bottom: 30px;">
        <h2 style="color: #064e3b; margin-bottom: 20px; font-size: 20px;">Step 1: Select Order to Bill</h2>
        <form method="POST" style="display: flex; gap: 15px;">
            <select name="order_id" required style="flex: 1; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px;">
                <option value="">-- Choose an Order --</option>
                <?php while($row = mysqli_fetch_assoc($dropdown_query)) { ?>
                    <option value="<?php echo $row['id']; ?>" <?php if(isset($selected_id) && $selected_id == $row['id']) echo 'selected'; ?>>
                        Order #<?php echo $row['id']; ?> - <?php echo $row['customer_name']; ?> (<?php echo $row['event_type']; ?>)
                    </option>
                <?php } ?>
            </select>
            <button type="submit" name="load_order" style="background: #064e3b; color: white; border: none; padding: 0 25px; border-radius: 10px; font-weight: bold; cursor: pointer;">Load Details</button>
        </form>
    </div>
</div>

<?php if ($bill): ?>
    <!-- INVOICE PREVIEW -->
    <div id="printableBill" style="background: white; max-width: 900px; margin: 0 auto; padding: 40px; border-radius: 5px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #eee;">
        
        <div style="display: flex; justify-content: space-between; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 30px;">
            <div>
                <h1 style="color: #064e3b; margin: 0;">CITY CATERING</h1>
                <p style="color: #64748b; font-size: 13px;">Quality Food & Service</p>
            </div>
            <div style="text-align: right;">
                <h2 style="margin: 0; color: #1e293b;">INVOICE</h2>
                <p style="color: #64748b; font-size: 13px;">Date: <?php echo date('d-M-Y'); ?></p>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 40px;">
            <div>
                <p style="color: #64748b; font-size: 11px; text-transform: uppercase; font-weight: bold;">Bill To:</p>
                <h3 style="margin: 0;"><?php echo $bill['customer_name']; ?></h3>
                <p style="color: #475569; font-size: 14px;">Phone: <?php echo $bill['customer_phone']; ?></p>
                <p style="color: #475569; font-size: 14px;">Event: <?php echo $bill['event_type']; ?></p>
            </div>
            <div style="text-align: right;">
                <p style="color: #64748b; font-size: 11px; text-transform: uppercase; font-weight: bold;">Order Details:</p>
                <p style="font-weight: bold; margin: 0;">ID: #<?php echo $bill['id']; ?></p>
                <p style="color: #475569; font-size: 14px;">Event Date: <?php echo date('d-m-Y', strtotime($bill['event_date'])); ?></p>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 12px; text-align: left; color: #64748b; font-size: 12px;">EVENT TIME</th>
                    <th style="padding: 12px; text-align: right; color: #64748b; font-size: 12px;">GUESTS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 15px;">Morning Function</td>
                    <td style="padding: 15px; text-align: right;"><?php echo $bill['members_morning']; ?></td>
                </tr>
                <tr>
                    <td style="padding: 15px;">Afternoon Function</td>
                    <td style="padding: 15px; text-align: right;"><?php echo $bill['members_afternoon']; ?></td>
                </tr>
                <tr>
                    <td style="padding: 15px;">Night Function</td>
                    <td style="padding: 15px; text-align: right;"><?php echo $bill['members_night']; ?></td>
                </tr>
            </tbody>
        </table>

        <div style="display: flex; justify-content: flex-end;">
            <div style="width: 300px;">
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-top: 2px solid #064e3b;">
                    <span style="font-weight: bold; color: #064e3b; font-size: 20px;">GRAND TOTAL:</span>
                    <span style="font-weight: bold; color: #064e3b; font-size: 20px;">₹<?php echo number_format($bill['total_bill'], 2); ?></span>
                </div>
            </div>
        </div>

        <div style="margin-top: 50px; text-align: center; border-top: 1px dashed #e2e8f0; padding-top: 20px;">
            <p style="color: #94a3b8; font-size: 12px;">Thank you for your business!</p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 20px;" class="no-print">
        <button style="background: #f97316; color: white; border: none; padding: 12px 30px; border-radius: 8px; font-weight: bold; cursor: pointer;">confirm</button>
    </div>
<?php elseif(isset($_POST['load_order'])): ?>
    <p style="text-align: center; color: red;">Order not found.</p>
<?php endif; ?>

</body>
</html>
