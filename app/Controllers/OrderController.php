<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Services\OrderService;

class OrderController extends ResourceController {

    protected $format = 'json';
    protected OrderService $orderService;

    public function __construct() {
        $this->orderService = new OrderService();
    }

    public function create() {
        $data = $this->request->getPost();
        $orderId = $this->orderService->createOrder($data);
        return $this->respondCreated(['message' => 'Order created', 'order_id' => $orderId]);
    }

    public function index() {
        $orders = $this->orderService->getOrders();
        return $this->respond($orders);
    }

    public function addItem($orderId) {
        $data = $this->request->getPost();
        try {
            $this->orderService->addItem($orderId, $data['product_id'], intval($data['quantity']));
            return $this->respondCreated(['message' => 'Item added to order']);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function changeStatus($orderId) {
        try {
            $this->orderService->changeStatus($orderId, $this->request->getPost('status'));
            return $this->respond(['message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }
}