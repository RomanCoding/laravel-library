<?php

namespace App\Helpers;

use Illuminate\Mail\TransportManager;

class CustomTransportManager extends TransportManager
{
    /**
     * Create a new manager instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;

        $settings = new CustomSettings();

        $this->app['config']['mail'] = [
            'driver' => $settings->show('mail.driver'),
            'host' => $settings->show('mail.host'),
            'port' => $settings->show('mail.port'),
            'from' => [
                'address' => $settings->show('mail.from.address'),
                'name' => $settings->show('mail.from.name'),
            ],
            'encryption' => $settings->show('mail.encryption'),
            'username' => $settings->show('mail.username'),
            'password' => $settings->show('mail.password'),
        ];
    }
}
