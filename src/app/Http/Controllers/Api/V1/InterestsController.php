<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Interests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InterestsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $interests = Interests::all();
        return response()->json($interests);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'interest1' => 'required|boolean',
            'interest2' => 'required|boolean',
            'interest3' => 'required|boolean',
        ]);

        $interest = Interests::create($validated);
        return response()->json($interest, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $interest = Interests::findOrFail($id);
        return response()->json($interest);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $interest = Interests::findOrFail($id);
        $validated = $request->validate([
            'interest1' => 'required|boolean',
            'interest2' => 'required|boolean',
            'interest3' => 'required|boolean',
        ]);

        $interest->update($validated);
        return response()->json($interest);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $interest = Interests::findOrFail($id);
        $interest->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
