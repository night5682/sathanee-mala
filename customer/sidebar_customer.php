<?php
// sidebar_customer.php
require_once '../config/db.php';

try {
    $sql_sub = "SELECT DISTINCT sub_category FROM menus 
                WHERE is_active = 1 AND sub_category IS NOT NULL 
                ORDER BY sub_category ASC";
    $stmt_sub = $conn->query($sql_sub);
    $sub_categories = $stmt_sub->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo "Sidebar Error: " . $e->getMessage();
}
?>

<button class="sidebar-toggle-btn" onclick="toggleSidebar()">
    <span>เลือกหมวดหมู่</span>
</button>

<aside class="sidebar-customer" id="sidebar">
    <div class="sidebar-header">
        <span class="brand-name">SATHANI MALA</span>
        <div class="close-btn" onclick="toggleSidebar()">&times;</div>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="javascript:void(0)" class="nav-link active" onclick="filterMenu('all', this)">
                    เมนูทั้งหมด
                </a>
            </li>
            <?php foreach ($sub_categories as $sub): ?>
                <li>
                    <a href="javascript:void(0)" class="nav-link" onclick="filterMenu('<?= htmlspecialchars($sub) ?>', this)">
                        <?= htmlspecialchars($sub) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="sidebar-footer">
        &copy; 2026 SATHANI MALA
    </div>
</aside>

<div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>