<?php
session_start();
include 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
$id = $_SESSION['user_id'];

// ... (Your existing PHP logic for transfer stays the same) ...

if (isset($_POST['transfer'])) {
    $amount = floatval($_POST['amount']);
    $receiver_email = mysqli_real_escape_string($conn, $_POST['receiver_email']);
    $receiver_name = mysqli_real_escape_string($conn, $_POST['receiver_name']);
    $receiver_phone = mysqli_real_escape_string($conn, $_POST['receiver_phone']);

    $user_query = mysqli_query($conn, "SELECT balance FROM users WHERE user_id=$id");
    $user_data = mysqli_fetch_assoc($user_query);
    
    if ($amount > 0 && $user_data['balance'] >= $amount) {
        $receiver_query = mysqli_query($conn, "SELECT user_id FROM users WHERE email='$receiver_email' AND fullname='$receiver_name' AND phone='$receiver_phone'");
        if (mysqli_num_rows($receiver_query) > 0) {
            $receiver = mysqli_fetch_assoc($receiver_query);
            $receiver_id = $receiver['user_id'];
            if ($receiver_id == $id) {
                echo "<script>alert('You cannot transfer to yourself!');</script>";
            } else {
                mysqli_begin_transaction($conn);
                try {
                    mysqli_query($conn, "UPDATE users SET balance = balance - $amount WHERE user_id=$id");
                    mysqli_query($conn, "UPDATE users SET balance = balance + $amount WHERE user_id=$receiver_id");
                    // Inside sendmoney.php after the UPDATE queries
                    mysqli_query($conn, "INSERT INTO transactions (user_id, description, type, amount) 
                    VALUES ('$id', 'Sent to " . $receiver_name . "', 'transfer', '$amount')");

                    mysqli_query($conn, "INSERT INTO transactions (user_id, description, type, amount) 
                    VALUES ('$receiver_id', 'Received from " . $user['fullname'] . "', 'receive', '$amount')");
                    mysqli_commit($conn);
                    header("Location: dashboard.php?success=transfer");
                    exit();
                } catch (Exception $e) {
                    mysqli_rollback($conn);
                    echo "<script>alert('Transaction failed.');</script>";
                }
            }
        } else {
            echo "<script>alert('Receiver details do not match.');</script>";
        }
    } else {
        echo "<script>alert('Insufficient Balance!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Money | MyBank.Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="topbar">
    <div class="logo">MYBANK.PRO</div>
</header>

<main class="app">
    <div class="atm-container">
        <div class="atm-sidebar">
            <p class="sidebar-label">OPTIONS</p>
            <div class="atm-btn" onclick="location.href='dashboard.php'">
                <span>🏠</span>
                <div class="atm-btn-label">Back Home</div>
            </div>
        </div>

        <div class="atm-screen">
            <div class="welcome-section" style="margin-bottom: 30px; align-items: center;">
                <div>
                    <h2 style="margin: 0; color: var(--accent); font-size: 24px;">Send Money</h2>
                    <p style="color: var(--text-secondary); margin: 5px 0 0 0; font-size: 13px;">Secure Peer-to-Peer Transfer</p>
                </div>
                <div class="status-badge">
                    <span class="dot"></span> Verified
                </div>
            </div>

            <form method="POST">
                <div class="input-grid">
                    <div class="form-group">
                        <label>Receiver Email</label>
                        <input type="email" name="receiver_email" placeholder="user@mybank.pro" required>
                    </div>

                    <div class="form-group">
                        <label>Receiver Phone</label>
                        <input type="text" name="receiver_phone" placeholder="09123456789" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Receiver Full Name</label>
                    <input type="text" name="receiver_name" placeholder="Enter full name" required>
                </div>

                <div class="form-group">
                    <label>Amount (₱)</label>
                    <input type="number" name="amount" step="0.01" min="0.01" placeholder="0.00" required>
                </div>

                <button type="submit" name="transfer" class="btn">
                    INITIATE SECURE TRANSFER
                </button>
            </form>
        </div>
    </div>
</main>

</body>
</html>