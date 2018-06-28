<?php

namespace App\Http\Controllers;

use App\Folder;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return view('library.profile');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'business_address' => 'nullable|string|max:255',
            'password' => 'nullable|confirmed|string|max:255',
            'suburb' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'network_visible' => 'boolean',
        ]);

        return $request->user()->update($validated) ? response('', 204) : response('', 400);
    }

    /**
     * Update or create logo.
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image64:jpeg,jpg,png'
        ]);

        $imageData = $request->get('image');
        $fileName = $request->user()->id . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
        Image::make($request->get('image'))->save(public_path('images/logos/') . $fileName);

        User::where('id', $request->user()->id)->update([
            'logo' => $fileName
        ]);

        return response()->json(['error' => false]);
    }
}
