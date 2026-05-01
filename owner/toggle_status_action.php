<?php
session_start();
require_once '../config/db.php';

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];

    try {
        if ($type == 'active') {
            $sql = "UPDATE menus SET is_active = NOT is_active WHERE id = ?";
        } elseif ($type == 'recommend') {
            $sql = "UPDATE menus SET is_recommended = NOT is_recommended WHERE id = ?";
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

    } catch (PDOException $e) {
        // สามารถเพิ่มการจัดการ error ตรงนี้ได้
    }
}

header("Location: manage_menus_owner.php");
exit();