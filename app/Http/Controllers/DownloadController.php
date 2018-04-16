<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function file(Request $request, File $file)
    {
        try {
            $this->authorize('view', $file);
        } catch (AuthorizationException $e) {
            return response()->json([], 403);
        }

        return response()->download(storage_path('library') . $file->filepath, $file->filename);
    }
}