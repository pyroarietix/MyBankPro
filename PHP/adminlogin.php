<?php
session_start();
include 'config.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);

    $query = "SELECT * FROM admins WHERE username='$username' AND pass='$pass' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);
        $_SESSION['admin_id'] = $admin['admin_id'];
        header("Location: admindashboard.php");
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
    <title>Admin Login | MyBank.Pro</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style3.css">
</head>
<body>

<header class="topbar">
    <div class="logo">MYBANK.PRO</div>
    <nav class="nav-links">
        <button type="button" onclick="window.location.href='index.php'" class="back">Back</button>
    </nav>
</header>

<main class="app">
    <div class="card">
        <h2 style="margin-bottom: 10px;">Access your secure vault</h2>
        <p class="subtitle">Enter your credentials to continue</p>
        
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter your username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="pass" placeholder="••••••••" required>
            </div>

            <button type="submit" name="login" class="btn">SECURE LOGIN</button>
           	
        </form>
    </div>
</main>

</body>
</html>