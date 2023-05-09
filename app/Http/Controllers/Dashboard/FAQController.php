<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dashboard\FAQ;
use Illuminate\Http\Request;
use Inertia\Response;

class FAQController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : Response
    {
        $faqs = FAQ::query()->paginate();

        return inertia('Dashboard/FAQs', ['faqs' => $faqs]);
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
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $request->merge([
            'user_id' => auth()->id(),
        ]);

        FAQ::create($request->all());

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(FAQ $faq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FAQ $faq)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FAQ $faq)
    {
        if (! $faq) {
            // show error
        }

        $faq->fill($request->all());
        $faq->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FAQ $faq)
    {
        if (! $faq) {
            // show error
        }

        $faq->delete();

        return back();
    }
}
