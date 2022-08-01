<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Order;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'address' => [
                'required'
            ],
            'weight' => [
                'required'
            ],
            'type' => [
                'required'
            ],
            'pickup_date' => [
                'required'
            ],
        ]);

        if ($validate->fails()) {
            return ResponseFormatter::error(401, 'Validation errors', $validate->errors());
        }

        try {
            $order = Order::create([
                'user_id' => auth()->user()->id,
                'address' => $request->address,
                'weight' => $request->weight,
                'type' => $request->type,
                'pickup_date' => $request->pickup_date,
                'status' => 'Menunggu konfirmasi'
            ]);

            return ResponseFormatter::success('Success store order', $order);
        } catch (QueryException $error) {
            return ResponseFormatter::error(500, 'Query Error', $error);
        }
    }

    public function approved($id)
    {
        try {
            $order = Order::where('id', $id)
                ->update([
                    'status' => 'Sedang diproses'
                ]);
    
            return ResponseFormatter::success('Update order', $order);
        } catch (QueryException $error) {
            return ResponseFormatter::error(500, 'Query Error', $error);
        }
    }

    public function rejected($id)
    {
        try {
            $order = Order::where('id', $id)
                ->update([
                    'status' => 'Dibatalkan'
                ]);
    
            return ResponseFormatter::success('Update order', $order);
        } catch (QueryException $error) {
            return ResponseFormatter::error(500, 'Query Error', $error);
        }
    }
    
    public function finished($id)
    {
        try {
            $order = Order::where('id', $id)
                ->update([
                    'status' => 'Selesai'
                ]);
    
            return ResponseFormatter::success('Update order', $order);
        } catch (QueryException $error) {
            return ResponseFormatter::error(500, 'Query Error', $error);
        }
    }

    public function showAll()
    {
        try {
            $order = Order::orderBy('created_at', 'DESC')
                ->with('user')
                ->get();

            return ResponseFormatter::success('Show all orders', $order);
        } catch (QueryException $error) {
            return ResponseFormatter::error(500, 'Query Error', $error);
        }        
    }

    public function showByOrderID($id)
    {
        try {
            $order = Order::where('id', $id)
                ->with('user')
                ->first();
            
            return ResponseFormatter::success('Show order where id = ' . $id, $order);
        } catch (QueryException $error) {
            return ResponseFormatter::error(500, 'Query Error', $error);
        }
    }

    public function showByUserID($user_id)
    {
        try {
            $order = Order::where('user_id', $user_id)
                ->orderBy('created_at', 'DESC')
                ->with('user')
                ->get();
            
            return ResponseFormatter::success('Show order where user id = ' . $user_id, $order);
        } catch (QueryException $error) {
            return ResponseFormatter::error(500, 'Query Error', $error);
        }
    }

    public function showByStatus($status)
    {
        try {
            $order = Order::where('status', $status)
                ->orderBy('created_at', 'DESC')
                ->with('user')
                ->get();

            return ResponseFormatter::success('Show order where status = ' . $status, $order);
        } catch (QueryException $error) {
            return ResponseFormatter::error(500, 'Query Error', $error);
        }
    }

    public function deleted($id)
    {
        try {
            $order = Order::where('id', $id)
                ->delete();

            return ResponseFormatter::success('Delete order where id = ' . $id, $order);
        } catch (QueryException $error) {
            return ResponseFormatter::error(500, 'Query Error', $error);
        }
        
    }
}
