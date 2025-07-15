<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {

        $orders = Order::with(['customer'])->get();

        return response()->json($orders);
    }

    public function show($id): JsonResponse
    {
        try {
            $order = Order::with(['customer', 'details.barang'])->findOrFail($id);

            return response()->json($order, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }
    }

    // Menambahkan user baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customer,id',
            'total' => 'required|integer',
            'order_date' => 'required|date',
        ]);

        $order = Order::create($data);
        $order->load('customer');

        return response()->json($order);
    }

    // Mengupdate data user
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $order = Order::findOrFail($id);

            $request->validate([
                'customer_id' => 'sometimes|string|max:255',
                'order_date' => 'sometimes|string|date',
                'total' => 'sometimes|integer',
            ]);

            // Hanya update field yang dikirim
            $data = $request->only(['customer_id', 'order_date', 'total']);

            $order->update($data);


            return response()->json([
                'message' => $order->wasChanged()
                    ? 'Data order berhasil diupdate.'
                    : 'Tidak ada perubahan pada data stock.',
                'data' => $order
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return response()->json(['message' => 'Order berhasil dihapus.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }
    }

    public function updateTotal(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->total = $request->input('total');
        $order->save();

        return response()->json(['message' => 'Order total updated']);
    }

        public function count()
    {
        $count = \App\Models\Order::count();

        return response()->json([
            'total' => $count
        ]);
    }

    
}
