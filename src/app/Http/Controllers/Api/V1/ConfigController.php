<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $configs = Config::all();
        return response()->json($configs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'interface' => 'required|string|max:30|unique:config,interface',
            'IDmin' => 'required|integer',
            'IDmax' => 'required|integer'
        ]);

        $config = Config::create($validated);
        return response()->json($config, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $config = Config::where('interface', $id)->firstOrFail();
        return response()->json($config);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $config = Config::where('interface', $id)->firstOrFail();
        $validated = $request->validate([
            'IDmin' => 'required|integer',
            'IDmax' => 'required|integer'
        ]);

        $config->update($validated);
        return response()->json($config);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $config = Config::where('interface', $id)->firstOrFail();
        $config->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
