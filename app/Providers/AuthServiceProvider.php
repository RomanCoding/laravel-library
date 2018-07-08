<?php

namespace App\Providers;

use App\File;
use App\Folder;
use App\Policies\FilePolicy;
use App\Policies\FolderPolicy;
use App\Policies\UserPolicy;
use App\Policies\VideoPolicy;
use App\User;
use App\Video;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Folder::class => FolderPolicy::class,
        File::class => FilePolicy::class,
        Video::class => VideoPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

    }
}
