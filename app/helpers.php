<?php

use App\Models\Product;
use Illuminate\Http\Request;

    function image(Product $product)  {
        for ($i=0; $i < 3; $i++) {
            if (json_decode($product->images)[$i]!= "default.jpg" && json_decode($product->images)[$i]!= "") {
                return json_decode($product->images)[$i];
            }
        }
        return "default.jpg";
    }

    function removeEmptyValuesFromArray($inputArray) {
        // Filter the array to remove empty string values
        $filteredArray = array_filter($inputArray, function ($value) {
            return $value !== "";
        });

        return array_values($filteredArray);
    }

    function getRelatedProducts(Product $product)  {
        // Get the category ID of the current product
        $categoryId = $product->category_id;

        // Retrieve the last four products from the same category
        $relatedProducts = Product::where('category_id', $categoryId)
            ->where('id', '!=', $product->id) // Exclude the current product
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return $relatedProducts;
    }

?>
