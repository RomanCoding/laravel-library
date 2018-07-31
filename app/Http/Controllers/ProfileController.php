<?php

namespace App\Http\Controllers;

use App\Folder;
use App\Http\Requests\UpdateProfile;
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

    public function update(UpdateProfile $request)
    {
        return response('', $request->user()->update($request->validated()) ? 204 : 400);
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

        $fileName = $this->getFileName($request->user()->id, $request->image);
        Image::make($request->image)->save(public_path('images/logos/') . $fileName);

        User::where('id', $request->user()->id)->update([
            'logo' => $fileName
        ]);

        return response()->json(['error' => false]);
    }

    private function getFileName($userID, $imageData)
    {
        return $userID . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
    }
}
