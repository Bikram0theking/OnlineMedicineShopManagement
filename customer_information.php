<?php
$connection = new mysqli("localhost", "root", "", "probisuk");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch customer data from sold_medicine table
$sql = "SELECT DISTINCT customer_name, customer_phone, customer_address FROM sold_medicine";
$result = $connection->query($sql);

if ($result->num_rows > 0) {
    $customers = [];
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
} else {
    $customers = [];
}

$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Information</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="styles.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="logo.png">
</head>
<body>
    <div class="container text-center">
        <a href="home.html">
            <div class="header">
                <img src="logo.png" alt="Company Logo" class="logo">
                <h1>ProBiShuk</h1>
                <p>Enjoy the Wellness with us</p>
            </div>
        </a>
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-4 mb-4">Customer Information</h2>
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Customer Name</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($customers)): ?>
                            <?php foreach ($customers as $customer): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($customer['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($customer['customer_phone']); ?></td>
                                    <td><?php echo htmlspecialchars($customer['customer_address']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No customer information available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
