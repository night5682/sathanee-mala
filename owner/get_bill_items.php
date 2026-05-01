<?php
require_once '../config/db.php';
$order_id = $_GET['order_id'];

$sql = "SELECT m.name, oi.quantity, (m.price * oi.quantity) as subtotal 
        FROM order_items oi 
        JOIN menus m ON oi.menu_id = m.id 
        WHERE oi.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();

$total = 0;
echo '<table style="width:100%; border-collapse:collapse;">';
foreach ($items as $item) {
    echo "<tr>
            <td style='padding:8px 0;'>{$item['name']} x {$item['quantity']}</td>
            <td style='text-align:right;'>".number_format($item['subtotal'])."</td>
          </tr>";
    $total += $item['subtotal'];
}
echo '</table>';
echo '<hr>';
echo '<div style="display:flex; justify-content:space-between; font-size:20px; font-weight:bold;">';
echo '<span>ยอดรวมทั้งสิ้น</span>';
echo '<span>'.number_format($total).'.-</span>';
echo '</div>';
?>