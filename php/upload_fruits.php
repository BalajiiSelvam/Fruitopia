<?php
// Set the content type to JSON for response
header('Content-Type: application/json');

// Include database connection (change credentials as per your environment)
$servername = "localhost";
$username = "root"; // your DB username
$password = ""; // your DB password
$dbname = "fruitopia"; // your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Handle form data and file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['fruit-name'];
    $price = $_POST['fruit-price'];
    $image = $_FILES['fruit-image'];

    // Check if image file is uploaded
    if ($image['error'] == 0) {
        $uploads_dir = 'uploads/';
        $imagePath = $uploads_dir . basename($image['name']);

        // Move the uploaded file to the 'uploads' folder
        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            // Insert fruit data into the database
            $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $name, $price, $imagePath);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Fruit uploaded successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error saving to database: ' . $stmt->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error uploading image.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No image uploaded or error during upload.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

// Close connection
$conn->close();

?>
