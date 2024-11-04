<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Services\RabbitMQService;

class OrderController extends Controller
{
    public function __construct(private RabbitMQService $rabbitMQService) {}
    public function index()
    {
        $orders = Order::all()->map(function ($order) {
            unset($order->created_at);
            unset($order->updated_at);
            $order->price = 0;

            return $order;
        });

        return response()->json($orders);
    }

    public function store(OrderRequest $request)
    {
        $validData = $request->validated();
        $order = Order::create([
            'customer' => $validData['customer'],
        ]);
        if (!$order) {
            return response()->json(['message' => 'Order not created'], 500);
        }
        foreach ($validData['items'] as ['category' => $category, 'quantity' => $quantity]) {
            $order->items()->create([
                'category' => $category,
                'quantity' => $quantity,
            ]);
        }
        // $this->rabbitMQService->sendMessage($order->id);
        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order,
        ]);
    }

    public function show($id)
    {
        $order = Order::with('items')->find($id);
        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        foreach ($order->items as $item) {
            unset($item->created_at);
            unset($item->updated_at);
        }

        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // ? not required since items are deleted when order is deleted (specified on the item migration - cascade on delete)
        // $order->items()->delete();

        $order->delete();
    }
}
