<?php

namespace Modules\Blog\Providers;

use App\User;
use Illuminate\Support\ServiceProvider;
use Modules\Blog\Entities\Post;

class RelationshipsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::addExternalMethod('blog__posts', function () {
            return $this->morphMany(Post::class, 'author');
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}