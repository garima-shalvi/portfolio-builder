<?php
session_start();
include "db.php";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    
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
        $error = "Invalid credentials.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - CareerCanvas</title>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

<style>
* {
    box-sizing: border-box;
    font-family: "Montserrat", sans-serif;
}

body {
    margin: 0;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(165deg, #2699E6, #1a66cc, #0d33b3, #000099);
    background-size: 300% 300%;
    animation: gradientMove 12s ease infinite;
}

@keyframes gradientMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.container {
    width: 90%;
    max-width: 430px;
    background: #fbfbfb;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 25px 60px rgba(6, 101, 133, 0.3);
    animation: fadeIn 0.8s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.logo {
    text-align: center;
    margin-bottom: 30px;
}

.logo h1 {
    margin: 0;
    font-size: 38px;
    font-weight: 700;
    color: #00b4d8;
}

.logo h1 span {
    color: #083188;
}

.logo p {
    margin-top: 8px;
    color: #098497;
    font-size: 14px;
}

h2 {
    text-align: center;
    color: #083188;
    margin-bottom: 30px;
}

label {
    display: block;
    margin-top: 14px;
    margin-bottom: 6px;
    font-weight: 600;
    color: #023047;
    font-size: 14px;
}

input {
    width: 100%;
    padding: 12px 13px;
    border-radius: 10px;
    border: 1px solid #bde0fe;
    font-size: 15px;
    background: #e8f9fb;
    transition: 0.3s ease;
}

input:focus {
    outline: none;
    border-color: #0077b6;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.2);
}

button {
    width: 100%;
    margin-top: 28px;
    padding: 13px;
    border: none;
    border-radius: 30px;
    background: linear-gradient(135deg, #0077b6, #00b4d8);
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.35s ease;
}

button:hover {
    transform: scale(1.03);
    box-shadow: 0 10px 25px rgba(0, 180, 216, 0.45);
}

.error {
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 10px;
    background: #ffe8e8;
    border: 1px solid #ffaaaa;
    color: #c62828;
    text-align: center;
    font-size: 13px;
}

.signup-link {
    margin-top: 22px;
    text-align: center;
    color: #555;
    font-size: 14px;
}

.signup-link a {
    color: #0077b6;
    font-weight: 700;
    text-decoration: none;
}

.signup-link a:hover {
    color: #00a6c7;
}

@media (max-width: 500px) {
    .container {
        padding: 30px 25px;
    }

    .logo h1 {
        font-size: 32px;
    }
}
</style>
</head>

<body>

<div class="container">

    <div class="logo">
        <h1><span>C</span>areer<span>C</span>anvas</h1>
        <p>Paint your own career story</p>
    </div>

    <h2>Welcome Back</h2>

    <?php if(isset($error)): ?>
        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">

        <label>Email or Username</label>
        <input type="text" name="email" placeholder="Enter your email or username" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>

        <button type="submit" name="login">Login</button>

    </form>

    <div class="signup-link">
        Don't have an account?
        <a href="signup.php">Create Account</a>
    </div>

</div>

</body>
</html>