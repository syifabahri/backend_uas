<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Barang;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderDetailsController extends Controller
{
    public function index(): JsonResponse
    {

        $orderDetails = OrderDetails::with(['barang'])->get();

        return response()->json($orderDetails);
    }
    public function show($order_id): JsonResponse
    {
        $details = OrderDetails::with('barang') // optional: eager load relasi barang
            ->where('order_id', $order_id)
            ->get();

        if ($details->isEmpty()) {
            return response()->json(['message' => 'Order details tidak ditemukan'], 404);
        }

        return response()->json($details, 200);
    }



public function store(Request $request, $orderId)
{
    $items = $request->input('items', []);

    foreach ($items as $item) {
        OrderDetails::create([
            'order_id' => $orderId,
            'id_barang' => $item['id_barang'],
            'jumlah' => $item['jumlah'],
        ]);
    }

    return response()->json(['message' => 'Order details berhasil disimpan']);
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

    public function getDetailsByOrderId($orderId): JsonResponse
    {
        try {
            $details = OrderDetails::with('barang') // relasi ke tabel barang
                ->where('order_id', $orderId)
                ->get();

            return response()->json($details, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengambil detail order'], 500);
        }
    }
}
