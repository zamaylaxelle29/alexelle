<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "skcon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize $row array to hold old data
$row = array();

// Check if user ID is provided
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch existing user data from database
    $sql_select = "SELECT * FROM user WHERE id=?";
    $stmt = $conn->prepare($sql_select);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "User not found";
        exit;
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $middle_name = isset($_POST['middle_name']) ? $_POST['middle_name'] : '';
    $ext = isset($_POST['ext']) ? $_POST['ext'] : '';
    $sex = isset($_POST['sex']) ? $_POST['sex'] : '';
    $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
    $civil_status = isset($_POST['civil_status']) ? $_POST['civil_status'] : '';
    $age = isset($_POST['age']) ? $_POST['age'] : '';
    $contact_no = isset($_POST['contact_no']) ? $_POST['contact_no'] : '';
    $registered_voter = isset($_POST['registered_voter']) ? 1 : 0;
    $purok = isset($_POST['purok']) ? $_POST['purok'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $highest_educational_attainment = isset($_POST['highest_educational_attainment']) ? $_POST['highest_educational_attainment'] : '';
    $out_of_school_reason = isset($_POST['out_of_school_reason']) ? $_POST['out_of_school_reason'] : '';
    $working = isset($_POST['working']) ? 1 : 0;
    $government_agency = isset($_POST['government_agency']) ? $_POST['government_agency'] : '';
    $disability = isset($_POST['disability']) ? 1 : 0;
    $disability_type = isset($_POST['disability_type']) ? $_POST['disability_type'] : '';
    $medical_condition = isset($_POST['medical_condition']) ? 1 : 0;
    $medical_type = isset($_POST['medical_type']) ? $_POST['medical_type'] : '';
    $youth_organization = isset($_POST['youth_organization']) ? 1 : 0;
    $youth_organization_name = isset($_POST['youth_organization_name']) ? $_POST['youth_organization_name'] : '';
    $skills = isset($_POST['skills']) ? $_POST['skills'] : '';
    $interest = isset($_POST['interest']) ? $_POST['interest'] : '';

    // Prepare and execute SQL statement to update user data
    $sql_update = "UPDATE user SET 
        last_name=?, 
        first_name=?, 
        middle_name=?, 
        ext=?, 
        sex=?, 
        birthdate=?, 
        civil_status=?, 
        age=?, 
        contact_no=?, 
        registered_voter=?, 
        purok=?, 
        email=?, 
        highest_educational_attainment=?, 
        out_of_school_reason=?, 
        working=?, 
        government_agency=?, 
        disability=?, 
        disability_type=?, 
        medical_condition=?, 
        medical_type=?, 
        youth_organization=?, 
        youth_organization_name=?, 
        skills=?, 
        interest=? 
        WHERE id=?";

    // Prepare the statement
    $stmt = $conn->prepare($sql_update);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ssssssssssssssssssssssssi", 
            $last_name, 
            $first_name, 
            $middle_name, 
            $ext, 
            $sex, 
            $birthdate, 
            $civil_status, 
            $age, 
            $contact_no, 
            $registered_voter, 
            $purok, 
            $email, 
            $highest_educational_attainment, 
            $out_of_school_reason, 
            $working, 
            $government_agency, 
            $disability, 
            $disability_type, 
            $medical_condition, 
            $medical_type, 
            $youth_organization, 
            $youth_organization_name, 
            $skills, 
            $interest,
            $user_id);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/edit.css">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            display: flex;
            flex-direction: column;
            width: 80%;
            margin: auto;
            background-color: #040454;
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group label {
            font-weight: 500;
        }

        .form-control {
            border-radius: 5px;
        }

        .btn-primary, .btn-secondary {
            margin-top: 10px;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>

</head>
<body>

<div class="container mt-5">
    <h2>Edit User</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id ?? ''); ?>">

        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($row['last_name'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($row['first_name'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="middle_name">Middle Name:</label>
            <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($row['middle_name'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="ext">Extension:</label>
            <input type="text" class="form-control" id="ext" name="ext" value="<?php echo htmlspecialchars($row['ext'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="sex">Sex:</label>
            <select class="form-control" id="sex" name="sex">
                <option value="Male" <?php echo (isset($row['sex']) && $row['sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo (isset($row['sex']) && $row['sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
            </select>
        </div>

        <div class="form-group">
            <label for="birthdate">Birthdate:</label>
            <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($row['birthdate'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="civil_status">Civil Status:</label>
            <select class="form-control" id="civil_status" name="civil_status">
                <option value="Single" <?php echo (isset($row['civil_status']) && $row['civil_status'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                <option value="Married" <?php echo (isset($row['civil_status']) && $row['civil_status'] == 'Married') ? 'selected' : ''; ?>>Married</option>
            </select>
        </div>

        <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($row['age'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="contact_no">Contact No:</label>
            <input type="text" class="form-control" id="contact_no" name="contact_no" value="<?php echo htmlspecialchars($row['contact_no'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="registered_voter">Registered Voter:</label>
            <select class="form-control" id="registered_voter" name="registered_voter">
                <option value="1" <?php echo (isset($row['registered_voter']) && $row['registered_voter'] == 1) ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?php echo (isset($row['registered_voter']) && $row['registered_voter'] == 0) ? 'selected' : ''; ?>>No</option>
            </select>
        </div>

        <div class="form-group">
            <label for="purok">Purok:</label>
            <input type="text" class="form-control" id="purok" name="purok" value="<?php echo htmlspecialchars($row['purok'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($row['email'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="highest_educational_attainment">Highest Educational Attainment:</label>
            <select class="form-control" id="highest_educational_attainment" name="highest_educational_attainment">
                <option value="Elementary" <?php echo (isset($row['highest_educational_attainment']) && $row['highest_educational_attainment'] == 'Elementary') ? 'selected' : ''; ?>>Elementary</option>
                <option value="High School" <?php echo (isset($row['highest_educational_attainment']) && $row['highest_educational_attainment'] == 'High School') ? 'selected' : ''; ?>>High School</option>
                <option value="College" <?php echo (isset($row['highest_educational_attainment']) && $row['highest_educational_attainment'] == 'College') ? 'selected' : ''; ?>>College</option>
            </select>
        </div>

        <div class="form-group">
            <label for="out_of_school_reason">Out of School Reason:</label>
            <input type="text" class="form-control" id="out_of_school_reason" name="out_of_school_reason" value="<?php echo htmlspecialchars($row['out_of_school_reason'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="working">Working:</label>
            <select class="form-control" id="working" name="working">
                <option value="1" <?php echo (isset($row['working']) && $row['working'] == 1) ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?php echo (isset($row['working']) && $row['working'] == 0) ? 'selected' : ''; ?>>No</option>
            </select>
        </div>

        <div class="form-group">
            <label for="government_agency">Government Agency:</label>
            <input type="text" class="form-control" id="government_agency" name="government_agency" value="<?php echo htmlspecialchars($row['government_agency'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="disability">Disability:</label>
            <select class="form-control" id="disability" name="disability">
                <option value="1" <?php echo (isset($row['disability']) && $row['disability'] == 1) ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?php echo (isset($row['disability']) && $row['disability'] == 0) ? 'selected' : ''; ?>>No</option>
            </select>
        </div>

        <div class="form-group">
            <label for="disability_type">Disability Type:</label>
            <input type="text" class="form-control" id="disability_type" name="disability_type" value="<?php echo htmlspecialchars($row['disability_type'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="medical_condition">Medical Condition:</label>
            <select class="form-control" id="medical_condition" name="medical_condition">
                <option value="1" <?php echo (isset($row['medical_condition']) && $row['medical_condition'] == 1) ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?php echo (isset($row['medical_condition']) && $row['medical_condition'] == 0) ? 'selected' : ''; ?>>No</option>
            </select>
        </div>

        <div class="form-group">
            <label for="medical_type">Medical Type:</label>
            <input type="text" class="form-control" id="medical_type" name="medical_type" value="<?php echo htmlspecialchars($row['medical_type'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="youth_organization">Youth Organization:</label>
            <select class="form-control" id="youth_organization" name="youth_organization">
                <option value="1" <?php echo (isset($row['youth_organization']) && $row['youth_organization'] == 1) ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?php echo (isset($row['youth_organization']) && $row['youth_organization'] == 0) ? 'selected' : ''; ?>>No</option>
            </select>
        </div>

        <div class="form-group">
            <label for="youth_organization_name">Youth Organization Name:</label>
            <input type="text" class="form-control" id="youth_organization_name" name="youth_organization_name" value="<?php echo htmlspecialchars($row['youth_organization_name'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="skills">Skills:</label>
            <input type="text" class="form-control" id="skills" name="skills" value="<?php echo htmlspecialchars($row['skills'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="interest">Interest:</label>
            <input type="text" class="form-control" id="interest" name="interest" value="<?php echo htmlspecialchars($row['interest'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" value="">
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <button type="button" class="btn btn-secondary" onclick="window.location.href='sklist.php';">Close</button>
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
