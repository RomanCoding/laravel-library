<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Auth\Access\AuthorizationException;

class DownloadController extends Controller
{
    /**
     * Download a file.
     *
     * @param File $file
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function file(File $file)
    {
        try {
            $this->authorize('view', $file);
        } catch (AuthorizationException $e) {
            return response()->json([], 403);
        }

        return response()->download(storage_path('library') . $file->filepath, $file->filename);
    }
}