<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $query = Category::query();

        if ($keyword) {
            $query->where('nama', 'like', '%'.$keyword.'%');
        }

        $categories = $query->orderBy('id','desc')->paginate(10);
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required']);
        Category::create($request->only('nama'));

        return redirect()->route('categories.index')->with('success','Kategori berhasil dibuat.');
    }

    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['nama' => 'required']);
        $category->update($request->only('nama'));

        return redirect()->route('categories.index')->with('success','Kategori diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success','Kategori dihapus.');
    }
}
