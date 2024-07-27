<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Customer data
$customerName = $data['customer']['name'];
$customerPhone = $data['customer']['phone'];
$customerAddress = $data['customer']['address'];
$billingDate = $data['customer']['date'];

// Insert customer data into customers table (if applicable)
// $sql = "INSERT INTO customers (name, phone, address) VALUES ('$customerName', '$customerPhone', '$customerAddress')";
// $conn->query($sql);

// Get the inserted customer ID
// $customerId = $conn->insert_id;

// Medicines data
foreach ($data['medicines'] as $medicine) {
    $medicineName = $medicine['name'];
    $quantity = $medicine['quantity'];
    $price = $medicine['price'];
    $total = $medicine['total'];

    $sql = "INSERT INTO sold_medicine (customer_name, customer_phone, customer_address, billing_date, medicine_name, quantity, price, total)
            VALUES ('$customerName', '$customerPhone', '$customerAddress', '$billingDate', '$medicineName', '$quantity', '$price', '$total')";
    $conn->query($sql);
}

// Generate bill display
$billHtml = "<h3>Bill for $customerName</h3>";
$billHtml .= "<p>Phone: $customerPhone</p>";
$billHtml .= "<p>Address: $customerAddress</p>";
$billHtml .= "<p>Date: $billingDate</p>";
$billHtml .= "<table class='table'>";
$billHtml .= "<thead><tr><th>Medicine</th><th>Quantity</th><th>Price</th><th>Total</th></tr></thead><tbody>";

foreach ($data['medicines'] as $medicine) {
    $billHtml .= "<tr>
                    <td>{$medicine['name']}</td>
                    <td>{$medicine['quantity']}</td>
                    <td>{$medicine['price']}</td>
                    <td>{$medicine['total']}</td>
                  </tr>";
}

$billHtml .= "</tbody></table>";

echo $billHtml;

$conn->close();
?>
