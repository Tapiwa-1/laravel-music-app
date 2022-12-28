<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use App\Services\ImageService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         try {
            $postPerPage = 10;
            $post = Post::with('user')->orderBy('updated_at', 'desc')->simplePaginate($postPerPage);
            $pageCount = count(Post::all()) / $postPerPage;

            return response()->json([
                'paginate'=> $post,
                'page_count'=>ceil($pageCount)
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'message'=>'Something went wrong in PostController.store',
                'error' => $e->getMessage(),
            ]);
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        //
        try {
            if ($request->hasFile('image')=== false){
                return response()->json(['error' => 'There is no image to upload'], 400);
            }

            $post = new Post;
            $post->user_id = $request->get('user_id');
            $post->image = $request->get('image');
            $post->title = $request->get('title');
            $post->location = $request->get('location');
            $post->description = $request->get('description');

            $post->save();

            (new ImageService)->updateImage($post, $request, '/images/posts/', 'store');

            return response()->json('New Post Created', 200);
        }catch (\Exception $e){
            return response()->json([
                'message'=>'Something went wrong in PostController.store',
                'error' => $e->getMessage(),

            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
        try {
            $post = Post::with('user')->findOrFail($id);

            return response()->json($post, 200);
        }catch (\Exception $e){
            return response()->json([
                'message'=>'Something went wrong in PostController.store',
                'error' => $e->getMessage(),
            ]);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, int $id)
    {
        try {
            $post = Post::findOrFail($id);

            if ($request->hasFile('image')){
                (new ImageService)->updateImage($post, $request, '/images/posts/', 'update');
            }
            $post->title = $request->get('title');
            $post->location = $request->get('location');
            $post->description = $request->get('description');

            $post->save();



            return response()->json('New Post Updated', 200);
        }catch (\Exception $e){
            return response()->json([
                'message'=>'Something went wrong in PostController.update',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $post = Post::findOrFail($id);
            if (!empty($post->image)){
                $currentImage = public_path() . '/images/posts/' . $post->image;
                if (file_exists($currentImage) ){
                     unlink($currentImage);
                    }
            }
            $post->delete();



            return response()->json('New Post deleted', 200);
        }catch (\Exception $e){
            return response()->json([
                'message'=>'Something went wrong in PostController.destroy',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
