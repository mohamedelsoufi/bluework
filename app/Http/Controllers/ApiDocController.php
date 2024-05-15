<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiDocController extends Controller
{
    public function api()
    {
        $swagger = \OpenApi\scan(app_path());
        return response()->json($swagger, 200, [], JSON_PRETTY_PRINT);
    }
}
