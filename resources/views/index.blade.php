@extends('layouts.main')
@section('title',config('app.name'))
@section('content')
    <header>
      <div class="carousel" data-count="{{ $slides->count() }}" data-current="1">

        <div class="items">
          <button class="btn btn-control" data-direction="right" style="outline:none"> <i class="ion-ios-arrow-right"></i></button>
          <button class="btn btn-control" data-direction="left" style="outline:none"> <i class="ion-ios-arrow-left"></i></button>

          @foreach ($slides as $item)
          <div class="item {{ $loop->first?'center':'' }}" data-marker="{{ $loop->iteration }}">
            <img src="{{ asset('storage/'.$item->image_display) }}" alt="{{ $item->subject }}" class="background hidden-xs hidden-sm"/>
            <img src="{{ asset('storage/'.$item->image_display) }}" alt="{{ $item->subject }}" class="background visible-sm"/>
            <img src="{{ asset('storage/'.$item->image_display) }}" alt="{{ $item->subject }}" class="background visible-xs"/>

            <div class="content">
              <div class="outside-content">
                <div class="inside-content">
                  <div class="container align-right">

                    <h1 class="h3 colorful blue hidden-xs">{{ $item->subject }}</h1>

                    <hr class="offset-sm">
                    <h2 class="h1 lg upp colorful blue">{{ $item->content }}</h2>
                    <hr class="offset-md">
                    <hr class="offset-md">
                    <a href="{{ $item->url }}" rel="nofollow" class="btn btn-primary btn-lg black"> Xem </a>

                  </div>
                </div>
              </div>
            </div>
          </div>
          @section('load')
              <li data-marker="{{ $loop->iteration }}" data-style="white" class="{{ $loop->first?'active':'' }}"></li>
              @parent
          @endsection
          @endforeach
        </div>

        <ul class="markers">
            @yield('load')
        </ul>

      </div>
    </header>
    
    <hr class="offset-lg">
    <hr class="offset-lg">
<div class="bars">
    <div class="container">
      <div class="row">
        @php
            $productOne = $productNew->splice(0,1)->all();
            $productTwo = $productNew->splice($productNew->count() - 1,1)->all();
        @endphp
        @if ($productOne)
          <div class="col-sm-6 col-md-4 no-padding padding-xs">
            <div class="bar medium" data-background="{{ asset('storage/'.$productOne[0]->image_display) }}">
              <h3 class="title black">{{ $productOne[0]->name }}</h3>

              <div class="wrapper">
                <div class="content">
                  <hr class="offset-sm">
                  <a href="{{ route('product.show', ['name'=> $productOne[0]->name_not_utf8 ]) }}" rel="nofollow" class="btn btn-default black"> Xem </a>
                </div>
              </div>
            </div>
          </div>
        @endif
        
        <div class="col-sm-6 col-md-4">
          @foreach ($productNew as $item)
          <div class="bar small" data-background="{{ asset('storage/'.$item->image_display) }}">
            <h3 class="title black">{{ $item->name }}</h3>

            <div class="wrapper">
              <div class="content">
                <hr class="offset-sm">
                <a href="{{ route('product.show', ['name'=> $item->name_not_utf8]) }}" rel="nofollow" class="btn btn-primary black"> Xem </a>
              </div>
            </div>
          </div>
          <hr class="offset-xs">
          <hr class="offset-xs">
        @endforeach
        </div>
        @if ($productTwo)
        <div class="col-sm-6 col-md-4 no-padding hidden-xs hidden-sm">
          <div class="bar medium" data-background="{{ asset('storage/'.$productTwo[0]->image_display) }}">
            <h3 class="title black">{{ $productTwo[0]->name }}</h3>
            
            <div class="wrapper">
              <div class="content">
                <hr class="offset-sm">
                <a href="{{ route('product.show', ['name'=> $productTwo[0]->name_not_utf8 ]) }}" rel="nofollow" class="btn btn-primary black"> Xem </a>
              </div>
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
  <hr class="offset-lg">
  <hr class="offset-md">
  @foreach ($products as $item)
  <section class="products">
    <div class="container">
      <h2 class="h2 upp align-center"> {{ __($item->name) }} </h2>
      <hr class="offset-lg">

      <div class="row">

        @php
          if($item->products->count())
            $count = 12/$item->products->count();
        @endphp
        @foreach ($item->products as $product)
        <div class="col-sm-6 col-md-{{ $count }} product">
          <div class="body">
            <a href="{{ route('product.show', ['name'=> $product->name_not_utf8]) }}"><img src="{{ asset('storage/'.$product->image_display) }}" alt="{{ $product->name }}"/></a>

            <div class="content align-center">
              <p class="price" style="{{ !$product->price_new?'margin:17px 0;':'' }}">{{ number_format($product->price_new?:$product->price_old,0,'','.').' VNĐ' }}</p>
              @if ($product->price_new)
                  <p class="price through">{{ number_format($product->price_old,0,'','.').' VNĐ' }}</p>
              @endif
              <h2 class="h3 text-hides">{{ $product->name }}</h2>
              <hr class="offset-sm">

              <a href="{{ route('product.show', ['name'=> $product->name_not_utf8]) }}" class="btn btn-link"> <i class="ion-android-open"></i> Chi Tiết</a>
              <button class="btn btn-primary btn-sm rounded cr" data-id = "{{ $product->id }}"> <i class="ion-bag"></i> Thêm Vào Giỏ</button>
            </div>
          </div>
        </div>
        @endforeach

          </div>

      <div class="align-right align-center-xs">
        <hr class="offset-sm">
        <a href="{{ route('category', ['name'=>$item->name_not_utf8]) }}"> <h5 class="upp">Xem Tất Cả</h5> </a>
      </div>
    </div>
  </section>
  @endforeach
  <section class="blog">
    <div class="container">
      <h2 class="h2 upp align-center"> {{ __('Tin Tức') }} </h2>
      <hr class="offset-lg">

      <div class="row">
        @foreach ($blogs as $item)
        <div class="col-sm-6 col-md-6 item">

          <div class="body">
            <a href="{{ route('blog.show', ['name'=> $item->name_not_utf8]) }}" class="view"><i class="ion-ios-book-outline"></i></a>
            <a href="{{ route('blog.show', ['name'=> $item->name_not_utf8]) }}">
              <img src="{{ asset('storage/'.$item->image_display) }}" title="{{ $item->name }}" alt="{{ $item->name }}">
            </a>

            <div class="caption">
              <h2 class="h3 text-hides">{{ $item->name }}</h2>
              <label>{{ Carbon\Carbon::parse($item->created_at)->format('d.m.Y') }}</label>
              <hr class="offset-sm">
              <p class="summary-hides">{{ $item->summary }}</p>
              <hr class="offset-sm">

              <a href="{{ route('blog.show', ['name'=> $item->name_not_utf8]) }}"> Xem </a>
            </div>
          </div>
        </div>
        @endforeach

      </div>

      <div class="align-right align-center-xs">
        <hr class="offset-sm">
        <a href="{{ route('blog') }}"> <h5 class="upp">Xem Tất Cả </h5> </a>
      </div>
    </div>
  </section>
@endsection