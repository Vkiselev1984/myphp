<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', User::class);

        $users = User::select(['id', 'name', 'email', 'is_admin'])->orderBy('id')->get();
        return response()->json($users);
    }
}
