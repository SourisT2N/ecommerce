@extends('layouts.main')
@section('title','Đăng Nhập')
@section('content')
<hr class="offset-lg">
<hr class="offset-lg">

<div class="container">
  <div class="row">
    <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 md-padding">
      <h1 class="align-center">Đăng Nhập</h1>
      <br>

      <form class="join" action="{{ route('login.post') }}" method="post">
        <input type="email" name="email" value="" placeholder="E-mail" required="" class="form-control" />
        <br>
        <input type="password" name="password" value="" placeholder="Password" required="" class="form-control" />
        <br>
        <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
        <br>
        <button type="submit" class="btn btn-primary">Đăng Nhập</button>
        <a href="{{ route('forgot') }}" data-action="Forgot-Password" class="xs-margin">Quên Mật Khẩu ></a>
        <br><br>
        <hr class="offset-xs">

        <a href="{{ route('redirect', ['provider'=> 'facebook']) }}" class="btn btn-login facebook"> <i class="ion-social-facebook"></i> Login with Facebook </a>
        <a href="{{ route('redirect', ['provider'=> 'google']) }}" class="btn btn-login google"> <i class="ion-social-google"></i> Login with Google </a>
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
    <script src="{{ asset('client/login.js') }}"></script>
@endsection
