@extends('admin.layouts.main')
@section('title',$title)
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/light-box/css/ekko-lightbox.css') }}">
<x-formheader url="{{ request()->url() }}" :name="$title"></x-formheader>
<div class="row">
    <div class="col-sm-12">
        <div class="card" id="card">
            <div class="card-header">
               <h5 class="card-header-text">Đơn Hàng</h5>
            </div>
            <!-- end of modal -->

            <div class="card-block">
               <form id="blog" action="{{ route('admin.orders.update', ['order'=>$order->id]) }}">
                <div class="form-group">
                    <label class="form-control-label">Tên Người Đặt:</label>
                    <span style="margin-left: 10px">{{ $order->users->name }}</span>
                </div>
                <div class="form-group">
                  <label class="form-control-label">Mã Đơn Hàng:</label>
                  <span style="margin-left: 10px">{{ $order->code_billing }}</span>
                </div>
                <div class="form-group">
                  <label class="form-control-label">Tên Người Nhận:</label>
                  <span style="margin-left: 10px">{{ $order->name }}</span>
                </div>
                <div class="form-group">
                  <label class="form-control-label">Số Điện Thoại:</label>
                  <span style="margin-left: 10px">{{ $order->phone }}</span>
                </div>
                <div class="form-group">
                  <label class="form-control-label">Địa Chỉ Người Nhận:</label>
                  <span style="margin-left: 10px">{{ $order->province . ', ' . $order->district . ', ' . $order->ward . ', ' . $order->address }}</span>
                </div>
                <div class="form-group">
                  <label class="form-control-label">Phương Thức Thanh Toán:</label>
                  <span style="margin-left: 10px">{{ $order->payments->name }}</span>
                </div>
                <div class="form-group">
                  <label class="form-control-label">Chi Tiết Sản Phẩm:</label>
                  <div class="card">
                    <div class="card-block">
                       <div class="row">
                          <div class="col-sm-12 table-responsive">
                             <table class="table">
                                <thead>
                                   <tr>
                                      <th>Tên Sản Phẩm</th>
                                      <th>Số Lượng</th>
                                      <th>Tổng Giá</th>
                                   </tr>
                                </thead>
                                <tbody>
                                   @foreach ($order->details as $item)
                                    <tr>
                                      <td>{{ $item->name }}</td>
                                      <td>{{ $item->pivot->count }}</td>
                                      <td>{{ number_format($item->pivot->price,0,'','.') }} VNĐ</td>
                                  </tr>
                                   @endforeach
                                </tbody>
                             </table>
                          </div>
                       </div>
                    </div>
                 </div>
                </div>
                <div class="form-group">
                  <label class="form-control-label">Trạng Thái Thanh Toán</label>
                  <select class="form-control" name="payment">
                    <option value="0" {{ !$order->status_payment?'selected':'' }}>Chưa Thanh Toán</option>
                    <option value="1" {{ $order->status_payment?'selected':'' }}>Đã Thanh Toán</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-control-label">Trạng Thái Đơn Hàng</label>
                  <select class="form-control" name="status">
                      @foreach ($orderStatus as $item)
                          @if ($order->id_status == $item->id)
                            <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                          @else
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                          @endif
                      @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-control-label">Tổng Tiền:</label>
                  <span style="margin-left: 10px">{{ number_format($order->total,0,'','.') }} VNĐ</span>
                </div>
                  <button type="submit" class="btn btn-success waves-effect waves-light m-r-30">Cập Nhật Đơn Hàng</button>
               </form>
            </div>
         </div>
    </div>
</div>
@endsection
@section('footer')
 <script src="{{ asset('js/order.js') }}"></script>
@endsection
