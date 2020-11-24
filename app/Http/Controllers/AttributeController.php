<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Inertia::render('Attribute/AttributeList', [
            'attributes' => Attribute::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Attribute/AttributeCreate');
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
            'name' => 'required'
        ]);

        Attribute::create(['name' => $request->name]);

        return redirect()->route('attributes.index')->with('message', 'Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param Attribute $attribute
     * @return void
     */
    public function show(Attribute $attribute)
    {
        // nothing to show
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Attribute $attribute
     * @return Response
     */
    public function edit(Attribute $attribute)
    {
        return Inertia::render('Attribute/AttributeEdit', ['attribute' => $attribute]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Attribute $attribute
     * @return RedirectResponse
     */
    public function update(Request $request, Attribute $attribute)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $attribute->update([
            'name' => $request->name
        ]);

        return redirect()->route('attributes.index')->with('message', 'Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Attribute $attribute
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return redirect()->back()->with('message', 'Deleted Successfully.');
    }
}
