<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    public function updated(User $user): void
    {
        if ($user->isDirty('is_blocked')) {
            $status = $user->is_blocked ? 'blocked' : 'unblocked';
            Log::info("User {$user->id} {$status}");
        }
    }
}
