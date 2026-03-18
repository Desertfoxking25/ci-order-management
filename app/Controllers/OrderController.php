<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Services\OrderService;

class OrderController extends Controller {

    protected OrderService $orderService;

    public function __construct() {
        $this->orderService = new OrderService();
    }

    public function list() {
        return view('orders/orders');
    }

    public function index() {
        return $this->response->setJSON($this->orderService->getOrdersWithItems());
    }

    public function create() {
        $data = $this->request->getPost();
        $orderId = $this->orderService->createOrder($data);
        return $this->response->setJSON(['message'=>'Order created','order_id'=>$orderId]);
    }

    public function addItem($orderId) {
        try {
            $data = $this->request->getPost();

            $this->orderService->addItem(
                $orderId,
                $data['product_id'],
                intval($data['quantity'])
            );

            return $this->response->setJSON([
                'message' => 'Item added'
            ]);

        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'message' => $e->getMessage()
                ]);
        }
    }

    public function changeStatus($orderId) {
        try {
            $status = $this->request->getPost('status');

            $this->orderService->changeStatus($orderId, $status);

            return $this->response->setJSON([
                'message' => 'Status updated'
            ]);

        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'message' => $e->getMessage()
                ]);
        }
    }

    public function statusLog($orderId) {
        $logs = $this->orderService->getStatusLog($orderId);
        return $this->response->setJSON($logs);
    }
}