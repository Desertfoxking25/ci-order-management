const API_URL = '/order';

// --- Lista betöltése ---
async function loadOrders() {
    const res = await fetch(API_URL);
    const orders = await res.json();
    const tbody = document.querySelector('#ordersTable tbody');
    tbody.innerHTML = '';

    orders.forEach(order => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${order.id}</td>
            <td>${order.customer_name}</td>
            <td>${order.status}</td>
            <td>
                ${order.items && order.items.length
                    ? order.items.map(i => `${i.name} (${i.quantity})`).join('<br>')
                    : '-'}
            </td>
            <td>${order.created_at}</td>
            <td>
                <button onclick="addItemPrompt(${order.id})">Add Item</button>
                <button onclick="changeStatusPrompt(${order.id})">Change Status</button>
                <button onclick="showLog(${order.id}, this)">Show Log</button>
                <div class="log hidden"></div>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// --- Új rendelés létrehozása ---
document.getElementById('createOrderBtn').addEventListener('click', async () => {
    const customer_name = document.getElementById('customer_name').value.trim();
    if (!customer_name) return alert('Customer name required');

    const res = await fetch(`${API_URL}/create`, {
        method: 'POST',
        body: new URLSearchParams({ customer_name })
    });
    const data = await res.json();
    alert(data.message);
    loadOrders();
});

// --- Tétel hozzáadása ---
async function addItemPrompt(orderId) {
    const product_id = prompt('Enter product ID:');
    const quantity = prompt('Enter quantity:');

    if (!product_id || !quantity) return;
    const res = await fetch(`${API_URL}/addItem/${orderId}`, {
        method: 'POST',
        body: new URLSearchParams({ product_id, quantity })
    });
    const data = await res.json();

    if (!res.ok) {
        alert(data.message || 'Error occurred');
        return;
    }

    alert(data.message);
    loadOrders();
}

// --- Státuszváltás ---
async function changeStatusPrompt(orderId) {
    const status = prompt('Enter new status (draft, submitted, paid, cancelled):');
    if (!status) return;

    const res = await fetch(`${API_URL}/changeStatus/${orderId}`, {
        method: 'POST',
        body: new URLSearchParams({ status })
    });
    const data = await res.json();

    if (!res.ok) {
        alert(data.message || 'Error occurred');
        return;
    }
    
    alert(data.message);
    loadOrders();
}

// --- Státusznapló megjelenítése ---
async function showLog(orderId, btn) {
    const logDiv = btn.nextElementSibling;
    if (!logDiv.classList.contains('hidden')) {
        logDiv.classList.add('hidden');
        return;
    }

    const res = await fetch(`${API_URL}/status-log/${orderId}`);
    const logs = await res.json();
    logDiv.innerHTML = logs.map(l => `From: ${l.from_status} → To: ${l.to_status} at ${l.changed_at}`).join('<br>');
    logDiv.classList.remove('hidden');
}

// --- Inicializálás ---
loadOrders();