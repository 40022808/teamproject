<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;



class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "price" => "required|numeric",
            "image" => "nullable|file|mimes:jpg,jpeg,png|max:2048"
        ]);

        $data = $request->all();

        // Kép mentése
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $data['imageURL'] = Storage::url($path); // Helyes URL generálása
        }

        $product = Product::create($data);
        return response()->json($product, 201);
    }
    public function index()
    {
        return response()->json(Product::all(), 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            "name" => "required|string|max:255",
            "price" => "required|numeric",
            "description" => "nullable|string",
            "image" => "nullable|file|mimes:jpg,jpeg,png|max:2048"
        ]);

        $product->update($validatedData);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product, 200);
    }
}
