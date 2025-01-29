<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Request;

class UserObserver
{
    public function created(User $user)
    {
        $user->sites()->create([
            'site_option_id' => request('site_option_id'),
            'area_id' => request('area_id'),
            'user_id' => $user->id  // Forzar relación
        ]);
    }
}
