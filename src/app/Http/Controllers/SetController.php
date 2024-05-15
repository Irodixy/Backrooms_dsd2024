<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Http\Controllers\IdentifyInterface;

class SetController extends Controller
{
	// Handle file upload
    public function prepareFile(Request $request)
    {
        // Validate the file upload
        $request->validate([
            'fileToUpload' => 'required|mimes:json|max:5000', // Example validation rules
        ]);

		// Decode the JSON content into an array
		$jsonArray = json_decode(file_get_contents($request->file('fileToUpload')->path()), true);

		// Depending on the InterfaceId, a different query is waiting
        $pickInterface = new IdentifyInterface();
		$flag = $pickInterface->InterfaceID($jsonArray["InterfaceId"], $jsonArray);
		
		// if returns string it means that an erro occur. (WORK IN PROGRESS!)
		if(is_string($flag))
		{
			$jsonString = $flag;
			return view('show', ['jsonString' => $jsonString]);
		}
		else
		{
			$jsonString = json_encode($flag, JSON_PRETTY_PRINT);
			return view('show', ['jsonString' => $jsonString]);
		}
    }
}
