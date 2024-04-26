<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class JsonController extends Controller
{
    public function transformJsonToString($file)
    {
        // Path to the JSON file
        $jsonFilePath = storage_path($file);

        // Read the content of the JSON file
        $jsonContent = File::get($jsonFilePath);

        // Decode the JSON content into an array
        $jsonData = json_decode($jsonContent, true);

        // Initialize an empty array to store string representations
        $stringData = [];

        // Convert each element into a string representation
        foreach ($jsonData as $key => $value) {
            // Convert the key and value into a string
            $stringData[$key] = "$value";
        }

        // Return the array of string representations
        return $stringData;
    }
}
