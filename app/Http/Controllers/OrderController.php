<?php

namespace App\Http\Controllers;

use App\Events\OrderShopping;
use App\Http\Requests\OrderRequest;
use App\Models\Payment;
use App\Services\PayPalService;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class OrderController extends Controller
{
    //
    public function orderDefault(OrderRequest $request)
    {
        try
        {
            $data = $request->validated();

            $carts = Auth::user()->carts();
            if(!$carts->count())
                throw new ModelNotFoundException('Chưa Có Sản Phẩm');
            $total = $carts->sum('price');
            if($data['payment'] == 1 && Arr::has($data,'orderId'))
                throw new ValidationException('Dữ Liệu Nhập Vào Không Đúng');
            if($data['payment'] != 1 && Arr::has($data,'orderId'))
            {
                if(!$this->checkOrder($data['orderId'],$data['payment']))
                    throw new ErrorException('Không Tìm Thấy Mã Đơn Hàng Hoặc Mã Đơn Hàng Đã Bị Huỷ');
                $data['code_billing'] = Arr::pull($data,'orderId');
                $data['status_payment'] = 1;
            }
            else
                $data['code_billing'] = Str::random(15) . (Carbon::now())->format('u');
            $data['id_status'] = 3;
            $data['total'] = $total;
            $data['id_payment'] = $data['payment'];
            unset($data['payment']);
            $order = Auth::user()->billing()->create($data);
            foreach ($carts->get() as $item)
                $order->details()->attach($item->cart->id_product, [
                    'count' => $item->cart->count,
                    'price' => $item->cart->price,
                ]);
            $carts->detach();
            event(new OrderShopping($order,Auth::user()));
            return response()->json(['message' => 'Đặt Hàng Thành Công','status' => 200],JsonResponse::HTTP_OK);
        }
        catch (Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => $e->getMessage(), 'status' => 404],JsonResponse::HTTP_NOT_FOUND);
            if($e instanceof ValidationException)
                return response()->json(['error' => $e->getMessage(), 'status' => 422],JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            if($e instanceof ErrorException)
                return response()->json(['error' => $e->getMessage(), 'status' => 400],JsonResponse::HTTP_BAD_REQUEST);
            return response()->json(['error' => 'Lỗi Server','status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function orderTotal($id)
    {
        try
        {
            if(!request()->ajax())
                abort(404);
            Payment::findOrFail($id);
            $total = Auth::user()->carts()->sum('price');
            if($id != 1)
            {
                $res = Http::get('https://api.exchangerate.host/latest',[
                    'base' => 'VND',
                    'symbols' => 'USD',
                    'amount' => $total
                ]);
                $total = round($res->json('rates.USD'),2);
            }
            return response()->json(['total' => $total,'status' => 200],JsonResponse::HTTP_OK);
        }
        catch (Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không Tìm Thấy Phương Thức Thanh Toán','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => 'Lỗi Server','status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function checkOrder($orderId,$id)
    {
        try
        {
            $order = Auth::user()->billing()->where('code_billing',$orderId)->first();
            if($order)
                throw new ErrorException('Mã Đơn Hàng Đã Tồn Tại');
            if($id == 2)
                return PayPalService::checkOrder($orderId);
        }
        catch (Exception $e)
        {
            if($e instanceof ErrorException)
                throw new ErrorException($e->getMessage());
        }
    }

    public function updateOrder($id)
    {
        try
        {
            $order = Auth::user()->billing()->where('code_billing',$id)->first();
            if(!$order)
                throw new ModelNotFoundException();
            if((int)$order->id_status === 9)
                throw new ErrorException('Đơn Hàng Đã Bị Huỷ Rồi');
            elseif((int)$order->id_status === 8)
                throw new ErrorException('Đơn Hàng Đã Được Giao Không Thể Huỷ Đơn');
            $order->update(['id_status' => 9]);
            event(new OrderShopping($order,Auth::user()));
            return response()->json(['message' => 'Đơn Hàng Đã Được Huỷ','status' => 200],JsonResponse::HTTP_OK);
        }
        catch (Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Đơn Hàng Không Tồn Tại','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            if($e instanceof ErrorException)
                return response()->json(['error' => $e->getMessage(),'status' => 400],JsonResponse::HTTP_BAD_REQUEST);
            return response()->json(['error' => 'Lỗi Server','status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
