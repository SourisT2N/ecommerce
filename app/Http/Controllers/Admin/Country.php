<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country as CT;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class Country extends Controller
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
            $data = CT::query()
            ->when(!empty($request->s??''),function($q) use ($request) {
                return $q->where('name','like',"%".$request->s."%");
            })
            ->when($request->order && $request->type,function($q) use ($request) {
                return $q->orderBy($request->order,$request->type);
            })
            ->paginate(5);
        }
        catch (Exception $e)
        {
            $data = CT::latest()->paginate(5);
        }
        $name = 'country';
        $query = $request->only(['order','type','s']);
        $nameRoute = $request->route()->getName();
        if($request->ajax())
            return view('admin.country.page',compact('data','query','nameRoute'))->render();
        return view('admin.country.index',compact('data','query','nameRoute','name'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(),
            ['name' => 'required|unique:countries,name|string|min:2|max:20'],
            ['name.*' => ':attribute chưa được khởi tạo và độ dài 2 - 20'],
            ['name' => 'Tên quốc gia']);
            if($validator->fails())
                throw new ValidationException($validator->errors());
            $data = $validator->validated();
            $data['name'] = Str::title($data['name']);
            $str = '';
            foreach(explode(' ',$data['name']) as $val)
                $str .= Str::ascii($val[0]);
            $data['code_country'] = $str . mt_rand(100,999);
            CT::create($data);
            return response()->json(['message' => 'Thêm quốc gia thành công','status' => 201],JsonResponse::HTTP_CREATED);
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
            $country = CT::findOrFail($id);
            return response()->json(['data' => $country->toArray(),'status' => 200],JsonResponse::HTTP_OK);
        }
        catch(Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy quốc gia','status' => 404],JsonResponse::HTTP_NOT_FOUND);
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
            ['name' => 'required|string|min:2|max:20|unique:categories,name,'.$id],
            ['name.*' => ':attribute chưa được khởi tạo và độ dài 2 - 20'],
            ['name' => 'Tên quốc gia']);
            if($validator->fails())
                throw new ValidationException($validator->errors());
            $data = $validator->validated();
            $country = CT::findOrFail($id);
            $country->name = Str::title($data['name']);
            $str = '';
            foreach(explode(' ',$data['name']) as $val)
                $str .= Str::ascii($val[0]);
            $country->code_country = $str . mt_rand(100,999);
            $country->save();
            return response()->json(['message' => 'Sửa quốc gia thành công','status' => 202],JsonResponse::HTTP_ACCEPTED);
        }
        catch(Exception $e)
        {
            if($e instanceof ValidationException)
                return response()->json(['error' => $e->validator,'status' => 422],JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy quốc gia','status' => 404],JsonResponse::HTTP_NOT_FOUND);
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
            CT::findOrFail($arrId);
            CT::destroy($arrId);
            return response('',JsonResponse::HTTP_NO_CONTENT);
        }
        catch(Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy quốc gia','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
