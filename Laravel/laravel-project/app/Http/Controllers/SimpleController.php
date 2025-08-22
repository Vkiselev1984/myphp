<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SimpleController extends Controller
{
    public function test(Request $request)
    {
        $response = ['param1' => $request->param, 'param2' => $request->param2];
        return response()->json($response);
    }
}
