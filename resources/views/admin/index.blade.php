@extends('admin.layouts.main')
@section('title','Quản Lý')
@section('content')
<x-formheader url="{{ route('admin.index') }}" name="Dashboard"></x-formheader>
<div class="row dashboard-header">
    <div class="col-lg-3 col-md-6">
       <div class="card dashboard-product">
          <span>Tổng Doanh Thu</span>
          <h2 class="dashboard-total-products">{{ number_format($total,0,'','.') }} VNĐ</h2>
          <span class="label label-warning">Doanh Thu</span>
          <div class="side-box">
             <i class="ti-signal text-warning-color"></i>
          </div>
       </div>
    </div>
    <div class="col-lg-3 col-md-6">
       <div class="card dashboard-product">
          <span>Doanh Thu Tháng</span>
          <h2 class="dashboard-total-products">{{ number_format($totalMonth,0,'','.') }} VNĐ</h2>
          <span class="label label-primary">Doanh Thu</span>
          <div class="side-box ">
             <i class="ti-gift text-primary-color"></i>
          </div>
       </div>
    </div>
    <div class="col-lg-3 col-md-6">
       <div class="card dashboard-product">
          <span>Tổng Đơn Hàng</span>
          <h2 class="dashboard-total-products"><span>{{ $totalOrder }}</span></h2>
          <span class="label label-success">Đơn Hàng</span>
          <div class="side-box">
             <i class="ti-direction-alt text-success-color"></i>
          </div>
       </div>
    </div>
    <div class="col-lg-3 col-md-6">
       <div class="card dashboard-product">
          <span>Tổng Người Dùng</span>
          <h2 class="dashboard-total-products"><span>{{ $totalUser }}</span></h2>
          <span class="label label-danger">Người Dùng</span>
          <div class="side-box">
             <i class="ti-rocket text-danger-color"></i>
          </div>
       </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
       <div class="card">
          <div class="user-block-2">
             <img class="img-fluid" src="assets/images/widget/user-1.png" alt="user-header">
             <h5>{{ Auth::user()->name }}</h5>
          </div>
       </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card">
           <div class="card-block">
              <div class="table-responsive">
                 <table class="table m-b-0 photo-table">
                    <thead>
                       <tr class="text-uppercase">
                          <th>Mã Đơn Hàng</th>
                          <th>Trạng Thái Thanh Toán</th>
                          <th>Trạng Thái Đơn Hàng</th>
                          <th>Ngày Đặt Hàng</th>
                       </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $item)
                         <tr>
                             <td>
                                 <a href="{{ route('admin.orders.edit', ['order'=> $item->id]) }}">{{ $item->code_billing }}</a>
                             </td>
                             <td>
                                 {{ $item->status_payment?'Đã Thanh Toán':'Chưa Thanh Toán' }}
                             </td>
                             <td>{{ $item->orderStatus->name }}</td>
                             <td>{{ $item->created_at }}</td>
                         </tr>
                        @endforeach
 
                    </tbody>
                 </table>
              </div>
           </div>
        </div>
     </div>
 </div>
@endsection