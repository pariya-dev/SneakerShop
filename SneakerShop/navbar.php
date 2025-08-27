<?php
include('connect_db.php');

// ตรวจสอบ session ว่าล็อกอินหรือยัง
if (!isset($_SESSION["user_admin"])) {
    header("Location: login.php");
    exit;
}

// ดึงข้อมูล admin
$user_admin = $_SESSION["user_admin"];
$sql = "SELECT * FROM tbl_admin WHERE user_admin = '$user_admin'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name_admin = $row["name_admin"];
    $img_admin = $row["img_admin"];
} else {
    // กรณีไม่เจอข้อมูล
    $name_admin = "ไม่ทราบชื่อ";
    $img_admin = "";
}

// กำหนด path รูปภาพ
$imgPath = !empty($img_admin) ? "uploads/" . $img_admin : "images/default-user.png";
?>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-sm p-3 navbox-shadow" style="background-color: white;">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="#">
            <img src="images/canival-logo.png" alt="logo" width="124">
        </a>

        <!-- Toggler button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav Items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="manage_admin.php">MANAGE ADMIN</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_type.php">MANAGE TYPE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_product.php">MANAGE PRODUCT</a>
                </li>
                <li class="nav-item dropdown ms-2 d-flex align-items-center">
                    <span class="me-3"><?php echo htmlspecialchars($name_admin); ?></span>
                    <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
