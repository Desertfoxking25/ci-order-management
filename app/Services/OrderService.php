<?php

namespace App\Services;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ProductModel;
use App\Models\OrderStatusLogModel;
use CodeIgniter\Database\BaseConnection;

class OrderService {

    protected OrderModel $orderModel;
    protected OrderItemModel $itemModel;
    protected ProductModel $productModel;
    protected OrderStatusLogModel $statusLogModel;
    protected BaseConnection $db;

    public function __construct() {

        $this->orderModel = new OrderModel();
        $this->itemModel = new OrderItemModel();
        $this->productModel = new ProductModel();
        $this->statusLogModel = new OrderStatusLogModel();
        $this->db = \Config\Database::connect();
    }

    // Rendelés létrehozása
    public function createOrder(array $data): int {
        $data['status'] = 'draft';
        return $this->orderModel->insert($data);
    }

    // Rendelés listázása
    public function getOrders(): array {
        return $this->orderModel->findAll();
    }
}