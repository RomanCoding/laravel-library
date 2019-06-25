<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Partner extends Model
{
    protected $guarded = [];

    public function deleteLogoFromStorage()
    {
        $path = str_after($this->img, 'storage');
        if (Storage::disk('public')->exists($path)) {
            try {
                Storage::disk('public')->delete($path);
            } catch (\Exception $e) {
                return false;
            }
        }
        return true;
    }
}
