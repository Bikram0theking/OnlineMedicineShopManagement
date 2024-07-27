<?php
$connection = new mysqli("localhost", "root", "", "probisuk");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$query = $_POST['query'];
$sql = "SELECT name FROM medicines_stock WHERE name LIKE '%$query%'";
$result = $connection->query($sql);

$output = '<ul class="list-group">';
while ($row = $result->fetch_assoc()) {
    $output .= '<li class="list-group-item medicine-item">' . $row['name'] . '</li>';
}
$output .= '</ul>';

echo $output;

$connection->close();
?>
