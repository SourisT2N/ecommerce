@extends('user.layouts.main')
@section('title','Đơn Hàng '.$order->code_billing)
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h4>{{ 'Đơn Hàng Của Bạn' }}</h4>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form id="order" action="{{ route('order.update',['id' => $order->code_billing]) }}">
                        <div class="form-group row" style="margin-left: 1rem">
                            <label class="col-4 col-form-label">Mã Đơn Hàng:</label>
                            <span>{{ $order->code_billing }}</span>
                        </div>
                        <div class="form-group row" style="margin-left: 1rem">
                            <label class="col-4 col-form-label">Tên Người Nhận:</label>
                            <span>{{ $order->name }}</span>
                        </div>
                        <div class="form-group row" style="margin-left: 1rem">
                            <label class="col-4 col-form-label">Số Điện Thoại:</label>
                            <span>{{ $order->phone }}</span>
                        </div>
                        <div class="form-group row" style="margin-left: 1rem">
                            <label class="col-4 col-form-label">Địa Chỉ Nhận:</label>
                            <span>{{ $order->province . ', ' . $order->district . ', ' . $order->ward . ', ' . $order->address }}</span>
                        </div>
                        <div class="form-group row" style="margin-left: 1rem">
                            <label class="col-4 col-form-label">Phương Thức Thanh Toán:</label>
                            <span>{{ $order->payments->name }}</span>
                        </div>
                        <div class="form-group row" style="margin-left: 1rem">
                            <label class="col-4 col-form-label">Chi Tiết Sản Phẩm:</label>
                            <div class="col-8">
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
                        <div class="form-group row" style="margin-left: 1rem">
                            <label class="col-4 col-form-label">Trạng Thái Thanh Toán:</label>
                            <span>{{ $order->status_payment?'Đã Thanh Toán':'Chưa Thanh Toán' }}</span>
                        </div>
                        <div class="form-group row" style="margin-left: 1rem">
                            <label class="col-4 col-form-label">Trạng Thái Đơn Hàng:</label>
                            <span>{{ $order->orderStatus->name }}</span>
                        </div>
                        <div class="form-group row" style="margin-left: 1rem">
                            <label class="col-4 col-form-label">Tổng Tiền:</label>
                            <span>{{ number_format($order->total,0,'','.') }} VNĐ</span>
                        </div>
                        @if((int)$order->id_status !== 8 && (int)$order->id_status !== 9)
                            <div class="form-group row" style="margin-left: 1rem">
                                <div class="offset-4 col-8">
                                    <button name="submit" type="submit" class="btn btn-danger">Huỷ Đơn Hàng</button>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="{{ asset('client/order.js') }}"></script>
@endsection
