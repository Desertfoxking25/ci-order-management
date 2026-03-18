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

     // Termék hozzáadása
    public function addItem(int $orderId, int $productId, int $quantity) {

        if ($quantity <= 0) {
            throw new \Exception('Quantity must be positive');
        }

        $order = $this->orderModel->find($orderId);
        if (!$order) throw new \Exception('Order not found');
        if ($order['status'] !== 'draft') throw new \Exception('Cannot modify this order');

        $product = $this->productModel->find($productId);
        if (!$product || !$product['is_active']) throw new \Exception('Product inactive or not found');

        $existingItem = $this->itemModel->where(['order_id' => $orderId, 'product_id' => $productId])->first();
        if ($existingItem) throw new \Exception('Product already in order');

        return $this->itemModel->insert([
            'order_id'   => $orderId,
            'product_id' => $productId,
            'quantity'   => $quantity,
            'price_net'  => $product['price_net']
        ]);
    }

    // Státuszváltás kezelése
    public function changeStatus(int $orderId, string $newStatus) {

        $order = $this->orderModel->find($orderId);
        if (!$order) throw new \Exception('Order not found');

        $currentStatus = $order['status'];

        $this->db->transStart();

        try {
            if ($currentStatus === 'draft' && $newStatus === 'submitted') {
                $this->submitOrder($orderId);
            } elseif ($currentStatus === 'submitted' && $newStatus === 'paid') {
                $this->orderModel->update($orderId, ['status' => 'paid']);
            } elseif (in_array($newStatus, ['cancelled'])) {
                $this->cancelOrder($orderId, $currentStatus);
            } else {
                throw new \Exception("Cannot change status from $currentStatus to $newStatus");
            }

            // Státusznaplózás
            $this->logStatusChange($orderId, $currentStatus, $newStatus);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Failed to update status');
            }

        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    // ----- Private helper methods -----
    private function submitOrder(int $orderId) {

        $items = $this->itemModel->where('order_id', $orderId)->findAll();

        foreach ($items as $item) {
            $product = $this->productModel->find($item['product_id']);
            if ($product['stock_quantity'] < $item['quantity']) {
                throw new \Exception("Not enough stock for product {$product['name']}");
            }
        }

        // Készletfoglalás
        foreach ($items as $item) {
            $product = $this->productModel->find($item['product_id']);
            $this->productModel->update($item['product_id'], [
                'stock_quantity' => $product['stock_quantity'] - $item['quantity']
            ]);
        }

        $this->orderModel->update($orderId, ['status' => 'submitted']);
    }

    private function cancelOrder(int $orderId, string $currentStatus) {

        if ($currentStatus === 'draft') {
            $this->orderModel->update($orderId, ['status' => 'cancelled']);
        } elseif ($currentStatus === 'submitted') {
            $items = $this->itemModel->where('order_id', $orderId)->findAll();
            foreach ($items as $item) {
                $product = $this->productModel->find($item['product_id']);
                $this->productModel->update($item['product_id'], [
                    'stock_quantity' => $product['stock_quantity'] + $item['quantity']
                ]);
            }
            $this->orderModel->update($orderId, ['status' => 'cancelled']);
        } elseif ($currentStatus === 'cancelled') {
            throw new \Exception('Order already cancelled');
        }
    }

    private function logStatusChange(int $orderId, string $fromStatus, string $toStatus) {

        $this->statusLogModel->insert([
            'order_id'    => $orderId,
            'from_status' => $fromStatus,
            'to_status'   => $toStatus,
            'changed_at'  => date('Y-m-d H:i:s')
        ]);
    }
}