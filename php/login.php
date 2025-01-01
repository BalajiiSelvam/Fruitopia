<?php
header('Content-Type: application/json');

session_start(); // Start a session at the beginning

$servername = 'localhost';
$username = 'myaccount';
$password = 'abc123***ABC';
$dbname = 'fruitopia';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'DB connection failed: ' . $conn->connect_error]));
}

$data = json_decode(file_get_contents('php://input'), true);
$user = $data['username'];
$pass = $data['password'];

if (empty($user) || empty($pass)) {
    echo json_encode(['success' => false, 'message' => 'Username and Password cannot be Empty.']);
    exit;
}

$stmt = $conn->prepare('SELECT password, role FROM register_table WHERE username = ?');
$stmt->bind_param('s', $user);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($hashed_password, $db_role); // Bind both password and role
    $stmt->fetch();

    if (password_verify($pass, $hashed_password)) {
        // Successful login: set session variables
        $_SESSION['username'] = $user;  // Store username in session
        $_SESSION['role'] = $db_role;   // Store role in session

        echo json_encode(['success' => true, 'role' => $db_role]); // Return role after successful login
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid Password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Username not found']);
}

$stmt->close();
$conn->close();
?>
