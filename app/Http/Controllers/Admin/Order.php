<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Billing as Bil;
use App\Models\OrderStatus;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Order extends Controller
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
            $data = Bil::query()
            ->select('id','code_billing','total','status_payment','created_at','id_status')
            ->with('orderStatus:id,name')
            ->when(!empty($request->s??''),function($q) use ($request) {
                return $q->where('code_billing','like',"%".$request->s."%");
            })
            ->when($request->order,function($q) use ($request) {
                if($request->order == 1)
                    return $q->orderBy('created_at','desc')->orderBy('updated_at','desc');
                return $q->orderBy('created_at','asc')->orderBy('updated_at','asc');
            })
            ->when(!$request->order || !$request->type,function($q) use ($request) {
                $q->orderBy('created_at','desc')->orderBy('updated_at','desc');
            })
            ->paginate(10);
        }
        catch (Exception $e)
        {
            $data = Bil::latest()->paginate(10);
        }
        $name = 'order';
        $query = $request->only(['order','type','s']);
        $nameRoute = $request->route()->getName();
        if($request->ajax())
            return view('admin.order.page',compact('data','query','nameRoute'))->render();
        return view('admin.order.index',compact('data','query','nameRoute','name'));
    }

    public function edit($id)
    {
        try
        {
            $order = Bil::findOrFail($id)->load('orderStatus:id,name','payments:id,name','users:id,name','details:id,name');
            $orderStatus = OrderStatus::all();
            $title = 'Thông Tin Đơn Hàng';
            return view('admin.order.form',compact('order','title','orderStatus','order'));
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
    public function update(Request $request, $id)
    {
        //
        try
        {
            $validate = Validator::make($request->all(),[
                'payment' => 'required|integer|between:0,1',
                'status' => 'required|exists:order_status,id'
            ],[
                'payment.*' => 'Trạng thái thanh toán chỉ không đúng',
                'status.*' => 'Không tìm thấy trạng thái đơn hàng'
            ]);
            if($validate->fails())
                throw new ValidationException($validate);
            $data = $validate->validated();
            $order = Bil::findOrFail($id);
            $order->update([
                'status_payment' => $data['payment'],
                'id_status' => $data['status']
            ]);
            
            return response()->json(['message' => 'Cập Nhật Đơn Hàng Thành Công','status' => 202],JsonResponse::HTTP_ACCEPTED);
        }
        catch(Exception $e)
        {
            if($e instanceof ValidationException)
                return response()->json(['error' => $e->errors(),'status' => 422],JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không Tìm Thấy Đơn Hàng','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => 'Lỗi Server','status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
