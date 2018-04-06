<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function scopeAccessible($query)
    {
        if (auth()->user()->access_level > 1) {
            return $query;
        }
        return $query->whereHas('folder', function($f) {
            $f->where('accessible_1', 1);
        });
    }
}
