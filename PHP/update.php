<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
$id = $_SESSION['user_id'];

// Fetch current data to pre-fill the inputs
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE user_id=$id"));

if (isset($_POST['update'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Update all fields
    mysqli_query($conn, "UPDATE users SET fullname='$fullname', email='$email', phone='$phone', password='$password' WHERE user_id=$id");
    
    // Redirect back to profile to see changes
    header("Location: profile.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile | MyBank</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="topbar">
    <div class="logo">MYBANK.PRO</div>
</header>

<main class="app">
    <div class="atm-container">
        <div class="atm-sidebar">
            <div class="atm-btn" onclick="location.href='profile.php'">
                <span>⬅️</span>
                <div class="atm-btn-label">Cancel</div>
            </div>
        </div>

        <div class="atm-screen">
            <h2>Edit Your Information</h2>
            <p style="color: var(--text-secondary); margin-bottom: 20px;">Update your details below and press save.</p>
            
            <form method="POST">
                <div class="form-group">
                    <label>New Full Name</label>
                    <input type="text" name="fullname" value="<?php echo $user['fullname']; ?>" required>
                </div>

                <div class="form-group">
                    <label>New Email Address</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                </div>

                <div class="form-group">
                    <label>New Phone Number</label>
                    <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required>
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <input type="text" name="password" value="<?php echo $user['password']; ?>" required>
                </div>

                <button type="submit" name="update" class="btn">SAVE CHANGES</button>
            </form>
        </div>
    </div>
</main>

</body>
</html>