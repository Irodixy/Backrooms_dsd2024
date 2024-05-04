<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeedbackResource;
use App\Http\Requests\FeedbackRequest;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feedbacks = Feedback::with(['user', 'store', 'item'])->get();
        return FeedbackResource::collection($feedbacks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FeedbackRequest $request)
    {
        $feedback = Feedback::create($request->validated());
        return new FeedbackResource($feedback);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $feedback = Feedback::with(['user', 'store', 'item'])->findOrFail($id);
        return new FeedbackResource($feedback);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FeedbackRequest $request, string $id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->update($request->validated());
        return new FeedbackResource($feedback);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();
        return response()->noContent();
    }
}
