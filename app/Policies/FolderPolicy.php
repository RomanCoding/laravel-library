<?php

namespace App\Policies;

use App\User;
use App\Folder;
use Illuminate\Auth\Access\HandlesAuthorization;

class FolderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the folder.
     *
     * @param  \App\User  $user
     * @param  \App\Folder  $folder
     * @return mixed
     */
    public function view(User $user, Folder $folder)
    {
        return ($user->access_level > 1 || $folder->accessible_1 = 1);
    }

    /**
     * Determine whether the user can create folders.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->access_level === User::MAX_LEVEL;
    }

    /**
     * Determine whether the user can update the folder.
     *
     * @param  \App\User  $user
     * @param  \App\Folder  $folder
     * @return mixed
     */
    public function update(User $user, Folder $folder)
    {
        return $user->access_level === User::MAX_LEVEL;
    }

    /**
     * Determine whether the user can delete the folder.
     *
     * @param  \App\User  $user
     * @param  \App\Folder  $folder
     * @return mixed
     */
    public function delete(User $user, Folder $folder)
    {
        //
    }
}
