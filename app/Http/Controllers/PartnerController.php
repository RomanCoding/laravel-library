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
}
