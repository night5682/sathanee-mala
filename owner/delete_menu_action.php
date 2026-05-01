<?php
session_start();
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // ดึงชื่อรูปมาลบทิ้งก่อนลบข้อมูล
        $stmt = $conn->prepare("SELECT image_path FROM menus WHERE id = ?");
        $stmt->execute([$id]);
        $menu = $stmt->fetch();

        if ($menu && !empty($menu['image_path'])) {
            $path = "../assets/img/menus/" . $menu['image_path'];
            if (file_exists($path)) {
                unlink($path);
            }
        }

        // ลบข้อมูลจากฐานข้อมูล
        $del = $conn->prepare("DELETE FROM menus WHERE id = ?");
        $del->execute([$id]);

    } catch (PDOException $e) {
        // 
    }
}

header("Location: manage_menus_owner.php");
exit();