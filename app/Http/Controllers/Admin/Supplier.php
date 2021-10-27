<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier as Sup;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Supplier extends Controller
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
            $data = Sup::query()
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
            $data = Sup::latest()->paginate(5);
        }
        $name = 'supplier';
        $query = $request->only(['order','type','s']);
        $nameRoute = $request->route()->getName();
        if($request->ajax())
            return view('admin.supplier.page',compact('data','query','nameRoute'))->render();
        return view('admin.supplier.index',compact('data','query','nameRoute','name'));
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
            ['name' => 'required|unique:suppliers,name|string|min:2|max:20'],
            ['name.*' => ':attribute chưa được khởi tạo và độ dài 2 - 20'],
            ['name' => 'Tên thương hiệu']);
            if($validator->fails())
                throw new ValidationException($validator->errors());
            $data = $validator->validated();
            $data['name'] = Str::title($data['name']);
            $str = '';
            foreach(explode(' ',$data['name']) as $val)
                $str .= Str::ascii($val[0]);
            $data['code_supplier'] = $str . mt_rand(100,999);
            Sup::create($data);
            return response()->json(['message' => 'Thêm thương hiệu thành công','status' => 201],JsonResponse::HTTP_CREATED);
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
            $supplier = Sup::findOrFail($id);
            return response()->json(['data' => $supplier->toArray(),'status' => 200],JsonResponse::HTTP_OK);
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
            ['name' => 'required|string|min:2|max:20|unique:suppliers,name,'.$id],
            ['name.*' => ':attribute chưa được khởi tạo và độ dài 2 - 20'],
            ['name' => 'Tên thương hiệu']);
            if($validator->fails())
                throw new ValidationException($validator->errors());
            $data = $validator->validated();
            $supplier = Sup::findOrFail($id);
            $supplier->name = Str::title($data['name']);
            $str = '';
            foreach(explode(' ',$data['name']) as $val)
                $str .= Str::ascii($val[0]);
            $supplier->code_supplier = $str . mt_rand(100,999);
            $supplier->save();
            return response()->json(['message' => 'Sửa thương hiệu thành công','status' => 202],JsonResponse::HTTP_ACCEPTED);
        }
        catch(Exception $e)
        {
            if($e instanceof ValidationException)
                return response()->json(['error' => $e->validator,'status' => 422],JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy thương hiệu','status' => 404],JsonResponse::HTTP_NOT_FOUND);
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
            Sup::findOrFail($arrId);
            Sup::destroy($arrId);
            return response('',JsonResponse::HTTP_NO_CONTENT);
        }
        catch(Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy thương hiệu','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
