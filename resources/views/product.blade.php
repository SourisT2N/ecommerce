@extends('layouts.main')
@section('title','Sản Phẩm')
@section('content')
@php
    $ct = explode(' ',$query['ct']??'');
    $ctry = explode(' ',$query['ctry']??'');
    $sp = explode(' ',$query['sp']??'');
@endphp
    <hr class="offset-top">
    <div class="tags">
      <div class="container">
          <div class="btn-group pull-right sorting">
            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="ion-arrow-down-b"></i> Sắp Xếp
            </button>

            <ul class="dropdown-menu" id="sort">
              <li class="{{ ($query['sort']??'') == 1?'active':'' }}" data-id="1"><a href="#"> <i class="ion-arrow-down-c"></i> Tên [A-Z]</a></li>
              <li class="{{ ($query['sort']??'') == 2?'active':'' }}" data-id="2"><a href="#"> <i class="ion-arrow-up-c"></i> Tên [Z-A]</a></li>
              <li class="{{ ($query['sort']??'') == 3?'active':'' }}" data-id="3"><a href="#"> <i class="ion-arrow-down-c"></i> Giá [Thấp-Cao]</a></li>
              <li class="{{ ($query['sort']??'') == 4?'active':'' }}" data-id="4"><a href="#"> <i class="ion-arrow-up-c"></i> Giá [Cao-Thấp]</a></li>
              <li class="{{ ($query['sort']??'') == 5?'active':'' }}" data-id="5"><a href="#"> <i class="ion-arrow-down-c"></i> Cũ</a></li>
              <li class="{{ ($query['sort']??'') == 6 || ($query['sort']??'') == '' ?'active':'' }}" data-id="6"><a href="#"> <i class="ion-arrow-up-c"></i> Mới</a></li>
            </ul>
          </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <!-- Filter -->
        <div class="col-sm-4 col-md-3">
          <hr class="offset-lg">

          <div class="filter">
            @empty($parameter)
            <div class="item">
                <div class="title">
                    <a href="#clear" data-action="open" class="down"> <i class="ion-android-arrow-dropdown"></i> Mở</a>
                    <h1 class="h4">Kiểu</h1>
                </div>

                <div class="controls">
                    @foreach ($categories as $item)
                    <div class="checkbox-group" data-status="{{ in_array($item->code_category,$ct)?'active':'inactive' }}">
                        <div class="checkbox"><i class="ion-android-done"></i></div>
                        <div class="label">{{ $item->name }}</div>
                        <input type="checkbox" name="ct" value="{{ $item->code_category }}" {{ in_array($item->code_category,$ct)?'checked':'' }}>
                    </div>
                    @endforeach
                </div>
            </div>
            <br>
            @endempty


            <div class="item">
                <div class="title">
                    <a href="#clear" data-action="open" class="down"> <i class="ion-android-arrow-dropdown"></i> Mở</a>
                    <h1 class="h4">Xuất Xứ</h1>
                </div>

                <div class="controls grid">
                    @foreach ($countries as $item)
                    <div class="checkbox-group" data-status="{{ in_array($item->code_country,$ctry)?'active':'inactive' }}">
                        <div class="checkbox"><i class="ion-android-done"></i></div>
                        <div class="label">{{ $item->name }}</div>
                        <input type="checkbox" name="ctry" value="{{ $item->code_country }}" {{ in_array($item->code_country,$ctry)?'checked':'' }}>
                    </div>
                    @endforeach
                </div>
            </div>

            <br>

            <div class="item lite">
                <div class="title">
                    <a href="#clear" data-action="open" class="down"> <i class="ion-android-arrow-dropdown"></i> Mở</a>
                    <h1 class="h4">Thương Hiệu</h1>
                </div>

                <div class="controls" style="display: none;">
                    @foreach ($suppliers as $item)
                    <div class="checkbox-group" data-status="{{ in_array($item->code_supplier,$sp)?'active':'inactive' }}">
                        <div class="checkbox"><i class="ion-android-done"></i></div>
                        <div class="label">{{ $item->name }}</div>
                        <input type="checkbox" name="sp" value="{{ $item->code_supplier }}" {{ in_array($item->code_supplier,$sp)?'checked':'' }}>
                    </div>
                    @endforeach
                </div>
            </div>
          </div>
        </div>
        <!-- /// -->

        <!-- Products -->
        <div class="col-sm-8 col-md-9">
          <hr class="offset-lg">

          <div class="products row">
            <div>

            @foreach ($products as $item)
               <div class="col-sm-6 col-md-4 product">
                <div class="body">
                  <a href="{{ route('product.show', ['name'=> $item->name_not_utf8]) }}"><img src="{{ asset('storage/'.$item->image_display) }}" alt="{{ $item->name }}"></a>

                  <div class="content">
                    <h1 class="h3 text-hides">{{ $item->name }}</h1>
                    <p class="price" style="{{ !$item->price_new?'margin:17px 0;':'' }}">{{ number_format($item->price_new?:$item->price_old,0,'','.').' VNĐ' }}</p>
                    @if ($item->price_new)
                        <p class="price through">{{ number_format($item->price_old,0,'','.').' VNĐ' }}</p>
                    @endif
                    <label>{{ $item->categories->name }}</label>

                    <a href="{{ route('product.show', ['name'=> $item->name_not_utf8]) }}" class="btn btn-link"> <i class="ion-android-open"></i> Chi Tiết</a>
                    <button class="btn btn-primary btn-sm rounded cr" data-id="{{ $item->id }}"> <i class="ion-bag"></i> Thêm Vào Giỏ</button>
                  </div>
                </div>
              </div>
            @endforeach
            </div>
          </div>
          {{ $products->links('layouts.pagination',['query' => $query,'nameRoute' => $nameRoute,'parameter' => $parameter]) }}
        
        </div>
        <!-- /// -->
      </div>
    </div>
@endsection
@section('footer')
  <script src="{{ asset('client/main.js') }}" ></script>
@endsection