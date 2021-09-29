<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function index()
    {
        $name = 'info';
        return view('user.info',compact('name'));
    }

    public function changePassword()
    {
        $name = 'change';
        return view('user.changePassword',compact('name'));
    }

    public function orders(Request $request)
    {
        $name = 'order';
        $orders = Billing::where('id_user',Auth::user()->id)
        ->select('id','code_billing','total','status_payment','id_status','created_at')
        ->with('orderStatus:id,name')
        ->orderBy('created_at','desc')
        ->paginate(5);
        return view('user.order',compact('name','orders'));
    }

    public function showOrder($id)
    {
        try
        {
            $name = 'order';
            $order = Auth::user()->billing()->where('code_billing',$id)->with('orderStatus:id,name','payments:id,name','details:id,name')->first();
            if(!$order)
                throw new ModelNotFoundException();
            return view('user.detailOrder',compact('order','name'));
        }
        catch (\Exception $e)
        {
            if($e instanceof ModelNotFoundException)
                abort(404);
            abort(500);
        }
    }
}
