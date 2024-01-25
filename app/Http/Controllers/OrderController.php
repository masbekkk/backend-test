<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessOrder;
use App\Models\Order;
use App\Models\OrderDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_user_id' => ['required', 'integer'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'integer'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            $order = Order::create(['customer_user_id' => $request->customer_user_id]);

            // dd($request->items);
            $orderDetails = [];
            foreach ($request->items as $key => $value) {
                $orderDetails[] = new OrderDetail($value);
            }

            $order->details()->saveMany($orderDetails);

            ProcessOrder::dispatchSync($order);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully!',
                'data' => $order,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Error creating order: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create order',
                'data' => null,
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
