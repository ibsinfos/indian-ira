<?php

namespace IndianIra\Http\Controllers\Users;

use IndianIra\Order;
use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;

class OrdersController extends Controller
{
    /**
     * Display the orders which are placed by the authenticated user.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        $allOrders = $user->orders->groupBy('order_code');

        return view('users.orders.index', compact(
            'user', 'allOrders'
        ));
    }

    /**
     * Display the product details of the given order code.
     *
     * @param   string $orderCode
     * @return  \Illuminate\View\View
     */
    public function showProducts($orderCode)
    {
        $orders = $this->getAllOrders()->where('order_code', $orderCode);
        if ($orders->isEmpty()) {
            abort(404);
        }

        return view('users.orders.show_products', compact('orders'));
    }

    /**
     * Display the address details of the given order code.
     *
     * @param   string $orderCode
     * @return  \Illuminate\View\View
     */
    public function showAddress($orderCode)
    {
        $orders = $this->getAllOrders()->where('order_code', $orderCode);
        if ($orders->isEmpty()) {
            abort(404);
        }

        $address = $orders->first()->address;

        return view('users.orders.show_address', compact('orders', 'address'));
    }

    /**
     * Display the address details of the given order code.
     *
     * @param   string $orderCode
     * @return  \Illuminate\View\View
     */
    public function showHistory($orderCode)
    {
        $orders = $this->getAllOrders()->where('order_code', $orderCode);
        if ($orders->isEmpty()) {
            abort(404);
        }

        $history = $orders->first()->history()->orderBy('id', 'DESC')->get();

        $historyProducts = collect();
        foreach ($history as $data) {
            $details = $data->product_code;
            $details .= ' / '.$data->product_option_code;
            $details .= ' - '. title_case($data->product_name);

            if (! $historyProducts->contains($details)) {
                $historyProducts->put($data->id, $details);
            }
        }

        return view('users.orders.show_history', compact(
            'orders', 'history', 'historyProducts'
        ));
    }

    /**
     * Get all the orders of the authenticated user.
     *
     * @return  \Illuminate\Database\Eloquent\Collection
     */
    protected function getAllOrders()
    {
        return Order::withTrashed()
                    ->with(['address', 'user'])
                    ->orderBy('id', 'DESC')
                    ->whereUserId(auth()->id())
                    ->get();
    }
}
