<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$id'");
$user = mysqli_fetch_assoc($query);
$balance = isset($user['balance']) ? $user['balance'] : 0.00;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | MyBank ATM</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style3.css">
    <script src="script.js" defer></script>
</head>
<body>

<header class="topbar">
    <div class="logo">MYBANK.PRO</div>
    <nav class="nav-links">
        <button type="button" onclick="window.location.href='profile.php'" class="prof">👤 Profile</button>
        |
        <button type="button" class="logout" href="javascript:void(0)" onclick="openLogoutModal()" style="color: var(--danger);">Logout</button>
    </nav>
</header>

<main class="app">
    <div class="atm-container">
        <div class="atm-sidebar">
            <p class="sidebar-label">Menu</p>
            <div class="atm-btn active" onclick="location.href='dashboard.php'">
                <span>🏠</span>
                <div class="atm-btn-label">Dashboard</div>
            </div>
            <div class="atm-btn" onclick="location.href='sendmoney.php'">
                <span>📥</span>
                <div class="atm-btn-label">Send Money</div>
            </div>
            <div class="atm-btn" onclick="location.href='transaction.php'">
                <span>📜</span>
                <div class="atm-btn-label">Transactions</div>
            </div>
            <div class="atm-btn" onclick="location.href='transfer.php'">
                <span>💸</span>
                <div class="atm-btn-label">Bank Transfer</div>
            </div>
            <div class="atm-btn" onclick="location.href='savings.php'">
                <span>💰</span>
                <div class="atm-btn-label">Savings</div>
            </div>
            <div class="atm-btn" onclick="openLogoutModal()">
                <span>🚪</span>
                <div class="atm-btn-label">Logout</div>
            </div>
        </div>

        <div class="atm-screen">
            <div class="welcome-section" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px;">
                <div>
                    <p style="color: #c9d1d9; margin: 0 0 5px 0;">Welcome back,</p>
                    <h2 style="margin: 0; font-size: 28px; color: #ffffff;"><?php echo htmlspecialchars($user['fullname']); ?></h2>
                </div>
                <div class="status-badge" style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--success); background: rgba(35, 209, 139, 0.1); padding: 6px 16px; border-radius: 30px; border: 1px solid rgba(35, 209, 139, 0.2);">
                    <span class="dot" style="height: 8px; width: 8px; background: var(--success); border-radius: 50%; box-shadow: 0 0 10px var(--success);"></span> Secure
                </div>
            </div>

            <div class="balance-card" style="background: rgba(255, 255, 255, 0.03); padding: 40px; border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.05); margin-bottom: 30px;">
                <div class="card-header" style="display: flex; justify-content: space-between; color: #c9d1d9; font-size: 12px; letter-spacing: 1.5px; margin-bottom: 20px;">
                    <span>AVAILABLE BALANCE</span>
                    <button onclick="toggleBalance()" class="eye-btn" id="eyeIcon" style="background:none; border:none; cursor:pointer; color:var(--accent); font-size:20px;">👁️</button>
                </div>
                <div class="atm-amount" id="mainBalance" data-value="₱ <?php echo number_format($balance, 2); ?>" style="font-size: 52px; font-weight: 800; color: #ffffff; letter-spacing: -1px;">
                    ₱ <?php echo number_format($balance, 2); ?>
                </div>
                <p class="account-number" style="margin-top: 25px; font-family: monospace; color: #6e7681; font-size: 14px;">ACCOUNT ID: **** <?php echo substr($user['phone'], -4); ?></p>
            </div>

            <div style="background: rgba(188, 140, 242, 0.05); padding: 20px; border-radius: 16px; border: 1px solid rgba(188, 140, 242, 0.1); text-align: center;">
                <p style="color: var(--accent); margin: 0; font-size: 14px;">Select an option from the menu to start a transaction.</p>
            </div>
        </div>
    </div> 
</main>

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
let isHidden = false;
function toggleBalance() {
    const main = document.getElementById('mainBalance');
    const icon = document.getElementById('eyeIcon');
    const realValue = main.getAttribute('data-value');
    if (!isHidden) {
        main.innerText = '₱ ••••••';
        icon.innerText = '🙈';
        isHidden = true;
    } else {
        main.innerText = realValue;
        icon.innerText = '👁️';
        isHidden = false;
    }
}
function openLogoutModal() { document.getElementById('logoutModal').style.display = 'flex'; }
function closeLogoutModal() { document.getElementById('logoutModal').style.display = 'none'; }
</script>

</body>
</html>