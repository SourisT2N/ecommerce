@extends('layouts.main')
@section('title','Đăng Ký')
@section('content')
    <hr class="offset-lg hidden-xs">
    <hr class="offset-md">
    <div class="container">
        <div class="row">
          <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 md-padding">
            <h1 class="align-center">Đăng Ký</h1>
            <br>
  
            <form class="join" action="{{ route('register.post') }}" method="post">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-sm-12">
                    <input type="text" name="name" value="" placeholder="Tên" required="" class="form-control"><br>
                  </div>
                  <div class="col-sm-12">
                    <input type="email" name="email" value="" placeholder="Email" required="" class="form-control"><br>
                  </div>
                  <div class="col-sm-12">
                    <input type="password" name="password" value="" placeholder="Mật Khẩu" required="" class="form-control"><br>
                  </div>
                  <div class="col-sm-12">
                    <input type="password" name="password_confirmation" value="" placeholder="Nhập Lại Mật Khẩu" required="" class="form-control"><br>
                  </div>
                </div>
              </div>
              <br>
              <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
              <br>
              <button type="submit" class="btn btn-primary">Đăng Ký</button>
  
              <br><br>
              <p>
                Tạo tài khoản, bạn sẽ có thể mua sắm nhanh hơn, cập nhật trạng thái đơn hàng và theo dõi các đơn hàng bạn đã thực hiện trước đó.              </p>
            </form>
  
            <br class="hidden-sm hidden-md hidden-lg">
          </div>
        </div>
      </div>
      <br>
      <br>
@endsection
@section('footer')
    <script src="{{ asset('client/register.js') }}"></script>  
@endsection