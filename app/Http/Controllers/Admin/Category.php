<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category as Cate;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class Category extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $data = Cate::query()
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
            $data = Cate::paginate(5);   
        }
        $name = 'category';
        $query = $request->only(['order','type','s']);
        $nameRoute = $request->route()->getName();
        if($request->ajax())
            return view('admin.category.page',compact('data','query','nameRoute'))->render();
        return view('admin.category.index',compact('data','query','nameRoute','name'));
    }

    public function show($id)
    {
        try
        {
            $category = Cate::findOrFail($id);
            return response()->json(['data' => $category->toArray(),'status' => 200],JsonResponse::HTTP_OK);
        }
        catch(Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy danh mục','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(),
            ['name' => 'required|unique:categories,name|string|min:2|max:20'],
            ['name.*' => ':attribute chưa được khởi tạo và độ dài 2 - 20'],
            ['name' => 'Tên danh mục']);
            if($validator->fails())
                throw new ValidationException($validator->errors());
            $data = $validator->validated();
            $data['name_not_utf8'] = Str::slug($data['name']);
            $data['name'] = Str::title($data['name']);
            $str = '';
            foreach(explode(' ',$data['name']) as $val)
                $str .= Str::ascii($val[0]);
            $data['code_category'] = $str . mt_rand(100,999);
            Cate::create($data);
            return response()->json(['message' => 'Thêm danh mục thành công','status' => 201],JsonResponse::HTTP_CREATED);
        }
        catch(Exception $e)
        {
            if($e instanceof ValidationException)
                return response()->json(['error' => $e->validator,'status' => 422],JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            return response()->json(['message' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request,$id)
    {
        try
        {
            $validator = Validator::make($request->all(),
            ['name' => 'required|string|min:2|max:20|unique:categories,name,'.$id],
            ['name.*' => ':attribute chưa được khởi tạo và độ dài 2 - 20'],
            ['name' => 'Tên danh mục']);
            if($validator->fails())
                throw new ValidationException($validator->errors());
            $data = $validator->validated();
            $cate = Cate::findOrFail($id);
            $cate->name = Str::title($data['name']);
            $cate->name_not_utf8 = Str::slug($data['name']);
            $str = '';
            foreach(explode(' ',$data['name']) as $val)
                $str .= Str::ascii($val[0]);
            $cate->code_category = $str . mt_rand(100,999);
            $cate->save();
            return response()->json(['message' => 'Sửa danh mục thành công','status' => 202],JsonResponse::HTTP_ACCEPTED);
        }
        catch(Exception $e)
        {
            if($e instanceof ValidationException)
                return response()->json(['error' => $e->validator,'status' => 422],JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy danh mục','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['message' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try
        {
            $arrId = explode(',',$id);
            Cate::findOrFail($arrId);
            Cate::destroy($arrId);
            return response('',JsonResponse::HTTP_NO_CONTENT);
        }
        catch(Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy danh mục','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
