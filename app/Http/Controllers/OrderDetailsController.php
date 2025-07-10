<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderDetailsController extends Controller
{
    public function index(): JsonResponse
    {

        $orderDetails = OrderDetails::with(['barang'])->get();

        return response()->json($orderDetails);
    }

    public function show($id): JsonResponse
    {
        try {
            $order = OrderDetails::findOrFail($id);
            return response()->json($order, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Orders details tidak ditemukan'], 404);
        }
    }

   public function store(Request $request, $id_order)
{
    try {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:barang,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        $results = [];

        foreach ($data['items'] as $item) {
            $barang = Barang::findOrFail($item['id_barang']);
            $harga = $barang->harga;
            $subtotal = $harga * $item['jumlah'];

            $orderDetail = OrderDetails::create([
                'order_id' => $id_order, // pastikan field ini sesuai dengan nama kolom di DB
                'id_barang' => $item['id_barang'],
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $harga,
                'total' => $subtotal,
            ]);

            $results[] = $orderDetail;
        }

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Server Error: ' . $e->getMessage(),
            'trace' => $e->getTrace(),
        ], 500);
    }
}



    // Mengupdate data user
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $orderDetail = OrderDetails::findOrFail($id);

            $request->validate([
                'order_id' => 'sometimes|uuid',
                'id_barang' => 'sometimes|uuid',
                'jumlah' => 'sometimes|integer',
            ]);

            // Hanya update field yang dikirim
            $data = $request->only(['order_id', 'id_barang', 'jumlah']);

            $orderDetail->update($data);


            return response()->json([
                'message' => $orderDetail->wasChanged()
                    ? 'Data order berhasil diupdate.'
                    : 'Tidak ada perubahan pada data stock.',
                'data' => $orderDetail
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $order = OrderDetails::findOrFail($id);
            $order->delete();

            return response()->json(['message' => 'Order berhasil dihapus.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }
    }
}
