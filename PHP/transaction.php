<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$id = $_SESSION['user_id'];

// Fetch current user details
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$id'");
$user = mysqli_fetch_assoc($user_query);

// Fetch ALL transactions for this user
$trans_query = mysqli_query($conn, "SELECT * FROM transactions WHERE user_id='$id' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions | MyBank.Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="topbar">
    <div class="logo">MYBANK.PRO</div>
</header>

<main class="app">
    <div class="atm-container">
            <div class="atm-sidebar">
                <p class="sidebar-label">ATM Menu</p>
                <div class="atm-btn" onclick="location.href='dashboard.php'">
                    <span>🏠</span>
                    <div class="atm-btn-label">Dashboard</div>
                </div>
                <div class="atm-btn" onclick="location.href='sendmoney.php'">
                    <span>📥</span>
                    <div class="atm-btn-label">Send Money</div>
                </div>
                <div class="atm-btn active" onclick="location.href='transaction.php'">
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
            </div>

        <div class="atm-screen">
            <div class="welcome-section" style="margin-bottom: 30px;">
                <h2 style="margin: 0; color: var(--accent); font-size: 24px;">Statement of Account</h2>
                <p style="color: var(--text-secondary); margin: 5px 0 0 0; font-size: 13px;">Viewing all transactions for <strong><?php echo htmlspecialchars($user['fullname']); ?></strong></p>
            </div>

            <div style="background: rgba(255,255,255,0.02); border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05);">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(0,0,0,0.3);">
                            <th style="text-align: left; padding: 20px; color: #6e7681; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Date</th>
                            <th style="text-align: left; padding: 20px; color: #6e7681; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Description</th>
                            <th style="text-align: left; padding: 20px; color: #6e7681; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Type</th>
                            <th style="text-align: right; padding: 20px; color: #6e7681; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(mysqli_num_rows($trans_query) > 0) {
                            while($row = mysqli_fetch_assoc($trans_query)) {
                                // Determine if it's money in or money out
                                $is_income = ($row['type'] == 'receive' || $row['type'] == 'deposit');
                                $color = $is_income ? 'var(--success)' : 'var(--danger)';
                                $prefix = $is_income ? '+' : '-';
                                ?>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td style="padding: 20px; color: #c9d1d9; font-size: 13px;">
                                        <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                    </td>
                                    <td style="padding: 20px; color: #ffffff; font-size: 14px;">
                                        <?php echo htmlspecialchars($row['description']); ?>
                                    </td>
                                    <td style="padding: 20px;">
                                        <span style="font-size: 10px; padding: 4px 10px; border-radius: 4px; background: rgba(255,255,255,0.05); color: #c9d1d9; text-transform: uppercase;">
                                            <?php echo htmlspecialchars($row['type']); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 20px; text-align: right; color: <?php echo $color; ?>; font-weight: bold; font-size: 15px;">
                                        <?php echo $prefix; ?> ₱ <?php echo number_format($row['amount'], 2); ?>
                                    </td>
                                </tr>
                                <?php 
                            }
                        } else {
                            echo "<tr><td colspan='4' style='padding:50px; text-align:center; color:#6e7681;'>No transaction records found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> 
</main>

</body>
</html>