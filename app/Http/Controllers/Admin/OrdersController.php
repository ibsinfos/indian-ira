<?php

namespace IndianIra\Http\Controllers\Admin;

use IndianIra\Order;
use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;

class OrdersController extends Controller
{
    /**
     * Display all the orders.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $allOrders = $this->getAllOrders()->groupBy('order_code');

        return view('admin.orders.index', compact('allOrders'));
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

        return view('admin.orders.show_products', compact('orders'));
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

        return view('admin.orders.show_address', compact('orders', 'address'));
    }

    /**
     * Temporarily delete an order of the given order code.
     *
     * @param   string  $orderCode
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function delete($orderCode)
    {
        $orders = $this->getAllOrders()->where('order_code', $orderCode);
        if ($orders->isEmpty()) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Order with that code cannot be found!'
            ]);
        }

        $orders->each(function ($order) {
            $order->delete();
        });

        $allOrders = $this->getAllOrders()->groupBy('order_code');

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Order deleted successfully !',
            'htmlResult' => view('admin.orders.table', compact('allOrders'))->render()
        ]);
    }

    /**
     * Temporarily delete an order of the given order code.
     *
     * @param   string  $orderCode
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function restore($orderCode)
    {
        $orders = $this->getAllOrders()
                       ->where('order_code', $orderCode)
                       ->where('deleted_at', '<>', null);

        if ($orders->isEmpty()) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Order with that code cannot be found!'
            ]);
        }

        $orders->each(function ($order) {
            $order->restore();
        });

        $allOrders = $this->getAllOrders()->groupBy('order_code');

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Order restored successfully !',
            'htmlResult' => view('admin.orders.table', compact('allOrders'))->render()
        ]);
    }

    /**
     * Temporarily delete an order of the given order code.
     *
     * @param   string  $orderCode
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($orderCode)
    {
        $orders = $this->getAllOrders()
                       ->where('order_code', $orderCode)
                       ->where('deleted_at', '<>', null);

        if ($orders->isEmpty()) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Order with that code cannot be found!'
            ]);
        }

        $orders->first()->address()->delete();

        $orders->each(function ($order) {
            $order->forceDelete();
        });

        $allOrders = $this->getAllOrders()->groupBy('order_code');

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Order destroyed successfully !',
            'htmlResult' => view('admin.orders.table', compact('allOrders'))->render()
        ]);
    }

    /**
     * Get all the orders.
     *
     * @return  \Illuminate\Database\Eloquent\Collection
     */
    protected function getAllOrders()
    {
        return Order::withTrashed()
                    ->with(['address', 'user'])
                    ->orderBy('id', 'DESC')
                    ->get();
    }
}
