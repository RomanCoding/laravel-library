<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Extension extends Model
{
    protected $guarded = [];

    public static function getCommaSeparatedList()
    {
        return implode(',', self::all(['ext'])->pluck('ext')->toArray());
    }

    /**
     * Get extension with leading point.
     *
     * @param $value
     * @return string
     */
    public function getExtAttribute($value)
    {
        return ".$value";
    }

    /**
     * Remove leading point when setting ext.
     *
     * @param $value
     */
    public function setExtAttribute($value)
    {
        $this->attributes['ext'] = trim($value, '.');
    }
}
