<?php
session_start();
require_once '../config/db.php';

try {
    $sql = "SELECT m.*, c.name AS category_name 
            FROM menus m
            JOIN categories c ON m.category_id = c.id
            WHERE c.name = 'เครื่องดื่ม'
            ORDER BY m.stock_quantity ASC, m.id DESC";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $drinks = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>คลังเครื่องดื่ม - SATHANI MALA</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/sidebar_owner.css">
    <link rel="stylesheet" href="../assets/css/stock_owner.css">
</head>
<body>
    <div class="owner-container">
        <?php include 'sidebar_owner.php'; ?>

        <main class="main-content">
            <div class="content-frame">
                <?php if(isset($_GET['success'])): ?>
                    <div class="alert-success">
                        อัปเดตจำนวนสต็อกเรียบร้อยแล้ว
                    </div>
                <?php endif; ?>

                <header class="content-header">
                    <div>
                        <h2>คลังเครื่องดื่ม</h2>
                        <p>จัดการจำนวนคงเหลือและสถานะการขาย</p>
                    </div>
                    <button onclick="location.reload()" class="btn-refresh">รีเฟรช</button>
                </header>

                <table class="stock-table">
                    <thead>
                        <tr>
                            <th>รูป</th>
                            <th>ชื่อเครื่องดื่ม</th>
                            <th>ชนิด</th>
                            <th style="text-align: center;">จำนวนในคลัง</th>
                            <th>สถานะ</th>
                            <th style="text-align: center;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($drinks)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 50px; color: #64748b;">
                                    ไม่พบข้อมูลเครื่องดื่มในระบบ
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($drinks as $drink): ?>
                            <tr>
                                <td>
                                    <img src="../assets/img/menus/<?= htmlspecialchars($drink['image_path']) ?>" class="menu-img">
                                </td>
                                <td>
                                    <div class="menu-name"><?= htmlspecialchars($drink['name']) ?></div>
                                    <div class="menu-price">ราคา: <?= number_format($drink['price'], 2) ?>.-</div>
                                </td>
                                <td><?= htmlspecialchars($drink['sub_category']) ?></td>
                                <td style="text-align: center;">
                                    <span class="stock-number <?= ($drink['stock_quantity'] <= $drink['low_stock_threshold']) ? 'stock-low' : '' ?>">
                                        <?= number_format($drink['stock_quantity']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($drink['stock_quantity'] <= 0): ?>
                                        <span class="badge bg-danger">สินค้าหมด</span>
                                    <?php elseif ($drink['stock_quantity'] <= $drink['low_stock_threshold']): ?>
                                        <span class="badge bg-warning">ใกล้หมด</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">ปกติ</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn-manage" onclick="editStock(<?= $drink['id'] ?>)">แก้ไขสต็อก</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        function editStock(id) {
            const newStock = prompt("กรุณาระบุจำนวนสต็อกใหม่:");
            if (newStock !== null && !isNaN(newStock) && newStock !== "") {
                window.location.href = `update_stock_action.php?id=${id}&qty=${newStock}`;
            }
        }
    </script>
</body>
</html>