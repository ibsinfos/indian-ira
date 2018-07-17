<?php

namespace IndianIra\Http\Controllers\Admin;

use IndianIra\Order;
use IndianIra\OrderHistory;
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

        return view('admin.orders.show_history', compact(
            'orders', 'history', 'historyProducts'
        ));
    }

    /**
     * Update the order history.
     *
     * @param   string  $orderCode
     * @param   \Illuminate\Http\Request $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function storeHistory($orderCode, Request $request)
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

        $this->validate($request, [
            'select_products' => 'required',
            'notes'           => 'nullable|max:250',
            'status'          => 'required|in:Processing,Shipped,Completed,Cancelled',
        ]);

        $rowData = $request->select_products;

        if (! is_array($rowData)) {
            $rowData = [$request->select_products];
        }

        foreach ($rowData as $historyId) {
            $history = OrderHistory::find($historyId);

            OrderHistory::create([
                'order_id'              => $history->order_id,
                'order_code'            => $history->order_code,
                'user_id'               => $history->user_id,
                'user_full_name'        => $history->user_full_name,
                'user_email'            => $history->user_email,
                'product_id'            => $history->product_id,
                'product_code'          => $history->product_code,
                'product_name'          => $history->product_name,
                'product_option_id'     => $history->product_option_id,
                'product_option_code'   => $history->product_option_code,
                'shipping_company'      => $history->shipping_company_name,
                'shipping_tracking_url' => $history->shipping_tracking_url,
                'status'                => $request->status,
                'notes'                 => $request->notes,
            ]);
        }

        $history = $orders->first()->history()->orderBy('id', 'DESC')->get();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Order History added successfully...',
            'htmlResult' => view('admin.orders._history_table', compact('history'))->render()
        ]);
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

        $orders->first()->history->each(function ($history) {
            $history->delete();
        });

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
