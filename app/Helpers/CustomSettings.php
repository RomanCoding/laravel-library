<?php

namespace App\Helpers;

use App\Config;

class CustomSettings
{
    static $available = [
        'mail.username',
        'mail.password',
        'mail.host',
        'mail.from.address',
        'mail.from.name',
    ];

    public function index()
    {
        $data = [];
        foreach (self::$available as $key) {
            $data[$key] = $this->show($key);
        }
        return $data;
    }

    public function show($name)
    {
        return ($config = Config::where('key', $name)->first()) ? $config->value : config($name, null);
    }
}
