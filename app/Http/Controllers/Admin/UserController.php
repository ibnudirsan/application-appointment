<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $user = User::query()
                ->when(request('search'), function ($query) {
                    return $query->where('name', 'like', '%' . request('search') . '%');
                });
        return $user->paginate(10);
    }

    public function store()
    {
        request()->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
        ]);
            return User::create([
                'name' => request('name'),
                'email' => request('email'),
                'password' => bcrypt(request('password')), 
            ]);
    }

    public function update(User $user)
    {
        request()->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id]
        ]);
            $user->update([
                'name'      => request('name'),
                'email'     => request('email'),
                'password'  => request('password') ? bcrypt(request('password')) : $user->password, 
            ]);
                return $user;
    }

    public function destroy(User $user)
    {
        $user->delete();
            return response()->noContent();
    }

    public function changeRole(User $user)
    {
        $user->update([
            'role' => request('role')
        ]);
            return response()->json(['success'  => true]);
    }

    public function bulkDelete()
    {
        User::whereIn('id',request('ids'))->delete();
            return response()->json(['message'  => 'Users deleted successfully!']);
    }
}
