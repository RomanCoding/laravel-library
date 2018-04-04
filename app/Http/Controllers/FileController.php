<?php

namespace App\Http\Controllers;

use App\File;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FileController extends Controller
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
     * Return all files stored in database.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return File::with('folder')->get();
    }
}
