function showBill(orderId, tableNo) {
    const modal = document.getElementById('billModal');
    const billItems = document.getElementById('billItems');
    const modalTitle = document.getElementById('modalTitle');
    const printBtn = document.getElementById('printBtn');

    modalTitle.innerText = 'รายละเอียดโต๊ะ ' + tableNo;
    billItems.innerHTML = '<div style="text-align:center; padding:20px;">กำลังโหลดรายการ...</div>';
    modal.style.display = 'block';
    
    // ดึงข้อมูลรายการจาก get_bill_items.php
    fetch('get_bill_items.php?order_id=' + orderId)
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.text();
        })
        .then(data => {
            billItems.innerHTML = data;
            
            // ตั้งค่าปุ่มเช็คบิล
            printBtn.onclick = function() {
                if(confirm('ยืนยันการพิมพ์บิลและปิดโต๊ะนี้?')) {
                    // เปิดหน้าปริ้น Thermal
                    const printWindow = window.open('print_thermal.php?order_id=' + orderId, '_blank');
                    
                    // เมื่อหน้าปริ้นถูกสั่ง (หรือปิดไป) ให้รีโหลดหน้าหลักเพื่ออัปเดตสถานะโต๊ะ
                    const timer = setInterval(function() { 
                        if(printWindow.closed) {
                            clearInterval(timer);
                            location.reload(); 
                        }
                    }, 500);
                }
            };
        })
        .catch(err => {
            billItems.innerHTML = '<div style="color:red; text-align:center;">เกิดข้อผิดพลาดในการดึงข้อมูล</div>';
        });
}

function closeModal() {
    document.getElementById('billModal').style.display = 'none';
}

// คลิกข้างนอก Modal ให้ปิด
window.onclick = function(event) {
    const modal = document.getElementById('billModal');
    if (event.target == modal) {
        closeModal();
    }
}