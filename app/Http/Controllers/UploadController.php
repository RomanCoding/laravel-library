<?php

namespace App\Http\Controllers;

use App\Extension;

class UploadController extends Controller
{
    /**
     * Show component to work with uploads.
     * Here we can select which file extensions are allowed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $extensions = Extension::getCommaSeparatedList();
        return view('admin.uploads', compact('extensions'));
    }
}
