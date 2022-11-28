<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreUpdateOrderRequest;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::all();
        return OrderResource::collection($orders);
    }

    public function getOrdersOfUser(User $user)
    {
        return OrderResource::collection($user->orders);
    }

    public function getOrdersInProgressOfUser(User $user)
    {
        return OrderResource::collection($user->orders()->where('status', 'in', 'P, R',)->get());
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    public function store(StoreUpdateOrderRequest $request)
    {
        $newOrder = Order::create($request->validated());
        return new OrderResource($newOrder);
    }

    public function update(StoreUpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());
        return new OrderResource($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return new OrderResource($order);
    }
}
