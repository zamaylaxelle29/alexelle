<?php
// Start session and include necessary files
include 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
   header('Location: login.php');
   exit();
}

// Check if user ID is provided in the URL
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $edit_user_id = $_GET['id'];

    // Fetch user details based on the provided ID
    $userQuery = "SELECT * FROM user WHERE id = ?";
    $stmt = $conn->prepare($userQuery);
    $stmt->bind_param('i', $edit_user_id);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userResult && $userResult->num_rows > 0) {
        $fetch = $userResult->fetch_assoc();
    } else {
        echo "User details not found!";
        exit();
    }
} else {
    echo "User ID not provided!";
    exit();
}


// Fetch user details based on the provided ID
$userQuery = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param('i', $edit_user_id);
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
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $middle_name = $_POST['middle_name'] ?? '';
    $purok = $_POST['purok'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $ext = $_POST['ext'] ?? '';
    $sex = $_POST['sex'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';
    $civil_status = $_POST['civil_status'] ?? '';
    $age = $_POST['age'] ?? '';
    $contact_no = $_POST['contact_no'] ?? '';
    $registered_voter = isset($_POST['registered_voter']) && $_POST['registered_voter'] === 'Yes' ? 1 : 0;
    $highest_educational_attainment = $_POST['highest_educational_attainment'] ?? '';
    $out_of_school_reason = $_POST['out_of_school_reason'] ?? '';
    $working = $_POST['working'] ?? '';
    $government_agency = $_POST['government_agency'] ?? '';
    $disability = isset($_POST['disability']) && $_POST['disability'] === 'yes' ? 1 : 0;
    $disability_type = $_POST['disability_type'] ?? '';
    $medical_condition = isset($_POST['medical_condition']) && $_POST['medical_condition'] === 'yes' ? 1 : 0;
    $medical_type = $_POST['medical_type'] ?? '';
    $youth_organization = isset($_POST['youth_organization']) && $_POST['youth_organization'] === 'yes' ? 1 : 0;
    $youth_organization_name = $_POST['youth_organization_name'] ?? '';
    $skills = $_POST['skills'] ?? '';
    $interest = $_POST['interest'] ?? '';
    // Handle image upload
  

    $updateQuery = "UPDATE user SET first_name=?, last_name=?, middle_name=?, purok=?, email=?, password=?, ext=?, sex=?, birthdate=?, civil_status=?, age=?, contact_no=?, registered_voter=?, highest_educational_attainment=?, out_of_school_reason=?, working=?, government_agency=?, disability=?, disability_type=?, medical_condition=?, medical_type=?, youth_organization=?, youth_organization_name=?, skills=?, interest=? WHERE id=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('ssssssssssssssssssssssssss', $first_name, $last_name, $middle_name, $purok, $email, $password, $ext, $sex, $birthdate, $civil_status, $age, $contact_no, $registered_voter, $highest_educational_attainment, $out_of_school_reason, $working, $government_agency, $disability, $disability_type, $medical_condition, $medical_type, $youth_organization, $youth_organization_name, $skills, $interest, $edit_user_id);

    if ($stmt->execute()) {
        echo "User data updated successfully!";
    } else {
        echo "Error updating user data: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
</head>
<body>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($fetch['first_name']); ?>"><br><br>

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($fetch['last_name']); ?>"><br><br>

    <label for="middle_name">Middle Name:</label>
    <input type="text" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($fetch['middle_name']); ?>"><br><br>

    <label for="purok">Address:</label>
    <input type="text" id="purok" name="purok" value="<?php echo htmlspecialchars($fetch['purok']); ?>"><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($fetch['email']); ?>"><br><br>

    <label for="password">New Password:</label>
    <input type="password" id="password" name="password"><br><br>

    <label for="ext">Extension:</label>
    <input type="text" id="ext" name="ext" value="<?php echo htmlspecialchars($fetch['ext']); ?>"><br><br>

    <label for="sex">Sex:</label>
    <input type="text" id="sex" name="sex" value="<?php echo htmlspecialchars($fetch['sex']); ?>"><br><br>

    <label for="birthdate">Birthdate:</label>
    <input type="text" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($fetch['birthdate']); ?>"><br><br>

    <label for="civil_status">Civil Status:</label>
    <input type="text" id="civil_status" name="civil_status" value="<?php echo htmlspecialchars($fetch['civil_status']); ?>"><br><br>

    <label for="age">Age:</label>
    <input type="text" id="age" name="age" value="<?php echo htmlspecialchars($fetch['age']); ?>"><br><br>

    <label for="contact_no">Contact No:</label>
    <input type="text" id="contact_no" name="contact_no" value="<?php echo htmlspecialchars($fetch['contact_no']); ?>"><br><br>

    <label for="registered_voter">Registered Voter:</label>
    <input type="text" id="registered_voter" name="registered_voter" value="<?php echo htmlspecialchars($fetch['registered_voter']); ?>"><br><br>

    <label for="highest_educational_attainment">Highest Educational Attainment:</label>
    <input type="text" id="highest_educational_attainment" name="highest_educational_attainment" value="<?php echo htmlspecialchars($fetch['highest_educational_attainment']); ?>"><br><br>

    <label for="out_of_school_reason">Out of School Reason:</label>
    <input type="text" id="out_of_school_reason" name="out_of_school_reason" value="<?php echo htmlspecialchars($fetch['out_of_school_reason']); ?>"><br><br>

    <label for="working">Working:</label>
    <input type="text" id="working" name="working" value="<?php echo htmlspecialchars($fetch['working']); ?>"><br><br>

    <label for="government_agency">Government Agency:</label>
    <input type="text" id="government_agency" name="government_agency" value="<?php echo htmlspecialchars($fetch['government_agency']); ?>"><br><br>

    <label for="disability">Disability:</label>
    <input type="text" id="disability" name="disability" value="<?php echo htmlspecialchars($fetch['disability']); ?>"><br><br>

    <label for="disability_type">Disability Type:</label>
    <input type="text" id="disability_type" name="disability_type" value="<?php echo htmlspecialchars($fetch['disability_type']); ?>"><br><br>

    <label for="medical_condition">Medical Condition:</label>
    <input type="text" id="medical_condition" name="medical_condition" value="<?php echo htmlspecialchars($fetch['medical_condition']); ?>"><br><br>

    <label for="medical_type">Medical Type:</label>
    <input type="text" id="medical_type" name="medical_type" value="<?php echo htmlspecialchars($fetch['medical_type']); ?>"><br><br>

    <label for="youth_organization">Youth Organization:</label>
    <input type="text" id="youth_organization" name="youth_organization" value="<?php echo htmlspecialchars($fetch['youth_organization']); ?>"><br><br>

    <label for="youth_organization_name">Youth Organization Name:</label>
    <input type="text" id="youth_organization_name" name="youth_organization_name" value="<?php echo htmlspecialchars($fetch['youth_organization_name']); ?>"><br><br>

    <label for="skills">Skills:</label>
    <input type="text" id="skills" name="skills" value="<?php echo htmlspecialchars($fetch['skills']); ?>"><br><br>

    <label for="interest">Interest:</label>
    <input type="text" id="interest" name="interest" value="<?php echo htmlspecialchars($fetch['interest']); ?>"><br><br>

    <input type="submit" value="Update Profile">
    <a href="sklist.php">Back</a>

<?php if (!empty($fetch['image'])): ?>
    <h3>Current Profile Image:</h3>
    <img src="<?php echo $fetch['image']; ?>" alt="Profile Image" width="150">
<?php endif; ?>
</body>
</html>
