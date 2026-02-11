<?php
include 'config.php';

if (isset($_POST['register'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "INSERT INTO users (fullname, email, phone, password, balance, savings) 
            VALUES ('$fullname', '$email', '$phone', '$password', 0.00, 0.00)";
    
    if(mysqli_query($conn, $sql)) {
        header("Location: login.php?registered=success");
        exit();
    } else {
        echo "<script>alert('Registration failed. Email or Phone might already exist.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | MyBank.Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="topbar">
    <div class="logo">MYBANK.PRO</div>
</header>

<main class="app">
    <div class="card">
        <h2>Open Account</h2>
        <p class="subtitle">Join our secure Philippine network</p>
        
        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" placeholder="Juan Dela Cruz" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="juan@example.com" required>
            </div>

            <div class="form-group">
                <label>Mobile Number</label>
                <input type="tel" name="phone" 
                       placeholder="09123456789" 
                       pattern="09[0-9]{9}" 
                       title="Enter a valid PH mobile number (11 digits starting with 09)" 
                       required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" name="register" class="btn">CREATE ACCOUNT</button>
            
            <div class="footer-link">
                Already a member? <a href="login.php">Log in here</a>
            </div>
        </form>
    </div>
</main>

</body>
</html>