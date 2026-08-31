<?php
session_start();
include "db.php";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // find user
    $sql = "SELECT * FROM auth_users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['auth_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: dashboard.php");
        exit();

    } else {
        echo "Invalid credentials.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login - Career Canvas</title>
</head>
<body>

<h2>Login</h2>

<form action="login.php" method="POST">
    <input type="text" name="email" placeholder="Email or Username" required><br><br>

    <input type="password" name="password" placeholder="Password" required><br><br>

    <button type="submit" name="login">Login</button>
</form>

<a href="signup.php">Create account</a>

</body>
</html>