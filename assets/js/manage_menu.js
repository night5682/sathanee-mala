document.addEventListener('DOMContentLoaded', function() {
    // ระบบคลิกดาวแบบไม่ต้องโหลดหน้าใหม่ (Optional - ถ้าพี่เขียน API รองรับ)
    // แต่ตอนนี้เน้นดักจับการกดลบเพื่อให้ชัวร์ขึ้น
    const deleteBtns = document.querySelectorAll('.delete-btn');
    
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if(!confirm('รายการนี้จะถูกลบถาวร ยืนยันไหมครับพี่?')) {
                e.preventDefault();
            }
        });
    });

    // ทำ Effect ให้ดาวเวลาเอาเมาส์ไปวาง
    const starBtns = document.querySelectorAll('.star-btn');
    starBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'rotate(15deg) scale(1.2)';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'rotate(0) scale(1)';
        });
    });
});