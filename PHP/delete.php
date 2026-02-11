<?php
session_start();
include 'config.php';

if (!isset($_POST['user_id']) || !is_numeric($_POST['user_id'])) {
    header("Location: admindashboard.php");
    exit();
}

$user_id = intval($_POST['user_id']);

// OPTIONAL: prevent admin from deleting themselves
if (isset($_SESSION['userid']) && $_SESSION['userid'] == $user_id) {
    header("Location: admindashboard.php?error=self_delete");
    exit();
}

mysqli_query($conn, "DELETE FROM users WHERE user_id = $user_id");

header("Location: admindashboard.php?deleted=1");
exit();
