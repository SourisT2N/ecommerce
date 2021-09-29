<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if(!$request->ajax())
            abort(404);
        $products = Auth::user()->carts()->with('categories:id,name')->get()->map(function ($item){
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'nameCategory' => $item->categories->name,
                    'count' => $item->cart->count,
                    'price' => $item->cart->price,
                    'image_display' => $item->image_display,
                    'url' => route('product.show',['name' => $item->name_not_utf8])
                ];
        });
        $total = $products->sum('price');
        return response()->json(['data' => $products->toArray(),'total' => $total,'status' => 200],JsonResponse::HTTP_OK);        
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
            $id = $request->all()['data-id'];
            $product = Product::findOrFail($id);
            $cart = Auth::user()->carts()->wherePivot('id_product',$id)->first();
            if($cart)
                $cart->cart->update([
                    'count' => ++$cart->cart->count,
                    'price' => ($product->price_new ?: $product->price_old) * $cart->cart->count,
                ]);
            else
                Auth::user()->carts()->attach($id,[
                    'count' => 1,
                    'price' => ($product->price_new ?: $product->price_old)
                ]);
            return response()->json(['message' => 'Thêm Vào Giỏ Thành Công', 'status' => 201],JsonResponse::HTTP_CREATED);
        }
        catch (Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không Tìm Thấy Sản Phẩm','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => 'Lỗi Server', 'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
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
            $validate = Validator::make($request->all(),
                ['count' => 'required|integer|min:1'],
                ['count.*' => 'Số lượng chỉ được lớn hơn 1']
            );
            if($validate->fails())
                throw new ValidationException($validate);
            $count = $validate->validated()['count'];
            $cart = Auth::user()->carts()->wherePivot('id_product',$id)->first();
            if(!$cart)
                throw new ModelNotFoundException();
            $cart->cart->update([
                'count' => $count,
                'price' => ($cart->price_new?:$cart->price_old) * $count
            ]);
            $total = Auth::user()->carts()->sum('price');
            return response()->json(['data' => ['count' => $cart->cart->count,'price' => $cart->cart->price,'total' => $total]
            ,'status' => 202],JsonResponse::HTTP_ACCEPTED);
        }
        catch (Exception $e)
        {
            if($e instanceof ValidationException)
                return response()->json(['error' => $e->errors(),'status' => 404],JsonResponse::HTTP_NOT_FOUND);
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không Tìm Thấy Sản Phẩm','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
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
            $cart = Auth::user()->carts()->wherePivot('id_product',$id)->first();
            if(!$cart)
                throw new ModelNotFoundException();
            $cart->cart->delete();
            $total = Auth::user()->carts()->sum('price');
            return response(['message' => 'Xoá Thành Công','total' => $total,'status' => '202'],JsonResponse::HTTP_ACCEPTED);
        }
        catch (Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không Tìm Thấy Sản Phẩm','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => 'Lỗi Server','status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
