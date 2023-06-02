<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $description = $_POST["description"];
    $imgURL = "";

    // Handle file upload
    if (isset($_FILES["imgURL"])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $targetFilePath = $targetDir . basename($_FILES["imgURL"]["name"]);
        if (move_uploaded_file($_FILES["imgURL"]["tmp_name"], $targetFilePath)) {
            $imgURL = $targetFilePath;
        } else {
            echo "Error uploading image.";
            exit;
        }
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO product (name, price, description, imgURL) VALUES ('$name', '$price', '$description', '$imgURL')";

    if ($conn->query($sql) === TRUE) {
        echo "Product added successfully.";
    } else {
        echo "Error adding product: " . $conn->error;
    }

    $conn->close();
}
?>
