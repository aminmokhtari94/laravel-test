<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Inertia::render('Category/CategoryList', [
            'categories' => Category::with('attributes')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Category/CategoryCreate', [
            'attributes' => Attribute::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'has_attributes' => 'required',
            'has_warranty' => 'required|boolean'
        ]);

        $cat = Category::create([
            'name' => $request->name,
            'has_warranty' => $request->has_warranty
        ]);
        $cat->attributes()->attach($request->has_attributes);

        return redirect()->route('categories.index')->with('message', 'Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return void
     */
    public function show(Category $category)
    {
        // nothing to show
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $category
     * @return Response
     */
    public function edit(Category $category)
    {
        $category->has_attributes = $category->attributes->map(function ($a) {
            return $a->id;
        });
        return Inertia::render('Category/CategoryEdit', [
            'category' => $category,
            'attributes' => Attribute::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Category $category
     * @return RedirectResponse
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'has_attributes' => 'required',
            'has_warranty' => 'required|boolean'
        ]);

        $category->update([
            'name' => $request->name,
            'has_warranty' => $request->has_warranty
        ]);

        $category->attributes()->sync($request->has_attributes);

        return redirect()->route('categories.index')->with('message', 'Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Category $category)
    {
        if(count($category->products)){
            return redirect()->back()->withErrors('Deleted Failed! Category has some Product.');
        }
        $category->delete();
        return redirect()->back()->with('message', 'Deleted Successfully.');
    }
}
