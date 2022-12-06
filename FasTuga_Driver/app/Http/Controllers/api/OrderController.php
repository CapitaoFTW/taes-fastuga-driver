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
    public function index()
    {
        $orders = Order::get()->sortBy('distance');

        return OrderResource::collection($orders);
    }

    public function update_accepted(Order $order, User $user)
    {
        $order->driver_id = $user->id;
        $order->accepted = 1;
        $order->save();

        return new OrderResource($order);
    }

    public function update_status(Request $request, Order $order)
    {
        $order->status = $request['status'];
        $order->save();

        return new OrderResource($order);
    }

    public function update_delivered(Order $order, User $user)
    {
        $order->delivered = 1;
        $order->status = 'D';
        $order->save();

        if ($order->distance <= 3)
        $user->balance += 2;

        else if ($order->distance <= 10)
        $user->balance += 3;

        else
        $user->balance += 4;

        $user->save();

        return new OrderResource($order);
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }
}
