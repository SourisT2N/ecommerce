<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderStatus as OS;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class OrderStatus extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        try
        {
            $data = OS::query()
            ->when(!empty($request->s??''),function($q) use ($request) {
                return $q->where('name','like',"%".$request->s."%");
            })
            ->when($request->order && $request->type,function($q) use ($request) {
                return $q->orderBy($request->order,$request->type);
            })
            ->paginate(5);
        }
        catch(Exception $e)
        {
            $data = OS::latest()->paginate(5);
        }
        $name = 'status';
        $query = $request->only(['order','type','s']);
        $nameRoute = $request->route()->getName();
        if($request->ajax())
            return view('admin.orderstatus.page',compact('data','query','nameRoute'))->render();
        return view('admin.orderstatus.index',compact('data','query','nameRoute','name'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try
        {
            $validator = Validator::make($request->all(),
            ['name' => 'required|unique:order_status,name|string|min:2|max:20'],
            ['name.*' => ':attribute chưa được khởi tạo và độ dài 2 - 20'],
            ['name' => 'Tên trạng thái']);
            if($validator->fails())
                throw new ValidationException($validator->errors());
            $data = $validator->validated();
            $data['name'] = Str::title($data['name']);
            OS::create($data);
            return response()->json(['message' => 'Thêm trạng thái thành công','status' => 201],JsonResponse::HTTP_CREATED);
        }
        catch(Exception $e)
        {
            if($e instanceof ValidationException)
                return response()->json(['error' => $e->validator,'status' => 422],JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            return response()->json(['message' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try
        {
            $status = OS::findOrFail($id);
            return response()->json(['data' => $status->toArray(),'status' => 200],JsonResponse::HTTP_OK);
        }
        catch(Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy thương hiệu','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        try
        {
            $validator = Validator::make($request->all(),
            ['name' => 'required|string|min:2|max:20|unique:order_status,name,'.$id],
            ['name.*' => ':attribute chưa được khởi tạo và độ dài 2 - 20'],
            ['name' => 'Tên thương hiệu']);
            if($validator->fails())
                throw new ValidationException($validator->errors());
            $data = $validator->validated();
            $status = OS::findOrFail($id);
            $status->name = Str::title($data['name']);
            $status->save();
            return response()->json(['message' => 'Sửa trạng thái thành công','status' => 202],JsonResponse::HTTP_ACCEPTED);
        }
        catch(Exception $e)
        {
            if($e instanceof ValidationException)
                return response()->json(['error' => $e->validator,'status' => 422],JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy trạng thái','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['message' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
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
        try
        {
            $arrId = explode(',',$id);
            OS::findOrFail($arrId);
            OS::destroy($arrId);
            return response('',JsonResponse::HTTP_NO_CONTENT);
        }
        catch(Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy trạng thái','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
