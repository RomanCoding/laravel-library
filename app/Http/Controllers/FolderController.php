<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FolderController extends Controller
{
    /**
     * Show view with widget to work with files.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLibraryPage()
    {
        return view('library.index');
    }

    /**
     * Show view with widget to edit folders permissions.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFoldersPage()
    {
        return view('admin.folders');
    }

    /**
     * Return all folders.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index(Request $request)
    {
        return Folder::accessible()->get();
    }

    public function updatePermissions($id)
    {
        $folder = Folder::findOrFail($id);
        $folder->accessible_1 = !$folder->accessible_1;
        $folder->save();
    }
}
