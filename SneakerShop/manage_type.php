<?php
session_start();
include("connect_db.php"); // เชื่อมต่อฐานข้อมูล
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARNIVAL ™</title>
    <link rel="icon" href="https://www.carnivalbkk.com/media/favicon/stores/4/star-602148_960_720_1_.png" type="image/x-icon">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<?php include("navbar.php"); ?>

<!-- Table -->
    <div class="container mt-4">
        <h1 class="h3 mb-4">จัดการข้อมูลประเภทสินค้า</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>รหัสประเภทสินค้า</th>
                    <th>ชื่อประเภทสินค้า</th>
                    <th class="text-center">แก้ไข</th>
                    <th class="text-center">ลบ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tbl_type";
                $result = mysqli_query($conn, $sql);
                $typeArray = [];

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $typeArray[$row['id_type']] = $row;
                        echo "
                            <tr>
                                <td>{$row['id_type']}</td>
                                <td>{$row['name_type']}</td>
                                <td class='text-center'>
                                    <button class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#editModal' onclick='edit({$row['id_type']})'>แก้ไข</button>
                                </td>
                                <td class='text-center'>
                                    <button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#delModal' onclick='del({$row['id_type']})'>ลบ</button>
                                </td>
                            </tr>
                        ";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>ไม่มีข้อมูล</td></tr>";
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
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">เพิ่มประเภทสินค้า</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>ชื่อประเภทสินค้า:</label>
                        <input type="text" name="name_type" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">บันทึก</button>
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
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">แก้ไขประเภทสินค้า</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="edit_id_type" name="edit_id_type" class="form-control mb-2" readonly>
                        <input type="text" id="edit_name_type" name="edit_name_type" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">บันทึก</button>
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
                        <h5 class="modal-title">ลบประเภทสินค้า</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>คุณต้องการลบรายการนี้ใช่หรือไม่</label>
                        <input type="text" id="del_id_type" name="del_id_type" class="form-control mt-2" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">ลบ</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    const typeData = <?php echo json_encode($typeArray); ?>;
    function edit(id) {
        document.getElementById('edit_id_type').value = id;
        document.getElementById('edit_name_type').value = typeData[id]['name_type'];
    }
    function del(id) {
        document.getElementById('del_id_type').value = id;
    }
</script>

<?php

    // PHP+SQL เพิ่ม
    if(isset($_POST["name_type"]))
    {
        $name_type = $_POST["name_type"];
        $sql_insert = "INSERT INTO tbl_type Values('','$name_type')";
        if(mysqli_query($conn, $sql_insert)){
            echo "<script > Swal.fire({ title: 'แจ้งการบันทึกข้อมูล', text: 'ระบบบันทึกข้อมูลเรียบร้อยแล้ว', icon: 'success' }); </script> ";
            echo "<script langquage='javascript'> window.location='manage_type.php'; </script> ";
        } else {
        echo "<script > Swal.fire({ title: 'แจ้งการบันทึกข้อมูล', text: 'ระบบไม่สามารถบันทึกข้อมูลได้', icon: 'warning' });";
            }
    }
    // PHP+SQL แก้ไข
    if(isset($_POST["edit_id_type"]))
    {
        $edit_id_type = $_POST["edit_id_type"];
        $edit_name_type = $_POST["edit_name_type"];
        $sql_edit = "UPDATE tbl_type SET name_type='$edit_name_type' WHERE id_type='$edit_id_type' ";
        if(mysqli_query($conn, $sql_edit)){
            echo "<script>Swal.fire({title: 'แจ้งการแก้ไขข้อมูล', text: 'การแก้ไขข้อมูลเรียบร้อยแล้ว', icon: 'success' }); </script>";
            echo "<script langquage='javascript'> window.location='manage_type.php'; </script> ";
        } else {
            echo "<script > Swal.fire({ title: 'แจ้งการแก้ไขข้อมูล', text: 'ระบบไม่สามารถแก้ไขข้อมูลได้', icon: 'warning' }); </script>";
            }
    }
    // PHP+SQL ลบ
    if (isset($_POST["del_id_type"])) {
            $del_id_type = $_POST["del_id_type"];
            $sql_del = "DELETE FROM `tbl_type` WHERE id_type = '$del_id_type'";
            if (mysqli_query($conn, $sql_del)) {
                echo "<script>Swal.fire({title:'แจ้งการลบข้อมูล',text:'การลบข้อมูลเรียบร้อยแล้ว',icon:'success'}).then(() => {window.location='manage_type.php';});</script>";
            } else {
                echo "<script>Swal.fire({title:'แจ้งการลบข้อมูล',text:'ระบบไม่สามารถลบข้อมูลได้',icon:'warning'});</script>";
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
