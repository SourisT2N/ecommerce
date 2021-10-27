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

      <form class="join" action="{{ route('user.reset',['code' => $code]) }}" method="post">
        <input type="password" name="password" value="" placeholder="Mật Khẩu" required="" class="form-control" />
        <input type="password" name="password_confirmation" value="" placeholder="Nhập Lại Mật Khẩu" required="" class="form-control" />
        <br>
        <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
        <br>
        <button type="submit" class="btn btn-primary">Đổi Mật Khẩu</button>
      </form>
    </div>
  </div>
</div>
@endsection
@section('footer')
    <script src="{{ asset('client/reset.js') }}"></script>
@endsection