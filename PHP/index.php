<?php
session_start();
include 'config.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Front Page | MyBank.Pro</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="style3.css">
    <script src="script.js" defer></script>
</head>
<body>

<header class="topbar">
    <div class="logo">MYBANK.PRO</div>
    <nav class="nav-links">
        <button type="button" onclick="window.location.href='login.php'" class="back">Login</button>
        <button type="button" onclick="window.location.href='adminlogin.php'" class="logout">Admin</button>
    </nav>
</header>

<main class="app">
    <div class="atm_screen">
        <center><div class="brand">MYBANK.PRO</div>
        <h2 style="margin-bottom: 10px;">"The Bank where your money is secured!"</h2>
        <p class="subtitle">Banking with MyBank.Pro is much secured -- <br> Join millions of users who pick our bank</p>        
        <br>
        <form method="POST">
            <button type="button" name="login" class="logbtn" onclick="userlogin()">LOGIN</button>
        </form>
        </center>
    </div>
</main>

</body>
</html>