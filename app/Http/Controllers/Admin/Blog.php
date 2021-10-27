<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog as Bl;
use App\Http\Requests\BlogRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Blog extends Controller
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
            $data = Bl::query()
            ->when(!empty($request->s??''),function($q) use ($request) {
                return $q->where('name','like',"%".$request->s."%");
            })
            ->when($request->order && $request->type,function($q) use ($request) {
                return $q->orderBy($request->order,$request->type);
            })
            ->when(!$request->order || !$request->type,function($q) use ($request) {
                return $q->orderBy('created_at','desc')->orderBy('updated_at','desc');
            })
            ->paginate(10);
        }
        catch (Exception $e)
        {
            $data = Bl::latest()->paginate(10);
        }
        $name = 'blog';
        $query = $request->only(['order','type','s']);
        $nameRoute = $request->route()->getName();
        if($request->ajax())
            return view('admin.blog.page',compact('data','query','nameRoute'))->render();
        return view('admin.blog.index',compact('data','query','nameRoute','name'));
    }

    public function create()
    {
        $title = 'Thêm Blog';
        return view('admin.blog.form',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogRequest $request)
    {
        //
        try
        {
            $path = $request->file('image_display')->store('blogs','public');
            $data = $request->validated();
            $data['name'] = Str::title($data['name']);
            $data['name_not_utf8'] = Str::slug($data['name']);
            $data['image_display'] = $path;
            Bl::create($data);
            return response()->json(['message' => 'Thêm blog thành công','status' => 201],JsonResponse::HTTP_CREATED);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        try
        {
            $blog = Bl::findOrFail($id);
            $title = 'Sửa Blog';
            return view('admin.blog.form',compact('blog','title'));
        }
        catch(Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return view('admin.layouts.blank');
            abort(500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogRequest $request, $id)
    {
        //
        try
        {
            $blog = Bl::findOrFail($id);
            $data = $request->validated();
            $blog->name = Str::title($data['name']);
            $blog->name_not_utf8 = Str::slug($data['name']);
            $blog->summary = $data['summary'];
            $blog->body = $data['body'];
            if($request->hasFile('image_display'))
            {
                Storage::disk('public')->delete($blog->image_display);
                $path = $request->file('image_display')->store('blogs','public');
                $blog->image_display = $path;
            }
            $blog->save();
            return response()->json(['message' => 'Sửa blog thành công','status' => 202],JsonResponse::HTTP_ACCEPTED);
        }
        catch(Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy blog','status' => 404],JsonResponse::HTTP_NOT_FOUND);
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
            $blogs = Bl::findOrFail($arrId);
            foreach($blogs as $val) 
                Storage::disk('public')->delete($val->image_display);           
            Bl::destroy($arrId);
            return response('',JsonResponse::HTTP_NO_CONTENT);
        }
        catch(Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy blog','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
