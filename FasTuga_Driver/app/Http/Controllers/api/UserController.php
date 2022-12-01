<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /*public function create(CreateUserRequest $request, User $user)
    {
        $user->create($request->validated());
        return new UserResource($user);
    }*/

    public function index()
    {
        return UserResource::collection(User::all()->where('type', 'D'));
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
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
