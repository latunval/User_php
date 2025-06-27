<?php
require "header.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Registration Form</h1>
    <form action="submit.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        </div>
       
        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <input type="radio" id="male" name="gender" value="male">
            <label for="male">Male</label>
            <br>
            <input type="radio" id="female" name="gender" value="female">
            <label for="female">Female</label>
        </div>
<button type="submit" name="submit">Submit</button>
    </form>
</body>
</html>