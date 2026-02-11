<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}

$id = $_SESSION['user_id'];

// Fetch user data to get current balance
$query = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$id'");
$user = mysqli_fetch_assoc($query);

if (isset($_POST['btn_transfer'])) {
    $bank = mysqli_real_escape_string($conn, $_POST['bank_provider']);
    $acc_name = mysqli_real_escape_string($conn, $_POST['account_name']);
    $acc_num = mysqli_real_escape_string($conn, $_POST['account_number']);
    $amount = floatval($_POST['amount']);
    
    // 1. Check if user has enough balance
    if ($user['balance'] >= $amount) {
        // 2. Deduct from balance
        $new_balance = $user['balance'] - $amount;
        mysqli_query($conn, "UPDATE users SET balance = '$new_balance' WHERE user_id = '$id'");

        // 3. Create the Detailed Description for History
        $detailed_desc = "Bank Transfer: " . $bank . " (" . $acc_num . " - " . $acc_name . ")";

        // 4. Insert into Transactions Table
        $insert_query = "INSERT INTO transactions (user_id, description, type, amount) 
                         VALUES ('$id', '$detailed_desc', 'transfer', '$amount')";
        
        if (mysqli_query($conn, $insert_query)) {
            echo "<script>alert('Transfer Successful!'); window.location='dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Insufficient balance!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Transfer | MyBank</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Ensuring the new grid layout works without breaking your existing CSS */
        .input-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        @media (max-width: 600px) {
            .input-grid { grid-template-columns: 1fr; }
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
            <h2 style="color: var(--accent); margin-bottom: 5px;">Bank Transfer</h2>
            <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 30px;">Transfer funds to other banks or e-wallets.</p>
            
            <form method="POST">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="color: var(--text-secondary); display: block; margin-bottom: 8px; font-size: 12px; text-transform: uppercase;">Select Provider</label>
                    <select name="bank_provider" required style="width: 100%; padding: 12px; background: #0d1117; border: 1px solid #30363d; color: #ffffff; border-radius: 8px;">
                        <option value="" disabled selected>-- Choose Bank / E-Wallet --</option>
                        <option value="GCash">GCash</option>
                        <option value="Maya">Maya</option>
                        <option value="BDO">BDO Unibank</option>
                        <option value="BPI">BPI</option>
                        <option value="Unionbank">Unionbank</option>
                    </select>
                </div>

                <div class="input-grid">
                    <div class="form-group">
                        <label style="color: var(--text-secondary); display: block; margin-bottom: 8px; font-size: 12px; text-transform: uppercase;">Account Name</label>
                        <input type="text" name="account_name" placeholder="Full Name" required style="width: 100%; padding: 12px; background: #0d1117; border: 1px solid #30363d; color: #ffffff; border-radius: 8px;">
                    </div>
                    <div class="form-group">
                        <label style="color: var(--text-secondary); display: block; margin-bottom: 8px; font-size: 12px; text-transform: uppercase;">Account Number</label>
                        <input type="text" name="account_number" placeholder="09123456789" required style="width: 100%; padding: 12px; background: #0d1117; border: 1px solid #30363d; color: #ffffff; border-radius: 8px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 30px;">
                    <label style="color: var(--text-secondary); display: block; margin-bottom: 8px; font-size: 12px; text-transform: uppercase;">Amount (₱)</label>
                    <input type="number" name="amount" step="0.01" min="1" placeholder="0.00" required style="width: 100%; padding: 12px; background: #0d1117; border: 1px solid #30363d; color: #ffffff; border-radius: 8px;">
                </div>

                <button type="submit" name="btn_transfer" class="btn" style="width: 100%; padding: 15px; background: linear-gradient(135deg, #bc8cf2 0%, #6e40aa 100%); border: none; color: white; font-weight: bold; border-radius: 12px; cursor: pointer;">
                    SECURE TRANSFER
                </button>
            </form>
        </div>
    </div>
</main>

</body>
</html>