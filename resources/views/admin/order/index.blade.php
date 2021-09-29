@extends('admin.layouts.main')
@section('title','Đơn Hàng')
@section('content')
<x-formheader url="{{ route('admin.orders.index') }}" name="Đơn Hàng"></x-formheader>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
               <h5 class="card-header-text">Chức Năng</h5>
               <div class="d-flex m-t-2" style="justify-content: space-between;">
                <div class="d-flex col-md-5" style="justify-content: space-evenly;">
                    <select class="form-control-sm col-md-5" name="order" style="text-align-last: center;">
                        @php
                            $array = [
                                "1" => "Đơn Hàng Mới Nhất",
                                "2" => "Đơn Hàng Cũ Nhất",
                            ];
                        @endphp
                        @foreach ($array as $key => $value)
                            @if ($key == ($query['order']??''))
                                <option value="{{ $key }}" selected>{{ $value }}</option>
                            @else
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                    <div class="col-md-7">
                        <form id="search">
                            <input type="text" name="search" class="form-control-sm col-md-7" placeholder="Mã Đơn Hàng">
                            <button type="submit" class="btn btn-info waves-effect waves-light col-md-3 m-l-1" data-toggle="tooltip" data-placement="top" >Tìm
                            </button>
                        </form>
                    </div>
                </div>
               </div>
            </div>
            <div class="card-block">
               <div class="row">
                  <div class="col-sm-12 table-responsive">
                        @include('admin.order.page',['data' => $data,'query' => $query,'nameRoute' => $nameRoute])
                  </div>
               </div>
            </div>
         </div>
    </div>
</div>
@endsection
@section('footer')
<script src="{{ asset('js/order.js') }}"></script>
@endsection