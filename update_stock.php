<?php
$connection = new mysqli("localhost", "root", "", "probisuk");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$medicines = $_POST['medicines'];

foreach ($medicines as $medicine) {
    $medicineName = $medicine['medicineName'];
    $quantity = $medicine['quantity'];

    // Prepare statement to get medicine_id from medicines_stock
    $stmt = $connection->prepare("SELECT medicine_id FROM medicines_stock WHERE medicine_name = ?");
    $stmt->bind_param("s", $medicineName);
    $stmt->execute();
    $result = $stmt->get_result();
    $medicineStock = $result->fetch_assoc();
    $stmt->close();
    
    if ($medicineStock) {
        $medicineId = $medicineStock['medicine_id'];

        // Prepare statement to get current stock quantity
        $stmt = $connection->prepare("SELECT quantity FROM current_stock WHERE medicine_id = ?");
        $stmt->bind_param("i", $medicineId);
        $stmt->execute();
        $result = $stmt->get_result();
        $currentStock = $result->fetch_assoc();
        $stmt->close();

        if ($currentStock) {
            $newQuantity = $currentStock['quantity'] - $quantity;

            // Prepare statement to update stock quantity
            $updateStmt = $connection->prepare("UPDATE current_stock SET quantity = ?, last_updated = NOW() WHERE medicine_id = ?");
            $updateStmt->bind_param("ii", $newQuantity, $medicineId);
            if (!$updateStmt->execute()) {
                die("Error: " . $updateStmt->error);
            }
            $updateStmt->close();

            // Prepare statement to insert into stock_entries
            $insertStmt = $connection->prepare("INSERT INTO stock_entries (medicine_id, quantity, entry_type, date) VALUES (?, ?, 'outflow', NOW())");
            $insertStmt->bind_param("ii", $medicineId, $quantity);
            if (!$insertStmt->execute()) {
                die("Error: " . $insertStmt->error);
            }
            $insertStmt->close();
        }
    }
}

$connection->close();
?>
