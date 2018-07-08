<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{

    public function webinars(Request $request)
    {
        $videos = Video::where('type', 'webinar');
        if (!$request->user()->isAdmin()) {
            $videos = $videos->where('display', true);
        }
        return $videos->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'link' => 'required',
            'type' => 'required|in:webinar,video',
            'description' => 'nullable|string'
        ]);

        $video = Video::create([
            'link' => $request->get('link'),
            'type' => $request->get('type'),
            'description' => $request->get('description'),
            'display' => true
        ]);

        return $video;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Video $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Video $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Video $video
     * @return Video
     */
    public function update(Request $request, Video $video)
    {
        $this->validate($request, [
            'display' => 'required|boolean'
        ]);
        $video->update($request->only([
            'display'
        ]));
        return $video;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Video $video
     * @return bool
     */
    public function destroy(Video $video)
    {
        return [
            'success' => $video->delete()
        ];
    }
}
