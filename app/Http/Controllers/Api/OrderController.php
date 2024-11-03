<?php

namespace App\Http\Api\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all()->map(function ($order) {
            unset($order->customer);
            return $order;
        });
        return response()->json($orders);
    }
    public function store(OrderRequest $request)
    {
        $validData = $request->validated();
        $order = Order::create([
            "customer" => $validData["customer"],
        ]);
        foreach ($validData["items"] as ["category" => $category, "quantity" => $quantity,]) {
            $order->items()->create([
                "category" => $category,
                "quantity" => $quantity,
            ]);
        }
    }
    public function show($id)
    {
        return response()->json(Order::with('items')->findOrFail($id));
    }
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        // ? not required since items are deleted when order is deleted (specified on the item migration)
        // $order->items()->delete();

        $order->delete();
    }
}
