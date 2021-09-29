<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\Product as Pr;
use App\Models\Supplier;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Controller
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
            $data = Pr::query()
            ->with('categories','suppliers','countries')
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
            $data = Pr::latest()->paginate(10);
        }
        $name = 'product';
        $query = $request->only(['order','type','s']);
        $nameRoute = $request->route()->getName();
        if($request->ajax())
            return view('admin.product.page',compact('data','query','nameRoute'))->render();
        return view('admin.product.index',compact('data','query','nameRoute','name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        try
        {
            $categories = Category::all();
            $countries = Country::all();
            $suppliers = Supplier::all();
            $title = 'Thêm Sản Phẩm';
            return view('admin.product.form', compact('title','categories','suppliers','countries'));
        }
        catch (Exception $e)
        {
            abort(500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        //
        try
        {
            $folder = 'products/'.Carbon::now()->timestamp;
            $pathImage = $request->file('image_display')->store($folder,'public');
            $images = [];
            foreach($request->file('images') as $image)
            {
                $path = $image->store($folder,'public');
                $images[]['image_display'] = $path;
            }
            $data = $request->validated();
            unset($data['images']);
            $data['name_not_utf8'] = Str::slug($data['name']);
            $data['image_display'] = $pathImage;
            Pr::create($data)->images()->createMany($images);
            return response()->json(['message' => 'Thêm sản phẩm thành công','status' => 201],JsonResponse::HTTP_CREATED);
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
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
            $product = Pr::findOrFail($id)->load('images');
            $categories = Category::all();
            $countries = Country::all();
            $suppliers = Supplier::all();
            $title = 'Sửa Blog';
            return view('admin.product.form',compact('product','title','categories','suppliers','countries'));
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
    public function update(ProductRequest $request, $id)
    {
        //
        try
        {
            $product = Pr::findOrFail($id)->load('images');
            preg_match('`^\w*\/\d*`',$product->image_display,$folder);
            $data = $request->validated();
            unset($data['images']);
            $data['name_not_utf8'] = Str::slug($data['name']);
            if($request->hasFile('image_display'))
            {
                Storage::disk('public')->delete($product->image_display);
                $pathImage = $request->file('image_display')->store($folder[0],'public');
                $data['image_display'] = $pathImage;
            }
            $product->update($data);
            if($request->hasFile('images'))
            {
                foreach($product->images as $val)
                    Storage::disk('public')->delete($val->image_display);
                $product->images()->delete();
                foreach($request->file('images') as $image)
                {
                    $path = $image->store($folder[0],'public');
                    $images[]['image_display'] = $path;
                }
                $product->images()->createMany($images);
            }
            return response()->json(['message' => 'Sửa sản phẩm thành công','status' => 202],JsonResponse::HTTP_ACCEPTED);
        }
        catch (Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy sản phẩm','status' => 404],JsonResponse::HTTP_NOT_FOUND);
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
            $products = Pr::findOrFail($arrId);
            foreach($products as $product)
            {
                $path = $product->image_display;
                preg_match('`^\w*\/\d*`',$path,$folder);
                Storage::disk('public')->deleteDirectory($folder[0]);
            }
            Pr::destroy($arrId);
            return response('',JsonResponse::HTTP_NO_CONTENT);
        }
        catch(Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Không tìm thấy sản phẩm','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
