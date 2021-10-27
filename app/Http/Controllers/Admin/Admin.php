<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Admin extends Controller
{
    //
    public function index()
    {
        $total = Billing::where(['status_payment' => 1,['id_status','!=',9]])->sum('total');
        $totalMonth = Billing::where(['status_payment' => 1,['id_status','!=',9]])->whereYear('created_at',Carbon::now()->year)
        ->whereMonth('created_at',Carbon::now()->month)->sum('total');
        $totalOrder = Billing::count();
        $totalUser = User::count();
        $orders = Billing::select('id','code_billing','status_payment','id_status','created_at')->with('orderStatus:id,name')->latest()->take(5)->get();
        return view('admin.index',compact('total','totalMonth','totalOrder','totalUser','orders'));
    }
}
