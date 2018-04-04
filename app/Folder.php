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

    public function files()
    {
        return $this->hasMany(File::class)->orderBy('filename');
    }

    public function folders()
    {
        return $this->hasMany(Folder::class, 'parent_id')->orderBy('name');
    }

    public function getChildrenAttribute()
    {
        return $this->folders->concat($this->files);
    }
}
