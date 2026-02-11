<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$id = $_SESSION['user_id'];

// Fetch current user data from the database
$query = mysqli_query($conn, "SELECT * FROM users WHERE user_id=$id");
$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | MyBank.Pro</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Specific styling for the read-only display */
        .profile-info-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #30363d;
            color: #ffffff;
            font-size: 16px;
            min-height: 20px;
        }
        .info-label {
            color: var(--text-secondary);
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: block;
        }
    </style>
</head>
<body>

<header class="topbar">
    <div class="logo">MYBANK.PRO</div>
</header>

<main class="app">
    <div class="atm-container">
        <div class="atm-sidebar">
            <p class="sidebar-label" style="color: var(--text-muted); font-size: 11px; padding: 10px 20px; text-transform: uppercase;">Options</p>
            <div class="atm-btn" onclick="location.href='dashboard.php'" style="cursor: pointer;">
                <span>🏠</span>
                <div class="atm-btn-label">Back Home</div>
            </div>
        </div>

        <div class="atm-screen">
            <div class="welcome-section" style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="margin: 0; color: var(--accent); font-size: 24px;">Account Profile</h2>
                    <p style="color: var(--text-secondary); margin: 5px 0 0 0; font-size: 13px;">Personal Information</p>
                </div>
                
                <button onclick="location.href='update.php'" class="btn" style="width: auto; padding: 10px 25px; background: linear-gradient(135deg, #bc8cf2 0%, #6e40aa 100%);">
                    ✏️ Edit Profile
                </button>
            </div>

            <?php if(isset($_GET['success'])): ?>
                <div style="background: rgba(35, 209, 139, 0.1); color: var(--success); padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(35, 209, 139, 0.2); text-align: center; font-size: 14px;">
                    ✅ Profile successfully updated!
                </div>
            <?php endif; ?>

            <div class="profile-details" style="display: grid; gap: 20px;">
                
                <div class="info-group">
                    <span class="info-label">Full Name</span>
                    <div class="profile-info-box"><?php echo htmlspecialchars($user['fullname']); ?></div>
                </div>

                <div class="info-group">
                    <span class="info-label">Email Address</span>
                    <div class="profile-info-box"><?php echo htmlspecialchars($user['email']); ?></div>
                </div>

                <div class="info-group">
                    <span class="info-label">Mobile Number</span>
                    <div class="profile-info-box"><?php echo htmlspecialchars($user['phone']); ?></div>
                </div>

                <div class="info-group">
                    <span class="info-label">Password</span>
                    <div class="profile-info-box"><?php echo htmlspecialchars($user['password']); ?></div>
                </div>

                <div class="info-group">
                    <span class="info-label">Account ID</span>
                    <div class="profile-info-box" style="color: var(--text-secondary); border-style: dashed;">
                        #<?php echo str_pad($user['user_id'], 6, '0', STR_PAD_LEFT); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

</body>
</html>