// Simple client-side cart implementation for Mini Treasures
(function(){
  function getCart(){
    return JSON.parse(localStorage.getItem('mt_cart') || '[]');
  }
  function saveCart(cart){
    localStorage.setItem('mt_cart', JSON.stringify(cart));
    updateCartCount();
  }
  function updateCartCount(){
    const cart = getCart();
    const totalQty = cart.reduce((s,i)=>s+(i.qty||0),0);
    const el = document.getElementById('cart-count');
    if(el) el.textContent = totalQty;
  }
  // User UI: welcome and logout
  function getCurrentUser(){
    const logged = localStorage.getItem('mt_loggedIn');
    const email = localStorage.getItem('mt_userEmail');
    if(logged && email) return { email };
    return null;
  }
  function updateUserUI(){
    const user = getCurrentUser();
    const welcomeEl = document.getElementById('user-welcome');
    const logoutBtn = document.getElementById('logout-btn');
    if(user && welcomeEl && logoutBtn){
      const users = JSON.parse(localStorage.getItem('mt_users') || '{}');
      const name = (users[user.email] && users[user.email].name) ? users[user.email].name : user.email;
      welcomeEl.textContent = `Welcome, ${name}`;
      welcomeEl.classList.remove('hidden');
      logoutBtn.classList.remove('hidden');
    } else {
      if(welcomeEl) welcomeEl.classList.add('hidden');
      if(logoutBtn) logoutBtn.classList.add('hidden');
    }
  }
  function logout(){
    localStorage.removeItem('mt_loggedIn');
    localStorage.removeItem('mt_userEmail');
    updateUserUI();
    // redirect to homepage
    window.location.href = 'index.html';
  }
  function addToCart(item){
    const cart = getCart();
    const existing = cart.find(i=>i.id === item.id);
    if(existing){ existing.qty += item.qty; }
    else cart.push(item);
    saveCart(cart);
  }

  // Render Add to Cart buttons for product cards
  function attachAddButtons(){
    document.querySelectorAll('.product-card, .group.relative').forEach(card=>{
      if(card.querySelector('.add-to-cart')) return; // already added
      // don't add add-to-cart controls for guests (not logged in)
      if(!getCurrentUser()) return;
      const titleEl = card.querySelector('h3');
      const priceEl = card.querySelector('p.text-sm.font-medium');
      const imgEl = card.querySelector('img');
      const container = card.querySelector('.mt-4.flex.justify-between');
      if(!container || !titleEl || !priceEl) return;
      const title = titleEl.textContent.trim();
      const priceText = priceEl.textContent.trim();
      const price = parseFloat(priceText.replace(/[^0-9.]/g,'')) || 0;
      const img = imgEl ? imgEl.src : '';
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'ml-3 px-3 py-1 text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 add-to-cart';
      btn.textContent = 'Add to cart';
      btn.addEventListener('click', ()=>{
        const id = (title + '|' + price).replace(/\s+/g,'_');
        addToCart({ id, title, price, image: img, qty: 1 });
        btn.textContent = 'Added';
        setTimeout(()=>btn.textContent = 'Add to cart',1200);
      });
      // Ensure button is clickable above any absolute overlays
      btn.style.position = 'relative';
      btn.style.zIndex = '50';
      btn.style.pointerEvents = 'auto';
      // append to right side (price area)
      container.appendChild(btn);
    });
  }

  // Render cart page content
  function renderCartPage(){
    if(!document.body.classList.contains('bg-gray-50')) return; // basic page check
    const isCart = location.pathname.endsWith('cart.html') || location.pathname.endsWith('/cart.html');
    if(!isCart) return;
    const root = document.querySelector('.max-w-7xl');
    if(!root) return;
    const cart = getCart();
    if(cart.length===0){
      root.innerHTML = `
        <div class="mb-6">
          <button id="cart-back-btn" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200">← Back</button>
        </div>
        <h1 class="text-3xl font-extrabold">Your cart is empty</h1>
        <p class="mt-4">Browse products and add items to your cart.</p>
        <div class="mt-6">
          <a href="shop.html" class="inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-md">Continue shopping</a>
        </div>
      `;
      // attach back handler for empty cart
      const backBtnEmpty = document.getElementById('cart-back-btn');
      if(backBtnEmpty){
        backBtnEmpty.addEventListener('click', (e)=>{
          e.preventDefault();
          try{ if(window.history && window.history.length>1) window.history.back(); else window.location.href = 'index.html'; }
          catch(err){ window.location.href = 'index.html'; }
        });
      }
      return;
    }
    let subtotal = 0;
    const rows = cart.map((it,idx)=>{
      const line = it.price * it.qty; subtotal += line;
      return `
        <div class="flex items-center py-4 border-b">
          <img src="${it.image}" class="w-20 h-20 object-cover rounded mr-4">
          <div class="flex-1">
            <div class="font-semibold">${it.title}</div>
            <div class="text-sm text-gray-500">$${it.price.toFixed(2)}</div>
          </div>
          <div class="w-40 flex items-center">
            <input data-idx="${idx}" type="number" min="1" value="${it.qty}" class="qty-input border px-2 py-1 w-20 mr-2">
            <button data-idx="${idx}" class="remove-btn text-sm text-red-600">Remove</button>
          </div>
          <div class="w-32 text-right font-medium">$${line.toFixed(2)}</div>
        </div>
      `;
    }).join('');

    const fee = 2.50; // flat shipping
    const total = subtotal + fee;

    root.innerHTML = `
      <div class="mb-6">
        <button id="cart-back-btn" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200">← Back</button>
      </div>
      <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 mb-8">Your Cart</h1>
      <div class="bg-white p-6 rounded shadow">
        ${rows}
        <div class="flex justify-between items-center mt-6">
          <div class="text-lg font-medium">Subtotal</div>
          <div class="text-lg font-semibold">$${subtotal.toFixed(2)}</div>
        </div>
        <div class="flex justify-between items-center mt-2">
          <div class="text-sm text-gray-500">Shipping</div>
          <div class="text-sm">$${fee.toFixed(2)}</div>
        </div>
        <div class="flex justify-between items-center mt-4 border-t pt-4">
          <div class="text-xl font-bold">Total</div>
          <div class="text-xl font-extrabold">$${total.toFixed(2)}</div>
        </div>
        <div class="mt-6">
          <h3 class="text-lg font-medium mb-2">Checkout</h3>
          <form id="checkout-form" class="grid grid-cols-1 gap-3">
            <input id="checkout-name" type="text" placeholder="Full name" required class="px-3 py-2 border rounded">
            <input id="checkout-email" type="email" placeholder="Email" required class="px-3 py-2 border rounded">
            <button id="checkout-btn" class="mt-2 px-4 py-2 bg-pink-600 text-white rounded">Place Order</button>
          </form>
        </div>
      </div>
    `;

    // attach handlers
    const backBtn = document.getElementById('cart-back-btn');
    if(backBtn){
      backBtn.addEventListener('click', (e)=>{
        e.preventDefault();
        try{
          if(window.history && window.history.length>1){
            window.history.back();
          } else {
            window.location.href = 'index.html';
          }
        }catch(err){
          window.location.href = 'index.html';
        }
      });
    }
    root.querySelectorAll('.qty-input').forEach(input=>{
      input.addEventListener('change', (e)=>{
        const idx = parseInt(input.getAttribute('data-idx'));
        let v = parseInt(input.value)||1; if(v<1) v=1; input.value=v;
        const c = getCart(); c[idx].qty = v; saveCart(c); renderCartPage();
      });
    });
    root.querySelectorAll('.remove-btn').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const idx = parseInt(btn.getAttribute('data-idx'));
        const c = getCart(); c.splice(idx,1); saveCart(c); renderCartPage();
      });
    });

    const checkoutForm = document.getElementById('checkout-form');
    if(checkoutForm){
      checkoutForm.addEventListener('submit', (e)=>{
        e.preventDefault();
        const name = document.getElementById('checkout-name').value.trim();
        // prefer logged-in user's email if present to ensure orders show up in their account
        const loggedEmail = localStorage.getItem('mt_userEmail');
        let email = document.getElementById('checkout-email').value.trim();
        if(loggedEmail) email = loggedEmail;
        email = (email || '').toLowerCase();
        const orderId = 'MT-' + Date.now();
        // snapshot cart items before clearing
        const cartItems = getCart();
        const receipt = {
          id: orderId,
          name, email,
          items: cartItems,
          subtotal, fee, total
        };
        // persist order into mt_orders so account/admin can display it
        try{
          const orders = JSON.parse(localStorage.getItem('mt_orders')||'[]');
          // set a seller (store) and an estimated delivery date (5 days from now)
          const seller = localStorage.getItem('mt_seller_email') || 'MiniTreasures@websystem.com';
          const deliveryDate = new Date(Date.now() + 5*24*60*60*1000).toISOString();
          const orderRecord = { id: receipt.id, email: (receipt.email||'').toLowerCase(), name: receipt.name, items: receipt.items, subtotal: receipt.subtotal, fee: receipt.fee, total: receipt.total, status: 'paid', date: new Date().toISOString(), seller, deliveryDate };
          orders.push(orderRecord);
          localStorage.setItem('mt_orders', JSON.stringify(orders));
          // attach seller/delivery info to the runtime receipt we show immediately
          receipt.seller = seller;
          receipt.deliveryDate = deliveryDate;
        }catch(err){ console.warn('Failed to save order', err); }
        // clear cart
        saveCart([]);
        renderReceipt(receipt);
      });
    }
  }

  function renderReceipt(receipt){
    const root = document.querySelector('.max-w-7xl');
    if(!root) return;
    const itemsHtml = receipt.items.map(it=>`<div class="flex justify-between py-1"><div>${it.title} x${it.qty}</div><div>$${(it.price*it.qty).toFixed(2)}</div></div>`).join('');
    const dateStr = (new Date()).toLocaleString();
    const eta = receipt.deliveryDate ? new Date(receipt.deliveryDate).toLocaleString() : '';
    const seller = receipt.seller || 'MiniTreasures@websystem.com';
    root.innerHTML = `
      <h1 class="text-3xl font-extrabold">Order Confirmed</h1>
      <div class="mt-4 bg-white p-6 rounded shadow grid gap-6 md:grid-cols-2">
        <div>
          <div class="font-medium">Order ID: ${receipt.id}</div>
          <div class="text-sm text-gray-600">Placed: ${dateStr}</div>
          <div class="text-sm text-gray-600">Name: ${receipt.name} — ${receipt.email}</div>
          <div class="mt-4 border-t pt-4">
            <h3 class="font-semibold mb-2">Items</h3>
            <div class="space-y-2">${itemsHtml}</div>
          </div>
          <div class="mt-4 border-t pt-4">
            <div class="text-sm text-pink-600">Delivery ETA: ${eta || 'TBD'}</div>
            <div class="text-xs text-gray-400">Seller: ${seller}</div>
          </div>
        </div>
        <div>
          <div class="p-4 bg-gray-50 rounded">
            <div class="flex justify-between"><div class="text-sm">Subtotal</div><div class="font-medium">$${receipt.subtotal.toFixed(2)}</div></div>
            <div class="flex justify-between mt-2"><div class="text-sm">Shipping</div><div class="font-medium">$${receipt.fee.toFixed(2)}</div></div>
            <div class="border-t mt-4 pt-4 flex justify-between items-center"><div class="text-lg font-bold">Total</div><div class="text-xl font-extrabold">$${receipt.total.toFixed(2)}</div></div>
            <div class="mt-6 flex flex-col gap-2">
              <button id="print-receipt" class="px-4 py-2 bg-pink-600 text-white rounded">Print / Save</button>
              <button id="download-receipt" class="px-4 py-2 bg-white border rounded">Download Receipt</button>
              <a href="receipt.html?id=${receipt.id}" id="open-invoice-btn" class="px-4 py-2 bg-white border rounded text-center">Open Invoice</a>
              <a href="account.html?tab=purchases&order=${receipt.id}" id="view-orders-btn" class="px-4 py-2 bg-white border rounded text-center">View My Orders</a>
              <a href="index.html" class="px-4 py-2 bg-gray-200 rounded text-center">Continue Shopping</a>
            </div>
          </div>
        </div>
      </div>
    `;
    const printBtn = document.getElementById('print-receipt');
    if(printBtn) printBtn.addEventListener('click', ()=>window.print());
    const downloadBtn = document.getElementById('download-receipt');
    if(downloadBtn){
      downloadBtn.addEventListener('click', ()=>{
        const lines = [];
        lines.push(`Order ID: ${receipt.id}`);
        lines.push(`Name: ${receipt.name}`);
        lines.push(`Email: ${receipt.email}`);
        lines.push('');
        lines.push('Items:');
        receipt.items.forEach(it=>{
          lines.push(`${it.title} x${it.qty} — $${(it.price*it.qty).toFixed(2)}`);
        });
        lines.push('');
        lines.push(`Subtotal: $${receipt.subtotal.toFixed(2)}`);
        lines.push(`Shipping: $${receipt.fee.toFixed(2)}`);
        lines.push(`Total: $${receipt.total.toFixed(2)}`);
        const blob = new Blob([lines.join('\n')], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${receipt.id}_receipt.txt`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
      });
    }
    updateCartCount();
  }

  // init
  document.addEventListener('DOMContentLoaded', ()=>{
    updateCartCount();
    updateUserUI();
    const logoutBtn = document.getElementById('logout-btn');
    if(logoutBtn) logoutBtn.addEventListener('click', logout);
    attachAddButtons();
    renderCartPage();
  });

})();
