@extends('layouts.main')
@section('title','Thanh Toán')
@section('content')
    <hr class="offset-top">
    <div class="white">
        <div class="container checkout">
            <h1>Thanh Toán Đơn Hàng</h1>
            <hr class="offset-sm">
        </div>
    </div>
    <hr class="offset-md">
    <div class="container checkout">
        <form id="order" action="{{ route('order.default') }}" method="post">

        <div class="row">
            <div class="col-md-7">
                  <div class="row group">
                    <div class="col-sm-4"><h2 class="h4">Tên Người Nhận</h2></div>
                    <div class="col-sm-8"> <input type="text" class="form-control" name="name" placeholder="Nhập Tên"></div>
                  </div>

                  <div class="row group">
                    <div class="col-sm-4"><h2 class="h4">Số Điện Thoại</h2></div>
                    <div class="col-sm-8"> <input type="text" class="form-control" name="phone" placeholder="Nhập Số Điện Thoại"></div>
                  </div>

                  <div class="row group">
                    <div class="col-sm-4"><h2 class="h4">Tỉnh/Thành Phố</h2></div>
                    <div class="col-sm-8">
                      <div class="group-select justify" tabindex="1" id="province" >
                          <input class="form-control select" name="province" placeholder="Tỉnh/Thành Phố" required="">

                          <ul class="dropdown">

                          </ul>

                          <div class="arrow bold"><i class="ion-chevron-down"></i></div>
                      </div>
                    </div>
                  </div>

                  <div class="row group">
                    <div class="col-sm-4"><h2 class="h4">Quận/Huyện</h2></div>
                    <div class="col-sm-8">
                      <div class="group-select justify" tabindex="1" id="district" >
                          <input class="form-control select" name="district" placeholder="Quận/Huyện" required="">

                          <ul class="dropdown">

                          </ul>
                          <div class="arrow bold"><i class="ion-chevron-down"></i></div>
                      </div>
                    </div>
                  </div>

                  <div class="row group">
                    <div class="col-sm-4"><h2 class="h4">Phường/Xã</h2></div>
                    <div class="col-sm-8">
                      <div class="group-select justify" tabindex="1" id="ward" >
                          <input class="form-control select" name="ward" placeholder="Phường/Xã" required="">

                          <ul class="dropdown">

                          </ul>
                          <div class="arrow bold"><i class="ion-chevron-down"></i></div>
                      </div>
                    </div>
                  </div>
                  <div class="row group">
                    <div class="col-sm-4"><h2 class="h4">Địa Chỉ</h2></div>
                    <div class="col-sm-8"> <input type="text" class="form-control" name="address" placeholder="Nhập Địa Chỉ"></div>
                  </div>


                  <div class="row group">
                    <div class="col-sm-4"><h2 class="h4">Phương Thức Thanh Toán</h2></div>
                    <div class="col-sm-8">
                      <div class="group-select justify" tabindex="1">
                          <select name="payment" class="form-control">
                            @foreach ($payments as $item)
                              <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                          </select>
                      </div>
                    </div>
                  </div>

                  <hr class="offset-lg visible-xs visible-sm">
                  <hr class="offset-lg visible-xs">
            </div>

            <div class="col-md-5 white carts" style="max-height: 500px; overflow-y: auto;">
                <hr class="offset-md visible-xs visible-sm">
                <div class="checkout-cart">
                    <div class="content">

                    </div>
                </div>
                <hr class="offset-md visible-xs visible-sm">
            </div>

            <hr class="offset-lg hidden-xs">

            <div class="col-sm-12 white">
                <hr class="offset-md">
                <div class="row">
                    <div class="col-xs-6 col-md-4">
                        <h3 class="h4 no-margin" id="orderTotal">Tổng: 0 VNĐ</h3>
                    </div>
                    <div class="col-md-4 hidden-xs">
                    </div>
                    <div class="col-xs-6 col-md-4" id="btnPay">
                        <button class="btn btn-primary pull-right" type="submit">Thanh Toán</button>
                    </div>
                </div>
                <hr class="offset-md">
            </div>
        </div>
        </form>
    </div>
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="loader" style="position: absolute;top:50%;left:50%;transform: translate(-50%,-50%)"></div>
</div>
@endsection
@section('footer')
  <script
    src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}"> // Required. Replace YOUR_CLIENT_ID with your sandbox client ID.
  </script>
  <script src="{{ asset('client/checkout.js') }}"></script>
@endsection
