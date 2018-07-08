<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $this->authorize('view', Video::class);
        } catch (AuthorizationException $e) {
            return response()->json([]);
        }
        $videos = Video::where('type', $request->get('type', 'webinar'));
        if (!$request->user()->isAdmin()) {
            $videos = $videos->where('display', true);
        }
        return $videos->get();
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
            'link' => 'required|regex:/(https:\/\/vimeo\.com\/(\d*))/',
            'type' => 'required|in:webinar,video',
            'description' => 'nullable|string'
        ]);
        $id = $this->parseVideoId($request->get('link'));
        $video = Video::create([
            'link' => $id,
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

    private function parseVideoId($link)
    {
        return str_after($link, 'https://vimeo.com/');
    }
}
