<?php

namespace App\Http\Controllers;

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
        $extensions = implode(',', config('library.uploads_extensions', []));
        return view('admin.uploads', compact('extensions'));
    }
}
