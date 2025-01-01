<?php
// Database connection settings
$servername = 'localhost';
$username = 'myaccount';
$password = 'abc123***ABC';
$dbname = 'fruitopia';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]);
    exit();
}

// Fetch data from the register_table
$sql = "SELECT id, username, role FROM register_table";
$result = $conn->query($sql);

if ($result === false) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching users: ' . $conn->error
    ]);
    $conn->close();
    exit();
}

// Prepare users data
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = [
        'id' => $row['id'],
        'username' => $row['username'],
        'role' => $row['role']
    ];
}

// Return JSON response
echo json_encode([
    'success' => true,
    'users' => $users
]);

$conn->close();
?>
