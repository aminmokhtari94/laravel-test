<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Inertia::render('Branch/BranchList', [
            'branches' => auth()->user()->branches()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Branch/BranchCreate');
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
        $branch = Branch::create(['name' => $request->name]);
        auth()->user()->branches()->attach($branch);

        return redirect()->route('branches.index')->with('message', 'Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param Branch $branch
     * @return void
     */
    public function show(Branch $branch)
    {
        // nothing to show
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Branch $branch
     * @return Response
     */
    public function edit(Branch $branch)
    {
        return Inertia::render('Branch/BranchEdit', ['branch' => $branch]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Branch $branch
     * @return RedirectResponse
     */
    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $branch->update([
            'name' => $request->name
        ]);

        return redirect()->route('branches.index')->with('message', 'Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Branch $branch
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->back()->with('message', 'Deleted Successfully.');
    }
}
