<?php

namespace App\Http\Controllers;

use App\Folder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    /**
     * Show component to work with files.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLibraryPage()
    {
        return view('library.index');
    }

    /**
     * Show component to edit folders permissions.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFoldersPage()
    {
        return view('admin.folders');
    }

    /**
     * Return all folders ordered by name.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index(Request $request)
    {
        return Folder::orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'folder_name' => 'bail|required',
            'folder_id' => 'bail|required|exists:folders,id',
        ]);
        $parentFolder = Folder::find($request->get('folder_id'));
        $newFolderPath = $this->newFolderPath($parentFolder->storage_path, $request->get('folder_name'));

        if (Storage::exists($newFolderPath)) {
            return response()->json([
                'message' => 'Folder already exists'
            ], 409);
        }
        try {
            Storage::makeDirectory($newFolderPath);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Folder can not be created'
            ], 500);
        }
        $folder = Folder::create([
            'name' => $request->get('folder_name'),
            'storage_path' => $newFolderPath,
            'parent_id' => $parentFolder->id
        ]);
        return response()->json([
            'message' => 'Folder created',
            'folder' => $folder,
        ]);
    }

    /**
     * Reverse folder accessibility.
     *
     * @param $id
     */
    public function updatePermissions($id)
    {
        $folder = Folder::findOrFail($id);
        $folder->accessible_1 = !$folder->accessible_1;
        $folder->save();
    }

    /**
     * Check if parent folder is root, and returns proper path.
     *
     * @param $parentPath
     * @param $newName
     * @return string
     */
    private function newFolderPath($parentPath, $newName)
    {
        return $parentPath === '/' ?  "/{$newName}" : "{$parentPath}/{$newName}";
    }
}
