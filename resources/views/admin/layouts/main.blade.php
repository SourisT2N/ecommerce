<!DOCTYPE html>
<html lang="en">

<head>
   <title>@yield('title')</title>
   <!-- HTML5 Shim and Respond.js IE9 support of HTML5 elements and media queries -->
   <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
   <!--[if lt IE 9]>
     <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
     <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
     <![endif]-->

   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
   <!-- Favicon icon -->
   <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
   <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">

   <!-- Google font-->
   <link href="https://fonts.googleapis.com/css?family=Ubuntu:400,500,700" rel="stylesheet">

   <!-- themify -->
   <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/themify-icons/themify-icons.css') }}">

   <!-- iconfont -->
   <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/icofont/css/icofont.css') }}">

   <!-- simple line icon -->
   <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/simple-line-icons/css/simple-line-icons.css') }}">

   <!-- Required Fremwork -->
   <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

   <!-- Style.css -->
   <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main.css') }}">

   <!-- Responsive.css-->
   <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.css" rel="stylesheet">
</head>

<body class="sidebar-mini fixed">
   <div class="loader-bg">
      <div class="loader-bar">
      </div>
   </div>
   <div class="wrapper">
      <!-- Navbar-->
      <header class="main-header-top hidden-print">
         <a href="index.html" class="logo"><img class="img-fluid able-logo" src="{{ asset('assets/images/logo.png') }}" alt="Theme-logo"></a>
         <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#!" data-toggle="offcanvas" class="sidebar-toggle"></a>
            <!-- Navbar Right Menu-->
            <div class="navbar-custom-menu f-right">

               <ul class="top-nav">
                  <!--Notification Menu-->
                    @php
                        $notifications = Auth::user()->notifications;
                        \Carbon\Carbon::setLocale('vi');
                    @endphp
                  <li class="dropdown notification-menu">
                     <a href="#!" data-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                        <i class="icon-bell"></i>
                        <span class="badge badge-danger header-badge" id="countNotifi">{{ $notifications->count() }}</span>
                     </a>
                     <ul class="dropdown-menu" style="max-height:300px;overflow-y:auto">
                        <li class="not-head">Bạn Có <b class="text-primary" id="textNotifi">{{ $notifications->count() }}</b> Thông Báo.</li>
                        @foreach ($notifications as $item)
                        <li class="bell-notification">
                           <a href="{{ $item->data['url'] }}" class="media">
                              <div class="media-body"><span class="block">{{ $item->data['message'] }}</span><span class="text-muted block-time">{{ (new \Carbon\Carbon($item->created_at))->diffForHumans(\Carbon\Carbon::now()) }}</span></div>
                           </a>
                        </li>
                        @endforeach
                     </ul>
                  </li>
                  <!-- window screen -->
                  <li class="pc-rheader-submenu">
                     <a href="#!" class="drop icon-circle" onclick="javascript:toggleFullScreen()">
                        <i class="icon-size-fullscreen"></i>
                     </a>

                  </li>
                  <!-- User Menu-->
                  <li class="dropdown">
                     <a href="#!" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle drop icon-circle drop-image">
                        <span><img class="img-circle " src="{{ asset('assets/images/avatar-1.png') }}" style="width:40px;" alt="User Image"></span>
                        <span><b>{{ Auth::user()->name }}</b> <i class=" icofont icofont-simple-down"></i></span>

                     </a>
                     <ul class="dropdown-menu settings-menu">
                        <li><a href="{{ route('user.logout') }}" style="display:block;"><i class="icon-logout"></i> Logout</a></li>
                     </ul>
                  </li>
               </ul>
               <!-- search end -->
            </div>
         </nav>
      </header>
      <!-- Side-Nav-->
      <aside class="main-sidebar hidden-print ">
         <section class="sidebar" id="sidebar-scroll">
            <!-- Sidebar Menu-->
            <ul class="sidebar-menu">
                <li class="nav-level">--- Bảng Điều Khiển</li>
                <li class="{{ ($name??'') == ''?'active':'' }} treeview">
                    <a class="waves-effect waves-dark" href="{{ route('admin.index') }}">
                        <i class="icon-speedometer"></i><span> Dashboard</span>
                    </a>
                </li>
                @hasanyrole('super-admin|product')
                  <li class="nav-level">--- Chức Năng</li>
                  <li class="treeview"><a class="waves-effect waves-dark"><i class="icofont icofont-company"></i><span>Chức Năng Sản Phẩm</span><i class="icon-arrow-down"></i></a>
                     <ul class="treeview-menu" style="display: {{ (($name??'') == 'category' || ($name??'') == 'country' || ($name??'') == 'supplier' || ($name??'') == 'product')?'block':'none' }};">
                           <li class="{{ ($name??'') == 'category'?'active':'' }} treeview"><a class="waves-effect waves-dark" href="{{ route('admin.categories.index') }}"><i class="icofont-book-alt"></i><span>Danh Mục</span></a>
                           </li>
                           <li class="{{ ($name??'') == 'country'?'active':'' }} treeview"><a class="waves-effect waves-dark" href="{{ route('admin.countries.index') }}"><i class="icofont-world"></i><span>Xuất Xứ</span></a>
                           </li>
                           <li class="{{ ($name??'') == 'supplier'?'active':'' }} treeview"><a class="waves-effect waves-dark" href="{{ route('admin.suppliers.index') }}"><i class="icofont-badge"></i><span>Thương Hiệu</span></a>
                           </li>
                           <li class="{{ ($name??'') == 'product'?'active':'' }} treeview"><a class="waves-effect waves-dark" href="{{ route('admin.products.index') }}"><i class="icofont-database-locked"></i><span>Sản Phẩm</span></a>
                           </li>
                     </ul>
                  </li>
                @endhasanyrole

                @hasanyrole('super-admin|order')
                  <li class="treeview"><a class="waves-effect waves-dark"><i class="icofont icofont-company"></i><span>Chức Năng Đơn Hàng</span><i class="icon-arrow-down"></i></a>
                     <ul class="treeview-menu" style="display: {{ (($name??'') == 'status')?'block':'none' }};;">
                        <li class="{{ ($name??'') == 'status'?'active':'' }} treeview"><a class="waves-effect waves-dark" href="{{ route('admin.status.index') }}"><i class="icofont-sort-alt"></i><span>Trạng Thái</span></a>
                        </li>
                        <li class="{{ ($name??'') == 'order'?'active':'' }} treeview"><a class="waves-effect waves-dark" href="{{ route('admin.orders.index') }}"><i class="icofont-law-order"></i><span>Đơn Hàng</span></a>
                        </li>
                     </ul>
                  </li>
                @endhasanyrole

                @hasanyrole('super-admin|blog')
                  <li class="{{ ($name??'') == 'blog'?'active':'' }} treeview"><a class="waves-effect waves-dark" href="{{ route('admin.blogs.index') }}"><i class="icofont-blogger"></i><span>Blog</span></a>
                  </li>
                @endhasanyrole

                @hasanyrole('super-admin|slide')
                  <li class="{{ ($name??'') == 'slide'?'active':'' }} treeview"><a class="waves-effect waves-dark" href="{{ route('admin.slides.index') }}"><i class="icofont-image"></i><span>Slide</span></a>
                  </li>
                @endhasanyrole

                @hasanyrole('super-admin')
                  <li class="{{ ($name??'') == 'user'?'active':'' }} treeview"><a class="waves-effect waves-dark" href="{{ route('admin.users.index') }}"><i class="icofont-user-alt-3"></i><span>Người Dùng</span></a>
                  </li>
                @endhasanyrole
            </ul>
         </section>
      </aside>
      <div class="content-wrapper">
         <!-- Container-fluid starts -->
         <!-- Main content starts -->
         <div class="container-fluid">
            @yield('content')

            <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                     <div class="loader" style="position: absolute;top:50%;left:50%;transform: translate(-50%,-50%)"></div>
             </div>
         </div>
         <!-- Main content ends -->
         <!-- Container-fluid ends -->
      </div>
   </div>
   <div class="modal fade" id="deleteForm" tabindex="-1" aria-labelledby="deleteForm" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteFormLabel">Thông Báo</h5>
          </div>
          <div class="modal-body">
            Bạn có chắc chắn muốn xoá không ?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            <button type="button" class="btn btn-danger" id="btnDelete">Chắc Chắn</button>
          </div>
        </div>
      </div>
    </div>
   <script src="{{ asset('assets/plugins/Jquery/dist/jquery.min.js') }}"></script>
   <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
   <script src="{{ asset('assets/plugins/pjax/jquery.pjax.js') }}"></script>
   <script src="{{ asset('assets/plugins/tether/dist/js/tether.min.js') }}"></script>
   <script>
      $.ajaxSetup({
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
         }
      });
   </script>
   <!-- Required Fremwork -->
   <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.js"></script>
   <!-- Scrollbar JS-->
   <script src="{{ asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
   <script src="{{ asset('assets/plugins/jquery.nicescroll/jquery.nicescroll.min.js') }}"></script>

   <!--classic JS-->
   <script src="{{ asset('assets/plugins/classie/classie.js') }}"></script>

   <!-- notification -->
   <script src="{{ asset('assets/plugins/notification/js/bootstrap-growl.min.js') }}"></script>

   <!-- Sparkline charts -->
   <script src="{{ asset('assets/plugins/jquery-sparkline/dist/jquery.sparkline.js') }}"></script>

   <!-- custom js -->
   <script type="text/javascript" src="{{ asset('assets/js/main.min.js') }}"></script>

   <script type="text/javascript" src="{{ asset('assets/pages/elements.js') }}"></script>
   <script type="text/javascript" src="{{ asset('assets/pages/notification.js') }}"></script>
   <script src="{{ asset('assets/js/menu.min.js') }}"></script>
   <script src="{{ asset('js/app.js') }}"></script>
   <script src="{{ asset('js/main.js') }}"></script>
   <script>
      var userId = {{ Auth()->user()->id }};
      var countNotifi = {{ $notifications->count() }};
      var $window = $(window);
      var nav = $('.fixed-button');
      $window.scroll(function(){
          if ($window.scrollTop() >= 200) {
             nav.addClass('active');
          }
          else {
             nav.removeClass('active');
          }
      });
      $(document).ready(function(){
         if ($.support.pjax) {
             $.pjax.defaults.timeout = 20000; // time in milliseconds
            $(document).pjax('.page-link', '.table-responsive');
         }
      });
   </script>
   @yield('footer')


</body>

</html>
