<?php
session_start();
include 'config.php';

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['user_id'];
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid Email or Password');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | MyBank.Pro</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style3.css">
</head>
<body>

<header class="topbar">
    <div class="logo">MYBANK.PRO</div>
    <button type="button" onclick="window.location.href='index.php'" class="back">Back</button>
</header>

<main class="app">
    <div class="card">
        <h2 style="margin-bottom: 10px;">Access your secure vault</h2>
        <p class="subtitle">Enter your credentials to continue</p>
        
        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" name="login" class="btn">SECURE LOGIN</button>
            
            <div class="footer-link">
                Don't have an account yet? <a href="register.php">Register here</a>
            </div>
        </form>
    </div>
</main>

</body>
</html>