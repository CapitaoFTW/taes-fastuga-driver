<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->update($validated);

        if ($request->has('photo')) {
            Storage::delete('storage/fotos/' . $user->photo_url);
            $image_64 = $request['photo'];
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];

            if ($extension == 'jpeg')
                $extension = 'jpg';

            $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
            $image = str_replace($replace, '', $image_64);
            $image = str_replace(' ', '+', $image);
            $imageName = Str::random(10) . '.' . $extension;

            Storage::put('public/fotos/' . $imageName, base64_decode($image));

            $user->photo_url = $imageName;
            $user->save();
        }

        return new UserResource($user);
    }

    public function update_password(Request $request, User $user)
    {
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
            'password' => ['required', 'different:current_password'],
            'password_confirmation' => ['same:password'],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return new UserResource($user);
    }

    public function show_me(Request $request)
    {
        $user = $request->user();
        $user->name = explode(" ", $user->name)[0] . " " . explode(" ", $user->name)[substr_count($user->name, " ")];
        return new UserResource($user);
    }
}
