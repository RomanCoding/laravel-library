<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $guarded = [];

    public function setLinkAttribute($value)
    {
        $this->attributes['link'] = 'https://player.vimeo.com/video/' . $value;
    }
}
