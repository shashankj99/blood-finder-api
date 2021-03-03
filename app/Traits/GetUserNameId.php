<?php

namespace App\Traits;

use App\Models\User;

trait GetUserNameId
{
    /**
     * Method to get the username id from the table
     * @return int
     */
    public function getUserNameId()
    {
        // get the latest username id
        $user = User::orderBy('id', 'desc')->first();

        // if username id is empty or is less than 1000 then return 1000 else return the fetched username id
        return ($user == null || $user->username_id < 1000) ? 1000 : (int) ++$user->username_id;
    }
}
