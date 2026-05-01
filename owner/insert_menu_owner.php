<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $main_cat_name = $_POST['main_category']; // อาหาร หรือ เครื่องดื่ม
    $sub_category = $_POST['sub_category'];

    try {
        // 1. ตรวจสอบ category_id จากตาราง categories
        $stmt_cat = $conn->prepare("SELECT id FROM categories WHERE name = ?");
        $stmt_cat->execute([$main_cat_name]);
        $category = $stmt_cat->fetch();

        if ($category) {
            $category_id = $category['id'];
        } else {
            // ถ้ายังไม่มีประเภทใน DB ให้สร้างใหม่
            $ins_cat = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $ins_cat->execute([$main_cat_name]);
            $category_id = $conn->lastInsertId();
        }

        // 2. จัดการไฟล์รูปภาพ
        $image_path = "default.jpg";
        if (isset($_FILES['menu_image']) && $_FILES['menu_image']['error'] == 0) {
            $ext = pathinfo($_FILES['menu_image']['name'], PATHINFO_EXTENSION);
            $new_filename = "menu_" . time() . "." . $ext;
            $upload_dir = "../assets/img/menus/";

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            if (move_uploaded_file($_FILES['menu_image']['tmp_name'], $upload_dir . $new_filename)) {
                $image_path = $new_filename;
            }
        }

        // 3. Insert ข้อมูลลงตาราง menus (ตาม SQL พี่เป๊ะ)
        $sql = "INSERT INTO menus (category_id, sub_category, name, price, image_path, is_active) 
                VALUES (:cat_id, :sub, :name, :price, :img, 1)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':cat_id' => $category_id,
            ':sub'    => $sub_category,
            ':name'   => $name,
            ':price'  => $price,
            ':img'    => $image_path
        ]);

        // สำเร็จ: แจ้งเตือนและกลับหน้าจัดการ
        echo "<script>
                alert('เพิ่มเมนู " . $name . " เรียบร้อยแล้ว');
                window.location.href='manage_menus_owner.php';
              </script>";
        exit();

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}