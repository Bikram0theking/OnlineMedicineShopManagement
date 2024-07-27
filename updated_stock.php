<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Stock Entries</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="styles.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="logo.png">
</head>
<body>
    <div class="container text-center">
    <img src="logo.png" alt="Company Logo" class="logo">
        <div class="header">
            
            <h1>ProBiShuk</h1>
            <p>Enjoy the Wellness with us</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-4">
                    <div class="card-header">
                        <h2>Update Stock Entries</h2>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label for="medicine-id">Medicine ID:</label>
                                <input type="number" class="form-control" id="medicine-id" name="medicine_id" required>
                            </div>
                            <div class="form-group">
                                <label for="quantity">Quantity:</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" required>
                            </div>
                            <div class="form-group">
                                <label for="entry-type">Entry Type:</label>
                                <select class="form-control" id="entry-type" name="entry_type" required>
                                    <option value="inflow">Inflow</option>
                                    <option value="outflow">Outflow</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Stock Entry</button>
                        </form>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="current_stock.php" class="btn btn-secondary">View Current Stock</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Database credentials
$servername = "localhost";  // Replace with your database server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "probisuk";  // Replace with your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Database connection
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $medicine_id = $_POST['medicine_id'];
    $quantity = $_POST['quantity'];
    $entry_type = $_POST['entry_type'];

    // Ensure the connection is established
    if ($conn) {
        // Update stock_entries table
        $sql = "INSERT INTO stock_entries (medicine_id, quantity, entry_type) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $medicine_id, $quantity, $entry_type);

        if ($stmt->execute()) {
            // Update current_stock table
            if ($entry_type == 'inflow') {
                $update_stock_sql = "UPDATE current_stock SET quantity = quantity + ? WHERE medicine_id = ?";
            } else {
                $update_stock_sql = "UPDATE current_stock SET quantity = quantity - ? WHERE medicine_id = ?";
            }
            $update_stmt = $conn->prepare($update_stock_sql);
            $update_stmt->bind_param("ii", $quantity, $medicine_id);
            $update_stmt->execute();

            echo "<div class='alert alert-success'>Stock entry updated successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error updating stock entry: " . $conn->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Database connection error: " . $conn->connect_error . "</div>";
    }

    $conn->close();
}
?>
