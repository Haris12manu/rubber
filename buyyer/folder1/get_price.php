<?php
include_once('./api/db_connect.php');

if (isset($_GET['rubber_type_id'])) {
    $rubber_type_id = $_GET['rubber_type_id'];

    // Query to fetch the price
    $sql = "SELECT adjusted_price FROM store_prices WHERE rubber_type_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rubber_type_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $price_per_unit = 0;
    if ($row = $result->fetch_assoc()) {
        $price_per_unit = $row['adjusted_price'];
    }
    
    $stmt->close();
    $conn->close();
    
    // Return the price as JSON
    echo json_encode(['price_per_unit' => $price_per_unit]);
}
?>
