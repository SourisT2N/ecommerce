@extends('admin.layouts.main')
@section('title',$title)
@section('content')
<x-formheader url="{{ request()->url() }}" :name="$title"></x-formheader>
<div class="row">
    <div class="col-sm-12">
        <div class="card" id="card">
            <div class="card-header">
               <h5 class="card-header-text">Người Dùng</h5>
            </div>
            <!-- end of modal -->

            <div class="card-block">
               <form id="blog" method="post" action="
               @isset($user)
                  {{ route('admin.users.update',$user->id)}}
               @else
                  {{ route('admin.users.store')}}
               @endisset">
                  @isset($user)
                      @method('PUT')
                  @endisset
                  <div class="form-group">
                     <label for="name" class="form-control-label">Tên Người Dùng</label>
                     <input type="text" class="form-control" id="name" name="name" placeholder="Nhập Tên" value="{{ $user->name??'' }}">
                  </div>
                  <div class="form-group">
                     <label for="summary" class="form-control-label">Email</label>
                     <input type="text" class="form-control" id="email" name="email" placeholder="Nhập Email" value="{{ $user->email??'' }}">
                  </div>
                  <div class="form-group">
                    <label for="summary" class="form-control-label">Mật Khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Nhập Mật Khẩu" value="">
                 </div>
                 <div class="form-group">
                  <label class="form-control-label">Vai Trò</label>
                  <div>
                    @foreach ($roles as $item)
                      <input type="checkbox" name="roles[]" id="{{ $item->id }}" value="{{ $item->id }}" 
                        @isset($user)
                            {{ $user->roles->where('id', $item->id)->count()?'checked':'' }}
                        @endisset
                      >
                      <label for="{{ $item->id }}">{{ $item->name }}</label>
                    @endforeach
                  </div>
                </div>
                 <div class="form-group">
                  <label for="id_country" class="form-control-label">Trạng Thái Email</label>
                  <select class="form-control " id="status" name="status">
                     @php
                      $status = ['Chưa Xác Thực','Đã Xác Thực'];     
                     @endphp
                      @foreach ($status as $key => $value)
                        <option value="{{ $key }}" 
                        @isset($user)
                            {{ $user->status == $key ?'selected':'' }}
                        @endisset
                        >{{ $value }}</option>
                      @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="id_country" class="form-control-label">Trạng Thái Tài Khoản</label>
                  <select class="form-control " id="blocked" name="blocked">
                     @php
                      $status = ['Khoá','Hoạt Động'];     
                     @endphp
                      @foreach ($status as $key => $value)
                        <option value="{{ $key }}" 
                        @isset($user)
                            {{ $user->blocked == $key ?'selected':'' }}
                        @endisset
                        >{{ $value }}</option>
                      @endforeach
                  </select>
                </div>
                <button type="submit" class="btn btn-success waves-effect waves-light m-r-30">@isset($user)
                  Sửa Người Dùng
                @else
                  Thêm Người Dùng
                @endisset</button>
               </form>
            </div>
         </div>
    </div>
</div>
@endsection
@section('footer')
<script src="{{ asset('js/blog.js') }}"></script>
@endsection