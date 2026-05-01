<?php
session_start();
require_once '../config/db.php';

// 1. ดึงข้อมูลเมนูทั้งหมด
try {
    $sql_menu = "SELECT m.*, c.name AS category_name FROM menus m 
                 JOIN categories c ON m.category_id = c.id 
                 WHERE m.is_active = 1 
                 ORDER BY m.name ASC";
    $stmt_menu = $conn->query($sql_menu);
    $menus = $stmt_menu->fetchAll();

    // 2. ดึงหมวดหมู่ทั้งหมดสำหรับ Sidebar
    $sql_cat = "SELECT * FROM categories ORDER BY id ASC";
    $stmt_cat = $conn->query($sql_cat);
    $categories = $stmt_cat->fetchAll();
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SATHANI MALA - ORDER</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        /* --- CSS: ดุดัน ไม่เกรงใจใคร --- */
        * { font-family: 'Sarabun', sans-serif !important; box-sizing: border-box; border-radius: 0 !important; margin: 0; padding: 0; }
        body, html { background: #f1f5f9; min-height: 100vh; overflow-x: hidden; color: #1e293b; }

        .menu-container { display: flex; width: 100%; position: relative; }

        /* Sidebar: Desktop */
        .sidebar-customer { width: 280px; min-width: 280px; background: #1a1a1a; color: white; position: sticky; top: 0; height: 100vh; z-index: 1000; display: flex; flex-direction: column; }
        .brand-name { padding: 40px 20px; text-align: center; font-size: 26px; font-weight: 800; border-bottom: 1px solid #333; letter-spacing: 1px; }
        .sidebar-nav { flex: 1; overflow-y: auto; }
        .nav-link { display: block; padding: 18px 25px; color: #94a3b8; text-decoration: none; font-weight: 700; border-bottom: 1px solid #2d2d2d; transition: 0.2s; }
        .nav-link.active { background: #3b82f6; color: white; }

        /* Main Content */
        .main-content { flex: 1; padding: 30px; padding-bottom: 120px; }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
        .menu-card { background: #fff; border: 1px solid #e2e8f0; display: flex; flex-direction: column; overflow: hidden; }
        .image-box { width: 100%; aspect-ratio: 1/1; background: #eee; overflow: hidden; }
        .image-box img { width: 100%; height: 100%; object-fit: cover; }
        .menu-detail { padding: 15px; text-align: center; flex: 1; }
        .menu-name { font-size: 17px; font-weight: 800; margin-bottom: 5px; }
        .menu-price { font-size: 20px; font-weight: 800; color: #1a1a1a; }
        .btn-order { width: 100%; background: #1a1a1a; color: white; border: none; padding: 15px; font-weight: 800; cursor: pointer; }
        .btn-order:hover { background: #3b82f6; }

        /* แถบตะกร้าด้านล่าง */
        .cart-bar { position: fixed !important; bottom: 0 !important; left: 0; right: 0; height: 80px; background: #1e293b; color: white; display: none; align-items: center; justify-content: space-between; padding: 0 40px; z-index: 9000 !important; border-top: 4px solid #3b82f6; box-shadow: 0 -5px 20px rgba(0,0,0,0.3); cursor: pointer; }
        .cart-total { font-size: 24px; font-weight: 800; }
        .cart-btn-view { background: #3b82f6; padding: 12px 25px; font-weight: 800; text-transform: uppercase; }

        /* Modal สรุปรายการ */
        .order-modal { position: fixed !important; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999 !important; display: none; align-items: center; justify-content: center; }
        .modal-content { background: white; width: 95%; max-width: 500px; max-height: 85vh; display: flex; flex-direction: column; }
        .modal-header { padding: 20px; background: #f8fafc; border-bottom: 2px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
        .order-list { flex: 1; overflow-y: auto; padding: 10px; }
        
        /* ปุ่มจัดการรายการใน Modal */
        .order-item { display: flex; align-items: center; padding: 15px; border-bottom: 1px solid #f1f5f9; }
        .qty-control { display: flex; align-items: center; border: 2px solid #1a1a1a; margin: 0 15px; background: white; }
        .btn-qty { width: 35px; height: 35px; background: white; border: none; font-weight: 800; font-size: 20px; cursor: pointer; }
        .btn-qty:hover { background: #f1f5f9; }
        .qty-num { padding: 0 10px; font-weight: 800; min-width: 40px; text-align: center; font-size: 18px; }
        .btn-remove-item { background: #1a1a1a; color: white; border: none; width: 35px; height: 35px; font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .btn-remove-item:hover { background: #ef4444; }

        .modal-footer { padding: 25px; background: #f8fafc; border-top: 2px solid #e2e8f0; }
        .total-summary { font-size: 24px; font-weight: 800; margin-bottom: 15px; text-align: center; }
        .btn-confirm-order { width: 100%; background: #1a1a1a; color: white; border: none; padding: 20px; font-weight: 800; font-size: 20px; cursor: pointer; }

        /* สำหรับมือถือ */
        @media (max-width: 992px) {
            .sidebar-customer { position: fixed; left: -300px; transition: 0.3s; z-index: 9500; }
            .sidebar-customer.active { left: 0; }
            .sidebar-toggle-btn { display: block; position: fixed; bottom: 100px; left: 20px; background: #1a1a1a; color: white; padding: 15px 20px; z-index: 5000; border: none; font-weight: 800; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
            .menu-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; padding: 10px; }
            .cart-bar { padding: 0 20px; }
            .overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; z-index: 9400; }
            .overlay.active { display: block; }
        }
    </style>
</head>
<body>

<div class="menu-container">
    <aside class="sidebar-customer" id="sidebar">
        <div class="brand-name">SATHANI MALA</div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-link active" onclick="filterMenu('all', this)">เมนูทั้งหมด</a>
            <?php foreach ($categories as $cat): ?>
                <a href="#" class="nav-link" onclick="filterMenu('<?= htmlspecialchars($cat['name']) ?>', this)">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </aside>

    <main class="main-content">
        <button class="sidebar-toggle-btn" onclick="toggleSidebar()">เลือกหมวดหมู่</button>
        <div class="menu-grid">
            <?php foreach ($menus as $m): ?>
            <div class="menu-card" data-cat="<?= htmlspecialchars($m['category_name']) ?>">
                <div class="image-box">
                    <img src="../assets/img/menus/<?= $m['image_path'] ?>" onerror="this.src='../assets/img/default.jpg'">
                </div>
                <div class="menu-detail">
                    <h3 class="menu-name"><?= htmlspecialchars($m['name']) ?></h3>
                    <p class="menu-price"><?= number_format($m['price']) ?>.-</p>
                </div>
                <button class="btn-order" onclick="addToCart(<?= $m['id'] ?>, '<?= htmlspecialchars($m['name']) ?>', <?= $m['price'] ?>)">เลือกรายการนี้</button>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<div class="cart-bar" id="cart-bar" onclick="showOrderDetails()">
    <div class="cart-info">
        <span id="cart-qty">0</span> รายการ | <span class="cart-total">รวม <span id="cart-total-price">0</span>.-</span>
    </div>
    <div class="cart-btn-view">ดูรายการที่สั่ง</div>
</div>

<div class="order-modal" id="order-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>รายการที่สั่ง</h3>
            <button onclick="closeOrderDetails()" style="background:none; border:none; font-size:30px; cursor:pointer;">&times;</button>
        </div>
        <div id="order-items-list" class="order-list"></div>
        <div class="modal-footer">
            <div class="total-summary">รวมทั้งสิ้น: <span id="modal-total">0</span>.-</div>
            <button class="btn-confirm-order" onclick="submitOrder()">ยืนยันสั่งอาหาร</button>
        </div>
    </div>
</div>

<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

<script>
    let cart = [];

    // --- จัดการ Sidebar ---
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('overlay').classList.toggle('active');
    }

    function filterMenu(catName, element) {
        document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
        element.classList.add('active');

        const cards = document.querySelectorAll('.menu-card');
        cards.forEach(card => {
            const itemCat = card.getAttribute('data-cat');
            card.style.display = (catName === 'all' || itemCat === catName) ? 'flex' : 'none';
        });
        if (window.innerWidth <= 992) toggleSidebar();
    }

    // --- จัดการตะกร้าสินค้า ---
    function addToCart(id, name, price) {
        const idx = cart.findIndex(i => i.id === id);
        if (idx > -1) { cart[idx].qty += 1; } 
        else { cart.push({ id, name, price, qty: 1 }); }
        updateCartBar();
    }

    function changeQty(index, amt) {
        cart[index].qty += amt;
        if (cart[index].qty <= 0) cart.splice(index, 1);
        updateCartBar();
        if (cart.length > 0) showOrderDetails();
        else closeOrderDetails();
    }

    function updateCartBar() {
        const bar = document.getElementById('cart-bar');
        const totalQty = cart.reduce((s, i) => s + i.qty, 0);
        const totalPrice = cart.reduce((s, i) => s + (i.price * i.qty), 0);
        if (totalQty > 0) {
            bar.style.display = 'flex';
            document.getElementById('cart-qty').innerText = totalQty;
            document.getElementById('cart-total-price').innerText = totalPrice.toLocaleString();
        } else { bar.style.display = 'none'; }
    }

    function showOrderDetails() {
        const list = document.getElementById('order-items-list');
        list.innerHTML = cart.map((item, index) => `
            <div class="order-item">
                <div style="flex: 1;">
                    <div style="font-weight:800;">${item.name}</div>
                    <div style="font-size:14px; color:#64748b;">${item.price.toLocaleString()}.-</div>
                </div>
                <div class="qty-control">
                    <button class="btn-qty" onclick="changeQty(${index}, -1)">-</button>
                    <span class="qty-num">${item.qty}</span>
                    <button class="btn-qty" onclick="changeQty(${index}, 1)">+</button>
                </div>
                <div style="width: 80px; text-align: right; font-weight: 800;">
                    ${(item.price * item.qty).toLocaleString()}.-
                </div>
                <button class="btn-remove-item" style="margin-left:10px;" onclick="changeQty(${index}, -999)">&times;</button>
            </div>
        `).join('');
        const total = cart.reduce((s, i) => s + (i.price * i.qty), 0);
        document.getElementById('modal-total').innerText = total.toLocaleString();
        document.getElementById('order-modal').style.display = 'flex';
    }

    function closeOrderDetails() { document.getElementById('order-modal').style.display = 'none'; }
    
    function submitOrder() {
        if (cart.length === 0) return;
        alert("ส่งรายการสั่งอาหารสำเร็จ!");
        cart = [];
        updateCartBar();
        closeOrderDetails();
    }
</script>

</body>
</html>