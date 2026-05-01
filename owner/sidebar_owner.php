<?php
// เช็กชื่อไฟล์ปัจจุบันเพื่อทำสถานะ Active
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar-owner">
    <div class="sidebar-header">
        <span class="brand-name">SATHANI MALA</span>
    </div>

    <nav class="sidebar-nav">
        <a href="dashboard_owner.php" class="nav-link <?= ($current_page == 'dashboard_owner.php') ? 'active' : '' ?>">
        <span>หน้าหลัก</span>
        </a>
        
        <a href="active_bills_owner.php" class="nav-link <?= ($current_page == 'active_bills_owner.php') ? 'active' : '' ?>">
        <span>รายการบิล</span>
        </a>

        <a href="manage_menus_owner.php" class="nav-link <?= ($current_page == 'manage_menus_owner.php') ? 'active' : '' ?>">
        <span>จัดการเมนู</span>
        </a>

        <a href="stock_beverage_owner.php" class="nav-link <?= ($current_page == 'stock_beverage_owner.php') ? 'active' : '' ?>">
        <span>สต็อกสินค้า</span>
        </a>

        <a href="order_history_owner.php" class="nav-link <?= ($current_page == 'order_history_owner.php') ? 'active' : '' ?>">
        <span>ประวัติการขาย</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="../logout.php" class="btn-logout" onclick="return confirm('ยืนยันการออกจากระบบ?')">
        <span>ออกจากระบบ</span>
        </a>
    </div>
</aside>