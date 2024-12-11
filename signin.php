<!-- PHP FILE -->
<?php

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (!empty($full_name) && !empty($phone_number) && !empty($password)) {
        // Prepare an SQL query to prevent SQL injection
        $sql = "INSERT INTO users (full_name, email, phone_number, role, password) 
                VALUES ('$full_name', '$email', '$phone_number', '$role', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            echo "New account created successfully!";
        } else {
            echo "Failed to create a new account.";
        }
    } else {
        echo "Please fill in all fields";
    }
}
?>


<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Create an Account</title>
</head>
<body>
    <!-- Sign In Form Container -->
    <div class="form-container">
        <div class="form-box">
            <h2>Create an Account</h2>
            <form method="post" action="signin.php">
                Name: <input type="text" name="full_name" required><br><br>
                Email: <input type="email" name="email" required><br><br>
                Phone Number: <input type="text" name="phone_number" required><br><br>
                Password: <input type="password" name="password" required><br><br>
                <label for="role">Role:</label>
                <select id="role" name="role">
                    <option value="customer">customer</option>
                    <option value="therapist">therapist</option>
                    <option value="admin">admin</option>
                </select><br><br>
                <input type="submit" value="Sign Up">
            </form>
            <p class="inline">Already have an account? <a href="login.php">Login Here.</a></p>
        </div>
    </div>
</body>
</html>



