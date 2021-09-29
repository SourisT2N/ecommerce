<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User as Us;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class User extends Controller
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
            $data = Us::query()
            ->with('roles')
            ->when(!empty($request->s??''),function($q) use ($request) {
                return $q->where('name','like',"%".$request->s."%");
            })
            ->when($request->order && $request->type,function($q) use ($request) {
                return $q->orderBy($request->order,$request->type);
            })
            ->when(!$request->order || !$request->type,function($q) use ($request) {
                $q->orderBy('created_at','desc')->orderBy('updated_at','desc');
            })
            ->paginate(10);
        }
        catch (Exception $e)
        {
            $data = Us::latest()->paginate(10);
        }
        $name = 'user';
        $query = $request->only(['order','type','s']);
        $nameRoute = $request->route()->getName();
        if($request->ajax())
            return view('admin.user.page',compact('data','query','nameRoute'))->render();
        return view('admin.user.index',compact('data','query','nameRoute','name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roles = Role::all();
        $title = 'Thêm Người Dùng';
        return view('admin.user.form',compact('title','roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        //
        try
        {
            $data = $request->validated();
            $data['name'] = Str::title($data['name']);
            $data['password'] = bcrypt($data['password']);
            $roles = Arr::pull($data,'roles');
            $roles[] = 'user';
            Us::create($data)->assignRole($roles);
            return response()->json(['message' => 'Thêm người dùng thành công','status' => 201],JsonResponse::HTTP_CREATED);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => $e->getMessage(),'status'],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
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
            $roles = Role::all();
            $user = Us::findOrFail($id)->load('roles');
            $title = 'Sửa Người Dùng';
            return view('admin.user.form',compact('user','title','roles'));
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
    public function update(UserRequest $request, $id)
    {
        //
        try
        {
            $user = Us::findOrFail($id);
            if($id != Auth::user()->id && $user->hasRole('super-admin'))
                throw new ModelNotFoundException();
            $data = $request->validated();
            $data['name'] = Str::title($data['name']);
            $roles = Arr::pull($data, 'roles');
            $roles[] = 'user';
            if(Arr::has($data,'password'))
                $data['password'] = bcrypt($data['password']);
            $user->update($data);
            $user->syncRoles($roles);
            return response()->json(['message' => 'Sửa người dùng thành công','status' => 202],JsonResponse::HTTP_ACCEPTED);
        }
        catch(Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy người dùng hoặc bạn không đủ quyền để sửa','status' => 404],JsonResponse::HTTP_NOT_FOUND);
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
            $users = Us::findOrFail($arrId)->diff(Us::whereHas('roles',function($q){
                $q->where('name','super-admin');
            })->find($arrId));
            if(!$users->count())
                throw new ModelNotFoundException();
            foreach($users as $user)
                $user->syncRoles();
            Us::destroy($users->pluck('id'));
            return response('',JsonResponse::HTTP_NO_CONTENT);
        }
        catch(Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy người dùng hoặc bạn không đủ quyền để xoá','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
