@extends('admin.layouts.main')
@section('title',$title)
@section('content')
<x-formheader url="{{ request()->url() }}" name="Thêm Blog"></x-formheader>
<div class="row">
    <div class="col-sm-12">
        <div class="card" id="card">
            <div class="card-header">
               <h5 class="card-header-text">Blog</h5>
            </div>
            <!-- end of modal -->

            <div class="card-block">
               <form id="blog" method="post" action="
               @isset($blog)
                  {{ route('admin.blogs.update',$blog->id)}}
               @else
                  {{ route('admin.blogs.store')}}
               @endisset">
                  @isset($blog)
                      @method('PUT')
                  @endisset
                  <div class="form-group">
                     <label for="name" class="form-control-label">Tiêu Đề</label>
                     <input type="text" class="form-control" id="name" name="name" placeholder="Nhập Tiêu Đề" value="{{ $blog->name??'' }}">
                  </div>
                  <div class="form-group">
                     <label for="summary" class="form-control-label">Văn Bản Ngắn</label>
                     <textarea class="form-control" id="summary" name="summary" rows="4">{{ $blog->summary??'' }}</textarea>
                  </div>
                  <div class="form-group">
                     <label for="body" class="form-control-label">Nội Dung</label>
                     <textarea class="form-control" id="body" name="body" rows="4">{{ $blog->body??'' }}</textarea>
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
                  <button type="submit" class="btn btn-success waves-effect waves-light m-r-30">@isset($blog)
                    Sửa Blog
                  @else
                    Thêm Blog
                  @endisset</button>
               </form>
            </div>
         </div>
    </div>
</div>
@endsection
@section('footer')
<script>
   $(document).ready(function(){
 
     // Define function to open filemanager window
     var lfm = function(options, cb) {
       var route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';
       window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
       window.SetUrl = cb;
     };
 
     // Define LFM summernote button
     var LFMButton = function(context) {
       var ui = $.summernote.ui;
       var button = ui.button({
         contents: '<i class="note-icon-picture"></i> ',
         tooltip: 'Insert image with filemanager',
         click: function() {
 
           lfm({type: 'image', prefix: '/laravel-filemanager'}, function(lfmItems, path) {
             lfmItems.forEach(function (lfmItem) {
               context.invoke('insertImage', lfmItem.url);
             });
           });
 
         }
       });
       return button.render();
     };
 
     // Initialize summernote with LFM button in the popover button group
     // Please note that you can add this button to any other button group you'd like
     $('#body').summernote({
       toolbar: [
         ['style', ['bold', 'italic', 'underline', 'clear']],
         ['font', ['strikethrough', 'superscript', 'subscript']],
         ['fontsize', ['fontsize']],
         ['color', ['color']],
         ['para', ['ul', 'ol', 'paragraph']],
         ['height', ['height']],
         ['insert', ['link', 'video']],
         ['view', ['fullscreen', 'codeview', 'help']],
         ['popovers', ['lfm']],
       ],
       popover: {
         image: [],
         link: [],
         air: []
         },
       buttons: {
         lfm: LFMButton
      }
     })
   });
</script>
<script src="{{ asset('js/blog.js') }}"></script>
@endsection