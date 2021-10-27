
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/user.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/toastr.min.css') }}">
</head>
<body>

<div class="container">
    <div class="row profile">
        <div class="col-md-3">
            <div class="profile-sidebar">
                <!-- SIDEBAR USERPIC -->
                <div class="profile-userpic">
                    <img src="{{ asset('img/user.png') }}" class="img-responsive" alt="">
                </div>
                <!-- END SIDEBAR USERPIC -->
                <!-- SIDEBAR USER TITLE -->
                <div class="profile-usertitle">
                    <div class="profile-usertitle-name">
                        {{ Auth::user()->name }}
                    </div>
                </div>
                <div class="profile-userbuttons">
                    <a href="{{ route('index') }}" class="btn btn-success btn-sm">Trang Chủ</a>
                    <a href="{{ route('user.logout') }}" class="btn btn-danger btn-sm">Đăng Xuất</a>
                </div>
                <!-- END SIDEBAR BUTTONS -->
                <!-- SIDEBAR MENU -->
                <div class="profile-usermenu">
                    <ul class="nav">
                        <li class="{{ $name == 'info'?'active':'' }}">
                            <a href="{{ route('user.index') }}">
                                <i class="glyphicon glyphicon-user"></i>
                                Tài Khoản Của Tôi</a>
                        </li>
                        <li class="{{ $name == 'change'?'active':'' }}">
                            <a href="{{ route('user.changePassword') }}">
                                <i class="glyphicon glyphicon-ok"></i>
                                Đổi Mật Khẩu </a>
                        </li>
                        <li class="{{ $name == 'order'?'active':'' }}">
                            <a href="{{ route('user.order') }}">
                                <i class="glyphicon glyphicon-shopping-cart"></i>
                                Đơn Hàng </a>
                        </li>
                    </ul>
                </div>
                <!-- END MENU -->
            </div>
        </div>
        <div class="col-md-9" id="pjax">
            <div class="profile-content">
                @yield('content')
            </div>
        </div>
    </div>
</div>
<br>
<br>
<script src="{{ asset('assets/js/jquery-latest.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pjax/jquery.pjax.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('client/load.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
</script>
@yield('footer')
</body>
</html>
