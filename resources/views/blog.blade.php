@extends('layouts.main')
@section('title','Tin Tức')
@section('content')
    <hr class="offset-top">
    <div class="tags">
        <div class="container">
            <div class="btn-group pull-right sorting">
              <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ion-arrow-down-b"></i> Sắp Xếp
              </button>
  
              <ul class="dropdown-menu" id="sort">
                <li class="{{ ($query['sort']??'') == 5 ?'active':'' }}" data-id="5"><a href="#"> <i class="ion-arrow-down-c"></i> Cũ</a></li>
                <li class="{{ ($query['sort']??'') == 6 || ($query['sort']??'') == '' ?'active':'' }}" data-id="6"><a href="#"> <i class="ion-arrow-up-c"></i> Mới</a></li>
              </ul>
            </div>
        </div>
      </div>
    <hr class="offset-md">
    <div class="blog">
      <div class="container">
        <div class="row">
        @foreach ($blogs as $item)
        <div class="col-sm-6 col-md-4 item">
          <div class="body">
            <a href="{{ route('blog.show', ['name'=> $item->name_not_utf8]) }}" class="view"><i class="ion-ios-book-outline"></i></a>
            <a href="{{ route('blog.show', ['name'=> $item->name_not_utf8]) }}">
              <img src="{{ asset('storage/'.$item->image_display) }}" title="{{ $item->name }}" alt="{{ $item->name }}">
            </a>

            <div class="caption">
              <h1 class="h3 text-hides">{{ $item->name }}</h1>
              <label> {{ Carbon\Carbon::parse($item->created_at)->format('d.m.Y') }}</label>
              <hr class="offset-sm">
              <p class="summary-hides">{{ $item->summary }}</p>
              <hr class="offset-sm">

              <a href="{{ route('blog.show', ['name'=> $item->name_not_utf8]) }}"> Xem <i class="ion-ios-arrow-right"></i> </a>
            </div>
          </div>
        </div>
        @endforeach
        </div>

        {{ $blogs->links('layouts.pagination',['query' => $query,'nameRoute' => $nameRoute]) }}
      </div>
    </div>
@endsection
@section('footer')
    <script src="{{ asset('client/main.js') }}"></script>
@endsection