<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Requests\StoreEvent;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Event::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEvent $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEvent $request)
    {
        return Event::create($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return array|\Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        return [
            'success' => $event->delete()
        ];
    }
}
