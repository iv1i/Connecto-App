<?php

namespace App\Providers;

use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\User;
use App\Observers\ChatRoomObserver;
use App\Observers\MessageObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Message::observe(MessageObserver::class);
        ChatRoom::observe(ChatRoomObserver::class);
    }
}
