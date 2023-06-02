<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    // Retrieve the product name from the query parameter
    $name = $_GET["id"];

    // Retrieve the updated product data from the request body
    $requestData = json_decode(file_get_contents("php://input"), true);
    $newName = $requestData["name"];
    $price = $requestData["price"];
    $description = $requestData["description"];

    // Prepare the SQL statement
    $sql = "UPDATE product SET name=?, price=?, description=? WHERE name=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $newName, $price, $description, $name);

    if ($stmt->execute()) {
        echo json_encode(array("message" => "Product updated successfully."));
    } else {
        echo json_encode(array("error" => "Error updating product: " . $stmt->error));
    }

    $stmt->close();
    $conn->close();
}
?>
