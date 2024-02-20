<?php

namespace Tests\Feature\Order;

use App\Managements\OrderManagement;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function testSingleOrderCreation()
    {
        $orderData = [
            'order_id' => '123',
            'order_reference' => 'ref123',
            'order_total' => 100.00,
            'order_vat_ex' => 20.00,
            'order_date' => now(),
            'customer' => [
                'email' => 'test@example.com',
            ],
            'orderItems' => [
                [
                    'sku' => 'sku123',
                    'qty_ordered' => 1,
                    'unit_price' => 100.00,
                    'unit_price_vat_exl' => 80.00,
                    'row_total' => 100.00,
                    'row_total_vat_exl' => 80.00,
                ],
            ],
        ];

        $orderManagement = new OrderManagement();
        $order = $orderManagement->save($orderData);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals('123', $order->order_id);
        $this->assertEquals('ref123', $order->order_reference);
        $this->assertEquals(100.00, $order->order_total);
        $this->assertEquals(20.00, $order->order_vat_ex);
        $this->assertInstanceOf(Customer::class, $order->customer);
        $this->assertEquals('test@example.com', $order->customer->email);
        $this->assertCount(1, $order->orderItems);
    }

    public function testMultipleOrdersCreation()
    {
        $ordersData = [
            [
                'order_id' => '123',
                'order_reference' => 'ref123',
                'order_total' => 100.00,
                'order_vat_ex' => 20.00,
                'order_date' => now(),
                'customer' => [
                    'email' => 'test1@example.com',
                ],
                'orderItems' => [
                    [
                        'sku' => 'sku123',
                        'qty_ordered' => 1,
                        'unit_price' => 100.00,
                        'unit_price_vat_exl' => 80.00,
                        'row_total' => 100.00,
                        'row_total_vat_exl' => 80.00,
                    ],
                ],
            ],
            [
                'order_id' => '456',
                'order_reference' => 'ref456',
                'order_total' => 200.00,
                'order_vat_ex' => 40.00,
                'order_date' => now(),
                'customer' => [
                    'email' => 'test2@example.com',
                ],
                'orderItems' => [
                    [
                        'sku' => 'sku456',
                        'qty_ordered' => 2,
                        'unit_price' => 100.00,
                        'unit_price_vat_exl' => 80.00,
                        'row_total' => 200.00,
                        'row_total_vat_exl' => 160.00,
                    ],
                ],
            ],
        ];

        $orderManagement = new OrderManagement();
        $errors = [];
        $orders = $orderManagement->saveMany($ordersData, $errors);

        $this->assertCount(2, $orders);
        $this->assertCount(0, $errors);
    }
}
