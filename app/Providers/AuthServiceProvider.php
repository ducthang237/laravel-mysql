<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Config;
use App\Policies\PostPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Post' => 'App\Policies\PostPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        // Set expire for token
        $tokenLifeTime = Config::get('env.tokenLifeTime');
        Passport::personalAccessTokensExpireIn(now()->addHours($tokenLifeTime));

        Gate::resource('post', PostPolicy::class);
        Gate::define('post.publish', PostPolicy::class . '@publish');
        Gate::define('post.draft', PostPolicy::class . '@draft');
    }
}
