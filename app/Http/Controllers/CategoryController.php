<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::when($request->keyword, function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->keyword}%")
                ->orwhere('description', 'like', "%{$request->keyword}%");
        })->orderBy('id', 'desc')->paginate(10);

        return view('pages.categories.index', compact('categories'));
    }

    //create
    public function create()
    {

        return view('pages.categories.create');
    }

    //store 
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Category::create($request->all());
        return redirect()->route('categories.index')->with('sucess', 'Category created successfully');
    }

    //edit
    public function edit(Category $category)
    {
        return view('pages.categories.edit', compact('category'));
    }

    //update
    public function update(Request $request, Category $category)
    {
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    //delete
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
    }
}
