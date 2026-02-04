<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        // 'with' digunakan untuk memuat data kategori sekaligus (Eager Loading)
        $products = Product::with('category')->latest()->get();

        return response()->json(['data' => $products], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id', // Pastikan ID kategori ada
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $product = Product::create($request->all());

        return response()->json(['message' => 'Produk tersimpan', 'data' => $product], 201);
    }
    
    // ... (Implementasikan Show, Update, Destroy mirip dengan CategoryController)
    public function show($id)
    {
        $product = Product::with('category')->find($id);
        if (!$product) return response()->json(['message' => 'Tidak ditemukan'], 404);

        return response()->json(['data' => $product], 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Tidak ditemukan'], 404);

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|required',
            'price' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|numeric',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $product->update($request->all());

        return response()->json(['message' => 'Produk diperbarui', 'data' => $product], 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Tidak ditemukan'], 404);

        $product->delete();

        return response()->json(['message' => 'Produk dihapus'], 200);
    }
}

