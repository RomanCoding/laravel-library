<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideo;
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
        return $videos->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreVideo $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVideo $request)
    {
        return Video::create([
            'link' => $this->parseVideoId($request->link),
            'type' => $request->type,
            'description' => $request->description,
            'display' => true
        ]);
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
     * @return array
     * @throws \Exception
     */
    public function destroy(Video $video)
    {
        return [
            'success' => $video->delete()
        ];
    }

    /**
     * Get video ID from the link.
     *
     * @param $link
     * @return string
     */
    private function parseVideoId($link)
    {
        return str_after($link, 'https://vimeo.com/');
    }
}
