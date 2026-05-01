<?php
session_start();
require_once '../config/db.php';

try {
    $stmt_today = $conn->query("SELECT SUM(total_price) FROM orders WHERE status = 'completed' AND DATE(created_at) = CURDATE()");
    $today_sales = $stmt_today->fetchColumn() ?: 0;
    
    $stmt_month = $conn->query("SELECT SUM(total_price) FROM orders WHERE status = 'completed' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
    $month_sales = $stmt_month->fetchColumn() ?: 0;

    $stmt_year = $conn->query("SELECT SUM(total_price) FROM orders WHERE status = 'completed' AND YEAR(created_at) = YEAR(CURDATE())");
    $year_sales = $stmt_year->fetchColumn() ?: 0;
} catch (PDOException $e) { die("Error: " . $e->getMessage()); }
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;700;800&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ภาพรวมระบบ - SATHANI MALA</title>
    <link rel="stylesheet" href="../assets/css/sidebar_owner.css">
    <link rel="stylesheet" href="../assets/css/dashboard_owner.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="owner-page">
    <div class="owner-container" style="display: flex;">
        <?php include 'sidebar_owner.php'; ?>
        
        <main class="main-content" style="flex: 1; padding: 25px; background: #f4f7f6;">
            <div class="content-frame" style="background: #fff; padding: 30px; border-radius: 0;">
                <header style="margin-bottom: 25px;">
                    <h2 style="font-size: 28px; font-weight: 800; color: #1e293b; margin: 0;">ภาพรวม</h2>
                    <p style="color: #64748b; margin-top: 5px;">ระบบจัดการร้าน SATHANI MALA</p>
                </header>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="card-icon blue"><i class="fa-regular fa-calendar fa-2x"></i></div>
                        <div class="card-info">
                            <span style="display:block; color:#64748b; font-size:14px;">ยอดขายวันนี้</span>
                            <span style="font-size:24px; font-weight:800;"><?= number_format($today_sales) ?> บาท</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="card-icon purple"><i class="fa-regular fa-calendar-check fa-2x"></i></div>
                        <div class="card-info">
                            <span style="display:block; color:#64748b; font-size:14px;">ยอดขายเดือนนี้</span>
                            <span style="font-size:24px; font-weight:800;"><?= number_format($month_sales) ?> บาท</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="card-icon dark-blue"><i class="fa-regular fa-calendar-days fa-2x"></i></div>
                        <div class="card-info">
                            <span style="display:block; color:#64748b; font-size:14px;">ยอดขายรวมทั้งปี</span>
                            <span style="font-size:24px; font-weight:800;"><?= number_format($year_sales) ?> บาท</span>
                        </div>
                    </div>
                </div>

                <div class="chart-box">
                    <div style="text-align:center; font-weight:bold; margin-bottom:20px; color: #1e293b;">
                        <i class="fa-solid fa-boxes-stacked"></i> มูลค่าสินค้าคงเหลือรายคลัง
                    </div>
                    <div style="height: 350px;">
                        <canvas id="stockPieChart"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="../assets/js/dashboard_owner.js"></script>
</body>
</html>