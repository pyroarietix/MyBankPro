<?php
session_start();
include 'config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admindashboard.php");
    exit();
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $id");
$user = mysqli_fetch_assoc($query);
if (!$query || mysqli_num_rows($query) === 0) {
    header("Location: admindashboard.php");
    exit();
}

if (isset($_POST['update'])) {

    $newAmount = floatval($_POST['balance']);

    mysqli_query($conn, "
        UPDATE users 
        SET balance = balance + $newAmount 
        WHERE user_id = $id
    ");

    header("Location: admindashboard.php?success=1");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Account | MYBANK.PRO</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="style3.css">
    <script src="script.js" defer></script>
</head>
<body>

<header class="topbar">
    <div class="logo">MYBANK.PRO</div>
    <button type="button" onclick="window.location.href='admindashboard.php'" class="back">Back</button>
</header>
<main class="app">

    <div class="atm-container">
        <div class="atm-screen">
            <center><p style="color: #c9d1d9; margin: 0 0 5px 0; font-size: 30px;">Account Management</p></center>
            <div class="welcome-section" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px;">
                
                <div class="info-box">
                    <p><span>ID</span><br><?php echo $user['user_id']; ?></p>
                    <p><span>Email</span><br><?php echo htmlspecialchars($user['email']); ?></p>
                    <p><span>Fullname</span><br><?php echo htmlspecialchars($user['fullname']); ?></p>
                    <p><span>Phone</span><br><?php echo htmlspecialchars($user['phone']); ?></p>
                </div>

                <div style="flex: 1; margin-left: 20px;">
                    <div class="balance-box" style="display: flex; gap: 20px; justify-content: flex-end;">
                        <div class="balance current">
                            <span>Current Balance</span>
                            <h3 style="color: #2ea043;">₱ <?php echo number_format($user['balance'],2); ?></h3>
                        </div>

                        <div class="balance new">
                            <span>New Balance</span>
                            <form id="updateForm" method="POST">
                                <input type="number" name="balance" step="100" placeholder="₱ 500.00 min." min="500" required>
                            </form>
                        </div>
                    </div>

                    <div class="actions" style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                        
                        <button type="submit" form="updateForm" name="update" class="update">Update</button>

                        <form method="POST" action="delete.php" onsubmit="return confirm('Are you sure you want to delete this user?');" style="margin: 0;">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <button type="submit" class="delete">Delete</button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div> 
</main>

</body>
</html>