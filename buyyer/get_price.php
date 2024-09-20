<?php
include_once('../db_connect.php');

if (isset($_GET['rubber_type_id'])) {
    $rubber_type_id = intval($_GET['rubber_type_id']);

    // Fetch price for the selected rubber type
    $sql = "SELECT price FROM rubber_price WHERE rubber_type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rubber_type_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['price'];
    } else {
        echo "0"; // Default price if not found
    }
    $stmt->close();
}
$conn->close();
?>
