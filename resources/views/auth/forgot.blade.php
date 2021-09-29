@extends('layouts.main')
@section('title','Quên Mật Khẩu')
@section('content')
<hr class="offset-lg">
<hr class="offset-lg">

<div class="container">
  <div class="row">
    <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 md-padding">
      <h1 class="align-center">Quên Mật Khẩu</h1>
      <br>

      <form class="join" action="{{ route('forgot') }}" method="post">
        <input type="email" name="email" value="" placeholder="E-mail" required="" class="form-control" />
        <br>
        <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
        <br>
        <button type="submit" class="btn btn-primary">Xác Nhận</button>
        <a href="{{ route('login') }}" class="xs-margin">Đăng Nhập ></a>
        <br><br>
        <hr class="offset-xs">

        <a href="{{ route('redirect', ['provider'=> 'facebook']) }}" class="btn facebook"> <i class="ion-social-facebook"></i> Login with Facebook </a>
        <a href="{{ route('redirect', ['provider'=> 'google']) }}" class="btn google"> <i class="ion-social-google"></i> Login with Google </a>
        <hr class="offset-sm">

        <p>
          Bạn Chưa Có Tài Khoản? <a href="{{ route('register') }}"> Đăng Ký > </a>
        </p>

      </form>
    </div>
  </div>
</div>
@endsection
@section('footer')
    <script src="{{ asset('client/forgot.js') }}"></script>
@endsection