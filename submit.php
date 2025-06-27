<?php
session_start();
include "header.php";
echo <<<STYLE
<style>
    body { font-family: Arial, sans-serif; background: #f8f9fa; margin: 0; padding: 0; }
    .container { max-width: 700px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px #ccc; }
    h1, h2, h3 { color: #333; }
    img { border-radius: 8px; border: 1px solid #ddd; margin: 10px 0; }
    ul { list-style: none; padding: 0; }
    li { background: #f1f1f1; margin-bottom: 15px; padding: 15px; border-radius: 8px; }
    .profile-img { max-width: 100px; display: block; margin-top: 8px; }
</style>
<div class="container">
STYLE;
$target_dir = "uploads/";
$max_file_size = 200000; // 200KB
$allowed_types = ['jpg', 'png'];
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create upload directory if it doesn't exist
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit"])) {
    $username = $_POST['name'];
    $email = $_POST['email'];

    // Check if file was uploaded without errors
    if (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        die("Error uploading file - " . $_FILES["image"]["error"]);
    }

    // Validate file
    $file_tmp = $_FILES["image"]["tmp_name"];
    $file_name = basename($_FILES["image"]["name"]);
    $file_size = $_FILES["image"]["size"];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Check if image is actual image
    $check = getimagesize($file_tmp);
    if ($check === false) {
        die("File is not an image.");
    }

    // Check file size
    if ($file_size > $max_file_size) {
        die("File is too large. Maximum size is " . $max_file_size/1000 . "KB.");
    }

    // Allow certain file formats
    if (!in_array($file_ext, $allowed_types)) {
        die("Only JPG, PNG files are allowed.");
    }

    // Generate unique filename to prevent overwrites
    $new_filename = uniqid('img_', true) . '.' . $file_ext;
    $target_file = $target_dir . $new_filename;

    // Move the uploaded file
    if (move_uploaded_file($file_tmp, $target_file)) {
        // Store name in session
        $_SESSION['fullname'] = $username;

        // Save profile to profiles.txt
        $profile_line = $username . "|" . $email . "|" . $new_filename . PHP_EOL;
        file_put_contents("profiles.txt", $profile_line, FILE_APPEND);

        // Display welcome message and image
      echo "<h2>Welcome, " . htmlspecialchars($username) . "! Your profile picture:</h2>";
echo "<img src='" . htmlspecialchars($target_file) . "' alt='Uploaded Image' style='max-width:300px;' class='profile-img'><br><br>";

// Read and display all profiles
if (file_exists("profiles.txt")) {
    echo "<h3>All Profiles:</h3><ul>";
    $lines = file("profiles.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($name, $mail, $img) = explode("|", $line);
        echo "<li>";
        echo "<strong>" . htmlspecialchars($name) . "</strong> (" . htmlspecialchars($mail) . ")<br>";
        echo "<img src='uploads/" . htmlspecialchars($img) . "' alt='Profile Image' class='profile-img'>";
        echo "</li>";
    }
    echo "</ul>";
}
echo "</div>";
    } else {
        die("Sorry, there was an error uploading your file.");
    }
}
?>