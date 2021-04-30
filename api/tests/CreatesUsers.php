<?php

namespace Tests;

use App\Models\User;

trait CreatesUsers
{
    /**
     * Create a user that has user_type = 'System User'
     *
     * @return App\Models\User
     */
    public function createUser()
    {
        return User::factory()->create();
    }
}
