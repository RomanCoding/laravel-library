<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
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

    protected $appends = ['children'];

    protected $with = ['files', 'folders'];

    public function files()
    {
        return $this->hasMany(File::class)->orderBy('filename');
    }

    public function folders()
    {
        if (auth()->user()->access_level > 1) {
            return $this->hasMany(Folder::class, 'parent_id')->orderBy('name');
        }
        return $this->hasMany(Folder::class, 'parent_id')
            ->where('accessible_1', 1)
            ->orderBy('name');
    }

    public function getChildrenAttribute()
    {
        return $this->folders->concat($this->files);
    }
    
    public function scopeAccessible($query)
    {
        if (auth()->user()->access_level > 1) {
            return $query;
        }
        return $query->where('accessible_1', true);
    }
}
