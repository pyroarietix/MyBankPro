<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$id = $_SESSION['admin_id'];
$admin_query = mysqli_query($conn, "SELECT username FROM admins WHERE admin_id='$id'");
$admin = mysqli_fetch_assoc($admin_query);
$users_query = mysqli_query($conn, "SELECT user_id, fullname, email, phone, balance, savings FROM users ORDER BY user_id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | MyBank ATM</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style3.css">
    <script src="script.js" defer></script>
</head>
<body>

<header class="topbar">
    <div class="logo">MYBANK.PRO</div>
    <nav class="nav-links">
        <button type="button" class="logout" href="javascript:void(0)" onclick="openLogoutModal()" style="color: var(--danger);">Logout</button>
    </nav>
</header>

<main class="app">
    <div class="atm-container">
        
        <div class="atm-screen">
            <div class="welcome-section" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px;">
                <div>
                    <p style="color: #c9d1d9; margin: 0 0 5px 0; font-size: 30px;">Welcome back,</p>
                    <h2 style="margin: 0; font-size: 28px; color: #ffffff;"><?php echo htmlspecialchars($admin['username']); ?></h2>
                </div>
                <div class="status-badge" style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--success); background: rgba(35, 209, 139, 0.1); padding: 6px 16px; border-radius: 30px; border: 1px solid rgba(35, 209, 139, 0.2);">
                    <span class="dot" style="height: 8px; width: 8px; background: var(--success); border-radius: 50%; box-shadow: 0 0 10px var(--success);"></span> Secure
                </div>
            </div>

            <!-- ACCOUNT LIST -->
            <div style="margin-top: 40px;">
                <h3 style="color: var(--accent); font-size: 14px; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 20px;">
                    User Accounts
                </h3>

                <div style="background: rgba(255,255,255,0.02); border-radius: 16px; overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: rgba(0,0,0,0.2);">
                                <th style="padding: 15px; text-align: left;">ID</th>
                                <th style="padding: 15px; text-align: left;">Full Name</th>
                                <th style="padding: 15px; text-align: left;">Email</th>
                                <th style="padding: 15px; text-align: left;">Phone</th>
                                <th style="padding: 15px; text-align: right;">Balance</th>
                                <th style="padding: 15px; text-align: right;">Savings</th>
                                <th style="padding: 15px; text-align: right;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($users_query) > 0) {
                                while ($row = mysqli_fetch_assoc($users_query)) {
                            ?>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td style="padding: 15px;"><?php echo $row['user_id']; ?></td>
                                    <td style="padding: 15px;"><?php echo htmlspecialchars($row['fullname']); ?></td>
                                    <td style="padding: 15px;"><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td style="padding: 15px;"><?php echo htmlspecialchars($row['phone']); ?></td>
                                    <td style="padding: 15px; text-align: right; color: var(--success);">
                                        ₱ <?php echo number_format($row['balance'], 2); ?>
                                    </td>
                                    <td style="padding: 15px; text-align: right; color: var(--accent);">
                                        ₱ <?php echo number_format($row['savings'], 2); ?>
                                    </td>
                                    <td style="padding: 15px;"><a href="edit.php?id=<?php echo $row['user_id']; ?>">Edit</a></td>
                                </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='6' style='padding:30px; text-align:center;'>No users found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> 
</main>

<!-- LOGOUT -->
<div id="logoutModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); justify-content: center; align-items: center; z-index: 9999;">
    <div class="modal-card" style="background: var(--card-bg); padding: 40px; border-radius: 24px; border: 1px solid rgba(188, 140, 242, 0.2); text-align: center; width: 90%; max-width: 400px;">
        <div style="font-size: 40px; margin-bottom: 15px;">🔒</div>
        <h3 style="margin-top: 0; color: #ffffff;">Secure Logout</h3>
        <p style="color: #c9d1d9; margin-bottom: 25px;">Are you sure you want to end your secure session?</p>
        <div class="modal-actions" style="display: flex; gap: 10px; justify-content: center;">
            <button onclick="closeLogoutModal()" style="background: #30363d; border: none; color: #ffffff; padding: 12px 25px; border-radius: 12px; cursor: pointer; font-weight: 600;">Cancel</button>
            <button onclick="location.href='logout.php'" style="background: var(--danger); border: none; color: #ffffff; padding: 12px 25px; border-radius: 12px; cursor: pointer; font-weight: 600;">Logout</button>
        </div>
    </div>
</div>

<script>
function openLogoutModal() { document.getElementById('logoutModal').style.display = 'flex'; }
function closeLogoutModal() { document.getElementById('logoutModal').style.display = 'none'; }
</script>

</body>
</html>