@extends('admin.layouts.main')
@section('title',$title)
@section('content')
<x-formheader url="{{ request()->url() }}" :name="$title"></x-formheader>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
               <h5 class="card-header-text">Slide</h5>
            </div>
            <!-- end of modal -->

            <div class="card-block">
               <form id="blog" method="post" action="
               @isset($slide)
                  {{ route('admin.slides.update',$slide->id) }}
               @else
                  {{ route('admin.slides.store')}}
               @endisset">
                  @isset($slide)
                      @method('PUT')
                  @endisset
                  <div class="form-group">
                     <label for="name" class="form-control-label">Tiêu Đề</label>
                     <input type="text" class="form-control" id="subject" name="subject" placeholder="Nhập Tiêu Đề" value="{{ $slide->subject??'' }}">
                  </div>
                  <div class="form-group">
                     <label for="summary" class="form-control-label">Nội Dung Ngắn</label>
                     <textarea class="form-control" id="content" name="content" rows="4">{{ $slide->content??'' }}</textarea>
                  </div>
                  <div class="form-group">
                    <label for="name" class="form-control-label">Đường Dẫn</label>
                    <input type="text" class="form-control" id="url" name="url" placeholder="Nhập Đường Dẫn" value="{{ $slide->url??'' }}">
                 </div>
                  <div class="form-group">
                     <label class="col-form-label form-control-label">Hình Ảnh Hiển Thị</label>
                     <div>
                        <label for="file" class="custom-file">
                                 <input type="file" id="file" name="image_display" class="custom-file-input">
                                 <span class="custom-file-control"></span>
                        </label>
                     </div>
                  </div>
                  <button type="submit" class="btn btn-success waves-effect waves-light m-r-30">@isset($slide)
                    Sửa Slide
                  @else
                    Thêm Slide
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