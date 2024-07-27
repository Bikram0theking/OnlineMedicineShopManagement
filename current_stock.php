<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Stock</title>
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
            <p>Enjoy The Wellness With Us</p>
        </div>
        </a>

        <!-- New Medicine Form -->
        <div class="container my-4">
            <h2>Add New Medicine</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="medicineName">Medicine Name:</label>
                    <input type="text" class="form-control" id="medicineName" name="medicineName" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Medicine</button>
            </form>
        </div>

        <!-- Current Stock Table -->
        <div class="container">
            <h2>Current Stock</h2>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Medicine ID</th>
                        <th scope="col">Medicine Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Database connection
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "probisuk";

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Handle form submission to add new medicine
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["medicineName"]) && isset($_POST["quantity"])) {
                        $medicineName = $_POST["medicineName"];
                        $quantity = $_POST["quantity"];
                        $lastUpdated = date("Y-m-d H:i:s");

                        // Insert new medicine into medicines_stock table
                        $sql = "INSERT INTO medicines_stock (name) VALUES ('$medicineName')";
                        if ($conn->query($sql) === TRUE) {
                            $medicineId = $conn->insert_id;

                            // Insert into current_stock table
                            $sql = "INSERT INTO current_stock (medicine_id, quantity, last_updated) VALUES ($medicineId, $quantity, '$lastUpdated')";
                            if ($conn->query($sql) === TRUE) {
                                echo "<div class='alert alert-success'>New medicine added successfully.</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
                        }
                    }

                    // SQL query to retrieve current stock data
                    $sql = "SELECT cs.medicine_id, ms.name, cs.quantity, cs.last_updated
                            FROM current_stock cs
                            INNER JOIN medicines_stock ms ON cs.medicine_id = ms.medicine_id";

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["medicine_id"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["quantity"] . "</td>";
                            echo "<td>" . $row["last_updated"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No data found</td></tr>";
                    }

                    // Close connection
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
