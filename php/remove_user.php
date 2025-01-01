<?php
header('Content-Type:application/json');

$servername = 'localhost';
$username = 'myaccount';
$password = 'abc123***ABC';
$dbname = 'fruitopia';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'DB connection failed' . $conn->connect_error]));
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['userId'];

if (empty($userId)) {
    echo json_encode(['success' => false, 'message' => 'User ID is required.']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM register_table WHERE id = ?");
$stmt->bind_param('i', $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User removed successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error removing user: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
