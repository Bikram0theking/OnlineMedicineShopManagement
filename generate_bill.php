<?php
$connection = new mysqli("localhost", "root", "", "probisuk");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$customerName = $_POST['customerName'];
$customerPhone = $_POST['customerPhone'];
$customerAddress = $_POST['customerAddress'];
$billingDate = $_POST['billingDate'];
$medicines = $_POST['medicines'];

$grandTotal = 0;
foreach ($medicines as $medicine) {
    $medicineName = $medicine['medicineName'];
    $quantity = $medicine['quantity'];
    $price = $medicine['price'];
    $total = $medicine['total'];

    // Insert into sold_medicine
    $sql = "INSERT INTO sold_medicine (customer_name, customer_phone, customer_address, billing_date, medicine_name, quantity, price, total) 
            VALUES ('$customerName', '$customerPhone', '$customerAddress', '$billingDate', '$medicineName', '$quantity', '$price', '$total')";

    if (!$connection->query($sql)) {
        die("Error: " . $connection->error);
    }

    $grandTotal += $total;
}

// Prepare response data
$response = [
    'customerName' => $customerName,
    'customerPhone' => $customerPhone,
    'customerAddress' => $customerAddress,
    'billingDate' => $billingDate,
    'medicines' => $medicines,
    'grandTotal' => $grandTotal
];

echo json_encode($response);

$connection->close();
?>
