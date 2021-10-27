<?php

namespace App\Http\Controllers;

use App\Events\CommentEvent;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        //
        try
        {
            $validate = Validator::make($request->all(),[
                'comment' => 'required|min:5',
            ],
            [
                'comment.*' => 'Đánh Giá Không Được Để Trống'
            ]);

            if($validate->fails())
                throw new ValidationException($validate);
            $data = $validate->validated();
            $data['created_at'] = Carbon::now()->toDateTimeString();
            Product::findOrFail($id)->comments()->attach(Auth::user()->id,$data);
            event(new CommentEvent($data,Auth::user(),$id));
            return response()->json(['message' => 'Thêm Đánh Giá Thành Công','status' => 201],JsonResponse::HTTP_CREATED);
        }
        catch (Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không Tìm Thấy Sản Phẩm','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            if($e instanceof ValidationException)
                return response()->json(['error' => $e->errors(),'status' => 422],JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            return response()->json(['error' => 'Lỗi Server','status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
