<?php

namespace App\Http\Controllers;

use App\Extension;
use Illuminate\Http\Request;

class ExtensionController extends Controller
{
    public function index()
    {
        return view('admin.extensions', [
            'extensions' => Extension::all()
        ]);
    }

    public function destroy($id)
    {
        $ext = Extension::where('id', $id)->delete();

        return response()->json([
            'message' => 'Extension deleted',
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'ext' => 'required|max:12'
        ]);

        $extension = Extension::updateOrCreate([
            'ext' => $request->get('ext')
        ]);
        return response()->json([
            'message' => 'Extension added!',
            'extension' => $extension
        ], 201);
    }
}