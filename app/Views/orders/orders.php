<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders Management</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        button { padding: 4px 8px; margin: 2px; cursor: pointer; }
        input, select { padding: 4px; margin: 2px; }
        .hidden { display: none; }
        .log { margin-top: 10px; font-size: 0.9em; background: #f9f9f9; padding: 5px; border: 1px solid #ddd; }
    </style>
</head>
<body>

<h1>Orders</h1>

<!-- Új rendelés -->
<div>
    <input type="text" id="customer_name" placeholder="Customer name">
    <button id="createOrderBtn">Create Order</button>
</div>

<!-- Rendelés lista -->
<table id="ordersTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Items</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script src="/js/main.js"></script>
</body>
</html>