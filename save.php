<?php
// MySQL Database Credentials
$host = "sql12.freesqldatabase.com";
$dbname = "sql12770862";
$username = "sql12770862";
$password = "yVvzqBUqBy";
$port = 3306;

// Connect to MySQL
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Get JSON data from request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["name"]) && isset($data["number"])) {
    $name = $conn->real_escape_string($data["name"]);
    $number = (int)$data["number"];

    // Check if the user already exists
    $checkQuery = "SELECT * FROM members WHERE name = '$name'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        echo json_encode(["error" => "User has already spun."]);
    } else {
        // Insert data into MySQL
        $insertQuery = "INSERT INTO members (name, number) VALUES ('$name', $number)";
        if ($conn->query($insertQuery) === TRUE) {
            echo json_encode(["success" => "Ballot saved successfully!"]);
        } else {
            echo json_encode(["error" => "Error saving ballot: " . $conn->error]);
        }
    }
} else {
    echo json_encode(["error" => "Invalid input data."]);
}

$conn->close();
?>
