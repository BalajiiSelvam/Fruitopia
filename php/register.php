<?php
header('Content-Type:application/json');

$servername = 'localhost';
$username = 'myaccount';
$password = 'abc123***ABC';
$dbname = 'fruitopia';

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die(json_encode(['success'=>false, 'message'=>'DB connection failed'.$conn->connect_error]));
}
$data = json_decode(file_get_contents('php://input'),true);
$user = $data['username'];
$pass = $data['password'];
$role = $data['role'];
if(strlen($pass)<8){
    echo json_encode(['success'=>false, 'message'=>'Password length must be greater than 8.']);
    exit;
}
else if(!preg_match('/\d/',$pass)){
    echo json_encode(['success'=>false, 'message'=>'Password must contain atleast 1 digit.']);
    exit;
}
else if(!preg_match('/[A-Z]/',$pass)){
    echo json_encode(['success'=>false, 'message'=>'Password must conatin atleast 1 alphabet.']);
    exit;
}
else if(!preg_match('/[!@#$%^&*(),.?":{}|<>]/',$pass)){
    echo json_encode(['success'=>false, 'message'=>'Password must contain atleast 1 special character.']);
    exit;
}
else{
    $hashed_password = password_hash($pass,PASSWORD_BCRYPT);
    $stmt = $conn->prepare('INSERT INTO register_table(username, password, role) VALUES(?,?,?)');
    $stmt->bind_param('sss',$user,$hashed_password,$role);
    if($stmt->execute()){
        echo json_encode(['success'=>true]);
    }
    else{
        echo json_encode(['success'=>false, 'message'=>'Error'.$stmt->error]);
    }
    $stmt->close();
    $conn->close();
}
?>

