<?php

namespace App\Http\Controllers\Dashboard;

use \App\Http\Controllers\Controller;
use App\Models\Dashboard\ServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Response;

class ServiceProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : Response
    {
        $providers = ServiceProvider::query()->paginate();

        return inertia('Dashboard/Providers', ['providers' => $providers]);
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
        $validated = Validator::make($request->all(), [
            'provider' => 'required|string',
            'action' => 'required|string',
            'args' => 'required'
        ], attributes: [
            'args' => 'data'
        ])->validate();

        $validated['user_id'] = auth()->id();

        ServiceProvider::create($validated);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceProvider $serviceProvider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceProvider $serviceProvider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceProvider $provider)
    {
        $validated = Validator::make($request->all(), [
            'provider' => 'nullable|string',
            'action' => 'nullable|string',
            'args' => 'nullable'
        ], attributes: [
            'args' => 'data'
        ])->validate();

        if (! $provider) {
            // show error
        }

        $provider->fill($validated);
        $provider->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceProvider $provider)
    {
        if (! $provider) {
            // show error
        }

        $provider->delete();

        return back();
    }
}
