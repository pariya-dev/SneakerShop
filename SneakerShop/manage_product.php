<?php
session_start();
include("connect_db.php"); // เชื่อมต่อฐานข้อมูล

// ดึงประเภทสินค้า สำหรับ select option (ต้องดึงก่อนแสดง form)
$typeResult = mysqli_query($conn, "SELECT * FROM tbl_type ORDER BY name_type ASC");
$types = [];
while($typeRow = mysqli_fetch_assoc($typeResult)) {
    $types[$typeRow['id_type']] = $typeRow['name_type']; // สมมติว่ามีคอลัมน์ name_type
}
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

<!-- CARD -->
<div class="container-fluid">
    <div class="container mt-4">
        <h1 class="h3 mb-4">จัดการข้อมูลสินค้า</h1>
        <div class="row">

            <?php
            $sql = "SELECT * FROM tbl_product";  // สมมุติชื่อ table ว่า tbl_product
            $result = mysqli_query($conn, $sql);
            $productArray = [];

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $productArray[$row['id_product']] = $row;
                    $imgPath = !empty($row['img_product']) ? "uploads/" . $row['img_product'] : "default.png";

                    echo '
                    <div class="col-6 col-md-3 mb-4">
                        <div class="card">
                            <div>
                                <img src="' . $imgPath . '" class="card-img-top" alt="' . htmlspecialchars($row['name_product']) . '">
                            </div>
                            <div class="card-body">
                                <p class="product-name text-center">' . htmlspecialchars($row['name_product']) . '</p>
                                <p class="text-center">฿' . number_format($row['price'], 2) . '</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" onclick="edit(' . $row['id_product'] . ')">แก้ไข</button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delModal" onclick="del(' . $row['id_product'] . ')">ลบ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
                }
            } else {
                echo '<div class="col-12 text-center">ไม่มีข้อมูลสินค้า</div>';
            }
            ?>

        </div>
    </div>
</div>

<!-- ปุ่มเพิ่มสินค้า -->
<div class="text-center mt-3">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">เพิ่มรายการใหม่</button>
</div>

<!-- Modal: Add Product -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">เพิ่มสินค้า</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label>ชื่อสินค้า:</label>
          <input type="text" name="name_product" class="form-control" required />
          <label>รายละเอียด:</label>
          <textarea name="detail_product" class="form-control" required></textarea>
          <label>ราคา:</label>
          <input type="number" step="0.01" name="price" class="form-control" required />
          <label>จำนวนสินค้า:</label>
          <input type="number" name="value" class="form-control" required />
          <label>ประเภทสินค้า:</label>
          <select name="id_type" class="form-control" required>
            <option value="">-- เลือกประเภทสินค้า --</option>
            <?php foreach($types as $id => $name): ?>
              <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
            <?php endforeach; ?>
          </select>
          <label>รูปสินค้า:</label>
          <input type="file" name="img_product" class="form-control" accept="image/*" />
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_product" class="btn btn-primary">บันทึก</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Edit Product -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">แก้ไขสินค้า</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit_id_product" name="edit_id_product" />
          <label>ชื่อสินค้า:</label>
          <input type="text" id="edit_name_product" name="edit_name_product" class="form-control" required />
          <label>รายละเอียด:</label>
          <textarea id="edit_detail_product" name="edit_detail_product" class="form-control" required></textarea>
          <label>ราคา:</label>
          <input type="number" step="0.01" id="edit_price" name="edit_price" class="form-control" required />
          <label>จำนวนสินค้า:</label>
          <input type="number" id="edit_value" name="edit_value" class="form-control" required />
          <label>ประเภทสินค้า:</label>
          <select id="edit_id_type" name="edit_id_type" class="form-control" required>
            <option value="">-- เลือกประเภทสินค้า --</option>
            <?php foreach($types as $id => $name): ?>
              <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
            <?php endforeach; ?>
          </select>
          <label>รูปสินค้าใหม่ (ถ้าต้องการเปลี่ยน):</label>
          <input type="file" name="edit_img_product" class="form-control" accept="image/*" />
        </div>
        <div class="modal-footer">
          <button type="submit" name="edit_product" class="btn btn-primary">บันทึก</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Delete Product -->
<div class="modal fade" id="delModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title">ลบสินค้า</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>คุณต้องการลบสินค้านี้ใช่หรือไม่?</p>
          <input type="hidden" id="del_id_product" name="del_id_product" />
        </div>
        <div class="modal-footer">
          <button type="submit" name="delete_product" class="btn btn-danger">ลบ</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    const productData = <?php echo json_encode($productArray); ?>;

    function edit(id_product) {
        document.getElementById('edit_id_product').value = id_product;
        document.getElementById('edit_name_product').value = productData[id_product]['name_product'];
        document.getElementById('edit_price').value = productData[id_product]['price'];
        document.getElementById('edit_detail_product').value = productData[id_product]['detail_product'];
        document.getElementById('edit_value').value = productData[id_product]['value'];
        document.getElementById('edit_id_type').value = productData[id_product]['id_type']; // ใส่ค่า select ประเภท
    }

    function del(id_product) {
        document.getElementById('del_id_product').value = id_product;
    }
</script>

<?php
// เพิ่มสินค้า
if (isset($_POST['add_product'])) {
    $name_product = mysqli_real_escape_string($conn, $_POST['name_product']);
    $detail_product = mysqli_real_escape_string($conn, $_POST['detail_product']);
    $price = floatval($_POST['price']);
    $value = intval($_POST['value']);
    $id_type = intval($_POST['id_type']);

    $img_product = NULL;
    if (isset($_FILES['img_product']) && $_FILES['img_product']['error'] == 0) {
        $upload_dir = 'uploads/';
        $img_product = time() . "_" . basename($_FILES['img_product']['name']);
        move_uploaded_file($_FILES['img_product']['tmp_name'], $upload_dir . $img_product);
    }

    $sql_insert = "INSERT INTO tbl_product (name_product, detail_product, price, value, img_product, id_type)
                   VALUES ('$name_product', '$detail_product', $price, $value, '$img_product', $id_type)";
    if (mysqli_query($conn, $sql_insert)) {
        echo "<script>Swal.fire('สำเร็จ', 'เพิ่มสินค้าสำเร็จ', 'success').then(() => { window.location = 'manage_product.php'; });</script>";
    } else {
        echo "<script>Swal.fire('ผิดพลาด', 'ไม่สามารถเพิ่มสินค้าได้', 'error');</script>";
    }
}

// แก้ไขสินค้า
if (isset($_POST['edit_product'])) {
    $id_product = intval($_POST['edit_id_product']);
    $name_product = mysqli_real_escape_string($conn, $_POST['edit_name_product']);
    $detail_product = mysqli_real_escape_string($conn, $_POST['edit_detail_product']);
    $price = floatval($_POST['edit_price']);
    $value = intval($_POST['edit_value']);
    $id_type = intval($_POST['edit_id_type']);

    $img_product_sql = "";
    if (isset($_FILES['edit_img_product']) && $_FILES['edit_img_product']['error'] == 0) {
        $upload_dir = 'uploads/';
        $img_product = time() . "_" . basename($_FILES['edit_img_product']['name']);
        move_uploaded_file($_FILES['edit_img_product']['tmp_name'], $upload_dir . $img_product);
        $img_product_sql = ", img_product='$img_product'";
    }

    $sql_update = "UPDATE tbl_product SET 
        name_product='$name_product', 
        detail_product='$detail_product',
        price=$price, 
        value=$value,
        id_type=$id_type
        $img_product_sql
        WHERE id_product=$id_product";

    if (mysqli_query($conn, $sql_update)) {
        echo "<script>Swal.fire('สำเร็จ', 'แก้ไขสินค้าสำเร็จ', 'success').then(() => { window.location = 'manage_product.php'; });</script>";
    } else {
        echo "<script>Swal.fire('ผิดพลาด', 'ไม่สามารถแก้ไขสินค้าได้', 'error');</script>";
    }
}

// ลบสินค้า
if (isset($_POST['delete_product'])) {
    $id_product = intval($_POST['del_id_product']);
    $sql_delete = "DELETE FROM tbl_product WHERE id_product=$id_product";
    if (mysqli_query($conn, $sql_delete)) {
        echo "<script>Swal.fire('สำเร็จ', 'ลบสินค้าสำเร็จ', 'success').then(() => { window.location = 'manage_product.php'; });</script>";
    } else {
        echo "<script>Swal.fire('ผิดพลาด', 'ไม่สามารถลบสินค้าได้', 'error');</script>";
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
