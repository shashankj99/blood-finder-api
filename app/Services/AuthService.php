<?php

namespace App\Services;

use App\Models\Otp;
use App\Models\Role;
use App\Models\User;
use App\Traits\GetUserNameId;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    use GetUserNameId;

    /**
     * Method to register a new user
     * @param $request
     * @return string
     */
    public function register($request)
    {
        // find member role or create if not found
        $role = Role::firstOrCreate(
            ['slug' => 'member'],
            [
                'title' => 'Member',
                'slug' => 'member'
            ]
        );

        // get the last username id
        $usernameId = $this->getUserNameId();

        // create a new user object
        $user = new User();
        $user->first_name = ucfirst(trim($request->first_name));
        $user->last_name = ucfirst(trim($request->last_name));
        $user->mobile = $request->mobile;
        $user->username = 'donor'.$usernameId;
        $user->password = Hash::make($request->password);
        $user->role_id = $role->id;
        $user->username_id = $usernameId;
        $user->save();

        // return the response
        return 'You\'ve registered successfully';
    }

    /**
     * Method to login the user
     * @param $request
     * @return array
     */
    public function login($request)
    {
        // get user from mobile number
        $user = User::whereMobile($request->mobile)
            ->first();

        // throw not found error if unable to find the user
        if (!$user)
            throw new ModelNotFoundException('Sorry, Unable to find the user');

        // check whether the PW entered by the user matches or not
        if (!Hash::check($request->password, $user->password))
            return [
                'status' => 400,
                'message' => 'The password was incorrect'
            ];

        // return not verified error if user has not verified their account
        if (!$user->is_verified)
            return [
                'status' => 409,
                'message' => 'The user has not verified their account'
            ];

        // check if user token exist already
        if (!empty($user->token))
            $token = $user->token->token;
        else {
            // generate a new token
            $token = JWTAuth::fromUser($user);

            // save the token in the DB
            $user->token()->create([
                'token' => $token
            ]);
        }

        return [
            'status' => 200,
            'message' => 'Logged in successfully',
            'access_token' => $token
        ];
    }

    /**
     * Method to verify the user
     * @param $request
     * @return array
     */
    public function verifyOTP($request)
    {
        // get otp
        $otp = Otp::whereOtp($request->otp)
            ->first();

        // throw not found error if unable to find the error
        if (!$otp)
            throw new ModelNotFoundException('Sorry, The OTP is not valid');

        // start the DB transaction
        DB::beginTransaction();

        // verify the user
        $otp->user->is_verified = true;

        // if token exist previously delete it
        if ($otp->user->token)
            $otp->user->token->delete();

        // generate a new token
        $token = JWTAuth::fromUser($otp->user);

        // save the token in DB
        $otp->user->token()->create([
            'token' => $token
        ]);

        // save the changes
        $otp->user->save();

        // delete the OTP
        $otp->delete();

        // commit the transaction
        DB::commit();

        return [
            'message' => 'Verification successful',
            'access_token' => $token
        ];
    }
}
