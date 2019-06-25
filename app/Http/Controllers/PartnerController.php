<?php

namespace App\Http\Controllers;

use App\Partner;
use Illuminate\Http\Request;

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

    public function destroy(Partner $partner)
    {
        return [
            'success' => $partner->update(['deleted' => true])
        ];
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
            'url' => $request->get('url', $partner->url),
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image64:jpeg,jpg,png'
        ]);

        $randomFileName = 'partner' . time();
        $fileName = $this->getFileName($randomFileName, $request->image);
        Image::make($request->image)->save(public_path('images/partners/') . $fileName);

        $partner = new Partner();
        $partner->img = '/images/partners/' . $fileName;

        $partner->fill($request->only([
            'title', 'service', 'about', 'benefit',
            'email', 'contact', 'phone', 'url'
        ]));

        $partner->save();

        return $partner;
    }

    private function getFileName($userID, $imageData)
    {
        return $userID . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
    }
}
