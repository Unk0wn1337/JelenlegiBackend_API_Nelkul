<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegistered;



class UserObserver
{
    public function created(User $user)
    {
        if ($user->jogosultsag_azon === 3) {
            //Mail::to('admin@example.com')->send(new UserRegistered($user)); // rendszergazda email címét kell majd megadni itt
        }
    }
}
