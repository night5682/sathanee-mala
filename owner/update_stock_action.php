<?php
session_start();
require_once '../config/db.php';

// ตรวจสอบว่าส่งค่ามาครบไหม
if (isset($_GET['id']) && isset($_GET['qty'])) {
    $id = intval($_GET['id']);
    $new_qty = intval($_GET['qty']);

    try {
        $conn->beginTransaction();

        // 1. ดึงจำนวนสต็อกปัจจุบันก่อนเพื่อหา "ส่วนต่าง" มาลง Log
        $stmt = $conn->prepare("SELECT stock_quantity FROM menus WHERE id = ?");
        $stmt->execute([$id]);
        $old_qty = $stmt->fetchColumn();

        if ($old_qty !== false) {
            $diff = $new_qty - $old_qty; // ถ้าผลเป็นบวกคือเติมของ (in), ลบคือเอาของออก (out)
            $type = ($diff >= 0) ? 'in' : 'out';
            $abs_diff = abs($diff);

            // 2. อัปเดตจำนวนสต็อกใหม่ลงตาราง menus
            $update_sql = "UPDATE menus SET stock_quantity = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->execute([$new_qty, $id]);

            // 3. บันทึกประวัติลง stock_logs (ถ้ามีการเปลี่ยนแปลง)
            if ($diff != 0) {
                $log_sql = "INSERT INTO stock_logs (menu_id, type, amount, note) VALUES (?, ?, ?, ?)";
                $log_stmt = $conn->prepare($log_sql);
                $log_stmt->execute([$id, $type, $abs_diff, "Manual update via Dashboard"]);
            }

            $conn->commit();
            // อัปเดตเสร็จแล้วเด้งกลับหน้าสต็อกพร้อมแจ้งเตือน
            header("Location: stock_beverage_owner.php?success=1");
            exit();
        }

    } catch (Exception $e) {
        $conn->rollBack();
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: stock_beverage_owner.php");
    exit();
}