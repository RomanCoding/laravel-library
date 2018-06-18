<?php

namespace App\Http\Controllers;

use App\Folder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return view('library.profile');
    }
}
