<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Fortify\UpdateUserPassword;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->only(['name', 'email','role','avatar']);
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

    public function updateProfilePicture(Request $request)
    {
        if($request->hasFile('profile_picture')){
            $previousPath = $request->user()->getRawOriginal('avatar');
            $link = Storage::put('public/photos', $request->file('profile_picture'));
                $request->user()->update([
                    'avatar' => $link
                ]);
                    Storage::delete($previousPath);
                        return response()->json([
                            'message'   => 'Successfully profile picture updated',
                        ]);
        }
    }

    public function changePassword(Request $request, UpdateUserPassword $updateUserPassword)
    {
        $updateUserPassword->update(auth()->user(), [
            'current_password'      => $request->current_password,
            'password'              => $request->password,
            'password_confirmation' => $request->passwordConfirmation,
        ]);
            return response()->json([
                'message'   => 'Successfully password updated',
            ]);
    }
}
