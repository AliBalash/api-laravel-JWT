<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post as modelPost;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseTraits;
class Post extends Controller
{

use ResponseTraits;

    public function allPost()
    {
//        return response()->json(modelPost::orderBy('id', 'DESC')->get(), 200);
        return $this->returnData('Post',modelPost::orderBy('id', 'DESC')->get(),'All Post');
    }

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3',
            'desc' => "required|",
            'user_id' => "required|numeric|exists:users,id",

        ],
            [
//                'title.required'=>"اینم فیلد خالی هست",
//                'desc.required'=>"اینم فیلد خالی هست",
//                'user_id.required'=>"اینم فیلد خالی هست",
            ]);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code , $validator);
//            return response()->json($validator->errors(), 400);
        }

        $post = modelPost::create($request->all());
        if ($post) {
//            return response()->json($post, 200);

            return $this->returnData('Post',$post,'Crated Post');

        } else {

            return $this->returnError('404','Post not Created');
//            return response()->json('Post Not Created !!', 400);
        }

    }

    public function getById($id)
    {
        $post = modelPost::find($id);
        if ($post) {
            return $this->returnData('Post '.$post->id,$post,'get post by id');
//            return response()->json($post, 200);

        } else {
            return $this->returnError('404','Post not found for show get by id');
//            return response()->json('Post not found for show get by id', 404);

        }
    }

    public function update(Request $request)
    {

        $post = modelPost::find($request->id);
        if ($post) {
            $update_post = $post->update($request->all());
            if ($update_post) {
                return $this->returnData('Post'.$post->id ,$post,'Updated Post');

//                return response()->json($post);
            } else
                return $this->returnError('404','Post Not Updated');

//                return response()->json('Post Not Updated', 400);
        } else
            return $this->returnError('404','Post Not Found for Updated');

//            return response()->json('Post Not Found for Updated', 404);
    }

    public function delete(Request $request)
    {

        $post = \App\Models\Post::find($request->id);
        if ($post) {
            $post->delete();
            return $this->returnData('Post',$post,'Deleted Post'.$post->id);

//            return response()->json('Post Deleted', 200);
        } else {
//            return response()->json('Post Not Found For Deleted');
            return $this->returnError('404','Post Not Found for Deleted');

        }
    }
}
