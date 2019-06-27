<?php

namespace App\Http\Controllers;

use App\Partner;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $partners = Partner::query();
        if ($request->get('deleted', true)) {
            $partners = $partners->where('deleted', false);
        }
        return $partners->get();
    }

    public function destroy(Partner $partner, Request $request)
    {
        if ($request->has('hard')) {
            $partner->deleteLogoFromStorage();
            $partner->delete();

            return [
                'success' => true
            ];
        }
        
        return [
            'success' => $partner->update(['deleted' => !$partner->deleted])
        ];
    }

    public function updateLogo(Partner $partner, Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image64:jpeg,jpg,png'
        ]);

        $randomFileName = 'partner' . time();
        $fileName = $this->getFileName($randomFileName, $request->image);
        $image = Image::make($request->image);
        $image->stream();
        Storage::disk('public')->put("images/partners/{$fileName}", $image, 'public');

        $partner->deleteLogoFromStorage();
        $partner->img = '/storage/images/partners/' . $fileName;
        $partner->save();
    }

    public function update(Partner $partner, Request $request)
    {
        $partner->update([
            'title' => $request->get('title', $partner->title),
            'service' => $request->get('service', $partner->service),
            'about' => $request->get('about', $partner->about),
            'benefit' => $request->get('benefit', $partner->benefit),
            'email' => $request->get('email', $partner->email),
            'contact' => $request->get('contact', $partner->contact),
            'phone' => $request->get('phone', $partner->phone),
            'webinar_link' => $request->get('webinar_link', $partner->webinar_link),
            'url' => $request->get('url', $partner->url),
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'logo' => 'required|image64:jpeg,jpg,png'
        ]);

        $randomFileName = 'partner' . time();
        $fileName = $this->getFileName($randomFileName, $request->logo);
        $image = Image::make($request->logo);
        $image->stream();
        Storage::disk('public')->put("images/partners/{$fileName}", $image, 'public');

        $partner = new Partner();
        $partner->img = '/storage/images/partners/' . $fileName;

        $partner->fill($request->only([
            'title', 'service', 'about', 'benefit',
            'email', 'contact', 'phone', 'url',
            'webinar_link'
        ]));

        $partner->save();

        return $partner;
    }

    private function getFileName($userID, $imageData)
    {
        return $userID . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
    }
}
