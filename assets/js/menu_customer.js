let cart = [];

function toggleSidebar() {
    const sb = document.getElementById('sidebar');
    const ov = document.getElementById('overlay');
    if (sb && ov) { sb.classList.toggle('active'); ov.classList.toggle('active'); }
}

function addToCart(id, name, price, event) {
    const idx = cart.findIndex(item => item.id === id);
    if (idx > -1) { cart[idx].qty += 1; } 
    else { cart.push({ id, name, price, qty: 1 }); }

    const img = document.getElementById(`img-${id}`);
    if (img) {
        const fly = img.cloneNode();
        const rect = img.getBoundingClientRect();
        fly.className = 'fly-item';
        fly.style.top = rect.top + 'px';
        fly.style.left = rect.left + 'px';
        document.body.appendChild(fly);
        setTimeout(() => {
            fly.style.top = (window.innerHeight - 50) + 'px';
            fly.style.left = (window.innerWidth / 2) + 'px';
            fly.style.width = '20px'; fly.style.opacity = '0';
        }, 10);
        setTimeout(() => fly.remove(), 800);
    }
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
            <div style="flex: 1;"><strong>${item.name}</strong><br><small>${item.price.toLocaleString()}.-</small></div>
            <div class="qty-control">
                <button class="btn-qty" onclick="changeQty(${index}, -1)">-</button>
                <span class="qty-num">${item.qty}</span>
                <button class="btn-qty" onclick="changeQty(${index}, 1)">+</button>
            </div>
            <div style="width: 70px; text-align: right; font-weight: 800;">${(item.price * item.qty).toLocaleString()}</div>
            <button class="btn-remove-item" onclick="changeQty(${index}, -999)">&times;</button>
        </div>
    `).join('');
    const total = cart.reduce((s, i) => s + (i.price * i.qty), 0);
    document.getElementById('modal-total').innerText = total.toLocaleString();
    document.getElementById('order-modal').style.display = 'flex';
}

function closeOrderDetails() { document.getElementById('order-modal').style.display = 'none'; }
function submitOrder() { alert("สั่งอาหารสำเร็จ!"); cart = []; updateCartBar(); closeOrderDetails(); }