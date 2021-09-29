@extends('admin.layouts.main')
@section('title','Blog')
@section('content')
<x-formheader url="{{ route('admin.blogs.index') }}" name="Blog"></x-formheader>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
               <h5 class="card-header-text">Chức Năng</h5>
               <div class="d-flex m-t-2" style="justify-content: space-between;">
                <div class="d-flex col-md-5">
                    <a href="{{ route('admin.blogs.create') }}"class="btn btn-inverse-success waves-effect waves-light col-md-4 m-r-2" >Thêm Blog
                    </a>
                    <button type="button" class="btn btn-inverse-danger waves-effect waves-light col-md-4" id="deleteBtn" >Xoá Mục Đã Chọn
                    </button>
                </div>
                <div class="d-flex col-md-6" style="justify-content: space-evenly;">
                    <select class="form-control-sm col-md-4" name="order" style="text-align-last: center;">
                        @php
                            $array = [
                                "0" => "Sắp Xếp",
                                "id-desc" => "Id Từ Trên Xuống",
                                "id-asc" => "Id Từ Dưới Lên",
                                "name-desc" => "Tên Blog (z-a)",
                                "name-asc" => "Tên Blog (a-z)",
                                "created_at-desc" => 'Ngày Đăng Từ Trên Xuống',
                                "created_at-asc" => 'Ngày Đăng Từ Dưới Lên',
                                "updated_at-desc" => 'Ngày Cập Nhật Từ Trên Xuống',
                                "updated_at-asc" => 'Ngày Cập Nhật Từ Dưới Lên',
                                "count_view-desc" => 'Lượt Xem Từ Trên Xuống',
                                "count_view-asc" => 'Lượt Xem Từ Dưới Lên',
                            ];
                            $orderType = (($query['order'] ?? '') . '-' . ($query['type'] ?? ''))?:0;
                        @endphp
                        @foreach ($array as $key => $value)
                            @if ($key == $orderType)
                                <option value="{{ $key }}" selected>{{ $value }}</option>
                            @else
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                    <div class="col-md-7">
                        <form id="search">
                            <input type="text" name="search" class="form-control-sm col-md-7" placeholder="Tìm Kiếm Theo Tên">
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
                        @include('admin.blog.page',['data' => $data,'query' => $query,'nameRoute' => $nameRoute])
                  </div>
               </div>
            </div>
         </div>
    </div>
</div>
@endsection
@section('footer')
<script src="{{ asset('js/blog.js') }}"></script>
@endsection