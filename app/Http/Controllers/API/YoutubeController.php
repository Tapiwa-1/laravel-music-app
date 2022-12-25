<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Youtube\StoreYoutubeRequest;
use App\Models\Youtube;
use Illuminate\Http\Request;

class YoutubeController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreYoutubeRequest $request)
    {

        try {
            $yt = new Youtube();

            $yt->user_id = $request->get('user_id');
            $yt->title = $request->get('title');
            $yt->url = "https://www.youtube-nocookie.com/embed/". $request->get('url') ."?rel=0&amp;controls=0&amp;showinfo=0";
            $yt->save();

            return response()->json('New Youtube link saved', 200);

        }catch (\Exception $e){
            return response()->json([
                'message'=>'Something went wrong in YoutubeController.store',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Youtube  $youtube
     * @return \Illuminate\Http\Response
     */
    public function show(int $user_id)
    {
        try {

            $videosByUser = Youtube::where('user_id', $user_id)->get();
            return response()->json(['videos_by_user'=>$videosByUser], 200);

        }catch (\Exception $e){
            return response()->json([
                'message'=>'Something went wrong in YoutubeController.show',
                'error' => $e->getMessage(),
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Youtube  $youtube
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
        try {

            $yt = Youtube::findOrFail($id);
            $yt->delete();
            return response()->json('Video deleted', 200);

        }catch (\Exception $e){
            return response()->json([
                'message'=>'Something went wrong in YoutubeController.deleted',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
