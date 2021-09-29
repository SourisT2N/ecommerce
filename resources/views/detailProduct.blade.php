@extends('layouts.main')
@section('title',$product->name)
@section('content')
    <link href="{{ asset('assets/css/carousel-product.css') }}" rel="stylesheet">
    <hr class="offset-lg">
    <hr class="offset-lg">
    <hr class="offset-lg hidden-xs">


    <section class="product">
      <div class="container">
        <div class="row">
          <div class="col-sm-7 col-md-7 white no-padding">
            <div class="carousel-product" data-count="{{ $product->images->count() }}" data-current="1">

              <div class="items">
                <button class="btn btn-control" data-direction="right" style="outline:none"> <i class="ion-ios-arrow-right"></i></button>
                <button class="btn btn-control" data-direction="left" style="outline:none"> <i class="ion-ios-arrow-left"></i></button>

                @foreach ($product->images as $item)
                    <div class="item {{ $loop->first?'center':'' }}" data-marker="{{ $loop->iteration }}">
                        <img src="{{ asset('storage/'.$item->image_display) }}" alt="{{ $product->name }}" class="background"/>
                    </div>
                    @section('load')
                      <li data-marker="{{ $loop->iteration }}" class="{{ $loop->first?'active':'' }}"></li>
                      @parent
                    @endsection
                @endforeach
              </div>

              <ul class="markers">
                @yield('load')
            </ul>

            </div>
          </div>
          <div class="col-sm-5 col-md-5 no-padding-xs">
            <div class="caption">

              <h1>{{ $product->suppliers->name }}</h1>

              <p> &middot; {{ $product->system }}</p>
              <p> &middot; {{ $product->processor }}</p>
              <p> &middot; Xuất Xứ: {{ $product->countries->name }}</p>
              <hr class="offset-md hidden-sm">
              <hr class="offset-sm visible-sm">
              <hr class="offset-xs visible-sm">

              <p class="price">{{ number_format($product->price_new?:$product->price_old,0,'','.').' VNĐ' }}</p>
              @if ($product->price_new)
              <p class="price through">{{ number_format($product->price_old,0,'','.').' VNĐ' }}</p>
              @endif
              <hr class="offset-md">

              <button class="btn btn-primary rounded cr" data-id="{{ $product->id }}"> <i class="ion-bag"></i> Thêm Vào Giỏ</button>
            </div>
          </div>
        </div>
        <hr class="offset-sm hidden-xs">

        <div class="row">
          <div class="col-sm-7 white sm-padding">
            <hr class="offset-sm visible-xs">

            <h2 class="h1">{{ $product->name }}</h2>
            <br>

            <p>{{ $product->description }}</p>
            <br>

             <h2>Thông Tin Sản Phẩm</h2>
             <br>

              <div class="row specification">
                <div class="col-sm-4"> <label>Hệ Điều Hành</label> </div>
                <div class="col-sm-8"> <p>{{ $product->system }}</p> </div>
              </div>

              <div class="row specification">
                <div class="col-sm-4"> <label>Chi Tiết</label> </div>
                <div class="col-sm-8">
                  <pre style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 14px;background:unset;border:none;
                  border-radius:unset">{{ $product->display }}</pre>
                </div>
              </div>
              
              <div class="row specification">
                <div class="col-sm-4"> <label>CPU</label> </div>
                <div class="col-sm-8"> <p>{{ $product->processor }}</p> </div>
              </div>

              <div class="row specification">
                <div class="col-sm-4"> <label>Đồ Hoạ</label> </div>
                <div class="col-sm-8"> <p>{{ $product->graphics }}</p> </div>
              </div>

              <div class="row specification">
                <div class="col-sm-4"> <label>Ram</label> </div>
                <div class="col-sm-8"> <p>{{ $product->memory }}</p> </div>
              </div>

              <div class="row specification">
                <div class="col-sm-4"> <label>Ổ Cứng</label> </div>
                <div class="col-sm-8"> <p>{{ $product->hard_drive }}</p> </div>
              </div>

              <div class="row specification">
                <div class="col-sm-4"> <label>Kết Nối Không Dây</label> </div>
                <div class="col-sm-8">
                  <p>
                    {{ $product->wireless }}
                  </p>
                </div>
              </div>

              <div class="row specification">
                <div class="col-sm-4"> <label>Pin</label> </div>
                <div class="col-sm-8"> <p>{{ $product->battery }}</p> </div>
              </div>

              <hr class="offset-lg">
          </div>
          <div class="col-sm-5 no-padding-xs">
            <hr class="offset-sm hidden-xs">

            <div class="comments white">
              <h2 class="h3">Đánh Giá Sản Phẩm? (#{{ $product->comments->count() }})</h2>
              <br>


              <div class="wrapper">
                <div class="content" id="comments">
                  @foreach ($product->comments as $item)
                  <div class="comment">
                    <h3>{{ $item->name }}</h3>
                    <label>{{ (new \Carbon\Carbon($item->pivot->created_at))->diffForHumans(\Carbon\Carbon::now()) }}</label>
                    <p>
                      {{ $item->pivot->comment }}
                    </p>
                  </div>
                  @endforeach
                </div>
              </div>
              <hr class="offset-lg">
              <hr class="offset-md">

              <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#Modal-Comment"> <i class="ion-chatbox-working"></i> Thêm Đánh Giá </button>
              <hr class="offset-md visible-xs">
            </div>            
          </div>
        </div>
      </div>
    </section>
    <hr class="offset-lg">

    <section class="products">
      <div class="container">
        <h2 class="upp align-center-xs"> Những Sản Phẩm Mới </h2>
        <hr class="offset-lg">

        <div class="row">
          @foreach ($products as $item)
          <div class="col-sm-4 col-md-3 product">
            <div class="body">
              <a href="{{ route('product.show', ['name'=> $item->name_not_utf8]) }}"><img src="{{ asset('storage/'.$item->image_display) }}" alt="{{ $item->name }}"/></a>

              <div class="content align-center">
                <p class="price" style="{{ !$item->price_new?'margin:17px 0;':'' }}">{{ number_format($item->price_new?:$item->price_old,0,'','.').' VNĐ' }}</p>
                @if ($item->price_new)
                <p class="price through">{{ number_format($item->price_old,0,'','.').' VNĐ' }}</p>
                @endif
                <h2 class="h3 text-hides">{{ $item->name }}</h2>
                <hr class="offset-sm">

                <a href="{{ route('product.show', ['name'=> $item->name_not_utf8]) }}" class="btn btn-link"> <i class="ion-android-open"></i> Xem</a>
                <button class="btn btn-primary btn-sm rounded"> <i class="ion-bag"></i> Thêm Vào Giỏ</button>
              </div>
            </div>
          </div>
          @endforeach
        </div>

      </div>
    </section>


    <hr class="offset-lg">
    <hr class="offset-sm">
    

    <div class="modal fade" id="Modal-Comment" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header align-center">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="ion-android-close"></i></span></button>
            <h1 class="h4 modal-title">Đánh Giá Của Bạn</h1>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
            <form class="join" action="{{ route('comment.store', ['id'=>$product->id]) }}" method="post">
              <div class="row">
              	<div class="col-sm-12">
                	<textarea name="comment" placeholder="Đánh Giá" required="" class="form-control" rows="5"></textarea>
                	<br>
                </div>
                <div class="col-sm-12">
                	<div class="align-center">
	                	<br>
	                	<button type="submit" class="btn btn-primary btn-sm"> <i class="ion-android-send"></i> Gửi</button>
                		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal"> <i class="ion-android-share"></i>Huỷ</button>
	                	<br><br>
                	</div>
                </div>
              </div>
             </form>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
@section('footer')
  <script src="{{ asset('js/app.js')}}"></script>
  <script>
    var idProduct = {{ $product->id }};
  </script>
  <script src="{{ asset('assets/js/carousel-product.js') }}"></script>
@endsection
 