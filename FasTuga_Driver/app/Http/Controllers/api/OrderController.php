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
        $orders = Order::where('status', '!=', 'C', 'and', 'status', 'D')->get()->sortBy('distance');
        return OrderResource::collection($orders);
    }

    public function update_accepted(Request $request, Order $order, User $user) {

        $order->driver_id = $user->id;
        $order->accepted = 1;
        $order->save();

        return new OrderResource($order);
    }

    public function getOrdersOfUser(User $user)
    {
        return OrderResource::collection($user->orders);
    }

    public function getOrdersInProgressOfUser(User $user)
    {
        return OrderResource::collection($user->orders()->where('status', 'in', 'P, R')->get());
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }
}
