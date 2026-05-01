document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Preview รูปภาพตอนเลือกไฟล์
    const imgInput = document.getElementById('menu_image');
    const imgPreview = document.getElementById('img-preview');

    imgInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // 2. Logic ล็อกชนิดเมนู (ถ้าเลือกเครื่องดื่ม ให้ล็อกเป็นเครื่องดื่มทันที)
    const mainCat = document.getElementById('main_category');
    const subCat = document.getElementById('sub_category');

    mainCat.addEventListener('change', function() {
        const selected = this.value;
        subCat.innerHTML = ''; 

        if (selected === 'อาหาร') {
            // คืนค่าตัวเลือกสำหรับอาหาร
            const foodOptions = `
                <option value="เมนูต้ม">เมนูต้ม</option>
                <option value="เมนูทอด">เมนูทอด</option>
                <option value="เมนูทานเล่น">เมนูทานเล่น</option>
                <option value="อาหารจานเดียว">อาหารจานเดียว</option>
            `;
            subCat.innerHTML = foodOptions;
        } else if (selected === 'เครื่องดื่ม') {
            // ล็อกค่าเป็น 'เครื่องดื่ม' ตามที่สั่ง
            const drinkOptions = `<option value="เครื่องดื่ม" selected>เครื่องดื่ม</option>`;
            subCat.innerHTML = drinkOptions;
        }
    });
});