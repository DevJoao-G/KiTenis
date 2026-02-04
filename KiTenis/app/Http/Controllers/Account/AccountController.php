<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        return view('account.index', [
            'user' => $request->user(),
        ]);
    }

    public function orders(Request $request)
    {
        $user = $request->user();

        $orders = Order::query()
            ->where('user_id', $user->id)
            ->latest('id')
            ->get();

        return view('account.orders.index', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    public function showOrder(Request $request, $order)
    {
        $user = $request->user();

        $orderModel = Order::query()
            ->where('user_id', $user->id)
            ->with(['items.product'])
            ->findOrFail($order);

        return view('account.orders.show', [
            'user'  => $user,
            'order' => $orderModel,
        ]);
    }
}
