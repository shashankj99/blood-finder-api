<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Traits\GetUserNameId;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    use GetUserNameId;

    public function register($request)
    {
        $role = Role::firstOrCreate(
            ['slug' => 'member'],
            [
                'title' => 'Member',
                'slug' => 'member'
            ]
        );

        $usernameId = $this->getUserNameId();

        $user = new User();
        $user->first_name = ucfirst(trim($request->first_name));
        $user->last_name = ucfirst(trim($request->last_name));
        $user->mobile = $request->mobile;
        $user->username = 'donor'.$usernameId;
        $user->password = Hash::make($request->password);
        $user->role_id = $role->id;
        $user->username_id = $usernameId;
        $user->save();

        return 'You\'ve registered successfully';
    }
}
