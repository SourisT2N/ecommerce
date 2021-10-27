<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Country;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Slide;
use App\Models\Supplier;
use App\Models\UserActivation;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Notifications\UserActivation as UA;

class HomeController extends Controller
{
    //
    public function __construct()
    {
        $category = Category::all();
        $this->arrSort = [
            '1' => ['name','asc'],
            '2' => ['name','desc'],
            '3' => ['price_old','price_new','asc'],
            '4' => ['price_old','price_new','desc'],
            '5' => ['oldest'],
            '6' => ['latest']
        ];
        View::share('categories', $category);
    }

    public function index()
    {
        $productNew = Product::latest()->take(4)->get();
        $slides = Slide::all();
        $products = Category::has('products','>','0')->get();
        foreach($products as $cat)
            $cat->load(['products'=> function($q){
                $q->latest()->take(4);
            }]);
        $blogs = Blog::latest()->take(2)->get();
        return view('index',compact('products','blogs','productNew','slides'));
    }

    public function product($name = null,Request $request)
    {
        $countries = Country::all();
        $suppliers = Supplier::all();
        $query = $request->only(['sp','ct','ctry','sort','q']);
        $type = 'product';
        $parameter = $name?['name' => $name]:[];
        $nameRoute = $request->route()->getName();
        $products  = Product::query()
        ->with('categories')
        //1 Danh Mục
        ->when($name,function($q)use($name){
            $q->whereHas('categories',function($s)use($name){
                $s->where('name_not_utf8',$name);
            });
        })
        //Nhiều Danh Mục
        ->when($request->ct && !$name,function ($q)use($request){
            $ct = explode(' ',$request->ct);
            $q->whereHas('categories',function($s)use($ct){
                $s->whereIn('code_category',$ct);
            });
        })
        //Xuất Xứ
        ->when($request->ctry,function ($q)use($request){
            $ctry = explode(' ',$request->ctry);
            $q->whereHas('countries',function($s)use($ctry){
                $s->whereIn('code_country',$ctry);
            });
        })
        //Thương Hiệu
        ->when($request->sp,function ($q)use($request){
            $sp = explode(' ',$request->sp);
            $q->whereHas('suppliers',function($s)use($sp){
                $s->whereIn('code_supplier',$sp);
            });
        })
        //Sắp Xếp
        ->when(Arr::exists($this->arrSort,$request->sort),function($q)use($request){
            $val = $this->arrSort[$request->sort];
            if(count($val) == 2)
                $q->orderBy($val[0],$val[1]);
            elseif(count($val) == 3)
                $q->orderBy($val[0],$val[2])->orderBy($val[1],$val[2]);
            elseif(count($val) == 1)
                call_user_func([$q,$val[0]]);
        })
        //Tìm kiếm
        ->when($request->q,function($q)use($request){
            $q->where('name','like','%'.$request->q.'%');
        })
        ->when(!Arr::exists($this->arrSort,$request->sort),function($q){
            $q->latest();
        })
        ->paginate(9);
        return view('product',compact('products','countries','suppliers','query','type','nameRoute','parameter'));
    }

    public function showProduct($name)
    {
        $product = Product::with(['images','suppliers','countries','comments' => function($q){
            $q->orderByPivot('created_at','desc');
        }])->where(['name_not_utf8' => $name])->first();
        if(!$product)
            return redirect()->route('product');
        $products = Product::latest()->take(4)->get();
        return view('detailProduct',compact('product','products'));
    }

    public function blog(Request $request)
    {
        $query = $request->only('sort');
        $type = 'blog';
        $nameRoute = $request->route()->getName();
        $blogs = Blog::query()
        ->when($request->sort == 5 || $request->sort == 6,function($q)use($request){
            $val = $this->arrSort[$request->sort];
            call_user_func([$q,$val[0]]);
        })
        ->when($request->sort != 5 || $request->sort != 6,function($q){
            $q->latest();
        })
        ->paginate(6);
        return view('blog',compact('query','type','nameRoute','blogs'));
    }

    public function showBlog($name)
    {
        $blog = Blog::where(['name_not_utf8' => $name]);
        if(!$blog->count())
            return redirect()->route('blog');
        $blog->increment('count_view');
        $blog = $blog->first();
        return view('detailBlog',compact('blog'));
    }

    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function forgot()
    {
        return view('auth.forgot');
    }

    public function reset($code)
    {
        try
        {
            $token = UserActivation::where(['activation_code'=> $code,'type' => 'reset'])->first();
            if(!$token)
                throw new ModelNotFoundException();
            if(!(new Carbon($token->expires))->gte(Carbon::now()))
            {
                $token->delete();
                throw new ModelNotFoundException();
            }

            return view('auth.reset',compact('code'));
        }
        catch (Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                abort(404);
            abort(500);
        }
    }

    public function active($code)
    {
        try
        {
            $token = UserActivation::where(['activation_code'=> $code,'type' => 'email'])->with('user')->first();
            if(!$token)
                throw new ModelNotFoundException();
            $flag = 1;
            $message = '';
            if($token->user->status)
                $message = 'Bạn Đã Xác Thực Rồi. Nhấn Vào Link Bên Dưới Để Trở Lại Trang Chủ';
            else
            {
                if(!(new Carbon($token->expires))->gte(Carbon::now()))
                {
                    $flag = 0;
                    $token->user->notify(new UA($token->user,'email'));
                }
                else
                {
                    $token->user->update(['status' => 1]);
                    $token->delete();
                }
            }
            return view('auth.auth',compact('flag','message'));
        }
        catch(Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                abort(404);
            abort(500);
        }
    }

    public function checkout()
    {
        $payments = Payment::all();
        return view('checkout',compact('payments'));
    }
}
