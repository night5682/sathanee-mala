<?php
session_start();
require_once '../config/db.php';

try {
    $sql = "SELECT m.*, c.name AS category_name 
            FROM menus m 
            JOIN categories c ON m.category_id = c.id 
            ORDER BY m.is_recommended DESC, m.created_at DESC";
    $stmt = $conn->query($sql);
    $menus = $stmt->fetchAll();
} catch (PDOException $e) {
    die("เชื่อมต่อฐานข้อมูลผิดพลาด: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการเมนู - SATHANI MALA</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/sidebar_owner.css">
    <link rel="stylesheet" href="../assets/css/manage_menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="owner-page">
    <div class="owner-container" style="display: flex;">
        <?php include 'sidebar_owner.php'; ?>
        <main class="main-content" style="flex: 1; padding: 20px;">
            <header class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1>จัดการเมนูร้าน</h1>
                <a href="add_menu_owner.php" class="btn-add-new" style="background: #1a1a1a; color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none;">+ เพิ่มเมนูใหม่</a>
            </header>
            
            <div class="table-container">
                <table class="owner-table" style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <thead style="background: #f8fafc; text-align: left;">
                        <tr>
                            <th width="10%" style="padding: 15px;">รูป</th>
                            <th width="40%" style="padding: 15px;">รายการ</th>
                            <th width="15%" style="padding: 15px;">ราคา</th>
                            <th width="15%" style="padding: 15px;">สถานะ</th>
                            <th width="20%" style="padding: 15px;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($menus as $menu): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 15px;">
                                <img src="../assets/img/menus/<?= htmlspecialchars($menu['image_path']) ?>" class="menu-img-thumb" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            </td>
                            <td style="padding: 15px;">
                                <div class="menu-info" style="display: flex; align-items: center; gap: 10px;">
                                    <div class="menu-text">
                                        <strong style="display: block;"><?= $menu['name'] ?></strong>
                                        <small style="color: #64748b;"><?= $menu['category_name'] ?> | <?= $menu['sub_category'] ?></small>
                                    </div>
                                    <a href="toggle_status_action.php?id=<?= $menu['id'] ?>&type=recommend" 
                                       class="star-btn <?= $menu['is_recommended'] ? 'active' : '' ?>" style="color: <?= $menu['is_recommended'] ? '#f59e0b' : '#cbd5e1' ?>; font-size: 18px;">
                                        <i class="<?= $menu['is_recommended'] ? 'fas fa-star' : 'far fa-star' ?>"></i>
                                    </a>
                                </div>
                            </td>
                            <td style="padding: 15px; font-weight: bold;"><?= number_format($menu['price']) ?>.-</td>
                            <td style="padding: 15px;">
                                <a href="toggle_status_action.php?id=<?= $menu['id'] ?>&type=active" 
                                   style="text-decoration: none; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; background: <?= $menu['is_active'] ? '#dcfce7' : '#fee2e2' ?>; color: <?= $menu['is_active'] ? '#166534' : '#991b1b' ?>;">
                                    <?= $menu['is_active'] ? 'พร้อมขาย' : 'หมด' ?>
                                </a>
                            </td>
                            <td style="padding: 15px;">
                                <div class="action-btns" style="display: flex; gap: 10px;">
                                    <a href="edit_menu_owner.php?id=<?= $menu['id'] ?>" style="color: #3b82f6;"><i class="fas fa-edit"></i></a>
                                    <a href="delete_menu_action.php?id=<?= $menu['id'] ?>" style="color: #ef4444;" onclick="return confirm('ยืนยันการลบเมนูนี้?')"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>