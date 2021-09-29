@extends('admin.layouts.main')
@section('title','Danh Mục')
@section('content')
<x-formheader url="{{ route('admin.categories.index') }}" name="Danh Mục"></x-formheader>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
               <h5 class="card-header-text">Chức Năng</h5>
               <div class="d-flex m-t-2" style="justify-content: space-between;">
                <div class="d-flex col-md-5">
                    <button type="button" class="btn btn-inverse-success waves-effect waves-light col-md-4 m-r-2" id="btnAdd" data-toggle="modal" data-target="#modalInput" >Thêm Danh Mục
                    </button>
                    <button type="button" class="btn btn-inverse-danger waves-effect waves-light col-md-4" id="deleteBtn" >Xoá Mục Đã Chọn
                    </button>
                </div>
                <div class="d-flex col-md-5" style="justify-content: space-evenly;">
                    <select class="form-control-sm col-md-4" name="order" style="text-align-last: center;">
                        @php
                            $array = [
                                "0" => "Sắp Xếp",
                                "id-desc" => "Id Từ Trên Xuống",
                                "id-asc" => "Id Từ Dưới Lên",
                                "name-desc" => "Tên Danh Mục (z-a)",
                                "name-asc" => "Tên Danh Mục (a-z)",
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
                        <form>
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
                        @include('admin.category.page',['data' => $data,'query' => $query,'nameRoute' => $nameRoute])
                  </div>
               </div>
            </div>
         </div>
    </div>
</div>
<x-formadd title="Danh Mục" name="Tên Danh Mục"></x-formadd>
@endsection
@section('footer')
<script src="{{ asset('js/file.js') }}"></script>
@endsection