<?php
header('Content-Type: application/json');
$servername = 'localhost';
$username = 'myaccount';
$password = 'abc123***ABC';
$dbname = 'fruitopia';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'GET') {
    // Fetch fruits data
    $sql = "SELECT id, name, price, quantity, category, rating FROM added_fruits";
    $result = $conn->query($sql);

    if ($result) {
        $fruits = [];
        while ($row = $result->fetch_assoc()) {
            $fruits[] = $row;
        }
        echo json_encode(['success' => true, 'fruits' => $fruits]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error fetching fruits: ' . $conn->error]);
    }
} elseif ($requestMethod === 'POST') {
    // Handle insert or delete based on input
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['id'])) {
        // Delete request
        $id = $input['id'];
        $stmt = $conn->prepare("DELETE FROM added_fruits WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Fruit deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting fruit: ' . $stmt->error]);
        }

        $stmt->close();
    } elseif (isset($_POST['item-name'])) {
        // Insert new fruit
        $name = $_POST['item-name'];
        $price = $_POST['item-price'];
        $quantity = $_POST['item-quantity'];
        $category = $_POST['item-category'];
        $rating = $_POST['item-rating'];

        // Handle image upload
        $imageDestination = '';
        if (isset($_FILES['item-image']) && $_FILES['item-image']['error'] === 0) {
            $image = $_FILES['item-image'];
            $imageExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageExtension, $allowedExtensions) && $image['size'] < 5000000) {
                $imageDestination = "../uploads/" . uniqid('', true) . "." . $imageExtension;
                move_uploaded_file($image['tmp_name'], $imageDestination);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid image file']);
                exit();
            }
        }

        $stmt = $conn->prepare("INSERT INTO added_fruits (name, price, quantity, category, rating, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdisss", $name, $price, $quantity, $category, $rating, $imageDestination);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Fruit added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error adding fruit: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Unsupported request method']);
}

$conn->close();
?>
