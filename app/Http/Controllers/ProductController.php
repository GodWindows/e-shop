<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function create(Request $request): RedirectResponse
    {
        $categories = Category::all(['id']);
        $categories_id = array();
        foreach ($categories as $category) {
            $categories_id[] = $category->id;
        }
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                 Rule::unique('products'),
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'description' => [
                'required'
            ],
            'category' => [
                'required',
                'numeric',
                Rule::in($categories_id),
            ],

            'productImage1' => [
                'mimes:jpeg,png,jpg,gif,jfif,webp',
                'max:2048',
            ],
            'productImage2' => [
                'mimes:jpeg,png,jpg,gif,jfif,webp',
                'max:2048',
            ],
            'productImage3' => [
                'mimes:jpeg,png,jpg,gif,jfif,webp',
                'max:2048',
            ],
        ], [
            'name.unique' => 'Vous avez déjà un produit avec ce nom.',
            'category.in' => "Cette catégorie n'est pas valide.",
        ]);

        $atLeastOneImage = $request->hasFile('productImage1') || $request->hasFile('productImage2') || $request->hasFile('productImage3');
        $images =array();
        $images[0] = ($request->hasFile('productImage1') ) ? $request->file('productImage1')->store('images/products', 'public') : "";
        $images[1] = ($request->hasFile('productImage2') ) ? $request->file('productImage2')->store('images/products', 'public') : "";
        $images[2] = ($request->hasFile('productImage3') ) ? $request->file('productImage3')->store('images/products', 'public') : "";
        if (!$atLeastOneImage) {
            $images[0] = "images/products/default.jpg";
        }


        $discount_price = -1;
        if ($request->filled('discount_price')) {
            $discount_price = $request->discount_price;
        }
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'category_id' => $request->category,
            'images' => json_encode($images) ,
            'discount_price' => $discount_price,
        ]);
        return redirect()->route('dashboard')->with('success', 'Product created successfully');
    }
    public function edit(Request $request): RedirectResponse
    {

        $product = Product::where('id', $request->input('id'))->firstOrFail();
        $categories = Category::all(['id']);
        $categories_id = array();
        foreach ($categories as $category) {
            $categories_id[] = $category->id;
        }

        if ($request->name != $product->name) {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                     Rule::unique('products'),
                ],
                'price' => [
                    'required',
                    'numeric',
                    'min:0',
                ],
                'description' => [
                    'required'
                ],
                'category' => [
                    'required',
                    'numeric',
                    Rule::in($categories_id),
                ],

                'productImage1' => [
                    'mimes:jpeg,png,jpg,gif,jfif,webp',
                    'max:2048',
                ],
                'productImage2' => [
                    'mimes:jpeg,png,jpg,gif,jfif,webp',
                    'max:2048',
                ],
                'productImage3' => [
                    'mimes:jpeg,png,jpg,gif,jfif,webp',
                    'max:2048',
                ],
            ], [
                'name.unique' => 'Vous avez déjà un produit avec ce nom.',
                'category.in' => "Cette catégorie n'est pas valide.",
            ]);
        } else {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                ],
                'price' => [
                    'required',
                    'numeric',
                    'min:0',
                ],
                'description' => [
                    'required'
                ],
                'category' => [
                    'required',
                    'numeric',
                    Rule::in($categories_id),
                ],

                'categoryImage1' => [
                    'mimes:jpeg,png,jpg,gif,jfif,webp',
                    'max:2048',
                ],
                'categoryImage2' => [
                    'mimes:jpeg,png,jpg,gif,jfif,webp',
                    'max:2048',
                ],
                'categoryImage3' => [
                    'mimes:jpeg,png,jpg,gif,jfif,webp',
                    'max:2048',
                ],
            ], [
                'category.in' => "Cette catégorie n'est pas valide.",
            ]);
        }

        $images = array();
        $images[0] = ($request->hasFile('productImage1') ) ? $request->file('productImage1')->store('images/products', 'public') : json_decode($product->images)[0];
        $images[1] = ($request->hasFile('productImage2') ) ? $request->file('productImage2')->store('images/products', 'public') : json_decode($product->images)[1];
        $images[2] = ($request->hasFile('productImage3') ) ? $request->file('productImage3')->store('images/products', 'public') : json_decode($product->images)[2];


        $discount_price = -1;
        if ($request->filled('discount_price')) {
            $discount_price = $request->discount_price;
        }
        $product = Product::where('id', $request->input('id'))->firstOrFail();

        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->category_id = $request->category;
        $product->images = json_encode($images);
        $product->discount_price = $discount_price;
        $product->save();

        return redirect()->back()->with('success', 'Product successfully updated');
    }

    public function view($id)
    {
        $product = Product::find($id);
        $relatedProducts = getRelatedProducts($product);
        $categories = Category::all();
        return view('product', [
            "product" => $product,
            "categories" => $categories,
            "relatedProducts" => $relatedProducts,
        ]);
    }

    public function delete($id): RedirectResponse
    {
        $product = Product::find($id);
        $product->delete();
        return redirect()->back()->with('success', 'Product successfully deleted');
    }
}
