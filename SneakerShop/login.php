<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/login.css">
    <title>CARNIVAL ™</title>
    <link rel="icon" type="image/x-icon"
        href="https://www.carnivalbkk.com/media/favicon/stores/4/star-602148_960_720_1_.png">
</head>

<body>

    <div class="login-container">
        <img src="images/canival-logo.png" alt="logo" class="d-block mx-auto mb-3" width="150">
        <h2>Login to CARNIVAL</h2>
        <form method="POST" action="#">
            <!-- Username -->
            <div class="mb-3">
                <input type="text" class="form-control" id="user-admin" name="user_admin" placeholder="Username" required>
            </div>
            <!-- Password -->
            <div class="mb-3">
                <input type="password" class="form-control" id="pass-admin" name="pass_admin" placeholder="Password"
                    required>
            </div>
            <!-- Login Button -->
            <button type="submit" values="login" class="btn-login">Login</button>
        </form>


            <!-- Forget Password -->
            <div class="footer">
                <p><a href="#">Forgotten password?</a></p>
            </div>
            <hr>
            <!-- Register -->
            <div class="footer">
                <p>Don't have an account? <a href="register.php">Sign up</a></p>
            </div>
    </div>


<?php
    session_start();
    include('connect_db.php');

    if (isset($_POST["user_admin"])) {
        $user_admin = $_POST["user_admin"];
        $pass_admin = $_POST["pass_admin"];

        $sql = "SELECT * FROM `tbl_admin` WHERE user_admin = '$user_admin' AND pass_admin = MD5('$pass_admin')";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $name_admin = $row['name_admin'];

            // Set session
            $_SESSION["user_admin"] = $user_admin;
            $_SESSION["name_admin"] = $name_admin;

            header("Location: manage_admin.php");
            exit();
        } else {
            echo '<script>
                alert("ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!");
            </script>';
        }
    }
?>



    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>