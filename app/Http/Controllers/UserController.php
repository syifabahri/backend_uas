<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class UserController extends Controller
{
    /**
     * Menampilkan semua data user
     */
    public function index(): JsonResponse
    {
        $dataUser = User::all();
        return response()->json($dataUser, 200);
    }

    /**
     * Menampilkan detail user berdasarkan ID
     */
    public function show($user_id): JsonResponse
    {
        try {
            $user = User::findOrFail($user_id);
            return response()->json($user, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }
    }

    /**
     * Menambahkan user baru
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|string|unique:users,user_id',
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'membership_date' => 'required|date',
        ]);

        $user = User::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'username' => strtolower($request->username),
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'membership_date' => $request->membership_date,
        ]);

        return response()->json([
            'message' => 'Akun pengguna berhasil ditambahkan.',
            'data' => $user,
        ], 201);
    }

    /**
     * Mengupdate data user berdasarkan user_id
     */
    public function update(Request $request, $user_id): JsonResponse
    {
        try {
            $user = User::findOrFail($user_id);

            $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $user_id . ',user_id',
                'username' => 'sometimes|string|max:255|unique:users,username,' . $user_id . ',user_id',
                'password' => 'sometimes|string|min:8',
                'membership_date' => 'sometimes|date',
            ]);

            $data = $request->only(['name', 'email', 'username', 'password', 'membership_date']);
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }

            $user->update($data);

            return response()->json([
                'message' => $user->wasChanged()
                    ? 'Data pengguna berhasil diperbarui.'
                    : 'Tidak ada perubahan yang dilakukan.',
                'data' => $user,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat update.'], 500);
        }
    }

    /**
     * Menghapus user berdasarkan user_id
     */
    public function destroy($user_id): JsonResponse
    {
        try {
            $user = User::findOrFail($user_id);
            $user->delete();

            return response()->json(['message' => 'User berhasil dihapus.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus.'], 500);
        }
    }

    /**
     * Menghitung total user
     */
    public function count(): JsonResponse
    {
        $count = User::count();
        return response()->json([
            'total' => $count
        ]);
    }
}
