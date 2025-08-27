<?php
session_start();
include("connect_db.php"); // เชื่อมต่อฐานข้อมูล
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>จัดการข้อมูลผู้ดูแลระบบ</title>
    <link rel="icon" href="https://www.carnivalbkk.com/media/favicon/stores/4/star-602148_960_720_1_.png" type="image/x-icon" />

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
<?php include("navbar.php"); ?>

<div class="container mt-4">
    <h1 class="h3 mb-4">จัดการข้อมูลผู้ดูแลระบบ</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ยูสเซอร์ผู้ใช้ระบบ</th>
                <th>ชื่อแอดมิน</th>
                <th>รูปโปรไฟล์</th>
                <th class="text-center">แก้ไข</th>
                <th class="text-center">ลบ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM tbl_admin";
            $result = mysqli_query($conn, $sql);
            $adminArray = [];

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $adminArray[$row['user_admin']] = $row;
                    $imgPath = !empty($row['img_admin']) ? "uploads/" . $row['img_admin'] : "default.png";

                    echo "
                        <tr>
                            <td>{$row['user_admin']}</td>
                            <td>{$row['name_admin']}</td>
                            <td><img src='{$imgPath}' alt='Profile' width='50'></td>
                            <td class='text-center'>
                                <button class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#editModal' onclick='edit(\"{$row['user_admin']}\")'>แก้ไข</button>
                            </td>
                            <td class='text-center'>
                                <button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#delModal' onclick='del(\"{$row['user_admin']}\")'>ลบ</button>
                            </td>
                        </tr>
                    ";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>ไม่มีข้อมูล</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="text-center mt-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">เพิ่มรายการใหม่</button>
    </div>
</div>

<!-- Modal: Add -->
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มยูสเซอร์ผู้ใช้ระบบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>ยูสเซอร์ผู้ใช้:</label>
                    <input type="text" name="user_admin" class="form-control" required />
                    <label>รหัสผ่านผู้ใช้:</label>
                    <input type="password" name="pass_admin" class="form-control" required />
                    <label>ชื่อผู้ใช้:</label>
                    <input type="text" name="name_admin" class="form-control" required />
                    <label>รูปโปรไฟล์:</label>
                    <input type="file" name="img_admin" class="form-control" accept="image/*" />
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_admin" class="btn btn-primary">บันทึก</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">แก้ไขข้อมูลผู้ดูแลระบบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_user_admin" name="edit_user_admin" class="form-control" readonly />
                    <label>รหัสผ่านใหม่:</label>
                    <input type="password" name="edit_pass_admin" class="form-control" placeholder="เว้นว่างถ้าไม่เปลี่ยนแปลง" />
                    <label>ชื่อผู้ใช้:</label>
                    <input type="text" id="edit_name_admin" name="edit_name_admin" class="form-control" required />
                    <label>รูปโปรไฟล์ใหม่:</label>
                    <input type="file" name="edit_img_admin" class="form-control" accept="image/*" />
                </div>
                <div class="modal-footer">
                    <button type="submit" name="edit_admin" class="btn btn-primary">บันทึก</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Delete -->
<div class="modal fade" id="delModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">ลบผู้ดูแลระบบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>คุณต้องการลบผู้ใช้ระบบนี้ใช่หรือไม่?</label>
                    <input type="hidden" id="del_user_admin" name="del_user_admin" class="form-control" readonly />
                </div>
                <div class="modal-footer">
                    <button type="submit" name="delete_admin" class="btn btn-danger">ลบ</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const adminData = <?php echo json_encode($adminArray); ?>;
    function edit(user_admin) {
        document.getElementById('edit_user_admin').value = user_admin;
        document.getElementById('edit_name_admin').value = adminData[user_admin]['name_admin'];
    }
    function del(user_admin) {
        document.getElementById('del_user_admin').value = user_admin;
    }
</script>

<?php
// เพิ่ม admin
if (isset($_POST['add_admin'])) {
    $user_admin = mysqli_real_escape_string($conn, $_POST['user_admin']);
    $pass_admin = md5($_POST['pass_admin']); // ใช้ md5 แทน password_hash
    $name_admin = mysqli_real_escape_string($conn, $_POST['name_admin']);

    // อัพโหลดรูปภาพถ้ามี
    $img_admin = NULL;
    if (isset($_FILES['img_admin']) && $_FILES['img_admin']['error'] == 0) {
        $upload_dir = 'uploads/';
        $img_admin = time() . "_" . basename($_FILES['img_admin']['name']);
        move_uploaded_file($_FILES['img_admin']['tmp_name'], $upload_dir . $img_admin);
    }

    $sql_insert = "INSERT INTO tbl_admin (user_admin, pass_admin, name_admin, img_admin) VALUES ('$user_admin', '$pass_admin', '$name_admin', '$img_admin')";
    if (mysqli_query($conn, $sql_insert)) {
        echo "<script>Swal.fire('สำเร็จ', 'เพิ่มผู้ดูแลระบบเรียบร้อย', 'success').then(() => { window.location = 'manage_admin.php'; });</script>";
    } else {
        echo "<script>Swal.fire('ผิดพลาด', 'ไม่สามารถเพิ่มผู้ดูแลระบบได้', 'error');</script>";
    }
}

// แก้ไข admin
if (isset($_POST['edit_admin'])) {
    $user_admin = mysqli_real_escape_string($conn, $_POST['edit_user_admin']);
    $name_admin = mysqli_real_escape_string($conn, $_POST['edit_name_admin']);
    $pass_admin_sql = "";
    if (!empty($_POST['edit_pass_admin'])) {
        $pass_admin = md5($_POST['edit_pass_admin']); // ใช้ md5 แทน password_hash
        $pass_admin_sql = ", pass_admin='$pass_admin'";
    }

    // อัพโหลดรูปใหม่ถ้ามี
    $img_admin_sql = "";
    if (isset($_FILES['edit_img_admin']) && $_FILES['edit_img_admin']['error'] == 0) {
        $upload_dir = 'uploads/';
        $img_admin = time() . "_" . basename($_FILES['edit_img_admin']['name']);
        move_uploaded_file($_FILES['edit_img_admin']['tmp_name'], $upload_dir . $img_admin);
        $img_admin_sql = ", img_admin='$img_admin'";
    }

    $sql_update = "UPDATE tbl_admin SET name_admin='$name_admin' $pass_admin_sql $img_admin_sql WHERE user_admin='$user_admin'";

    if (mysqli_query($conn, $sql_update)) {
        echo "<script>Swal.fire('สำเร็จ', 'แก้ไขข้อมูลเรียบร้อย', 'success').then(() => { window.location = 'manage_admin.php'; });</script>";
    } else {
        echo "<script>Swal.fire('ผิดพลาด', 'ไม่สามารถแก้ไขข้อมูลได้', 'error');</script>";
    }
}

// ลบ admin
if (isset($_POST['delete_admin'])) {
    $user_admin = mysqli_real_escape_string($conn, $_POST['del_user_admin']);
    $sql_delete = "DELETE FROM tbl_admin WHERE user_admin='$user_admin'";
    if (mysqli_query($conn, $sql_delete)) {
        echo "<script>Swal.fire('สำเร็จ', 'ลบผู้ดูแลระบบเรียบร้อย', 'success').then(() => { window.location = 'manage_admin.php'; });</script>";
    } else {
        echo "<script>Swal.fire('ผิดพลาด', 'ไม่สามารถลบผู้ดูแลระบบได้', 'error');</script>";
    }
}
?>

<?php include("footer.php"); ?>

<?php mysqli_close($conn); ?>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
