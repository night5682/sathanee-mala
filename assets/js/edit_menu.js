function previewImage(input) {
    const preview = document.getElementById('imgPreview');
    const text = document.getElementById('uploadText');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            text.innerHTML = '<i class="fa-solid fa-check-circle"></i> เลือกรูปภาพใหม่แล้ว';
            text.style.color = '#28a745';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}