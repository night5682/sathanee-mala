<?php
session_start();
require_once '../config/db.php';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มเมนูใหม่ - SATHANI MALA</title>
    <link rel="stylesheet" href="../assets/css/sidebar_owner.css">
    <link rel="stylesheet" href="../assets/css/add_menu.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ล็อกโครงสร้างหน้าจอให้เหลี่ยมและนิ่ง */
        .owner-container {
            display: flex; /* บังคับให้อยู่แถวเดียวกัน ไม่ตกไปข้างล่าง */
            min-height: 100vh;
            width: 100%;
            background: #f4f7f6;
        }
        .main-content {
            flex: 1;
            padding: 25px;
            box-sizing: border-box;
        }
        .form-container-compact {
            background: #ffffff;
            padding: 30px;
            border-radius: 0 !important; /* ล็อกเหลี่ยมคมกริ๊บตามสั่ง */
            box-shadow: none !important;
        }
    </style>
</head>
<body style="margin: 0; padding: 0;">
    <div class="owner-container">
        <?php include 'sidebar_owner.php'; ?>

        <main class="main-content">
            <section class="form-container-compact">
                <header style="margin-bottom: 25px;">
                    <h1 style="margin: 0; font-size: 28px; font-weight: 800; color: #1e293b;">เพิ่มเมนูใหม่</h1>
                </header>

                <form action="insert_menu_owner.php" method="POST" enctype="multipart/form-data" class="split-form">
                    <div class="form-left">
                        <div class="image-preview-wrapper-compact">
                            <img id="img-preview" src="../assets/img/default.jpg" alt="Preview">
                        </div>
                        <div class="file-input-wrapper" style="margin-top: 15px;">
                            <label for="menu_image" class="btn-file">เลือกรูปภาพเมนู</label>
                            <input type="file" name="menu_image" id="menu_image" accept="image/*" required>
                        </div>
                    </div>

                    <div class="form-right">
                        <div class="form-group-compact">
                            <label>ชื่อเมนู</label>
                            <input type="text" name="name" placeholder="ระบุชื่อเมนู" required>
                        </div>

                        <div class="form-group-compact">
                            <label>ราคาขาย (บาท)</label>
                            <input type="number" name="price" placeholder="ระบุราคา เช่น 350" required>
                        </div>

                        <div class="form-group-compact">
                            <label>ประเภทหลัก (Category)</label>
                            <select name="main_category" id="main_category" required>
                                <option value="อาหาร">อาหาร</option>
                                <option value="เครื่องดื่ม">เครื่องดื่ม</option>
                            </select>
                        </div>

                        <div class="form-group-compact">
                            <label>ชนิดเมนู (Sub Category)</label>
                            <select name="sub_category" id="sub_category" required>
                                <option value="เมนูต้ม">เมนูต้ม</option>
                                <option value="เมนูทอด">เมนูทอด</option>
                                <option value="เมนูทานเล่น">เมนูทานเล่น</option>
                                <option value="อาหารจานเดียว">อาหารจานเดียว</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-submit-compact">บันทึกลงระบบ</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
    <script src="../assets/js/add_menu.js"></script>
</body>
</html>