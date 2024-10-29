<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('Location: login.php');
   exit();
}

if (isset($_GET['logout'])) {
   unset($user_id);
   session_destroy();
   header('Location: login.php');
   exit();
}

// Fetch user details
$userQuery = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$userResult = $stmt->get_result();

if ($userResult && $userResult->num_rows > 0) {
    $fetch = $userResult->fetch_assoc();
} else {
    echo "User details not found!";
    exit();
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_name = $_POST['middle_name'];
    $purok = $_POST['purok'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];
    $old_password = $_POST['old_password'];

    // Validate old password
    $old_password_db = $fetch['password'];
    if ($old_password !== $old_password_db) {
        header('Location: updateprofile.php?error=incorrect_password');
        exit();
    }

    // Check if new password and confirm new password match
    if ($new_password !== $confirm_new_password) {
        echo '<script>alert("New password and confirm new password do not match.");</script>';
        exit();
    }

    // Handle image upload if a new image is selected
    if ($_FILES['image']['size'] > 0) {
        $targetDir = "uploaded_img/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        
        // Check file type
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedExtensions = array("jpg", "jpeg", "png"); // Allowed image file extensions
        
        if (in_array($imageFileType, $allowedExtensions)) {
            // Move uploaded file to target directory
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                // Update image path in database
                $imagePath = $targetFile;
                $updateQuery = "UPDATE user SET image = ? WHERE id = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param('si', $imagePath, $user_id);
                if ($stmt->execute()) {
                    // Image path updated successfully
                } else {
                    echo "Error updating image path in database: " . $stmt->error;
                    exit();
                }
            } else {
                // Image upload failed
                echo "Error uploading image.";
                exit();
            }
        } else {
            // Invalid file type
            echo "Only JPEG, JPG, and PNG files are allowed.";
            exit();
        }
    }

    // Update password only if not empty
    if (!empty($new_password)) {
        // Check if new password and confirm new password match
        if ($new_password !== $confirm_new_password) {
            echo '<script>alert("New password and confirm new password do not match.");</script>';
            exit();
        }

        // Update the password in the database
        $updatePasswordQuery = "UPDATE user SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($updatePasswordQuery);
        $stmt->bind_param('si', $new_password, $user_id);
        if ($stmt->execute()) {
            // Password updated successfully
        } else {
            echo "Error updating password: " . $stmt->error;
            exit();
        }
    }

    // Update other profile details
    $updateQuery = "UPDATE user SET first_name=?, last_name=?, middle_name=?, purok=?, email=? WHERE id=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('sssssi', $first_name, $last_name, $middle_name, $purok, $email, $user_id);
    
    if ($stmt->execute()) {
        // Profile updated successfully
        echo '<script>alert("Profile updated successfully."); window.location.href = "dashboard.php";</script>';
        exit();
    } else {
        echo "Error updating profile: " . $stmt->error;
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Edit Profile</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: white;
            background-size: cover;
        }

        .container {
            background-color: #040454;
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 500px;
        }

        h2 {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-wrap: wrap;
        }

        .form-group label {
            width: 45%; /* Adjust as needed */
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 50%; /* Adjust as needed */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group button {
            width: 30%;
            padding: 10px 15px;
            background-color: #00a86b;
            border: none;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        .form-group button:hover {
            background-color: #007b5e;
        }
        .back-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #ccc;
            border: none;
            color: #000;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .back-btn:hover {
            background-color: #999;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Current Profile Image:</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
<div class="form-group">
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($fetch['first_name']); ?>"><br><br>
</div>
<div class="form-group">
    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($fetch['last_name']); ?>"><br><br>
</div>
<div class="form-group">
    <label for="middle_name">Middle Name:</label>
    <input type="text" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($fetch['middle_name']); ?>"><br><br>
</div>
<div class="form-group">
    <label for="purok">Address:</label>
    <input type="text" id="purok" name="purok" value="<?php echo htmlspecialchars($fetch['purok']); ?>"><br><br>
</div>
<div class="form-group">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($fetch['email']); ?>"><br><br>
</div>
<div class="form-group">
    <label for="old_password">Old Password:</label>
    <input type="password" id="old_password" name="old_password" required><br><br>
    <?php
    if (isset($_GET['error']) && $_GET['error'] === 'incorrect_password') {
        echo '<span style="color: red;">Incorrect old password.</span><br><br>';
    }
    ?>
</div> 
    <div class="form-group">
    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password"><br><br>
    </div>
    <div class="form-group">
    <label for="confirm_new_password">Confirm New Password:</label>
    <input type="password" id="confirm_new_password" name="confirm_new_password"><br><br>
    </div>
    <div class="form-group">
    <label for="file">Upload New Image</label>
    <input type="file" name="image">
    <?php if(!empty($fetch['image'])): ?>
        <img src="<?php echo $fetch['image']; ?>" alt="Current Image" width="100">
    <?php endif; ?>
</div>

    <div class="form-group">
                <button type="submit" name="update">Update</button>
                <a href="userdashboard.php" class="back-btn">Back</a>
    </div>
</form>
</div>
</body>
</html>

