<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dashboard\Myth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class MythController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : Response
    {
        $myths = Myth::query()->paginate();

        return inertia('Dashboard/Myths', ['myths' => $myths]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
            'myth' => 'required|string',
            'fact' => 'required|string'
        ]);

        $request->merge([
            'uuid' => uniqid(),
            'user_id' => auth()->id()
        ]);

        Myth::create($request->all());

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Myth $myth)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Myth $myth)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Myth $myth)
    {
        $request->validate([
            'myth' => 'nullable|string',
            'fact' => 'nullable|string'
        ]);

        if (! $myth) {
            // show error
        }

        $myth->fill($request->all());
        $myth->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Myth $myth)
    {
        if (! $myth) {
            // show error
        }

        $myth->delete();

        return back();
    }
}
