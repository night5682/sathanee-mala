<?php
session_start();
require_once '../config/db.php';

try {
    // ดึงเฉพาะบิลที่ยังไม่ชำระเงิน (โต๊ะที่มีลูกค้า) 
    // กรองเอาเฉพาะสถานะที่ไม่ใช่ completed
    $sql = "SELECT * FROM orders 
            WHERE status != 'completed' 
            ORDER BY CAST(table_number AS UNSIGNED) ASC";
    $stmt = $conn->query($sql);
    $active_tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;700;800&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โต๊ะที่กำลังใช้งาน - SATHANI MALA</title>
    <link rel="stylesheet" href="../assets/css/sidebar_owner.css">
    <link rel="stylesheet" href="../assets/css/active_bills.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="owner-page">
    <div class="owner-container" style="display: flex; min-height: 100vh; background: #f0f2f5;">
        <?php include 'sidebar_owner.php'; ?>
        
        <main class="main-content" style="flex: 1; padding: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <div>
                    <h2 style="margin: 0; font-size: 28px; color: #1e293b;">โต๊ะที่กำลังใช้งาน</h2>
                    <p style="color: #64748b; margin: 5px 0 0 0;">คลิกที่โต๊ะเพื่อดูรายการอาหารและพิมพ์บิล</p>
                </div>
                <div style="background: #fff; padding: 10px 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <span style="color: #64748b;">โต๊ะที่มีลูกค้า:</span> 
                    <strong style="color: #e74c3c; font-size: 18px; margin-left: 5px;"><?= count($active_tables) ?></strong>
                </div>
            </div>

            <div class="tables-grid">
                <?php if (count($active_tables) > 0): ?>
                    <?php foreach ($active_tables as $table): ?>
                        <div class="table-card occupied" onclick="showBill(<?= $table['id'] ?>, '<?= $table['table_number'] ?>')">
                            <div class="table-icon">
                                <i class="fa-solid fa-utensils"></i>
                            </div>
                            <span class="table-no">โต๊ะ <?= $table['table_number'] ?></span>
                            <span class="amount"><?= number_format($table['total_price']) ?>.-</span>
                            <div class="card-footer-text">
                                <i class="fa-solid fa-receipt"></i> ดูรายการสั่งซื้อ
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1/-1; text-align: center; padding: 100px; background: white; border-radius: 20px; color: #94a3b8;">
                        <i class="fa-solid fa-couch" style="font-size: 50px; margin-bottom: 20px; display: block;"></i>
                        <h3 style="margin: 0;">ตอนนี้ยังไม่มีลูกค้าในร้าน</h3>
                        <p>เมื่อมีการเปิดโต๊ะใหม่ รายการจะมาปรากฏที่นี่</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <div id="billModal" class="modal">
        <div class="modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 id="modalTitle" style="margin: 0; color: #1e293b;">รายละเอียด</h2>
                <span onclick="closeModal()" style="cursor: pointer; font-size: 24px; color: #94a3b8;"><i class="fa-solid fa-xmark"></i></span>
            </div>
            
            <div id="billItems" style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #e2e8f0; max-height: 400px; overflow-y: auto;">
                </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <button onclick="closeModal()" style="padding: 15px; border: 1px solid #e2e8f0; border-radius: 12px; background: #fff; color: #64748b; font-weight: bold; cursor: pointer;">
                    ย้อนกลับ
                </button>
                <button class="btn-checkout" id="printBtn">
                    <i class="fa-solid fa-print"></i> พิมพ์บิล/เช็คบิล
                </button>
            </div>
        </div>
    </div>

    <script src="../assets/js/active_bills.js"></script>
</body>
</html>