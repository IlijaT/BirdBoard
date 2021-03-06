<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserAvatarController extends Controller
{
    public function store(User $user)
    {
        request()->validate([
            'avatar' => 'required|image|max:10240'
        ]);

        $user->update(
            ['avatar_path' => request()->file('avatar')->store('avatars', 'public')]
        );

        return response([], 204);
    }
}
