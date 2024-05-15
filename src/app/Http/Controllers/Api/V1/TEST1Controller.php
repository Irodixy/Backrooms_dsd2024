<?php
namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DeleteController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /users
     */
    public function index()
    {
       DB::update('UPDATE users u
					SET u.username = "Mick"
					WHERE u.ID = 11');
		
    }

    /**
     * Store a newly created resource in storage.
     * POST /users
     */
    public function store(Request $request)
    {
        DB::update('UPDATE users u
					SET u.username = "Micael"
					WHERE u.ID = 11');
    }