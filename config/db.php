<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$host = 'localhost';
$db   = 'SathaniMala'; // ชื่อ Database ที่เราสร้าง
$user = 'root';        // Username (ปกติของ XAMPP คือ root)
$pass = '';            // Password (ปกติของ XAMPP คือว่างเปล่า)
$charset = 'utf8mb4';  // รองรับภาษาไทยสมบูรณ์แบบ

// สร้าง Data Source Name
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// ตั้งค่า Options ของ PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // ให้พ่น Error แบบละเอียด
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // ดึงข้อมูลออกมาเป็น Array (ชื่อคอลัมน์)
    PDO::ATTR_EMULATE_PREPARES   => false,                  // ใช้ Prepare Statement จริงเพื่อความปลอดภัย
];

try {
    // เริ่มการเชื่อมต่อ
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // ถ้าเชื่อมต่อไม่ได้ ให้แสดง Error
    die("Database Connection Failed: " . $e->getMessage());
}
?>