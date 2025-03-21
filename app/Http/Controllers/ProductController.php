<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
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
        $product->update($request->all());
        return response()->json($product, 200);
    }

    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json(null, 204);
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
