<?php
// Include database connection file
include_once('../db_connect.php');

header('Content-Type: application/json');

// SQL query to select all rubber types
$sql = "SELECT * FROM rubber_types";
$result = $conn->query($sql);

// Fetch data into an array
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Return JSON-encoded data
echo json_encode($data);

// Close connection
$conn->close();
?>
