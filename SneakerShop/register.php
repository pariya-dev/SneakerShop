<?php
session_start();
include('connect_db.php');

// เมื่อกดปุ่ม Register
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_admin = trim($_POST["user_admin"]);
    $pass_admin = trim($_POST["pass_admin"]);
    $confirm_pass = trim($_POST["confirm_pass"]);

    // ตรวจสอบว่ากรอกครบไหม
    if (empty($user_admin) || empty($pass_admin) || empty($confirm_pass)) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน!');</script>";
    } elseif ($pass_admin !== $confirm_pass) {
        echo "<script>alert('รหัสผ่านไม่ตรงกัน!');</script>";
    } else {
        // ตรวจสอบว่ามี user_admin อยู่แล้วหรือไม่
        $check_sql = "SELECT * FROM tbl_admin WHERE user_admin = '$user_admin'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            echo "<script>alert('ชื่อผู้ใช้นี้ถูกใช้แล้ว!');</script>";
        } else {
            // สมัครสมาชิกใหม่
            $sql = "INSERT INTO tbl_admin (user_admin, pass_admin, name_admin) 
                    VALUES ('$user_admin', MD5('$pass_admin'), '$user_admin')";

            if (mysqli_query($conn, $sql)) {
                echo "<script>
                    alert('สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ');
                    window.location.href='login.php';
                </script>";
            } else {
                echo "<script>alert('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง!');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" type="image/x-icon"
    href="https://www.carnivalbkk.com/media/favicon/stores/4/star-602148_960_720_1_.png">
    <title>Register | CARNIVAL ™</title>
</head>
<body>
    <div class="login-container">
        <img src="images/canival-logo.png" alt="logo" class="d-block mx-auto mb-3" width="150">
        <h2>Register to CARNIVAL</h2>
        <form method="POST" action="register.php">
            <!-- Username -->
            <div class="mb-3">
                <input type="text" class="form-control" name="user_admin" placeholder="Enter Username" required>
            </div>
            <!-- Password -->
            <div class="mb-3">
                <input type="password" class="form-control" name="pass_admin" placeholder="Enter Password" required>
            </div>
            <!-- Confirm Password -->
            <div class="mb-3">
                <input type="password" class="form-control" name="confirm_pass" placeholder="Re-enter Password" required>
            </div>
            <!-- Register Button -->
            <button type="submit" class="btn-login">Register</button>
        </form>
        <hr>
        <div class="footer">
            <p>Already have an account? <a href="login.php">Back to login</a></p>
        </div>
    </div>
</body>
</html>
