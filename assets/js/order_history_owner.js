document.addEventListener('DOMContentLoaded', function() {
    
    // ฟังก์ชันเปิด-ปิดแผงค้นหา
    const filterBtn = document.getElementById('toggleFilterBtn'); // เพิ่ม ID นี้ที่ปุ่มใน PHP
    const filterPanel = document.getElementById('filter-panel');

    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            if (filterPanel.style.display === 'none' || filterPanel.style.display === '') {
                filterPanel.style.display = 'block';
            } else {
                filterPanel.style.display = 'none';
            }
        });
    }

    // ทำ Highlight แถวที่เมาส์วาง (Fallback สำหรับเบราว์เซอร์เก่า)
    const rows = document.querySelectorAll('.history-table tbody tr');
    rows.forEach(row => {
        row.addEventListener('mouseenter', () => {
            row.style.cursor = 'pointer';
        });
    });
});