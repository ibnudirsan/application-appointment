<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->only(['name', 'email','role']);
    }

    public function update(Request $request)
    {
        $validation = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:users,email,'.$request->user()->id],
        ]);
            $request->user()->update($validation);
                return response()->json([
                    'success'   => true
                ]);
    }
}
