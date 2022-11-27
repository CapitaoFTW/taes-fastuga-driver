<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::all();
        return OrderResource::collection($orders);
    }

    public function show(Request $request, $id)
    {
        $order = Order::find($id);
        return new OrderResource($order);
    }

    public function getOrdersOfUser(User $user)
    {
        return OrderResource::collection($user->orders);
 
    }
}
