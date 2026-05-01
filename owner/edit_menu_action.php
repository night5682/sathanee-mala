<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $id = $_POST['id'];
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $sub_category = $_POST['sub_category'];
    $price = $_POST['price']; // รับราคามาตรงๆ
    $old_image = $_POST['old_image'];
    
    // ตั้งค่ารูปภาพเริ่มต้นเป็นรูปเก่า
    $image_name = $old_image;

    // 1. จัดการเรื่องรูปภาพ (ถ้ามีการเปลี่ยนรูปใหม่)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = time() . "." . $ext; 
        $target = "../assets/img/menus/" . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // ลบรูปเก่าทิ้ง (ถ้าไม่ใช่รูป default)
            if ($old_image != 'default.jpg' && file_exists("../assets/img/menus/" . $old_image)) {
                unlink("../assets/img/menus/" . $old_image);
            }
        }
    }

    try {
        // 2. อัปเดตข้อมูล (เน้นที่ราคาให้เป็นค่าที่รับมาตรงๆ)
        $sql = "UPDATE menus SET 
                name = ?, 
                category_id = ?, 
                sub_category = ?, 
                price = ?, 
                image_path = ? 
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        
        // ส่งค่าไปเรียงตามลำดับเครื่องหมาย ?
        // $price ตรงนี้ต้องเป็นค่าที่รับมาจาก $_POST['price'] เท่านั้น
        $stmt->execute([
            $name, 
            $category_id, 
            $sub_category, 
            $price, 
            $image_name, 
            $id
        ]);

        header("Location: manage_menus_owner.php");
        exit();

    } catch (PDOException $e) {
        die("เกิดข้อผิดพลาดในการบันทึก: " . $e->getMessage());
    }
} else {
    header("Location: manage_menus_owner.php");
    exit();
}