<table class="table table-hover">
    <thead>
       <tr>
          <th>#</th>
          <th>Tên Tiêu Đề</th>
          <th>Hình Ảnh Hiển Thị</th>
          <th>Đường Dẫn</th>
          <th>Chức Năng</th>
       </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td><input type="checkbox" value="{{ $item->id }}"></td>
                <td>{{ $item->subject }}</td>
                <td><img src="{{ asset('storage/'.$item->image_display) }}" alt="" style="width:50px"></td>
                <td>{{ $item->url }}</td>
                <td>
                    <a href="{{ route('admin.slides.edit',$item->id) }}"><i class="icofont-edit btnEdit" style="color:#66ba66;"></i></a>
                    <i class="icofont-ui-delete btn-delete" style="color:red" data-id="{{ $item->id }}" data-toggle="modal" data-target="#deleteForm"></i>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div style="text-align:center;">
    {{ $data->links('admin.layouts.pagination',['query' => $query,'nameRoute' => $nameRoute]) }}
</div>
