<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Inertia::render('Product/ProductList', [
            'products' => Product::with(['attributes', 'category', 'warranty'])->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Product/ProductCreate', [
            'categories' => Category::with('attributes')->get()
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
        $validates =[
            'name' => 'required',
            'category_id' => 'required|exists:categories,id'
        ];

        $cat = Category::with('attributes')->find($request->category_id);

        if (empty($request->warranty_months) && $cat->has_warranty)
            $validates += ['warranty_months'=>'required|numeric|min:3|max:48'];

        if (count($cat->attributes)) {
            $validates += ['attribute_values' => 'required|min:1'];
            foreach ($request->attribute_values as $key => $value){
                if (empty($value['value'])) {
                    $validates += ['all_attribute_values' => 'required|min:1'];
                    break;
                }
            }
            foreach ($cat->attributes as $value){
                if (!array_key_exists($value->id, $request->attribute_values)) {
                    $validates += ['all_attribute_values' => 'required|min:1'];
                    break;
                }
            }
        }

        $request->validate($validates);

        $product = Product::create(['name' => $request->name, 'category_id' => $request->category_id]);

        $product->attributes()->sync($request->attribute_values);

        if ($cat->has_warranty) {
            $product->warranty()->create(['name' => $request->warranty_name ? $request->warranty_name : 'orginal', 'duration_months' => $request->warranty_months]);
        }

        return redirect()->route('products.index')->with('message', 'Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return void
     */
    public function show(Product $product)
    {
        // nothing to show
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return Response
     */
    public function edit(Product $product)
    {
        return Inertia::render('Product/ProductEdit', [
            'product' => Product::with(['warranty', 'attributes'])->find($product->id),
            'categories' => Category::with('attributes')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $validates =[
            'name' => 'required',
            'category_id' => 'required|exists:categories,id'
        ];

        $cat = Category::with('attributes')->find($request->category_id);

        if (empty($request->warranty_months) && $cat->has_warranty)
            $validates += ['warranty_months'=>'required|numeric|min:3|max:48'];

        if (count($cat->attributes)) {
            $validates += ['attribute_values' => 'required|min:1'];
            foreach ($request->attribute_values as $key => $value){
                if (empty($value['value'])) {
                    $validates += ['all_attribute_values' => 'required|min:1'];
                    break;
                }
            }
            foreach ($cat->attributes as $value){
                if (!array_key_exists($value->id, $request->attribute_values)) {
                    $validates += ['all_attribute_values' => 'required|min:1'];
                    break;
                }
            }
        }

        $request->validate($validates);

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id
        ]);

        $product->attributes()->sync($request->attribute_values);

        if (Category::find($request->category_id)->has_warranty) {
            if (!$product->has('warranty'))
                $product->warranty()->create(['name' => $request->warranty_name ? $request->warranty_name : 'orginal', 'duration_months' => $request->warranty_months]);
            else
                $product->warranty()->update(['name' => $request->warranty_name ? $request->warranty_name : 'orginal', 'duration_months' => $request->warranty_months]);
        } else if ($product->has('warranty')) {
            $product->warranty()->delete();
        }

        return redirect()->route('products.index')->with('message', 'Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->back()->with('message', 'Deleted Successfully.');
    }
}
