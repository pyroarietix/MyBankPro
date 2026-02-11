<?php
$conn = mysqli_connect("localhost", "root", "", "db_onlinebank");


if (!$conn) {
die("Connection failed: " . mysqli_connect_error());
}
?>