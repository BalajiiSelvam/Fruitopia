<?php
// Database connection
$servername = 'localhost';
$username = 'myaccount';
$password = 'abc123***ABC';
$dbname = 'fruitopia';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['item-name'];
    $price = $_POST['item-price'];
    $quantity = $_POST['item-quantity'];
    $category = $_POST['item-category'];
    $rating = $_POST['item-rating'];

    // Handle image upload
    if (isset($_FILES['item-image'])) {
        $image = $_FILES['item-image'];
        $imageName = $image['name'];
        $imageTmpName = $image['tmp_name'];
        $imageSize = $image['size'];
        $imageError = $image['error'];
        $imageType = $image['type'];

        // Check if there was an error during upload
        if ($imageError === 0) {
            $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageExtension, $allowedExtensions)) {
                if ($imageSize < 5000000) { // Max 5MB file size
                    $imageNewName = uniqid('', true) . "." . $imageExtension;
                    // Ensure the path is accessible (relative to the root folder)
                    $imageDestination = "uploads/" . $imageNewName;
                    move_uploaded_file($imageTmpName, $imageDestination);
                } else {
                    echo "The image file is too large.";
                    exit;
                }
            } else {
                echo "Invalid file type. Only JPG, JPEG, PNG, GIF are allowed.";
                exit;
            }
        } else {
            echo "Error uploading image.";
            exit;
        }
    }

    // Use prepared statements to insert the data into the database
    $stmt = $conn->prepare("INSERT INTO added_fruits (name, price, quantity, category, rating, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdisss", $name, $price, $quantity, $category, $rating, $imageDestination);

    if ($stmt->execute()) {
        // Redirect back to the form page (add_fruits.html) after successful insert
        header("Location: ../add_fruits.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); // Close the prepared statement
}

// Fetch data from the added_fruits table
$sql = "SELECT id, name, price, quantity, category, rating FROM added_fruits";
$result = $conn->query($sql);

if ($result === false) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching fruits: ' . $conn->error
    ]);
    $conn->close();
    exit();
}

// Prepare fruits data
$fruits = [];
while ($row = $result->fetch_assoc()) {
    $fruits[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'price' => $row['price'],
        'quantity' => $row['quantity'],
       
        'category' => $row['category'],
        'rating' => $row['rating']
    ];
}

// Return JSON response
echo json_encode([
    'success' => true,
    'fruits' => $fruits
]);

$conn->close(); // Close the database connection

// Handle the delete request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $id = $data['id'];

    // Use prepared statement to delete the fruit by id
    $stmt = $conn->prepare("DELETE FROM added_fruits WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting fruit.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

$conn->close();
?>
