<?php
session_start();
session_unset();   // ล้าง session ทั้งหมด
session_destroy(); // ทำลาย session

header("Location: login.php");
exit();
?>
