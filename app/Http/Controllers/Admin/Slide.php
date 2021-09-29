<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SlideRequest;
use Illuminate\Http\Request;
use App\Models\Slide as Sl;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Slide extends Controller
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
            $data = Sl::query()
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
            $data = Sl::latest()->paginate(5);
        }
        $name = 'slide';
        $query = $request->only(['order','type','s']);
        $nameRoute = $request->route()->getName();
        if($request->ajax())
            return view('admin.slide.page',compact('data','query','nameRoute'))->render();
        return view('admin.slide.index',compact('data','query','nameRoute','name'));

    }

    public function create()
    {
        $title = 'Thêm Slide';
        return view('admin.slide.form',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SlideRequest $request)
    {
        //
        try
        {
            $path = $request->file('image_display')->store('slides','public');
            $data = $request->validated();
            $data['subject'] = Str::title($data['subject']);
            $data['image_display'] = $path;
            Sl::create($data);
            return response()->json(['message' => 'Thêm slide thành công','status' => 201],JsonResponse::HTTP_CREATED);
        }
        catch(Exception $e)
        {
            return response()->json(['message' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        try
        {
            $slide = Sl::findOrFail($id);
            $title = 'Sửa Blog';
            return view('admin.slide.form',compact('slide','title'));
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
    public function update(SlideRequest $request, $id)
    {
        //
        try
        {
            $slide = Sl::findOrFail($id);
            $data = $request->validated();
            $slide->subject = Str::title($data['subject']);
            $slide->content = $data['content'];
            $slide->url = $data['url'];
            if($request->hasFile('image_display'))
            {
                Storage::disk('public')->delete($slide->image_display);
                $path = $request->file('image_display')->store('slides','public');
                $slide->image_display = $path;
            }
            $slide->save();
            return response()->json(['message' => 'Sửa slide thành công','status' => 202],JsonResponse::HTTP_ACCEPTED);
        }
        catch(Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy slide','status' => 404],JsonResponse::HTTP_NOT_FOUND);
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
            $slides = Sl::findOrFail($arrId);
            foreach($slides as $val) 
                Storage::disk('public')->delete($val->image_display);           
            Sl::destroy($arrId);
            return response('',JsonResponse::HTTP_NO_CONTENT);
        }
        catch(Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy slide','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
