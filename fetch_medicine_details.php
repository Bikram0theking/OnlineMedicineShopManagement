<?php
$connection = new mysqli("localhost", "root", "", "probisuk");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$medicineName = $_POST['medicineName'];
$sql = "SELECT price FROM medicines_stock WHERE name = '$medicineName'";
$result = $connection->query($sql);
$medicineDetails = $result->fetch_assoc();

echo json_encode($medicineDetails);

$connection->close();
?>
