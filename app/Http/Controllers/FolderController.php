<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FolderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
     * Return all folders.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return Folder::all();
    }
}
