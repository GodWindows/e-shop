<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'categoryImage' => [
                'mimes:jpeg,png,jpg,gif,jfif,webp',
                'max:2048',
            ],
            'catname' => [
                'required',
                'string',
            ]
        ]);
        if ($request->hasFile('categoryImage')) {
            $filePath = $request->file('categoryImage')->store('images/categories', 'public');
        }else{
            $filePath = "images/categories/default.jpg";
        }


        Category::create([
            'name' => $request->catname,
            'image' => $filePath
        ]);
        return redirect()->route('dashboard')->with('success', 'Category created successfully');
    }

    public function edit(Request $request): RedirectResponse
    {
        $category = Category::where('id', $request->input('id'))->firstOrFail();
        $request->validate([
            'categoryImage' => [
                'mimes:jpeg,png,jpg,gif,jfif,webp',
                'max:2048',
            ],
            'catname' => [
                'required',
                'string',
            ]
        ]);
        $category->name = $request->catname;
        $category->image = ($request->hasFile('categoryImage') ) ? $request->file('categoryImage')->store('images/categories', 'public') : $category->image;
        $category->save();
        return redirect()->back()->with('success', 'Category successfully updated');
    }

    public function delete($id): RedirectResponse
    {
        $category = Category::find($id);
        $category->delete();
        return redirect()->back()->with('success', 'Category successfully deleted');
    }
}
