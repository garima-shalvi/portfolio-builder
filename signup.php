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
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up - CareerCanvas</title>

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
    padding: 30px 0;
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
    max-width: 450px;
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
    margin-bottom: 28px;
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
    margin-bottom: 25px;
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

.login-link {
    margin-top: 22px;
    text-align: center;
    color: #555;
    font-size: 14px;
}

.login-link a {
    color: #0077b6;
    font-weight: 700;
    text-decoration: none;
}

.login-link a:hover {
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

    <h2>Create Account</h2>

    <?php if(isset($error)): ?>
        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form action="signup.php" method="POST">

        <label>Full Name</label>
        <input type="text" name="name" placeholder="Enter your full name" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Username</label>
        <input type="text" name="username" placeholder="Choose a username" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Create a password" required>

        <button type="submit" name="signup">Create Account</button>

    </form>

    <div class="login-link">
        Already have an account?
        <a href="login.php">Login</a>
    </div>

</div>

</body>
</html>