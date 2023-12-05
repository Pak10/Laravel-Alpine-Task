<?php

namespace App\Policies;

use App\Models\User;

class CommentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        
    }

    public function created(User $user)
    {
        if($user){
            
            return true;
        }
        else{
            return false;
        }
    }
}
