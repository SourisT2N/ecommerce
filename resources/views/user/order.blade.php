@extends('user.layouts.main')
@section('title','Lịch Sử Đơn Hàng')
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h4>Đơn Hàng Của Bạn</h4>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('user.page',compact('orders'))
                </div>
            </div>

        </div>
    </div>
@endsection
@section('footer')
@endsection
