<?php

include "db.php";

if (isset($_POST['signup'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    
    $checkEmail = $conn->prepare(
        "SELECT id FROM auth_users WHERE email = ?"
    );
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $emailResult = $checkEmail->get_result();

    if ($emailResult->num_rows > 0) {
        $error = "This email is already registered. Please use another email or log in.";
    }

    $checkEmail->close();


    
    if (!isset($error)) {

        $checkUsername = $conn->prepare(
            "SELECT id FROM auth_users WHERE username = ?"
        );
        $checkUsername->bind_param("s", $username);
        $checkUsername->execute();
        $usernameResult = $checkUsername->get_result();

        if ($usernameResult->num_rows > 0) {
            $error = "This username is already taken. Please choose another username.";
        }

        $checkUsername->close();
    }


    
    if (!isset($error)) {

        
        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $sql = "INSERT INTO auth_users 
                (name, email, username, password)
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssss",
            $name,
            $email,
            $username,
            $hashed_password
        );

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Signup failed. Please try again.";
        }

        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - Career Canvas</title>
</head>
<body>

<h2>Create Account</h2>
<?php if (isset($error)): ?>
    <p style="color: red;">
        <?php echo htmlspecialchars($error); ?>
    </p>
<?php endif; ?>
<form action="signup.php" method="POST">
    <input type="text" name="name" placeholder="Full Name" required><br><br>

    <input type="email" name="email" placeholder="Email" required><br><br>

    <input type="text" name="username" placeholder="Username" required><br><br>

    <input type="password" name="password" placeholder="Password" required><br><br>

    <button type="submit" name="signup">Sign Up</button>
</form>

<a href="login.php">Already have an account?</a>

</body>
</html>