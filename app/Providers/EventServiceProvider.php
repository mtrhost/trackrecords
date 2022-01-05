<?php

namespace App\Providers;

use App\Models\Player\Player;
use App\Models\Player\PlayerStatistics;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Player::created(static function($player)
		{
			$statistics = new PlayerStatistics(['player_id' => $player->id]);
			$statistics->save();
		});
    }
}
