@extends('layouts.main')
@section('title',$blog->name)
@section('content')
<div class="blog-item">
    <hr class="offset-lg visible-xs">
    <hr class="offset-lg visible-xs">

    <img src="{{ asset('storage/'.$blog->image_display) }}" alt="{{ $blog->name }}" class="hidden-xs">
      <img src="{{ asset('storage/'.$blog->image_display) }}" alt="{{ $blog->name }}" class="visible-xs">

    <div class="white">
      <hr class="offset-md">
      <div class="container">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <h1 style="text-align:center">{{ $blog->name }}</h1>
                     <br>
                     {!! $blog->body !!}
                     <br>
                     <div class="fb-share-button" data-href="{{ route('blog.show', ['name'=> $blog->name_not_utf8]) }}" data-lazy="true" data-layout="button" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Chia sẻ</a></div>
                </div>
                <div style="text-align: center">
                    <div class="fb-comments" data-href="{{ route('blog.show', ['name'=> $blog->name_not_utf8]) }}" data-width="" data-numposts="5" data-lazy="true"></div>
                </div>
            </div>
        </div>
      <hr class="offset-lg">
      <hr class="offset-lg">
    </div>
  </div>
@endsection
@section('footer')
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v11.0&appId=1879028298915681&autoLogAppEvents=1" nonce="4xcjRHKL"></script>
@endsection
