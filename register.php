<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $images = [];

    // Handling image uploads
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['images']['name'][$key]);
        $target_file = "upload/" . $file_name;

        if (move_uploaded_file($tmp_name, $target_file)) {
            $images[] = $target_file;
        }
    }

    $images_serialized = serialize($images);

    $sql = "INSERT INTO users (username, password, images) VALUES ('$username', '$password', '$images_serialized')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful";
        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <form action="register.php" method="post" enctype="multipart/form-data">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        Images: <input type="file" name="images[]" multiple><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>
