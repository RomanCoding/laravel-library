<?php

namespace App\Policies;

use App\File;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->access_level == User::MAX_LEVEL) {
            return true;
        }
    }

    /**
     * Determine whether the user can view/download the file.
     *
     * @param  \App\User  $user
     * @param  \App\File  $file
     * @return bool
     */
    public function view(User $user, File $file)
    {
        return ($user->access_level > 1 || $file->folder->accessible_1 == 1);
    }
}
