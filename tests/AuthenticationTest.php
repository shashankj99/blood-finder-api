<?php

class AuthenticationTest extends TestCase
{
    public function isUserRegistered()
    {
        $parameters = [
            'first_name' => 'test',
            'last_name' => 'user',
            'mobile' => 1234567890,
            'password' => 'user@123'
        ];

        $this->post("register", $parameters, []);
        $this->seeStatusCode(200);
    }
}
