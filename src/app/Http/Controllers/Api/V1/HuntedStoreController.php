<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\HuntedStoreResource;
use App\Http\Requests\HuntedStoreRequest;
use App\Models\HuntedStore;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HuntedStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $huntedStores = HuntedStore::with(['user', 'store'])->get();
        return HuntedStoreResource::collection($huntedStores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HuntedStoreRequest $request)
    {
        $huntedStore = HuntedStore::create($request->validated());
        return new HuntedStoreResource($huntedStore);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $huntedStore = HuntedStore::with(['user', 'store'])->findOrFail($id);
        return new HuntedStoreResource($huntedStore);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HuntedStoreRequest $request, string $id)
    {
        $huntedStore = HuntedStore::findOrFail($id);
        $huntedStore->update($request->validated());
        return new HuntedStoreResource($huntedStore);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $huntedStore = HuntedStore::findOrFail($id);
        $huntedStore->delete();
        return response()->noContent();
    }
}
