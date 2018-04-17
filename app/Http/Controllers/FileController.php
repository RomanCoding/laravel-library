<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Show view with widget to work with files.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLibraryPage()
    {
        $extensions = json_encode(config('library.uploads_extensions', []));
        return view('library.index', compact('extensions'));
    }

    /**
     * Return all files stored in database.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return File::accessible()->with('folder')->get();
    }

    public function destroy($id)
    {
        $file = File::findOrFail($id);

        if (!Storage::exists($file->filepath)) {
            return response()->json([
                'message' => 'File not found'
            ], 404);
        }
        try {
            Storage::delete($file->filepath);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'File can not be deleted'
            ], 500);
        }
        $file->delete();
        return response()->json([
            'message' => 'File deleted',
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'folder_id' => 'required|exists:folders,id'
        ]);

        $extensions = config('library.uploads_extensions', []);

        $folder = Folder::find($request->get('folder_id'));
        $success = [];
        $failed = [];

        foreach ($request->file('files') as $file) {
            $extension = strtolower($file->extension());
            $filename = $file->getClientOriginalName();
            // if extension is not in allowed list, add to failed
            if (array_search(".$extension", $extensions) === false) {
                $failed[] = $filename;
                continue;
            }
            try {
                $filePath = '/' . $file->storeAs($folder->storage_path, $filename);
                $stat = stat(storage_path('library') . $filePath);
                $file = File::updateOrCreate([
                    'filepath' => $filePath,
                    'filename' => pathinfo($filename, PATHINFO_FILENAME),
                    'extension' => $extension,
                    'filesize' => $stat['size'],
                    'folder_id' => $folder->id,
                ], [
                    'mtime' => $stat['mtime'],
                ]);
                $success[] = $file;
            } catch (\ErrorException $e) {
                $failed[] = $filename;
            }
        }
        return response()->json([
            'success' => $success, 'failed' => $failed
        ], count($success) ? 201 : 406);
    }
}
