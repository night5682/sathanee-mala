<?php
session_start();
require_once '../config/db.php';

// 1. จัดการเรื่องวันที่ (Filter)
$date_start = isset($_GET['date_start']) ? $_GET['date_start'] : date('Y-m-d');
$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : date('Y-m-d');

try {
    // 2. ดึงข้อมูลออเดอร์ (แก้ SQL ให้ใช้ table_number ตามโครงสร้างตารางพี่)
    $sql = "SELECT * FROM orders 
            WHERE status = 'completed' 
            AND DATE(created_at) BETWEEN ? AND ? 
            ORDER BY created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$date_start, $date_end]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. คำนวณยอดรวมของรายการที่แสดง
    $total_in_period = 0;
    foreach ($orders as $order) {
        $total_in_period += $order['total_price'];
    }

} catch (PDOException $e) { 
    die("เชื่อมต่อฐานข้อมูลผิดพลาด: " . $e->getMessage()); 
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติออเดอร์ - SATHANI MALA</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/sidebar_owner.css">
    <link rel="stylesheet" href="../assets/css/dashboard_owner.css">
    <link rel="stylesheet" href="../assets/css/order_history_owner.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="owner-page">
    <div class="owner-container" style="display: flex; min-height: 100vh; background: #f0f2f5;">
        <?php include 'sidebar_owner.php'; ?>
        
        <main class="main-content" style="flex: 1; padding: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <div>
                    <h2 class="page-title" style="margin: 0; font-size: 28px;">ประวัติออเดอร์</h2>
                    <p style="color: #64748b; margin-top: 5px;">
                        <i class="fa-regular fa-calendar-days"></i> 
                        ช่วงวันที่: <?= date('d/m/Y', strtotime($date_start)) ?> ถึง <?= date('d/m/Y', strtotime($date_end)) ?>
                    </p>
                </div>
                <button id="toggleFilterBtn" class="stat-card" style="padding: 12px 24px; cursor: pointer; border: none; font-weight: bold; background: white; display: flex; align-items: center; gap: 10px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <i class="fa-solid fa-magnifying-glass"></i> ค้นหาขั้นสูง
                </button>
            </div>

            <div id="filter-panel" style="display: none; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 25px;">
                <form method="GET" style="display: flex; gap: 20px; align-items: flex-end;">
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #334155;">จากวันที่</label>
                        <input type="date" name="date_start" value="<?= $date_start ?>" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #334155;">ถึงวันที่</label>
                        <input type="date" name="date_end" value="<?= $date_end ?>" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
                    </div>
                    <button type="submit" style="background: #1e293b; color: white; border: none; padding: 12px 40px; border-radius: 8px; font-weight: bold; cursor: pointer; height: 45px;">
                        ตกลง
                    </button>
                </form>
            </div>

            <div class="stat-card" style="margin-bottom: 30px; padding: 25px; background: white; border-radius: 15px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div class="card-icon icon-today" style="width: 60px; height: 60px; font-size: 24px;">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <span style="color: #94a3b8; font-size: 14px;">ยอดรวมตามช่วงเวลาที่เลือก</span>
                        <h3 style="margin: 0; font-size: 32px; color: #1e293b;"><?= number_format($total_in_period) ?>.-</h3>
                    </div>
                </div>
                <div style="text-align: right;">
                    <span style="display: block; color: #94a3b8; font-size: 14px;">จำนวนรายการ</span>
                    <strong style="font-size: 20px; color: #1e293b;"><?= count($orders) ?> ออเดอร์</strong>
                </div>
            </div>

            <div class="history-table-container">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 100px;">ออเดอร์</th>
                            <th>วันเวลาที่ชำระเงิน</th>
                            <th style="text-align: center;">โต๊ะ</th>
                            <th style="text-align: right;">ยอดชำระสุทธิ</th>
                            <th style="text-align: center;">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($orders) > 0): ?>
                            <?php foreach($orders as $order): ?>
                            <tr>
                                <td style="text-align: center; font-weight: bold; color: #3b82f6;">#<?= $order['id'] ?></td>
                                <td style="color: #475569;"><?= date('d/m/Y - H:i', strtotime($order['created_at'])) ?> น.</td>
                                <td style="text-align: center;">
                                    <span style="background: #f1f5f9; color: #475569; padding: 4px 12px; border-radius: 6px; font-weight: bold;">
                                        โต๊ะ <?= $order['table_number'] ?>
                                    </span>
                                </td>
                                <td style="text-align: right; font-weight: 800; font-size: 16px; color: #1e293b;">
                                    <?= number_format($order['total_price']) ?>.-
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge-success">ชำระแล้ว</span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="padding: 60px; text-align: center; color: #94a3b8;">
                                    <i class="fa-solid fa-inbox" style="font-size: 40px; margin-bottom: 15px;"></i>
                                    <p>ไม่พบประวัติการขายในช่วงเวลาที่เลือก</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="../assets/js/order_history_owner.js"></script>
</body>
</html>