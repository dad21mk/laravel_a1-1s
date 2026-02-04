<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SellerController extends Controller
{
    // GET: Ambil Semua Seller
    public function index()
    {
        $sellers = Seller::all();
        return response()->json([
            'status' => true,
            'message' => 'List Seller',
            'data' => $sellers
        ], 200);
    }

    // POST: Tambah Seller
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sellers,name',
            'email' => 'required|email|unique:sellers,email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $seller = Seller::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Seller dibuat',
            'data' => $seller
        ], 201);
    }

    // GET: Detail 1 Seller
    public function show($id)
    {
        $seller = Seller::find($id);
        if (!$seller) return response()->json(['message' => 'Tidak ditemukan'], 404);

        return response()->json(['data' => $seller], 200);
    }

    // PUT: Update Seller
    public function update(Request $request, $id)
    {
        $seller = Seller::find($id);
        if (!$seller) return response()->json(['message' => 'Tidak ditemukan'], 404);

        $seller->update(['name' => $request->name]);

        return response()->json(['message' => 'Berhasil update', 'data' => $seller], 200);
    }

    // DELETE: Hapus Kategori
    public function destroy($id)
    {
        $seller = Seller::find($id);
        if (!$seller) return response()->json(['message' => 'Tidak ditemukan'], 404);

        $seller->delete();
        return response()->json(['message' => 'Berhasil dihapus'], 200);
    }
}
