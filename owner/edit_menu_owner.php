<?php
session_start();
require_once '../config/db.php';

// รับค่า ID และดึงข้อมูลเดิม
if (!isset($_GET['id'])) {
    header("Location: manage_menus_owner.php");
    exit();
}

$id = $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT * FROM menus WHERE id = ?");
    $stmt->execute([$id]);
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$menu) {
        die("ไม่พบข้อมูลเมนู");
    }

    // ดึงหมวดหมู่มาโชว์ใน Select
    $cat_stmt = $conn->query("SELECT * FROM categories ORDER BY id ASC");
    $categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขเมนู - <?= htmlspecialchars($menu['name']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/sidebar_owner.css">
    <link rel="stylesheet" href="../assets/css/edit_menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="owner-page">
    <div class="owner-container" style="display: flex; min-height: 100vh; background: #f8fafc;">
        <?php include 'sidebar_owner.php'; ?>
        
        <main class="main-content" style="flex: 1; padding: 40px;">
            <div class="edit-card">
                <div class="edit-header">
                    <h2><i class="fa-solid fa-pen-to-square"></i> แก้ไขรายการเมนู</h2>
                    <p>แก้ไขข้อมูลอาหารหรือเครื่องดื่มในระบบ</p>
                </div>

                <form action="edit_menu_action.php" method="POST" enctype="multipart/form-data" class="edit-form">
                    <input type="hidden" name="id" value="<?= $menu['id'] ?>">
                    <input type="hidden" name="old_image" value="<?= $menu['image_path'] ?>">

                    <div class="form-group">
                        <label><i class="fa-solid fa-tag"></i> ชื่อเมนู</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($menu['name']) ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fa-solid fa-list"></i> หมวดหมู่หลัก</label>
                            <select name="category_id" class="form-control">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $menu['category_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><i class="fa-solid fa-layer-group"></i> หมวดหมู่ย่อย (เช่น ร้อน/เย็น)</label>
                            <input type="text" name="sub_category" class="form-control" value="<?= htmlspecialchars($menu['sub_category']) ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fa-solid fa-baht-sign"></i> ราคาขาย (บาท)</label>
                        <input type="number" name="price" class="form-control" value="<?= $menu['price'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fa-solid fa-image"></i> รูปภาพเมนู</label>
                        <div class="upload-wrapper">
                            <input type="file" name="image" id="imageInput" class="form-control-file" accept="image/*" onchange="previewImage(this)">
                            <div class="preview-area">
                                <img id="imgPreview" src="../assets/img/menus/<?= $menu['image_path'] ?>" alt="Preview">
                                <p id="uploadText">คลิกเพื่อเปลี่ยนรูปภาพ</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">บันทึกการแก้ไข</button>
                        <a href="manage_menus_owner.php" class="btn-cancel">ยกเลิก</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="../assets/js/edit_menu.js"></script>
</body>
</html>