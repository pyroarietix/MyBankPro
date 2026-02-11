<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$id = $_SESSION['user_id'];

// Move Money Logic
if (isset($_POST['move_money'])) {
    $amount = floatval($_POST['amount']);
    $direction = $_POST['direction']; // 'to_savings' or 'to_main'

    // Fetch user current state
    $user_query = mysqli_query($conn, "SELECT balance, savings FROM users WHERE user_id=$id");
    $user = mysqli_fetch_assoc($user_query);

    if ($amount > 0) {
        if ($direction == 'to_savings' && $user['balance'] >= $amount) {
            // 1. Move from Main to Savings
            mysqli_query($conn, "UPDATE users SET balance = balance - $amount, savings = savings + $amount WHERE user_id=$id");
            
            // 2. LOG TRANSACTION
            $desc = "Moved to Savings Vault";
            mysqli_query($conn, "INSERT INTO transactions (user_id, description, type, amount) VALUES ('$id', '$desc', 'savings', '$amount')");
            
            header("Location: dashboard.php?msg=saved");
            exit();
        } elseif ($direction == 'to_main' && $user['savings'] >= $amount) {
            // 1. Move from Savings to Main
            mysqli_query($conn, "UPDATE users SET savings = savings - $amount, balance = balance + $amount WHERE user_id=$id");
            
            // 2. LOG TRANSACTION
            $desc = "Withdrawn from Savings";
            mysqli_query($conn, "INSERT INTO transactions (user_id, description, type, amount) VALUES ('$id', '$desc', 'deposit', '$amount')");
            
            header("Location: dashboard.php?msg=withdrawn");
            exit();
        } else {
            echo "<script>alert('Insufficient funds for this transfer!');</script>";
        }
    }
}

// Fetch current data for display
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT fullname, balance, savings FROM users WHERE user_id=$id"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savings Management | MyBank</title>
    <link rel="stylesheet" href="style.css">
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
            <h2 style="color: var(--accent); margin-bottom: 5px;">Savings Management</h2>
            <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 30px;">Manage your secure savings vault.</p>

            <div style="background: rgba(35, 209, 139, 0.05); padding: 30px; border-radius: 20px; border: 1px solid rgba(35, 209, 139, 0.2); margin-bottom: 30px; width: 100%;">
                <p style="font-size: 12px; color: var(--success); text-transform: uppercase; letter-spacing: 1px; font-weight: bold; margin: 0;">Vault Balance</p>
                <h1 style="color: #ffffff; margin: 10px 0; font-size: 42px;">₱ <?php echo number_format($user['savings'], 2); ?></h1>
                <p style="color: var(--text-muted); font-size: 12px; margin: 0;">Main Balance: ₱ <?php echo number_format($user['balance'], 2); ?></p>
            </div>

            <form method="POST" style="width: 100%;">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="color: var(--text-secondary); display: block; margin-bottom: 8px; font-size: 12px; text-transform: uppercase;">Amount to Move (₱)</label>
                    <input type="number" name="amount" step="0.01" min="1" placeholder="0.00" required style="width: 100%; padding: 12px; background: #0d1117; border: 1px solid #30363d; color: #ffffff; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 30px;">
                    <label style="color: var(--text-secondary); display: block; margin-bottom: 8px; font-size: 12px; text-transform: uppercase;">Transfer Direction</label>
                    <select name="direction" required style="width:100%; height:50px; background:#0d1117; color:white; border-radius:12px; border:1.5px solid #30363d; padding:0 15px;">
                        <option value="to_savings">Move Main Balance → Savings Vault</option>
                        <option value="to_main">Withdraw Savings Vault → Main Balance</option>
                    </select>
                </div>

                <button type="submit" name="move_money" class="btn" style="width: 100%; padding: 15px; background: linear-gradient(135deg, #bc8cf2 0%, #6e40aa 100%); border: none; color: white; font-weight: bold; border-radius: 12px; cursor: pointer;">
                    CONFIRM TRANSFER
                </button>
            </form>
        </div>
    </div>
</main>
</body>
</html>