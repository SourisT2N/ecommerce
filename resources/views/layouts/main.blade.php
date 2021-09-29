<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title> @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="Bootstrap template for you store - E-Commerce Bootstrap Template">
    <meta name="keywords" content="unistore, e-commerce bootstrap template, premium e-commerce bootstrap template, premium bootstrap template, bootstrap template, e-commerce, bootstrap template, sunrise digital">
    <meta name="author" content="Sunrise Digital">
    {{-- <link rel="shortcut icon" type="image/x-icon" href="favicon.png"> --}}

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/toastr.min.css') }}">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/ionicons-2.0.1/css/ionicons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link href='https://fonts.googleapis.com/css?family=Catamaran:400,100,300' rel='stylesheet' type='text/css'>

    <link href="{{ asset('assets/css/custom-scroll/jquery.mCustomScrollbar.css') }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>
  <body>

    <div class="cart carts" data-toggle="inactive">
      <div class="label">
        <i class="ion-bag"></i> 0
      </div>

      <div class="overlay"></div>

      <div class="window">
        <div class="title">
          <button type="button" class="close"><i class="ion-android-close"></i></button>
          <h4>{{ __('Giỏ Hàng') }}</h4>
        </div>

        <div class="content">

        </div>

        <div class="checkout container-fluid">
          <div class="row">
            <div class="col-xs-12 col-sm-12 align-right">
              <a class="btn btn-primary" href="{{ route('checkout') }}"> {{ __('Thanh Toán') }} </a>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="toplinks">
      @if (Auth::check())
        <a href="{{ route('user.logout') }}">
          <i class="ion-log-out"></i> Đăng Xuất
        </a>
        <a href="{{ route('user.index') }}">
          <i class="ion-person"></i> {{ Auth::user()->name }}
        </a>
        @if(Auth::user()->hasAnyRole(['admin','blog','product','slide','order','super-admin']))
          <a href="{{ route('admin.index') }}"> <i class="ion-ios-briefcase"></i> {{ __('Quản Trị') }}</a>
        @endif
      @else
        <a href="{{ route('register') }}"> <i class="ion-android-person-add"></i> {{ __('Đăng Ký') }}</a>
        <a href="{{ route('login') }}"> <i class="ion-unlocked"></i> {{ __('Đăng Nhập') }}</a>
      @endif
    </div>


    <nav class="navbar navbar-default">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/"> Unistore </a>
            <a class="navbar-brand pull-right hidden-sm hidden-md hidden-lg" href="#open-cart"> <i class="ion-bag"></i> 7 </a>
          </div>

          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="{{ ($type??'') == ''?'active':'' }}"><a href="/">{{ __('Trang Chủ') }}</a></li>
              <li class="{{ ($type??'') == 'product'?'active':'' }}"><a href="{{ route('product') }}">{{ __('Sản Phẩm') }}</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                  {{ __('Danh Mục') }} <i class="ion-android-arrow-dropdown"></i>
                </a>
                <ul class="dropdown-menu">
                  @foreach ($categories as $item)
                    <li><a href="{{ route('category', ['name' => $item->name_not_utf8]) }}">{{ $item->name }}</a></li>
                  @endforeach
                </ul>
              </li>
              <li class="{{ ($type??'') == 'blog'?'active':'' }}"><a href="{{ route('blog') }}">{{ __('Tin Tức') }}</a></li>
            </ul>
          </div><!--/.nav-collapse -->


          <div class="search hidden-xs" data-style="hidden">
            <div class="input">
              <button type="button"><i class="ion-ios-search"></i></button>

              <form action="{{ route('product') }}" id="search">
                <input type="text" name="q" style="outline:none" value="" placeholder="Tên Sản Phẩm" />
              </form>
            </div>
          </div>
        </div><!--/.container-fluid -->
    </nav>
    <div id="pjax">
        @yield('content')
    </div>

    <hr class="offset-lg">
    <hr class="offset-sm">
    <footer>
      <div class="about">
        <div class="container">
          <div class="row">
            <hr class="offset-md">

            <div class="col-xs-6 col-sm-3">
              <div class="item">
                <i class="ion-ios-telephone-outline"></i>
                <h1>24/7 <br> <span>Hỗ Trợ</span></h1>
              </div>
            </div>
            <div class="col-xs-6 col-sm-3">
              <div class="item">
                <i class="ion-ios-star-outline"></i>
                <h1>Giá Thấp <br> <span>Bảo Hành</span></h1>
              </div>
            </div>
            <div class="col-xs-6 col-sm-3">
              <div class="item">
                <i class="ion-ios-gear-outline"></i>
                <h1> Thương Hiệu <br> <span>Đảm Bảo</span></h1>
              </div>
            </div>
            <div class="col-xs-6 col-sm-3">
              <div class="item">
                <i class="ion-ios-loop"></i>
                <h1> Hoàn Tiền <br> <span>Bảo Hành</span> </h1>
              </div>
            </div>

            <hr class="offset-md">
          </div>
        </div>
      </div>

      <div class="subscribe">
        <div class="container align-center">
            <hr class="offset-lg">
            <hr class="offset-md">

            <div class="social">
              <a href="#"><i class="ion-social-facebook"></i></a>
              <a href="#"><i class="ion-social-twitter"></i></a>
              <a href="#"><i class="ion-social-googleplus-outline"></i></a>
              <a href="#"><i class="ion-social-instagram-outline"></i></a>
              <a href="#"><i class="ion-social-linkedin-outline"></i></a>
              <a href="#"><i class="ion-social-youtube-outline"></i></a>
            </div>


            <hr class="offset-md">
            <hr class="offset-md">
        </div>
      </div>

      <hr>

      <div class="container">
        <div class="row">
          <div class="col-sm-8 col-md-9 payments">
            <p>Các Phương Thức Thanh Toán</p>

            <div class="payment-icons">
              <img src="{{ asset('assets/img/payments/paypal.svg') }}" alt="paypal">
              <img src="{{ asset('assets/img/payments/visa.svg') }}" alt="visa">
              <img src="{{ asset('assets/img/payments/master-card.svg') }}" alt="mc">
              <img src="{{ asset('assets/img/payments/discover.svg') }}" alt="discover">
              <img src="{{ asset('assets/img/payments/american-express.svg') }}" alt="ae">
            </div>
            <br>

          </div>
          <div class="col-sm-4 col-md-3 align-right align-center-xs">
            <hr class="offset-sm hidden-sm">
            <hr class="offset-sm">
            <p>Unistore Pro © 2021 <br> Designed By <a href="https://sunrise.ru.com/" target="_blank">Souris</a></p>
            <hr class="offset-lg visible-xs">
          </div>
        </div>
      </div>
    </footer>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{ asset('assets/js/jquery-latest.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/core.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/store.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/carousel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.touchSwipe.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/js/custom-scroll/jquery.mCustomScrollbar.concat.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/js/jquery-ui-1.11.4.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.ui.touch-punch.js') }}"></script>
    <script src="{{ asset('client/load.js') }}"></script>
    <script src="{{ asset('client/cart.js') }}"></script>
    <script>
      $.ajaxSetup({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
      });
    </script>
    <!-- Messenger Plugin chat Code -->
    <div id="fb-root"></div>

    <!-- Your Plugin chat code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>

    <script>
        var chatbox = document.getElementById('fb-customer-chat');
        chatbox.setAttribute("page_id", "101687148606409");
        chatbox.setAttribute("attribution", "biz_inbox");

        window.fbAsyncInit = function() {
            FB.init({
                xfbml            : true,
                version          : 'v11.0'
            });
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    @yield('footer')
  </body>
</html>
