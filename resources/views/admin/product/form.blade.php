@extends('admin.layouts.main')
@section('title',$title)
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/light-box/css/ekko-lightbox.css') }}">
<x-formheader url="{{ request()->url() }}" :name="$title"></x-formheader>
<div class="row">
    <div class="col-sm-12">
        <div class="card" id="card">
            <div class="card-header">
               <h5 class="card-header-text">Sản Phẩm</h5>
            </div>
            <!-- end of modal -->

            <div class="card-block">
               <form id="blog" method="post" action="
               @isset($product)
                  {{ route('admin.products.update',$product->id)}}
               @else
                  {{ route('admin.products.store')}}
               @endisset">
                  @isset($product)
                      @method('PUT')
                  @endisset
                  <div class="form-group">
                     <label for="name" class="form-control-label">Tên Sản Phẩm</label>
                     <input type="text" class="form-control" id="name" name="name" placeholder="Nhập Tên Sản Phẩm" value="{{ $product->name??'' }}">
                  </div>
                  <div class="form-group">
                     <label for="summary" class="form-control-label">Mô Tả Ngắn</label>
                     <textarea class="form-control" id="description" name="description" rows="4">{{ $product->description??'' }}</textarea>
                  </div>
                  <div class="form-group">
                     <label for="body" class="form-control-label">Giá</label>
                     <input type="text" class="form-control" id="price_old" name="price_old" placeholder="Nhập Giá" value="{{ $product->price_old??'' }}">
                  </div>
                  <div class="form-group">
                    <label for="body" class="form-control-label">Giá Mới</label>
                    <input type="text" class="form-control" id="price_new" name="price_new" placeholder="Nhập Giá Mới" value="{{ $product->price_new??'' }}">
                 </div>
                 <div class="form-group">
                  <label for="body" class="form-control-label">Hệ Điều Hành</label>
                  <input type="text" class="form-control" id="system" name="system" placeholder="Nhập Hệ Điều Hành" value="{{ $product->system??'' }}">
                </div>
                <div class="form-group">
                  <label for="body" class="form-control-label">Chi Tiết</label>
                  <textarea class="form-control" id="display" name="display" rows="4">{{ $product->display??'' }}</textarea>
                </div>
                <div class="form-group">
                  <label for="body" class="form-control-label">Bộ Xử Lý</label>
                  <input type="text" class="form-control" id="processor" name="processor" placeholder="Nhập Bộ Xử Lý" value="{{ $product->processor??'' }}">
                </div>
                <div class="form-group">
                  <label for="body" class="form-control-label">Đồ Hoạ</label>
                  <input type="text" class="form-control" id="graphics" name="graphics" placeholder="Nhập Đồ Hoạ" value="{{ $product->graphics??'' }}">
                </div>
                <div class="form-group">
                  <label for="body" class="form-control-label">Bộ Nhớ</label>
                  <input type="text" class="form-control" id="memory" name="memory" placeholder="Nhập Bộ Nhớ" value="{{ $product->memory??'' }}">
                </div>
                <div class="form-group">
                  <label for="body" class="form-control-label">Ổ Cứng</label>
                  <input type="text" class="form-control" id="hard_drive" name="hard_drive" placeholder="Nhập Ổ Cứng" value="{{ $product->hard_drive??'' }}">
                </div>
                <div class="form-group">
                  <label for="body" class="form-control-label">Kết Nối Không Dây</label>
                  <input type="text" class="form-control" id="wireless" name="wireless" placeholder="Nhập Kết Nối Không Dây" value="{{ $product->wireless??'' }}">
                </div>
                <div class="form-group">
                  <label for="body" class="form-control-label">Pin</label>
                  <input type="text" class="form-control" id="battery" name="battery" placeholder="Nhập Pin" value="{{ $product->battery??'' }}">
                </div>
                <div class="form-group">
                  <label for="id_country" class="form-control-label">Xuất Xứ</label>
                  <select class="form-control " id="id_country" name="id_country">
                      @foreach ($countries as $item)
                          @if (isset($product) && $product->id_country == $item->id)
                            <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                          @else
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                          @endif
                      @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="id_category" class="form-control-label">Danh Mục</label>
                  <select class="form-control " id="id_category" name="id_category">
                      @foreach ($categories as $item)
                          @if (isset($product) && $product->id_category == $item->id)
                            <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                          @else
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                          @endif
                      @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="id_supplier" class="form-control-label">Thương Hiệu</label>
                  <select class="form-control " id="id_supplier" name="id_supplier">
                      @foreach ($suppliers as $item)
                          @if (isset($product) && $product->id_supplier == $item->id)
                            <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                          @else
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                          @endif
                      @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label class="col-form-label form-control-label">Những Hình Ảnh Chi Tiết</label>
                  <div class = "d-flex">
                     <label for="images" class="custom-file">
                            <input type="file" id="images" name="images[]" class="custom-file-input" multiple>
                            <span class="custom-file-control"></span>
                     </label>
                     @isset($product)
                      <div class="row">
                        @foreach ($product->images as $item)
                            <div class="col-xl-2 col-lg-3 col-sm-3 col-xs-12">
                              <a href="{{ asset('storage/'.$item->image_display) }}" data-toggle="lightbox" data-gallery="example-gallery">
                                <img src="{{ asset('storage/'.$item->image_display) }}" class="img-fluid" alt="">
                              </a>
                            </div>
                        @endforeach
                    </div>
                     @endisset
                  </div>
                </div>
                <div class="form-group">
                     <label class="col-form-label form-control-label">Hình Ảnh Hiển Thị</label>
                     <div class="d-flex">
                        <label for="image_display" class="custom-file">
                                 <input type="file" id="image_display" name="image_display" class="custom-file-input">
                                 <span class="custom-file-control"></span>
                        </label>
                        @isset($product)
                          <div class="row">
                            <div class="col-xl-2 col-lg-3 col-sm-3 col-xs-12">
                              <a href="{{ asset('storage/'.$product->image_display) }}" data-toggle="lightbox" data-gallery="example-gallery">
                                <img src="{{ asset('storage/'.$product->image_display) }}" class="img-fluid" alt="">
                              </a>
                            </div>
                          </div>
                        @endisset
                     </div>
                </div>
                  <button type="submit" class="btn btn-success waves-effect waves-light m-r-30">@isset($product)
                    Sửa Sản Phẩm
                  @else
                    Thêm Sản Phẩm
                  @endisset</button>
               </form>
            </div>
         </div>
    </div>
</div>
@endsection
@section('footer')
<script src="{{ asset('assets/plugins/light-box/js/ekko-lightbox.js') }}"></script>
<script src="{{ asset('js/blog.js') }}"></script>
<script type="text/javascript">
  //light box
  $(document).on('click', '[data-toggle="lightbox"]', function(event) {
    event.preventDefault();
    $(this).ekkoLightbox();
  });
</script>
@endsection